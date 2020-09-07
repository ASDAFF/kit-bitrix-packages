<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$this->setFrameMode(true);
$arVisual = $arResult['VISUAL'];

if (!empty($arResult['ERROR_MESSAGE']))
    ShowError($arResult['ERROR_MESSAGE']);

$arMap = [
    'COMPONENT' => null,
    'TEMPLATE' => '.default',
    'PARAMETERS' => [
        'INIT_MAP_TYPE' => 'MAP',
        'MAP_WIDTH' => '100%',
        'MAP_HEIGHT' => '100%',
        'CONTROLS' => [
            'ZOOM',
        ],
        'OPTIONS' => [
            'ENABLE_SCROLL_ZOOM',
            'ENABLE_DBLCLICK_ZOOM',
            'ENABLE_DRAGGING'
        ],
        'MAP_ID' => $arParams['MAP_ID'],
        'OVERLAY' => 'Y'
    ]
];

if ($arResult['MAP']['SHOW']) {
    if ($arResult['MAP']['VENDOR'] == 'yandex') {
        $arMap['COMPONENT'] = 'bitrix:map.yandex.view';
        $arMap['PARAMETERS']['MAP_DATA'] = serialize([
            'yandex_lat' => $arResult['POSITION']['LATITUDE'],
            'yandex_lon' => $arResult['POSITION']['LONGITUDE'],
            'yandex_scale' => 10,
            'PLACEMARKS' => $arResult['PLACEMARKS']
        ]);
    } else {
        $arMap['COMPONENT'] = 'bitrix:map.google.view';
        $arMap['PARAMETERS']['MAP_DATA'] = serialize([
            'google_lat' => $arResult['POSITION']['LATITUDE'],
            'google_lon' => $arResult['POSITION']['LONGITUDE'],
            'google_scale' => 10,
            'PLACEMARKS' => $arResult['PLACEMARKS']
        ]);
    }
}


?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-news-list c-news-list-store-1">
    <?php if ($arResult['MAP']['SHOW']) { ?>
        <div class="news-list-map">
            <?php $APPLICATION->IncludeComponent(
                $arMap['COMPONENT'],
                $arMap['TEMPLATE'],
                $arMap['PARAMETERS'],
                $component
            ) ?>
        </div>
    <?php } ?>
    <div class="news-list-items intec-content">
        <div class="news-list-items-wrapper intec-content-wrapper">
            <div class="news-list-items-wrapper-2">
                <?php foreach($arResult['ITEMS'] as $arItem) {
                    $sPicture = $templateFolder.'/images/picture.missing.jpg';

                    if (!empty($arItem['PREVIEW_PICTURE']['SRC']))
                        $sPicture = $arItem['PREVIEW_PICTURE']['SRC'];
                    ?>
                    <div class="news-list-item intec-grid intec-grid-wrap intec-grid-a-v-center">
                        <div class="news-list-image-wrap intec-grid-item-auto intec-grid-item-450-1">
                            <?= Html::tag('a', '', [
                                'href' => $arItem['DETAIL_PAGE_URL'],
                                'class' => [
                                    'news-list-image'
                                ],
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ],
                                'style' => [
                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                ]
                            ]) ?>
                        </div>
                        <div class="news-list-content intec-grid-item intec-grid-item intec-grid-item-450-1">
                            <div class="intec-grid intec-grid-wrap intec-grid-i-v-10">
                                <div class="news-list-address-wrap-2 intec-grid-item intec-grid-item-2 intec-grid-item-900-1">
                                    <?php if (!empty($arItem['ADDRESS'])) { ?>
                                        <div class="news-list-title">
                                            <i class="glyph-icon-location_2 intec-cl-text news-list-icon"></i>
                                            <?= Loc::getMessage('C_NEWS_LIST_STORES_1_ADDRESS') ?>:
                                        </div>
                                        <div class="news-list-text news-list-address-wrap">
                                            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="news-list-address intec-cl-text intec-cl-text-light-hover">
                                                <?= $arItem['ADDRESS'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="news-list-schedule-wrap intec-grid-item intec-grid-item-4 intec-grid-item-900-1">
                                    <?php if (!empty($arItem['SCHEDULE'])) { ?>
                                        <div class="news-list-title">
                                            <i class="period-icon glyph-icon-clock intec-cl-text news-list-icon"></i>
                                            <?= Loc::getMessage('C_NEWS_LIST_STORES_1_SCHEDULE') ?>:
                                        </div>
                                        <div class="news-list-text news-list-shedule">
                                            <?php if (Type::isArray($arItem['SCHEDULE'])) { ?>
                                                <?php foreach ($arItem['SCHEDULE'] as $item) {?>
                                                    <div><?= $item ?></div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div><?= $arItem['SCHEDULE'] ?></div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="news-list-contacts-wrap intec-grid-item intec-grid-item-4 intec-grid-item-900-1">
                                    <?php if (!empty($arItem['PHONE']['VALUE']) || !empty($arItem['EMAIL'])) { ?>
                                        <div class="news-list-title">
                                            <i class="glyph-icon-mail intec-cl-text news-list-icon"></i>
                                            <?= Loc::getMessage('C_NEWS_LIST_STORES_1_CONTACTS') ?>:
                                        </div>
                                        <?php if (!empty($arItem['PHONE']['VALUE'])) { ?>
                                            <div class="news-list-text news-list-phone-wrap">
                                                <span><?= Loc::getMessage('C_NEWS_LIST_STORES_1_PHONE') ?>:</span>
                                                <a href="tel:<?= $arItem['PHONE']['VALUE'] ?>" class="news-list-phone intec-cl-text-light-hover">
                                                    <?= $arItem['PHONE']['DISPLAY'] ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($arItem['EMAIL'])) { ?>
                                            <div class="news-list-text news-list-email-wrap">
                                                <a href="mailto:<?= $arItem['EMAIL'] ?>" class="news-list-email intec-cl-text-light-hover">
                                                    <?= $arItem['EMAIL'] ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php if (count($arResult['ITEMS']) > 1) { ?>
    <script type="text/javascript">
        (function ($, api) {
            var initialize;
            var loader;
            var map;

            initialize = function () {
                if (!api.isObject(window.maps))
                    return false;

                map = window.maps[<?= JavaScript::toObject($arParams['MAP_ID']) ?>];

                if (map == null)
                    return false;

                <?php if ($arResult['MAP']['VENDOR'] == 'yandex') { ?>
                    map.setBounds(map.geoObjects.getBounds(), {zoomMargin:50});
                <?php } else if ($arResult['MAP']['VENDOR'] == 'google') { ?>
                    var bounds = new google.maps.LatLngBounds();
                    <?php foreach ($arResult["PLACEMARKS"] as $arStore) { ?>
                    <?php //if (!$arStore['MAP']['SHOW']) continue ?>
                    bounds.extend(new google.maps.LatLng(
                        <?= $arStore['LAT'] ?>,
                        <?= $arStore['LON'] ?>
                    ));
                    <?php } ?>
                    map.fitBounds(bounds);
                <?php } ?>

                return true;
            };

            <?php if ($arResult['MAP']['VENDOR'] == 'google') { ?>
            BX.ready(initialize);
            <?php } else if ($arResult['MAP']['VENDOR'] == 'yandex') { ?>
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
            <?php } ?>
        })(jQuery, intec)
    </script>
<?php } ?>