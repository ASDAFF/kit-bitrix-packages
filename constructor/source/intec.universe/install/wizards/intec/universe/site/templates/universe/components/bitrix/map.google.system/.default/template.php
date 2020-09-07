<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$this->setFrameMode(true);

$sId = Type::toString($arParams['MAP_ID']);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];
$oRequest = Core::$app->request;
$sMapScriptUrl = $oRequest->getIsSecureConnection() ? 'https' : 'http';
$sMapScriptUrl .= '://www.google.com/jsapi?key='.$arParams['API_KEY'];

$arData = [
    'options' => [
        'zoom' => Type::toFloat($arParams['INIT_MAP_SCALE']),
        'center' => [
            'latitude' => Type::toFloat($arParams['INIT_MAP_LAT']),
            'longitude' => Type::toFloat($arParams['INIT_MAP_LON'])
        ],
        'mapTypeId' => $arParams['INIT_MAP_TYPE']
    ]
];

$arOptions = [
    'ALL' => ArrayHelper::merge($arResult['ALL_MAP_OPTIONS'], $arResult['ALL_MAP_CONTROLS']),
    'SET' => ArrayHelper::merge($arParams['OPTIONS'], $arParams['CONTROLS'])
];

foreach ($arOptions['ALL'] as $sKey => $sOption) {
    $bSet = ArrayHelper::isIn($sKey, $arOptions['SET']);
    $arOption = explode(':', $sOption);
    $arOption = [
        'key' => trim($arOption[0]),
        'value' => trim($arOption[1])
    ];

    if ($bSet) {
        if ($arOption['value'] === '#true#') {
            $arOption['value'] = true;
        } else {
            $arOption['value'] = false;
        }
    } else {
        if ($arOption['value'] === '#true#') {
            $arOption['value'] = false;
        } else {
            $arOption['value'] = true;
        }
    }

    $arData['options'][$arOption['key']] = $arOption['value'];
}

unset($arOptions);

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-map-google-system',
        'c-map-google-system-default'
    ],
    'style' => [
        'width' => !empty($arParams['MAP_WIDTH']) ? $arParams['MAP_WIDTH'] : null,
        'height' => !empty($arParams['MAP_HEIGHT']) ? $arParams['MAP_HEIGHT'] : null
    ]
]) ?>
    <div class="map-google-system-control" data-role="control">
        <?= Loc::getMessage('C_MAP_GOOGLE_SYSTEM_DEFAULT_LOADING') ?>
    </div>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var control = $('[data-role="control"]', root);
            var data = <?= JavaScript::toObject($arData) ?>;
            var initialize;
            var loader;

            initialize = function () {
                var options = data.options;
                var map;

                if (!window.google)
                    return;

                if (!window.google.maps)
                    return;

                options.center = new google.maps.LatLng(
                    options.center.latitude,
                    options.center.longitude
                );

                options.mapTypeId =  google.maps.MapTypeId[options.mapTypeId];
                control.html(null);
                map = new window.google.maps.Map(control.get(0), options);

                if (!api.isObject(window.maps))
                    window.maps = {};

                window.maps[<?= JavaScript::toObject($sId) ?>] = map;

                <?php if ($arVisual['OVERLAY']) { ?>
                    map.setOptions({
                        'scrollwheel': false
                    });

                    map.addListener('mousedown', function (event) {
                        this.setOptions({
                            'scrollwheel': true
                        });
                    });

                    map.addListener('mouseout', function (event) {
                        this.setOptions({
                            'scrollwheel': false
                        });
                    });
                <?php } ?>
            };

            <?php if ($arParams['DEV_MODE'] === 'Y') { ?>
                if (window.bGoogleMapScriptsLoading !== true && window.bGoogleMapScriptsLoaded !== true) {
                    if (window.bGoogleMapScriptsLoading === true) {
                        loader = function () {
                            if (window.bGoogleMapScriptsLoaded === true) {
                                initialize();
                            } else {
                                setTimeout(loader, 100);
                            }
                        };

                        loader();
                    } else {
                        window.bGoogleMapScriptsLoading = true;
                        loader = function () {
                            if (window.google && window.google.maps) {
                                window.bGoogleMapScriptsLoaded = true;
                                initialize();
                            } else {
                                setTimeout(loader, 100);
                            }
                        };

                        BX.loadScript('<?= $sMapScriptUrl ?>&rnd=' + Math.random(), function () {
                            if (BX.browser.IsIE()) {
                                setTimeout(function () {
                                    window.google.load('maps', <?= Type::toInteger($arParams['GOOGLE_VERSION'])?>, {
                                        'callback': loader,
                                        'other_params': 'language=<?=LANGUAGE_ID?>&key=<?=$arParams['API_KEY']?>'
                                    });
                                }, 1000);
                            } else {
                                window.google.load('maps', <?= Type::toInteger($arParams['GOOGLE_VERSION'])?>, {
                                    'callback': loader,
                                    'other_params': 'language=<?=LANGUAGE_ID?>&key=<?=$arParams['API_KEY']?>'
                                });
                            }
                        });
                    }
                } else {
                    initialize();
                }
            <?php } else { ?>
                BX.ready(initialize);
            <?php } ?>
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>