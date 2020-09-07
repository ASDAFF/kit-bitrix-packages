<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$arNavigation = !empty($arResult['NAV_RESULT']) ? [
    'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
    'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
    'NavNum' => $arResult['NAV_RESULT']->NavNum
] : [
    'NavPageCount' => 1,
    'NavPageNomer' => 1,
    'NavNum' => $this->randString()
];

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sTemplateContainer = $sTemplateId.'-'.$arNavigation['NavNum'];
$arVisual = $arResult['VISUAL'];
$arVisual['NAVIGATION']['LAZY']['BUTTON'] =
    $arVisual['NAVIGATION']['LAZY']['BUTTON'] &&
    $arNavigation['NavPageNomer'] < $arNavigation['NavPageCount'];
$iPropertiesCounter = 0;
/**
 * @var Closure $dData(&$arItem)
 * @var Closure $vButtons(&$arItem)
 * @var Closure $vImage(&$arItem)
 * @var Closure $vPrice(&$arItem)
 * @var Closure $vPurchase(&$arItem)
 * @var Closure $vQuantity(&$arItem)
 * @var Closure $vSku($arProperties)
 */
include(__DIR__.'/parts/data.php');
include(__DIR__.'/parts/image.php');
include(__DIR__.'/parts/price.php');
include(__DIR__.'/parts/measure.php');
include(__DIR__.'/parts/action.buttons.php');
include(__DIR__.'/parts/order.buttons.php');
include(__DIR__.'/parts/quantity.php');
include(__DIR__.'/parts/sku.php');
include(__DIR__.'/parts/purchase.php');

?>

