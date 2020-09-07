<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Json;

/**
 * @var array $arParams
 * @var array $arResult
 */

$this->setFrameMode(true);

Loc::loadMessages(__FILE__);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];

if ($arResult['TAB']['USE'] && !empty($arResult['TAB']['VALUE'])) {
    if (
        !isset($arResult['SECTIONS'][$arResult['TAB']['VALUE']]) ||
        $arVisual['VIEW']['VALUE'] !== 'tabs'
    ) return;

    foreach ($arResult['SECTIONS'] as &$arSection)
        $arSection['ACTIVE'] = false;

    unset($arSection);

    $arResult['SECTIONS'][$arResult['TAB']['VALUE']]['ACTIVE'] = true;
}

include(__DIR__.'/parts/data.php');
include(__DIR__.'/parts/quantity.php');
include(__DIR__.'/parts/price.range.php');

$arPrice = null;

if (!empty($arResult['ITEM_PRICES']))
    $arPrice = ArrayHelper::getFirstValue($arResult['ITEM_PRICES']);

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-element',
        'c-catalog-element-catalog-default-2'
    ],
    'data' => [
        'data' => Json::encode($arData, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'properties' => Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'available' => $arData['available'] ? 'true' : 'false',
        'subscribe' => $arData['subscribe'] ? 'true' : 'false',
        'wide' => $arVisual['WIDE'] ? 'true' : 'false',
        'panel-mobile' => $arVisual['PANEL']['MOBILE']['SHOW'] ? 'true' : 'false'
    ]
]) ?>
    <?php if ($arVisual['PANEL']['DESKTOP']['SHOW']) { ?>
        <!--noindex-->
        <? include(__DIR__.'/parts/panel.php') ?>
        <!--/noindex-->
    <?php } ?>
    <?php if ($arVisual['PANEL']['MOBILE']['SHOW']) { ?>
        <!--noindex-->
        <? include(__DIR__.'/parts/panel.mobile.php') ?>
        <!--/noindex-->
    <?php } ?>
    <?php if ($arVisual['WIDE']) { ?>
        <div class="catalog-element-wrapper intec-content intec-content-visible">
            <div class="catalog-element-wrapper-2 intec-content-wrapper">
    <?php } ?>
    <div class="catalog-element-information">
        <?php if ($arVisual['GALLERY']['SHOW']) { ?>
            <div class="catalog-element-information-left">
                <?php if ($arVisual['MARKS']['SHOW']) { ?>
                    <?php include(__DIR__.'/parts/marks.php') ?>
                <?php } ?>
                <?php if (empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'dynamic') { ?>
                    <?php include(__DIR__.'/parts/buttons.php') ?>
                <?php } ?>
                <?php include(__DIR__.'/parts/gallery.php') ?>
            </div>
            <div class="catalog-element-information-right">
        <?php } else { ?>
            <?php if ($arVisual['MARKS']['SHOW']) { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/marks.php') ?>
                </div>
            <?php } ?>
        <?php } ?>
            <div class="catalog-element-information-part intec-grid intec-grid intec-grid-i-h-10">
                <div class="intec-grid-item">
                    <?php if ($arVisual['ARTICLE']['SHOW']) { ?>
                        <?php include(__DIR__.'/parts/article.php') ?>
                    <?php } ?>
                </div>
                <div class="intec-grid-item-auto">
                    <?php if ($arVisual['BRAND']['SHOW']) { ?>
                        <?php include(__DIR__.'/parts/brand.php') ?>
                    <?php } ?>
                </div>
                <?php if ($arVisual['PRINT']['SHOW']) { ?>
                    <div class="catalog-element-print-wrap intec-grid-item-auto">
                        <div class="catalog-element-print" data-role="print">
                            <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.7427 5.12061H0.742676V14.1206H4.74268V18.1206H16.7427V14.1206H20.7427V5.12061ZM14.7427 16.1206H6.74268V11.1206H14.7427V16.1206ZM17.7427 9.12061C17.1927 9.12061 16.7427 8.67061 16.7427 8.12061C16.7427 7.57061 17.1927 7.12061 17.7427 7.12061C18.2927 7.12061 18.7427 7.57061 18.7427 8.12061C18.7427 8.67061 18.2927 9.12061 17.7427 9.12061ZM16.7427 0.120605H4.74268V4.12061H16.7427V0.120605Z" />
                            </svg>
                        </div>
                    </div>
                <?php } ?>
                <div class="intec-grid-item-auto">
                    <?php if ($arResult['SHARES']['SHOW']) { ?>
                        <?php include(__DIR__.'/parts/shares.php') ?>
                    <?php } ?>
                </div>
            </div>
            <div class="catalog-element-information-part">
                <?php if ($arVisual['VOTE']['SHOW'] || $arVisual['QUANTITY']['SHOW']) { ?>
                    <div class="catalog-element-information-part-wrapper intec-grid intec-grid-i-h-10 intec-grid-a-v-center">
                        <?php if ($arVisual['VOTE']['SHOW']) { ?>
                            <div class="intec-grid-item-auto">
                                <?php include(__DIR__.'/parts/vote.php') ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['QUANTITY']['SHOW'] && (empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'dynamic')) { ?>
                            <div class="intec-grid-item-auto">
                                <?php $vQuantity($arResult);

                                if (!empty($arResult['OFFERS']))
                                    foreach ($arResult['OFFERS'] as &$arOffer) {
                                        $vQuantity($arOffer, true);

                                        unset($arOffer);
                                    }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php if (empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'dynamic') { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/price.php') ?>
                </div>
                <?php if ($arVisual['PRICE']['RANGE']) { ?>
                    <div class="catalog-element-information-part">
                        <?php $vPriceRange($arResult);

                        if (!empty($arResult['OFFERS']))
                            foreach ($arResult['OFFERS'] as &$arOffer) {
                                $vPriceRange($arOffer, true);

                                unset($arOffer);
                            }
                        ?>
                    </div>
                <?php } ?>
            <?php } elseif(!empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'list') { ?>
                <div class="catalog-element-information-part intec-grid intec-grid-wrap intec-grid-i-5 intec-grid-a-h-start intec-grid-a-v-center">
                    <div class="intec-grid-item">
                        <?php if (!empty($arPrice)) { ?>
                            <div class="catalog-element-price-discount intec-grid-item-auto" data-role="price.discount">
                                <?= Loc::GetMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRICE_FROM');?>
                                <?= $arPrice['PRINT_PRICE'];?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="intec-grid-item-auto intec-grid-item-shrink-1">
                        <div class="intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-5 intec-ui-mod-round-half" onclick="(function () {
                                var id = <?= JavaScript::toObject('#'.$sTemplateId.'-'.'sku-list') ?>;
                                var content = $(id);

                                $(document).scrollTo(content, 500);
                                })()"
                        >
                            <?= Loc::GetMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SKU_MORE');?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ($arResult['FORM']['CHEAPER']['SHOW']) { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/cheaper.php') ?>
                </div>
            <?php } ?>
            <?php if (!empty($arResult['SKU_PROPS']) && !empty($arResult['OFFERS']) && $arResult['SKU_VIEW'] == 'dynamic') { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/sku.php') ?>
                </div>
            <?php } ?>
            <?php if ($arVisual['SIZES']['SHOW']) { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/sizes.php') ?>
                </div>
            <?php } ?>
            <?php if ($arVisual['ADDITIONAL']['SHOW']) { ?>
                <div class="catalog-element-information-part catalog-element-additional-products">
                    <?php include(__DIR__.'/parts/additional.php') ?>
                </div>
            <?php } ?>
            <?php if (empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'dynamic') { ?>
                <?php if ($arResult['ACTION'] !== 'none') { ?>
                    <?php include(__DIR__.'/parts/purchase.php') ?>
                <?php } ?>
            <?php } ?>
            <?php if ($arVisual['DESCRIPTION']['PREVIEW']['SHOW']) { ?>
                <div class="catalog-element-information-part">
                    <div class="catalog-element-description catalog-element-description-preview intec-ui-markup-text">
                        <?= $arResult['PREVIEW_TEXT'] ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($arVisual['PROPERTIES']['PREVIEW']['SHOW']) { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/properties.php') ?>
                </div>
            <?php } ?>
            <?php if ($arVisual['INFORMATION']['PAYMENT']['SHOW'] || $arVisual['INFORMATION']['SHIPMENT']['SHOW']) { ?>
                <div class="catalog-element-information-part">
                    <div class="catalog-element-other-information">
                        <?php include(__DIR__.'/parts/information.php') ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($arVisual['VIEW']['VALUE'] === 'narrow') { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/advantages.php'); ?>
                </div>
            <?php } ?>
            <?php if (!empty($arResult['SECTIONS']) && $arVisual['VIEW']['VALUE'] === 'narrow') { ?>
                <div class="catalog-element-information-part">
                    <?php include(__DIR__.'/parts/sections.narrow.php') ?>
                </div>
            <?php } ?>
        <?php if ($arVisual['GALLERY']['SHOW']) { ?>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
    </div>
    <?php if (!empty($arResult['OFFERS']) && $arResult['SKU_VIEW'] == 'list') {
        include(__DIR__.'/parts/sku.list.php');
    } ?>
    <?php if ($arVisual['VIEW']['VALUE'] !== 'narrow') {
        include(__DIR__.'/parts/advantages.php');
    } ?>
    <?php if ($arVisual['VIEW']['VALUE'] !== 'tabs') {
        include(__DIR__.'/parts/sets.php');
    } ?>
    <?php if (!empty($arResult['SECTIONS'])) {
        if ($arVisual['VIEW']['VALUE'] === 'wide') {
            include(__DIR__.'/parts/sections.wide.php');
        } else if (
            $arVisual['VIEW']['VALUE'] === 'tabs' &&
            $arVisual['VIEW']['POSITION'] === 'top'
        ) {
            include(__DIR__.'/parts/sections.tabs.php');
        }
    } ?>
    <?php if ($arVisual['VIEW']['VALUE'] === 'tabs') {
        include(__DIR__.'/parts/sets.php');
    } ?>
    <?php if ($arVisual['VIEW']['VALUE'] === 'narrow') { ?>
        <?php if ($arVisual['STORES']['SHOW']) { ?>
            <div class="catalog-element-sections catalog-element-sections-wide">
                <div class="catalog-element-section">
                    <div class="catalog-element-section-name">
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SECTIONS_STORES') ?>
                    </div>
                    <div class="catalog-element-section-content">
                        <?php include(__DIR__.'/parts/sections/stores.php'); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if ($arVisual['FORM']['SHOW']) { ?>
        <div class="catalog-element-sections catalog-element-sections-wide">
            <div class="catalog-element-section">
                <div class="catalog-element-section-content">
                    <?php include(__DIR__.'/parts/form.php'); ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($arVisual['ASSOCIATED']['SHOW'] || $arVisual['RECOMMENDED']['SHOW']) { ?>
        <?php if ($arVisual['ASSOCIATED']['SHOW']) { ?>
            <div class="catalog-element-sections catalog-element-sections-wide">
                <div class="catalog-element-section">
                    <div class="catalog-element-section-name">
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SECTIONS_ASSOCIATED') ?>
                    </div>
                    <div class="catalog-element-section-content">
                        <?php include(__DIR__.'/parts/associated.php') ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($arVisual['RECOMMENDED']['SHOW']) { ?>
            <div class="catalog-element-sections catalog-element-sections-wide">
                <div class="catalog-element-section">
                    <div class="catalog-element-section-name">
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SECTIONS_RECOMMENDED') ?>
                    </div>
                    <div class="catalog-element-section-content">
                        <?php include(__DIR__.'/parts/recommended.php') ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($arVisual['SERVICES']['SHOW']) { ?>
            <div class="catalog-element-sections catalog-element-sections-wide">
                <div class="catalog-element-section">
                    <div class="catalog-element-section-name">
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SECTIONS_SERVICES') ?>
                    </div>
                    <div class="catalog-element-section-content">
                        <?php include(__DIR__.'/parts/services.php') ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if (!empty($arResult['SECTIONS'])) {
        if (
            $arVisual['VIEW']['VALUE'] === 'tabs' &&
            $arVisual['VIEW']['POSITION'] === 'bottom'
        ) include(__DIR__.'/parts/sections.tabs.php');
    } ?>
    <?php include(__DIR__.'/parts/script.php') ?>
    <?php if ($arVisual['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
    <?php include(__DIR__.'/parts/microdata.php') ?>
<?= Html::endTag('div') ?>