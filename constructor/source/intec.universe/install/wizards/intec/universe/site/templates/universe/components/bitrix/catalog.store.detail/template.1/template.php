<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 */

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$this->setFrameMode(true);

?>
<div class="ns-bitrix c-catalog-store-detail c-catalog-store-detail-template-1" id="<?= $sTemplateId ?>">
    <div class="catalog-store-detail-wrap" itemscope itemtype="http://schema.org/Product" data-map-show="<?= $arResult['MAP']['SHOW'] ? 'true' : 'false' ?>">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="intec-grid intec-grid-wrap">
                    <?php if ($arResult['MAP']['SHOW']) { ?>
                        <div class="intec-grid-item-2 catalog-store-detail-map intec-grid-item-800-1">
                            <?php if ($arResult['MAP']['VENDOR'] == 0) {
                                $APPLICATION->IncludeComponent(
                                    'bitrix:map.yandex.view',
                                    '.default',
                                    [
                                        'INIT_MAP_TYPE' => 'MAP',
                                        'MAP_DATA' => serialize([
                                            'yandex_lat' => $arResult['MAP']['GPS']['N'],
                                            'yandex_lon' => $arResult['MAP']['GPS']['S'],
                                            'yandex_scale' => 10,
                                            'PLACEMARKS' => [[
                                                'LON' => $arResult['MAP']['GPS']['S'],
                                                'LAT' => $arResult['MAP']['GPS']['N'],
                                                'TEXT' => $arResult['ADDRESS']
                                            ]]
                                        ]),
                                        'CONTROLS' => [
                                            'ZOOM',
                                        ],
                                        'OPTIONS' => [
                                            'ENABLE_SCROLL_ZOOM',
                                            'ENABLE_DBLCLICK_ZOOM',
                                            'ENABLE_DRAGGING',
                                        ],
                                        'MAP_ID' => $arParams['MAP_ID'],
                                        'MAP_WIDTH' => '100%',
                                        'MAP_HEIGHT' => '100%',
                                        'OVERLAY' => 'Y'
                                    ],
                                    $component
                                );
                            } else {
                                $APPLICATION->IncludeComponent(
                                    'bitrix:map.google.view',
                                    '.default',
                                    [
                                        'INIT_MAP_TYPE' => 'MAP',
                                        'MAP_DATA' => serialize([
                                            'google_lat' => $arResult['MAP']['GPS']['N'],
                                            'google_lon' => $arResult['MAP']['GPS']['S'],
                                            'google_scale' => 10,
                                            'PLACEMARKS' => [[
                                                'LON' => $arResult['MAP']['GPS']['S'],
                                                'LAT' => $arResult['MAP']['GPS']['N'],
                                                'TEXT' => $arResult['ADDRESS']
                                            ]]
                                        ]),
                                        'CONTROLS' => [
                                            'ZOOM',
                                        ],
                                        'OPTIONS' => [
                                            'ENABLE_SCROLL_ZOOM',
                                            'ENABLE_DBLCLICK_ZOOM',
                                            'ENABLE_DRAGGING'
                                        ],
                                        'MAP_ID' => $arParams['MAP_ID'],
                                        'MAP_WIDTH' => '100%',
                                        'MAP_HEIGHT' => '100%',
                                        'OVERLAY' => 'Y'
                                    ],
                                    $component
                                );
                            } ?>
                        </div>
                    <?php } ?>
                    <div class="intec-grid-item catalog-store-detail-properties intec-grid-item-800-1">
                        <?php if (!empty($arResult['ADDRESS'])) { ?>
                            <div class="catalog-store-detail-property catalog-store-detail-property-address" itemprop="description">
                                <i class="fas fa-map-marker-alt intec-cl-text"></i>
                                <div class="catalog-store-detail-property-text">
                                    <span class="catalog-store-detail-property-name">
                                        <?= Loc::getMessage('C_CATALOG_STORE_DETAIL_TEMPLATE_1_ADDRESS') ?>:
                                    </span>
                                    <?= $arResult['ADDRESS'] ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arResult['PHONE'])) { ?>
                            <div class="catalog-store-detail-property catalog-store-detail-property-phone" itemprop="description">
                                <i class="fas fa-phone intec-cl-text"></i>
                                <div class="catalog-store-detail-property-text">
                                    <span class="catalog-store-detail-property-name">
                                        <?= Loc::getMessage('C_CATALOG_STORE_DETAIL_TEMPLATE_1_PHONE') ?>:
                                    </span>
                                    <a href="tel:<?= $arResult['PHONE']['VALUE'] ?>">
                                        <?= $arResult['PHONE']['DISPLAY'] ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arResult['EMAIL'])) { ?>
                            <div class="catalog-store-detail-property catalog-store-detail-property-email" itemprop="description">
                                <i class="fas fa-envelope intec-cl-text"></i>
                                <div class="catalog-store-detail-property-text">
                                    <span class="catalog-store-detail-property-name">
                                        <?= Loc::getMessage('C_CATALOG_STORE_DETAIL_TEMPLATE_1_EMAIL') ?>:
                                    </span>
                                    <a href="mailto:<?= $arResult['EMAIL'] ?>">
                                        <?= $arResult['EMAIL'] ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arResult['SCHEDULE'])) { ?>
                            <div class="catalog-store-detail-property catalog-store-detail-property-schedule" itemprop="description">
                                <i class="far fa-clock intec-cl-text"></i>
                                <div class="catalog-store-detail-property-text">
                                    <span class="catalog-store-detail-property-name">
                                        <?= Loc::getMessage('C_CATALOG_STORE_DETAIL_TEMPLATE_1_SCHEDULE') ?>:
                                    </span>
                                    <?= $arResult['~SCHEDULE'] ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="catalog-store-detail-property intec-grid intec-grid-nowrap">
                            <?php if (!empty($arResult['DESCRIPTION'])) { ?>
                                <div class="intec-grid-item catalog-store-detail-description intec-ui-markup-text" itemprop="description">
                                    <?= $arResult['DESCRIPTION'] ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="catalog-store-detail-links">
                            <a class="catalog-store-detail-back intec-cl-text intec-cl-text-light-hover" href="javascript:history.back();">
                                <span class="catalog-store-detail-back-icon far fa-angle-left"></span>
                                <span class="catalog-store-detail-back-text">
                                    <?= Loc::getMessage('C_CATALOG_STORE_DETAIL_TEMPLATE_1_BACK_URL') ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>