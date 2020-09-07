<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
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

$arData = [
    'marks' => []
];

if (!empty($arResult['POSITION'])) {
    if (!empty($arResult['POSITION']['PLACEMARKS'])) {
        foreach ($arResult['POSITION']['PLACEMARKS'] as $arMark) {
            $arData['marks'][] = [
                'position' => [
                    'latitude' => Type::toFloat($arMark['LAT']),
                    'longitude' => Type::toFloat($arMark['LON'])
                ],
                'title' => Type::toString($arMark['TEXT'])
            ];
        }

        unset($arMark);
    }
}

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-map-google-view',
        'c-map-google-view-default'
    ],
    'style' => [
        'width' => !empty($arParams['MAP_WIDTH']) ? $arParams['MAP_WIDTH'] : null,
        'height' => !empty($arParams['MAP_HEIGHT']) ? $arParams['MAP_HEIGHT'] : null
    ]
]) ?>
    <div class="map-google-view-control">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:map.google.system',
            '.default',
            [
                'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
                'INIT_MAP_LON' => $arResult['POSITION']['google_lon'],
                'INIT_MAP_LAT' => $arResult['POSITION']['google_lat'],
                'INIT_MAP_SCALE' => $arResult['POSITION']['google_scale'],
                'MAP_WIDTH' => $arParams['MAP_WIDTH'],
                'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
                'CONTROLS' => $arParams['CONTROLS'],
                'OPTIONS' => $arParams['OPTIONS'],
                'MAP_ID' => $sId,
                'API_KEY' => $arParams['API_KEY'],
                'OVERLAY' => $arParams['OVERLAY'],
                'DEV_MODE' => $arParams['DEV_MODE']
            ],
            $component,
            ['HIDE_ICONS' => 'Y']
        ) ?>
    </div>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var data = <?= JavaScript::toObject($arData) ?>;
            var initialize;
            var loader;

            initialize = function () {
                var map = null;

                if (!api.isObject(window.maps))
                    return;

                map = window.maps[<?= JavaScript::toObject($sId) ?>];

                if (map == null)
                    return;

                api.each(data.marks, function (index, mark) {
                    new google.maps.Marker({
                        'position': new google.maps.LatLng(
                            mark.position.latitude,
                            mark.position.longitude
                        ),
                        'map': map,
                        'title': mark.title
                    });
                })
            };

            <?php if ($arParams['DEV_MODE'] === 'Y') { ?>
                loader = function () {
                    var map = null;

                    if (window.maps)
                        map = window.maps[<?= JavaScript::toObject($sId) ?>]

                    if (map) {
                        initialize();
                    } else {
                        setTimeout(loader, 100);
                    }
                };

                loader();
            <?php } else { ?>
                BX.ready(initialize);
            <?php } ?>
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>