<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-catalog-list-2'
    ],
    'data' => [
        'properties' => !empty($arResult['SKU_PROPS']) ? Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
        'wide' => $arVisual['WIDE'] ? 'true' : 'false'
    ]
]) ?>
    <?php if ($arVisual['NAVIGATION']['TOP']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-top" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult['NAV_STRING'] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
    <div class="catalog-section-header">
        <div class="catalog-section-header-wrapper intec-grid intec-grid-a-v-center intec-grid-i-h-8">
            <div class="catalog-section-header-name intec-grid-item">
                <div class="catalog-section-header-name-wrapper">
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ITEM_NAME') ?>
                </div>
                <?php if(!empty($arResult['DISPLAY_PROPERTIES'])) { ?>
                    <div class="catalog-section-header-name-properties intec-grid intec-grid-wrap intec-grid-i-h-7 intec-grid-a-v-center">
                        <?php if ($arVisual['PROPERTIES']['AMOUNT'] >= 1) { ?>
                            <div class="catalog-section-header-name-property intec-grid-item-auto">
                                <?= $arResult['DISPLAY_PROPERTIES'][0]['NAME'] ?>
                            </div>
                        <?php } ?>
                        <?php if(!empty($arResult['DISPLAY_PROPERTIES'][1]) && $arVisual['PROPERTIES']['AMOUNT'] >= 2) { ?>
                            <div class="catalog-section-header-name-property intec-grid-item-auto">
                                <?= $arResult['DISPLAY_PROPERTIES'][1]['NAME'] ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="catalog-section-header-properties-wrap intec-grid-item">
                <?php if (count($arResult['DISPLAY_PROPERTIES']) > 2 && $arVisual['PROPERTIES']['AMOUNT'] > 2) { ?>
                    <div class="catalog-section-header-properties intec-grid intec-grid-1200-wrap intec-grid-a-v-center intec-grid-i-h-16 intec-grid-i-v-10">
                        <?php foreach($arResult['DISPLAY_PROPERTIES'] as $arProperty) {
                            $iPropertiesCounter ++;
                            if ($iPropertiesCounter <= 2)
                                continue;

                            if ($iPropertiesCounter > $arVisual['PROPERTIES']['AMOUNT'])
                                break;
                            ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'catalog-section-header-property' => true,
                                    'intec-grid-item' => [
                                        '4' => $arVisual['PROPERTIES']['COLUMNS'] == 4,
                                        '3' => $arVisual['PROPERTIES']['COLUMNS'] == 3,
                                        '2' => $arVisual['PROPERTIES']['COLUMNS'] == 2,
                                        '1' => $arVisual['PROPERTIES']['COLUMNS'] == 1,
                                        '1200-3' => $arVisual['PROPERTIES']['COLUMNS'] == 4,
                                        '1000-2' => $arVisual['PROPERTIES']['COLUMNS'] > 2,
                                    ]
                                ], true)
                            ]) ?>
                                <div class="catalog-section-header-property-name">
                                    <?= $arProperty['NAME'] ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="catalog-section-header-quantity-wrap intec-grid-item">
                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ITEM_QUANTITY') ?>
            </div>
            <div class="catalog-section-header-price-wrap intec-grid-item">
                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ITEM_UNIT_PRICE') ?>
            </div>
            <div class="catalog-section-header-buttons-wrap intec-grid-item"></div>
        </div>
    </div>
    <!-- items-container -->
    <?= Html::beginTag('div', [
        'class' => 'catalog-section-items',
        'data' => [
            'entity' => $sTemplateContainer
        ]
    ]) ?>
    <?php foreach($arResult['ITEMS'] as $arItem) { ?>
        <?php
        $sId = $sTemplateId.'_'.$arItem['ID'];
        $sAreaId = $this->GetEditAreaId($sId);
        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

        $sData = Json::encode($dData($arItem), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true);
        $sName = $arItem['NAME'];
        $sDescription = $arItem['PREVIEW_TEXT'];
        $sLink = $arItem['DETAIL_PAGE_URL'];
        $bOffers = $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']);
        $bQuantity = $arVisual['QUANTITY']['SHOW'] && ($bOffers || empty($arItem['OFFERS']));
        $bVote = $arVisual['VOTE']['SHOW'];
        $bCounter = $arVisual['COUNTER']['SHOW'] && $arItem['ACTION'] === 'buy';
        $iPropertiesCounter = 0;

        $arSkuProps = [];

        if (!empty($arResult['SKU_PROPS']))
            $arSkuProps = $arResult['SKU_PROPS'];
        else if (!empty($arItem['SKU_PROPS']))
            $arSkuProps = $arItem['SKU_PROPS'];
        ?>
        <?= Html::beginTag('div', [
            'id' => $sAreaId,
            'class' => 'catalog-section-item',
            'data' => [
                'id' => $arItem['ID'],
                'role' => 'item',
                'data' => $sData,
                'available' => $arItem['CAN_BUY'] ? 'true' : 'false',
                'entity' => 'items-row'
            ]
        ]) ?>
        <div class="catalog-section-item-background">
            <div class="catalog-section-item-wrapper">
                <div class="catalog-section-item-content">
                    <div class="intec-grid intec-grid-900-wrap intec-grid-a-v-center intec-grid-a-h-between intec-grid-i-h-8">
                        <div class="catalog-section-item-name intec-grid-item">
                            <?= Html::tag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', $sName, [
                                'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $sLink : null,
                                'class' => [
                                    'catalog-section-item-name-wrapper',
                                    'intec-cl-text-hover'
                                ],
                                'data' => [
                                    'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                                ]
                            ])?>
                            <?php if ($bVote) { ?>
                                <div class="catalog-section-item-vote">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:iblock.vote',
                                        'template.1',
                                        array(
                                            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                            'ELEMENT_ID' => $arItem['ID'],
                                            'ELEMENT_CODE' => $arItem['CODE'],
                                            'MAX_VOTE' => '5',
                                            'VOTE_NAMES' => array(
                                                0 => '1',
                                                1 => '2',
                                                2 => '3',
                                                3 => '4',
                                                4 => '5',
                                            ),
                                            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                            'CACHE_TIME' => $arParams['CACHE_TIME'],
                                            'DISPLAY_AS_RATING' => $arVisual['VOTE']['MODE'] === 'rating' ? 'rating' : 'vote_avg',
                                            'SHOW_RATING' => 'N'
                                        ),
                                        $component,
                                        ['HIDE_ICONS' => 'Y']
                                    ) ?>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem['DISPLAY_PROPERTIES'])) { ?>
                                <div class="catalog-section-item-name-properties intec-grid intec-grid-wrap intec-grid-i-h-8 intec-grid-i-v-2 intec-grid-a-v-center">
                                    <?php foreach($arItem['DISPLAY_PROPERTIES'] as $arProperty) {
                                        if ($iPropertiesCounter >= 2)
                                            break;

                                        $iPropertiesCounter ++;

                                        if (empty($arProperty['NAME']) || empty($arProperty['DISPLAY_VALUE']))
                                            continue;
                                        ?>
                                        <div class="catalog-section-item-name-property intec-grid-item-auto">
                                            <span class="catalog-section-item-name-property-name">
                                                <?= $arProperty['NAME'] ?>
                                            </span>
                                            <span class="catalog-section-item-name-property-value">
                                                <?= !Type::isArray($arProperty['DISPLAY_VALUE']) ?
                                                    $arProperty['DISPLAY_VALUE'] :
                                                    implode(', ', $arProperty['DISPLAY_VALUE']) ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <?php $iPropertiesCounter = 0 ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="catalog-section-item-properties-wrap intec-grid-item">
                            <div class="catalog-section-item-properties intec-grid intec-grid-1200-wrap intec-grid-a-v-center intec-grid-i-h-16 intec-grid-i-v-10">
                                <?php foreach($arItem['DISPLAY_PROPERTIES'] as $arProperty) {
                                    $iPropertiesCounter ++;

                                    if ($iPropertiesCounter <= 2)
                                        continue;

                                    if ($iPropertiesCounter > $arVisual['PROPERTIES']['AMOUNT'])
                                        break;
                                    ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'catalog-section-item-property' => true,
                                            'intec-grid-item' => [
                                                '4' => $arVisual['PROPERTIES']['COLUMNS'] == 4,
                                                '3' => $arVisual['PROPERTIES']['COLUMNS'] == 3,
                                                '2' => $arVisual['PROPERTIES']['COLUMNS'] == 2,
                                                '1' => $arVisual['PROPERTIES']['COLUMNS'] == 1,
                                                '1200-3' => $arVisual['PROPERTIES']['COLUMNS'] == 4,
                                                '1000-2' => $arVisual['PROPERTIES']['COLUMNS'] > 2,
                                            ]
                                        ], true)
                                    ]) ?>
                                        <div class="catalog-section-item-property-name">
                                            <?= $arProperty['NAME'] ?>
                                        </div>
                                        <div class="catalog-section-item-property-value">
                                            <?= !Type::isArray($arProperty['DISPLAY_VALUE']) ?
                                                $arProperty['DISPLAY_VALUE'] :
                                                implode(', ', $arProperty['DISPLAY_VALUE']) ?>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($bQuantity) { ?>
                            <div class="catalog-section-item-quantity-wrap intec-grid-item">
                                <?php $vQuantity($arItem) ?>
                            </div>
                        <?php } ?>
                        <div class="catalog-section-item-price-wrap intec-grid-item">
                            <?php $vPrice($arItem) ?>
                        </div>
                        <div class="catalog-section-item-buttons-wrap intec-grid-item">
                            <div class="catalog-section-item-buttons intec-grid intec-grid-wrap intec-grid-i-5">
                                <div class="catalog-section-item-action-buttons-wrap intec-grid-item intec-grid-item-900-auto">
                                    <!--noindex-->
                                    <?php $vButtons($arItem) ?>
                                    <!--/noindex-->
                                </div>
                                <div class="catalog-section-item-order-buttons-wrap intec-grid-item-1 intec-grid-item-900-auto">
                                    <!--noindex-->
                                    <?php $vOrder($arItem) ?>
                                    <!--/noindex-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($bOffers) { ?>
                    <div class="catalog-section-item-offers-wrap">
                        <!--noindex-->
                        <?php $vSku($arSkuProps) ?>
                        <!--/noindex-->
                    </div>
                <?php } ?>
            </div>
            <?php if ($arItem['ACTION'] == 'buy') { ?>
                <div class="catalog-section-item-additional-wrap" data-role="item.toggle">
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'catalog-section-item-additional' => true,
                            'intec-grid' => [
                                '' => true,
                                'wrap' => true,
                                'a-v-center' => true,
                                'a-h-end' => true,
                                'a-h-1000-between' => true,
                                'i-5' => true
                            ]
                        ], true)
                    ]) ?>
                    <?php if ($bCounter) { ?>
                        <!--noindex-->
                        <div class="catalog-section-item-counter intec-grid-item-auto intec-grid-item-768-1 intec-grid intec-grid-a-v-center intec-grid-a-h-400-between">
                            <div class="catalog-section-item-ratio">
                                <?php $vMeasure($arItem) ?>
                            </div>
                            <div class="catalog-section-item-counter-wrapper">
                                <div class="intec-ui intec-ui-control-numeric intec-ui-view-5 intec-ui-size-5 intec-ui-scheme-current" data-role="item.counter">
                                    <?= Html::tag('a', '-', [
                                        'class' => 'intec-ui-part-decrement',
                                        'href' => 'javascript:void(0)',
                                        'data-type' => 'button',
                                        'data-action' => 'decrement'
                                    ]) ?>
                                    <?= Html::input('text', null, 0, [
                                        'data-type' => 'input',
                                        'class' => 'intec-ui-part-input'
                                    ]) ?>
                                    <?= Html::tag('a', '+', [
                                        'class' => 'intec-ui-part-increment',
                                        'href' => 'javascript:void(0)',
                                        'data-type' => 'button',
                                        'data-action' => 'increment'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        <!--/noindex-->
                    <?php } ?>
                    <div class="catalog-section-item-purchase-wrap intec-grid-item-auto">
                        <!--noindex-->
                        <?php $vPurchase($arItem) ?>
                        <!--/noindex-->
                    </div>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
        </div>
        <?= Html::endTag('div');?>
    <?php } ?>
    <?= Html::endTag('div');?>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON']) { ?>
        <!--noindex-->
        <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
            <div class="catalog-section-more-button">
                <div class="catalog-section-more-icon intec-cl-background">
                    <i class="glyph-icon-show-more"></i>
                </div>
                <div class="catalog-section-more-text intec-cl-text">
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_LAZY_TEXT') ?>
                </div>
            </div>
        </div>
        <!--/noindex-->
    <?php } ?>
    <?php if ($arVisual['NAVIGATION']['BOTTOM']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-bottom" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult['NAV_STRING'] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div');?>
