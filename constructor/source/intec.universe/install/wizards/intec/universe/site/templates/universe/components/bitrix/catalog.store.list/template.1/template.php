<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

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
    if ($arResult['MAP']['VENDOR'] == 0) {
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

if (empty($arResult['STORES']))
    return;
?>
<div class="ns-bitrix c-catalog-store-list c-catalog-store-list-template-1">
    <?php if ($arResult['MAP']['SHOW']) { ?>
        <div class="catalog-store-list-map">
            <?php $APPLICATION->IncludeComponent(
                $arMap['COMPONENT'],
                $arMap['TEMPLATE'],
                $arMap['PARAMETERS'],
                $component
            ) ?>
        </div>
    <?php } ?>
    <div class="catalog-store-list-items intec-content">
        <div class="catalog-store-list-items-wrapper intec-content-wrapper">
            <div class="catalog-store-list-items-wrapper-2">
                <?php foreach($arResult['STORES'] as $arStore) {
                    $sPicture = $templateFolder.'/images/picture.missing.jpg';

                    if (!empty($arStore['DETAIL_IMG']['SRC']))
                        $sPicture = $arStore['DETAIL_IMG']['SRC'];

                    ?>
                    <div class="catalog-store-list-item intec-grid intec-grid-wrap intec-grid-a-v-center">
                        <div class="catalog-store-list-image-wrap intec-grid-item-auto intec-grid-item-450-1">
                            <?= Html::tag('a', '', [
                                'href' => $arStore['URL'],
                                'class' => [
                                    'catalog-store-list-image'
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
                        <div class="catalog-store-list-content intec-grid-item intec-grid-item intec-grid-item-450-1">
                            <div class="intec-grid intec-grid-wrap intec-grid-i-v-10">
                                <div class="catalog-store-list-address-wrap intec-grid-item intec-grid-item-2 intec-grid-item-900-1">
                                    <?php if (!empty($arStore['ADDRESS'])) { ?>
                                        <div class="catalog-store-list-title">
                                            <i class="glyph-icon-location_2 intec-cl-text catalog-store-list-icon"></i>
                                            <?= Loc::getMessage('C_CATALOG_STORE_LIST_TEMPLATE_1_ADDRESS') ?>:
                                        </div>
                                        <div class="catalog-store-list-text catalog-store-list-address-wrap">
                                            <a href="<?= $arStore['URL'] ?>" class="catalog-store-list-address intec-cl-text intec-cl-text-light-hover">
                                                <?= $arStore['ADDRESS'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="catalog-store-list-schedule-wrap intec-grid-item intec-grid-item-4 intec-grid-item-900-1">
                                    <?php if (!empty($arStore['SCHEDULE'])) { ?>
                                        <div class="catalog-store-list-title">
                                            <i class="period-icon glyph-icon-clock intec-cl-text catalog-store-list-icon"></i>
                                            <?= Loc::getMessage('C_CATALOG_STORE_LIST_TEMPLATE_1_SCHEDULE') ?>:
                                        </div>
                                        <div class="catalog-store-list-text catalog-store-list-shedule">
                                            <?= Html::decode($arStore['SCHEDULE']) ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="catalog-store-list-contacts-wrap intec-grid-item intec-grid-item-4 intec-grid-item-900-1">
                                    <?php if (!empty($arStore['PHONE']) || !empty($arStore['EMAIL'])) { ?>
                                        <div class="catalog-store-list-title">
                                            <i class="glyph-icon-mail intec-cl-text catalog-store-list-icon"></i>
                                            <?= Loc::getMessage('C_CATALOG_STORE_LIST_TEMPLATE_1_CONTACTS') ?>:
                                        </div>
                                        <?php if (!empty($arStore['PHONE'])) { ?>
                                            <div class="catalog-store-list-text catalog-store-list-phone-wrap">
                                                <span><?= Loc::getMessage('C_CATALOG_STORE_LIST_TEMPLATE_1_PHONE') ?>:</span>
                                                <a href="tel:<?= $arStore['PHONE']['VALUE'] ?>" class="catalog-store-list-phone intec-cl-text-light-hover">
                                                    <?= $arStore['PHONE']['DISPLAY'] ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($arStore['EMAIL'])) { ?>
                                            <div class="catalog-store-list-text catalog-store-list-email-wrap">
                                                <a href="mailto:<?= $arStore['EMAIL'] ?>" class="catalog-store-list-email intec-cl-text-light-hover">
                                                    <?= $arStore['EMAIL'] ?>
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
<?php if (count($arResult['STORES']) > 1) { ?>
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

                <?php if ($arResult['MAP']['VENDOR'] == 0) { ?>
                    map.setBounds(map.geoObjects.getBounds(), {zoomMargin:50});
                <?php } else if ($arResult['MAP']['VENDOR'] == 1) { ?>
                    var bounds = new google.maps.LatLngBounds();
                    <?php foreach ($arResult["STORES"] as $arStore) {?>
                        <?php if (!$arStore['MAP']['SHOW']) continue ?>
                        bounds.extend(new google.maps.LatLng(
                            <?= $arStore['MAP']['GPS']['N'] ?>,
                            <?= $arStore['MAP']['GPS']['S'] ?>
                        ));
                    <?php } ?>
                    map.fitBounds(bounds);
                <?php } ?>

                return true;
            };

            <?php if ($arResult['MAP']['VENDOR'] == 1) { ?>
                BX.ready(initialize);
            <?php } else if ($arResult['MAP']['VENDOR'] == 0) { ?>
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