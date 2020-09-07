<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
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

$arData = [
    'options' => [
        'zoom' => Type::toFloat($arParams['INIT_MAP_SCALE']),
        'center' => [
            Type::toFloat($arParams['INIT_MAP_LAT']),
            Type::toFloat($arParams['INIT_MAP_LON'])
        ],
        'type' => 'yandex#'.$arResult['ALL_MAP_TYPES'][$arParams['INIT_MAP_TYPE']]
    ],
    'behaviors' => [],
    'controls' => []
];

$arBehaviors = [
    'ALL' => $arResult['ALL_MAP_OPTIONS'],
    'SET' => $arParams['OPTIONS']
];

foreach ($arBehaviors['ALL'] as $sKey => $sBehavior) {
    $bSet = ArrayHelper::isIn($sKey, $arBehaviors['SET']);
    $arData['behaviors'][$sBehavior] = $bSet;
}

unset($arBehaviors);

$arControls = [
    'ALL' => $arResult['ALL_MAP_CONTROLS'],
    'SET' => $arParams['CONTROLS']
];

foreach ($arControls['ALL'] as $sKey => $sControl) {
    $bSet = ArrayHelper::isIn($sKey, $arControls['SET']);
    $arData['controls'][$sControl] = $bSet;
}

unset($arControls);

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-map-yandex-system',
        'c-map-yandex-system-default'
    ],
    'style' => [
        'width' => !empty($arParams['MAP_WIDTH']) ? $arParams['MAP_WIDTH'] : null,
        'height' => !empty($arParams['MAP_HEIGHT']) ? $arParams['MAP_HEIGHT'] : null
    ]
]) ?>
    <div class="map-yandex-system-control" data-role="control">
        <?= Loc::getMessage('C_MAP_YANDEX_SYSTEM_DEFAULT_LOADING') ?>
    </div>
    <?php if ($arVisual['OVERLAY']) { ?>
        <div class="map-yandex-system-overlay" data-role="overlay"></div>
    <?php } ?>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var control = $('[data-role="control"]', root);
            var overlay = $('[data-role="overlay"]', root);
            var data = <?= JavaScript::toObject($arData) ?>;
            var initialize;
            var loader;

            initialize = function () {
                var options = data.options;
                var behaviors = data.behaviors;
                var controls = data.controls;
                var map;

                if (!window.ymaps)
                    return;

                control.html(null);
                map = new window.ymaps.Map(control.get(0), options);

                api.each(behaviors, function (behavior, state) {
                    if (state) {
                        map.behaviors.enable(behavior);
                    } else if (map.behaviors.isEnabled(behavior)) {
                        map.behaviors.disable(behavior);
                    }
                });

                api.each(controls, function (control, state) {
                    if (state) map.controls.add(control);
                });

                <?php if (!empty($arParams['ONMAPREADY'])) { ?>
                    if (window.<?= $arParams['ONMAPREADY']?>) {
                        <?php if ($arParams['ONMAPREADY_PROPERTY']) { ?>
                            <?= $arParams['ONMAPREADY_PROPERTY']?> = map;
                            window.<?= $arParams['ONMAPREADY']?>();
                        <?php } else { ?>
                            window.<?= $arParams['ONMAPREADY']?>(map);
                        <?php } ?>
                    }
                <?php } ?>

                if (!api.isObject(window.maps))
                    window.maps = {};

                window.maps[<?= JavaScript::toObject($sId) ?>] = map;

                <?php if ($arVisual['OVERLAY']) { ?>
                    overlay.show();

                    root.on('mousedown', function (event) {
                        overlay.hide();
                    }).on('mouseleave', function (event) {
                        overlay.show();
                    });
                <?php } ?>
            };

            loader = function () {
                if (window.ymaps) {
                    ymaps.ready(initialize);
                } else {
                    setTimeout(loader, 100);
                }
            };

            <?php if ($arParams['DEV_MODE'] === 'Y') { ?>
                if (window.bYandexMapScriptsLoading !== true && window.bYandexMapScriptsLoaded !== true) {
                    window.bYandexMapScriptsLoading = true;

                    BX.loadScript('<?= $arResult['MAPS_SCRIPT_URL'] ?>', function () {
                        window.bYandexMapScriptsLoaded = true;
                        loader();
                    });
                } else {
                    loader();
                }
            <?php } else { ?>
                loader();
            <?php } ?>
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>