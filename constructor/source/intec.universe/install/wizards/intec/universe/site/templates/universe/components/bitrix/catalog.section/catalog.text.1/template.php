<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

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
 */
include(__DIR__.'/parts/buttons.php');
include(__DIR__.'/parts/data.php');
include(__DIR__.'/parts/image.php');
include(__DIR__.'/parts/price.php');
include(__DIR__.'/parts/purchase.php');
include(__DIR__.'/parts/quantity.php');

?>

<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-catalog-text-1'
    ],
    'data' => [
        'borders' => $arVisual['BORDERS'] ? 'true' : 'false',
        'properties' => Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'wide' => $arVisual['WIDE'] ? 'true' : 'false'
    ]
]) ?>
    <?php if ($arVisual['NAVIGATION']['TOP']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-top" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult["NAV_STRING"] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
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

            $arData = $dData($arItem);
            $sData = Json::encode($arData, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true);
            $sName = $arItem['NAME'];
            $sLink = $arItem['DETAIL_PAGE_URL'];
            $bOffers = !empty($arItem['OFFERS']);
            $bQuantity = $arVisual['QUANTITY']['SHOW'] && !$bOffers;
            $bVote = $arVisual['VOTE']['SHOW'];
            $bCounter = $arVisual['COUNTER']['SHOW'] && $arItem['ACTION'] === 'buy' && !$bOffers;
        ?>
            <?= Html::beginTag('div', [
                'id' => $sAreaId,
                'class' => 'catalog-section-item',
                'data' => [
                    'id' => $arItem['ID'],
                    'role' => 'item',
                    'data' => $sData,
                    'expanded' => 'false',
                    'available' => $arItem['CAN_BUY'] ? 'true' : 'false',
                    'entity' => 'items-row'
                ]
            ]) ?>
                <div class="catalog-section-item-wrapper">
                    <div class="catalog-section-item-background">
                        <div class="intec-grid intec-grid-wrap intec-grid-a-v-center">
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'intec-grid-item' => [
                                        '' => true,
                                        '550-1' => true
                                    ]
                                ], true)
                            ]) ?>
                                <div class="intec-grid intec-grid-wrap intec-grid-a-v-center">
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'intec-grid-item' => [
                                                '' => true,
                                                'auto' => true,
                                                '800-1' => !$arVisual['WIDE'],
                                                '720-auto' => !$arVisual['WIDE'],
                                                '550-1' => true
                                            ]
                                        ], true)
                                    ]) ?>
                                        <div class="catalog-section-item-image">
                                            <?php $vImage($arItem) ?>
                                            <?php if ($arResult['QUICK_VIEW']['USE'] && !$arResult['QUICK_VIEW']['DETAIL']) { ?>
                                                <div class="catalog-section-item-quick-view">
                                                    <div class="intec-aligner"></div>
                                                    <div class="catalog-section-item-quick-view-button" data-role="quick.view">
                                                        <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'intec-grid-item' => [
                                                '' => true,
                                                '800-1' => !$arVisual['WIDE'],
                                                '720' => !$arVisual['WIDE'],
                                                '550-1' => true,
                                                'shrink-1' => true
                                            ]
                                        ], true)
                                    ]) ?>
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
                                        <?php if ($bVote || $bQuantity) { ?>
                                            <!--noindex-->
                                            <div class="intec-grid intec-grid-wrap intec-grid-i-h-15 intec-grid-a-v-center intec-grid-a-h-550-center">
                                                <?php if ($bVote) { ?>
                                                    <div class="intec-grid-item intec-grid-item-auto">
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
                                                    </div>
                                                <?php } ?>
                                                <?php if ($bQuantity) { ?>
                                                    <div class="intec-grid-item intec-grid-item-auto">
                                                        <div class="catalog-section-item-quantity-wrap">
                                                            <?php $vQuantity($arItem) ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <!--/noindex-->
                                        <?php } ?>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?= Html::endTag('div') ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'intec-grid-item' => [
                                        'auto' => true,
                                        '1100-3' => !$arVisual['WIDE'],
                                        '800-1' => !$arVisual['WIDE'],
                                        '900-3' => $arVisual['WIDE'],
                                        '720-3' => !$arVisual['WIDE'],
                                        '550-1' => true
                                    ]
                                ], true)
                            ]) ?>
                                <?= Html::beginTag('div', [
                                    'class' => Html::cssClassFromArray([
                                        'intec-grid' => [
                                            '' => true,
                                            'wrap' => true,
                                            'a-v-center' => true,
                                            'a-h-end' => true,
                                            'a-h-800-center' => !$arVisual['WIDE'],
                                            'a-h-720-end' => !$arVisual['WIDE'],
                                            'a-h-550-center' => true
                                        ]
                                    ], true)
                                ]) ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'intec-grid-item' => [
                                                'auto' => true,
                                                '1100-1' => !$arVisual['WIDE'],
                                                '800-1' => !$arVisual['WIDE'],
                                                '550-1' => true
                                            ]
                                        ], true)
                                    ]) ?>
                                        <!--noindex-->
                                        <?php $vButtons($arItem) ?>
                                        <!--/noindex-->
                                    <?= Html::endTag('div') ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'catalog-section-item-price-wrap' => true,
                                            'intec-grid-item' => [
                                                'auto' => true,
                                                '1100-1' => !$arVisual['WIDE'],
                                                '900-1' => $arVisual['WIDE']
                                            ]
                                        ], true)
                                    ]) ?>
                                        <?php $vPrice($arItem) ?>
                                    <?= Html::endTag('div') ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'intec-grid-item' => [
                                                'auto' => true,
                                                '1100-1' => !$arVisual['WIDE'],
                                                '900-1' => $arVisual['WIDE']
                                            ]
                                        ], true)
                                    ]) ?>
                                        <?php if ($bCounter) { ?>
                                            <!--noindex-->
                                            <div class="catalog-section-item-counter">
                                                <div class="catalog-section-item-counter-wrapper">
                                                    <div class="intec-ui intec-ui-control-numeric intec-ui-view-1 intec-ui-scheme-current" data-role="item.counter">
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
                                    <?= Html::endTag('div') ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'intec-grid-item' => [
                                                'auto' => true,
                                                '1100-1' => !$arVisual['WIDE'],
                                                '900-1' => $arVisual['WIDE']
                                            ]
                                        ], true)
                                    ]) ?>
                                        <!--noindex-->
                                        <?php $vPurchase($arItem) ?>
                                        <!--/noindex-->
                                    <?= Html::endTag('div') ?>
                                <?= Html::endTag('div') ?>
                            <?= Html::endTag('div') ?>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div');?>
        <?php } ?>
    <?= Html::endTag('div');?>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON'] && ($arNavigation['NavPageNomer'] < $arNavigation['NavPageCount'])) { ?>
        <!--noindex-->
        <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
            <div class="catalog-section-more-button">
                <div class="catalog-section-more-icon intec-cl-background">
                    <i class="glyph-icon-show-more"></i>
                </div>
                <div class="catalog-section-more-text intec-cl-text">
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_1_LAZY_TEXT') ?>
                </div>
            </div>
        </div>
        <!--/noindex-->
    <?php } ?>
    <?php if ($arVisual['NAVIGATION']['BOTTOM']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-bottom" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult["NAV_STRING"] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div');?>
