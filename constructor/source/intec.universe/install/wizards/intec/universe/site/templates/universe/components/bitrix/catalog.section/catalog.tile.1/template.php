<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

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

/**
 * @var Closure $dData(&$arItem)
 * @var Closure $vButtons(&$arItem)
 * @var Closure $vImage(&$arItem)
 * @var Closure $vPrice(&$arItem)
 * @var Closure $vPurchase(&$arItem)
 * @var Closure $vQuantity(&$arItem)
 * @var Closure $vSku($arProperties)
 */
include(__DIR__.'/parts/buttons.php');
include(__DIR__.'/parts/data.php');
include(__DIR__.'/parts/image.php');
include(__DIR__.'/parts/price.php');
include(__DIR__.'/parts/purchase.php');
include(__DIR__.'/parts/quantity.php');
include(__DIR__.'/parts/sku.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-catalog-tile-1'
    ],
    'data' => [
        'borders' => $arVisual['BORDERS'] ? 'true' : 'false',
        'columns-desktop' => $arVisual['COLUMNS']['DESKTOP'],
        'columns-mobile' => $arVisual['COLUMNS']['MOBILE'],
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
    <!-- items-container -->
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-section-items',
            'intec-grid' => [
                '',
                'wrap',
                'a-v-stretch',
                'a-h-start'
            ]
        ],
        'data' => [
            'entity' => $sTemplateContainer
        ]
    ]) ?>
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <?php
            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

            $sData = Json::encode($dData($arItem), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true);
            $sName = $arItem['NAME'];
            $sLink = $arItem['DETAIL_PAGE_URL'];
            $bOffers = $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']);
            $bQuantity = $arVisual['QUANTITY']['SHOW'] && ($bOffers || empty($arItem['OFFERS']));
            $bVote = $arVisual['VOTE']['SHOW'];
            $bCounter = $arVisual['COUNTER']['SHOW'] && $arItem['ACTION'] === 'buy';
            $arPrice = null;

            if (!empty($arItem['ITEM_PRICES']))
                $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);

            $arSkuProps = [];

            if (!empty($arResult['SKU_PROPS']))
                $arSkuProps = $arResult['SKU_PROPS'];
            else if (!empty($arItem['SKU_PROPS']))
                $arSkuProps = $arItem['SKU_PROPS'];

        ?>
            <?= Html::beginTag('div', [
                'id' => $sAreaId,
                'class' => Html::cssClassFromArray([
                    'catalog-section-item' => true,
                    'intec-grid-item' => [
                        $arVisual['COLUMNS']['DESKTOP'] => true,
                        '450-1' => $arVisual['COLUMNS']['DESKTOP'] < 4 && $arVisual['COLUMNS']['MOBILE'] == 1,
                        '800-2' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '1000-3' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 3,
                        '700-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '720-3' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '950-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '1200-3' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 3
                    ]
                ], true),
                'data' => [
                    'id' => $arItem['ID'],
                    'role' => 'item',
                    'data' => $sData,
                    'expanded' => 'false',
                    'available' => $arItem['CAN_BUY'] ? 'true' : 'false',
                    'subscribe' => $arData['subscribe'] ? 'true' : 'false',
                    'entity' => 'items-row'
                ]
            ]) ?>
                <div class="catalog-section-item-wrapper">
                    <div class="catalog-section-item-background"></div>
                    <div class="catalog-section-item-content">
                        <div class="catalog-section-item-image">
                            <!--noindex-->
                            <div class="catalog-section-item-image-marks">
                                <?php $APPLICATION->IncludeComponent(
                                    'intec.universe:main.markers',
                                    'template.1', [
                                        'HIT' => $arItem['MARKS']['HIT'] ? 'Y' : 'N',
                                        'NEW' => $arItem['MARKS']['NEW'] ? 'Y' : 'N',
                                        'RECOMMEND' => $arItem['MARKS']['RECOMMEND'] ? 'Y' : 'N',
                                        'ORIENTATION' => 'vertical'
                                    ],
                                    $component,
                                    ['HIDE_ICONS' => 'Y']
                                ) ?>
                            </div>
                            <!--/noindex-->
                            <?php $vImage($arItem) ?>
                            <!--noindex-->
                            <?php $vButtons($arItem) ?>
                            <?php if ($arResult['QUICK_VIEW']['USE'] && !$arResult['QUICK_VIEW']['DETAIL']) { ?>
                                <div class="catalog-section-item-quick-view">
                                    <div class="intec-aligner"></div>
                                    <div class="catalog-section-item-quick-view-button" data-role="quick.view">
                                        <div class="catalog-section-item-quick-view-button-icon">
                                            <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                                        </div>
                                        <div class="catalog-section-item-quick-view-button-text">
                                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_1_QUICK_VIEW') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!--/noindex-->
                        </div>

                        <?php if ($bVote || $bQuantity) { ?>
                            <!--noindex-->
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
                                            'COMPONENT_TEMPLATE' => 'template.1',
                                            'DISPLAY_AS_RATING' => $arVisual['VOTE']['MODE'] === 'rating' ? 'rating' : 'vote_avg',
                                            'SHOW_RATING' => 'N'
                                        ),
                                        $component,
                                        ['HIDE_ICONS' => 'Y']
                                    ) ?>
                                </div>
                            <?php } ?>
                            <?php if ($bQuantity) { ?>
                                <div class="catalog-section-item-quantity-wrap">
                                    <?php $vQuantity($arItem) ?>
                                </div>
                            <?php } ?>
                            <!--/noindex-->
                        <?php } ?>

                        <div class="catalog-section-item-name">
                            <?= Html::tag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', $sName, [
                                'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $sLink : null,
                                'class' => [
                                    'catalog-section-item-name-wrapper',
                                    'intec-cl-text-hover',
                                ],
                                'data' => [
                                    'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                                ]
                            ]) ?>
                        </div>
                        <?= Html::beginTag('div', [
                            'class' => 'catalog-section-item-price',
                            'data' => [
                                'role' => 'item.price',
                                'show' => !empty($arPrice),
                                'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
                            ]
                        ]) ?>
                            <?php if ($bCounter || $bOffers) { ?>
                                <?php $vPrice($arItem) ?>
                            <?php } else { ?>
                                <div class="intec-grid intec-grid-nowrap intec-grid-i-5 intec-grid-a-v-center intec-grid-a-h-center">
                                    <div class="intec-grid-item">
                                        <?php $vPrice($arItem) ?>
                                    </div>
                                    <div class="intec-grid-item-auto">
                                        <!--noindex-->
                                        <?php $vPurchase($arItem) ?>
                                        <!--/noindex-->
                                    </div>
                                </div>
                            <?php } ?>
                        <?= Html::endTag('div') ?>
                        <div class="catalog-section-item-advanced" data-role="item-advanced">
                            <?php if ($bOffers) { ?>
                                <!--noindex-->
                                <?php $vSku($arSkuProps) ?>
                                <!--/noindex-->
                            <?php } ?>
                            <?php if ($bCounter || $bOffers) { ?>
                                <!--noindex-->
                                <div class="catalog-section-item-purchase">
                                    <div class="intec-grid intec-grid-wrap intec-grid-i-5 intec-grid-a-v-center">
                                        <div class="catalog-section-item-purchase-counter-wrap intec-grid-item">
                                            <?php if ($bCounter) { ?>
                                                <div class="catalog-section-item-purchase-counter intec-ui intec-ui-control-numeric intec-ui-view-1 intec-ui-scheme-current" data-role="item.counter">
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
                                            <?php } ?>
                                        </div>
                                        <div class="intec-grid-item-auto">
                                            <?php $vPurchase($arItem) ?>
                                        </div>
                                    </div>
                                </div>
                                <!--/noindex-->
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON']) { ?>
        <!--noindex-->
        <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
            <div class="catalog-section-more-button">
                <div class="catalog-section-more-icon intec-cl-background">
                    <i class="glyph-icon-show-more"></i>
                </div>
                <div class="catalog-section-more-text intec-cl-text">
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_1_LAZY_TEXT') ?>
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
<?= Html::endTag('div') ?>