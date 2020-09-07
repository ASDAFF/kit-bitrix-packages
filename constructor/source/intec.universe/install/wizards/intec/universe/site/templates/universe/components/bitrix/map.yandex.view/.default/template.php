<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\RegExp;
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
    'marks' => [],
    'polylines' => []
];

if (!empty($arResult['POSITION'])) {
    if (!empty($arResult['POSITION']['PLACEMARKS'])) {
        foreach ($arResult['POSITION']['PLACEMARKS'] as $arMark) {
            $sText = Type::toString($arMark['TEXT']);
            $arDataMark = [
                'position' => [
                    'latitude' => Type::toFloat($arMark['LAT']),
                    'longitude' => Type::toFloat($arMark['LON'])
                ],
                'title' => $sText,
                'text' => $sText
            ];

            if (!empty($sText)) {
                $sText = preg_split('/\r\n|\r|\n/', $sText);

                $arDataMark['title'] = reset($sText);
                $arDataMark['text'] = implode('<br />', $sText);
            }

            $arData['marks'][] = $arDataMark;

            unset($sText);
        }

        unset($arDataMark);
        unset($arMark);
    }

    if (!empty($arResult['POSITION']['POLYLINES'])) {
        foreach ($arResult['POSITION']['POLYLINES'] as $arPolyline) {
            $arDataPolyline = [
                'points' => [],
                'style' => !empty($arPolyline['STYLE']) ? $arPolyline['STYLE'] : null,
                'title' => Type::toString($arPolyline['TITLE'])
            ];

            if (Type::isArray($arPolyline['POINTS']))
                foreach ($arPolyline['POINTS'] as $arPoint) {
                    $arDataPoint = [
                        'latitude' => ArrayHelper::getValue($arPoint, 'LAT'),
                        'longitude' => ArrayHelper::getValue($arPoint, 'LON')
                    ];

                    $arDataPoint['latitude'] = Type::toFloat($arDataPoint['latitude']);
                    $arDataPoint['longitude'] = Type::toFloat($arDataPoint['longitude']);
                    $arDataPolyline['points'][] = $arDataPoint;
                }

            unset($arDataPoint);
            unset($arPoint);

            if (count($arDataPolyline['points']) > 1)
                $arData['polylines'][] = $arDataPolyline;
        }

        unset($arDataPolyline);
        unset($arPolyline);
    }
}

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-map-yandex-view',
        'c-map-yandex-view-default'
    ],
    'style' => [
        'width' => !empty($arParams['MAP_WIDTH']) ? $arParams['MAP_WIDTH'] : null,
        'height' => !empty($arParams['MAP_HEIGHT']) ? $arParams['MAP_HEIGHT'] : null
    ]
]) ?>
    <div class="map-yandex-view-control">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:map.yandex.system',
            '.default',
            [
                'KEY' => $arParams['KEY'],
                'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
                'INIT_MAP_LON' => $arResult['POSITION']['yandex_lon'],
                'INIT_MAP_LAT' => $arResult['POSITION']['yandex_lat'],
                'INIT_MAP_SCALE' => $arResult['POSITION']['yandex_scale'],
                'MAP_WIDTH' => $arParams['MAP_WIDTH'],
                'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
                'CONTROLS' => $arParams['CONTROLS'],
                'OPTIONS' => $arParams['OPTIONS'],
                'MAP_ID' => $arParams['MAP_ID'],
                'LOCALE' => $arParams['LOCALE'],
                'ONMAPREADY' => $arParams['ONMAPREADY'],
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
                    return false;

                map = window.maps[<?= JavaScript::toObject($sId) ?>];

                if (map == null)
                    return false;

                api.each(data.marks, function (index, mark) {
                    map.geoObjects.add(new ymaps.Placemark([
                        mark.position.latitude,
                        mark.position.longitude
                    ], {
                        'hintContent': mark.title,
                        'balloonContent': mark.text
                    }, {
                        'balloonCloseButton': true
                    }));
                });

                api.each(data.polylines, function (index, polyline) {
                    var points = [];
                    var parameters;

                    api.each(polyline.points, function (index, point) {
                        points.push([point.latitude, point.longitude]);
                    });

                    if (points.length < 2)
                        return;

                    parameters = {};
                    parameters.clickable = true;

                    if (polyline.style != null) {
                        parameters.strokeColor = polyline.style.strokeColor;
                        parameters.strokeWidth = polyline.style.strokeWidth;
                    }

                    map.geoObjects.add(new ymaps.Polyline(points, {
                        'balloonContent': polyline.title
                    }, parameters));
                });

                return true;
            };

            loader = function () {
                var load;

                load = function () {
                    if (!initialize())
                        setTimeout(load, 100);
                };

                if (window.ymaps) {
                    ymaps.ready(load);
                } else {
                    setTimeout(loader, 100);
                }
            };

            loader();
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>