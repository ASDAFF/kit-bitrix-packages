<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
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
        'c-catalog-section-catalog-list-1'
    ],
    'data' => [
        'borders' => $arVisual['BORDERS'] ? 'true' : 'false',
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
                <div class="catalog-section-item-wrapper">
                    <div class="catalog-section-item-background clearfix">
                        <div class="catalog-section-item-image">
                            <div class="catalog-section-item-image-wrapper">
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
                                <?php if ($arResult['QUICK_VIEW']['USE'] && !$arResult['QUICK_VIEW']['DETAIL']) { ?>
                                    <div class="catalog-section-item-quick-view">
                                        <div class="intec-aligner"></div>
                                        <div class="catalog-section-item-quick-view-button" data-role="quick.view">
                                            <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="catalog-section-item-purchase">
                            <div class="catalog-section-item-purchase-wrapper">
                                <?php $vPrice($arItem) ?>
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
                                <!--noindex-->
                                <?php $vPurchase($arItem) ?>
                                <div class="catalog-section-item-price-buttons-wrap">
                                    <?php $vButtons($arItem) ?>
                                </div>
                                <!--/noindex-->
                            </div>
                        </div>
                        <div class="catalog-section-item-content">
                            <div class="catalog-section-item-name">
                                <?= Html::tag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', $sName, [
                                    'class' => [
                                        'catalog-section-item-name-wrapper',
                                        'intec-cl-text-hover'
                                    ],
                                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $sLink : null,
                                    'data' => [
                                        'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                                    ]
                                ])?>
                            </div>
                            <?php if ($bVote || $bQuantity) { ?>
                                <!--noindex-->
                                <div class="intec-grid intec-grid-wrap intec-grid-i-h-15 intec-grid-a-v-center">
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
                            <?php if (!empty($sDescription)) { ?>
                                <div class="catalog-section-item-description">
                                    <?= $sDescription ?>
                                </div>
                            <?php } ?>
                            <?php if ($arVisual['PROPERTIES']['SHOW'] && !empty($arItem['DISPLAY_PROPERTIES'])) { ?>
                                <div class="catalog-section-item-properties">
                                    <ul class="catalog-section-item-properties-wrapper intec-ui-mod-simple">
                                        <?php foreach($arItem['DISPLAY_PROPERTIES'] as $arProperty) { ?>
                                            <li>
                                                <span class="intec-cl-text bullet">
                                                    &#x2022;
                                                </span>
                                                <span>
                                                    <?= $arProperty['NAME'].' &#8212; '.(!Type::isArray($arProperty['DISPLAY_VALUE']) ?
                                                        $arProperty['DISPLAY_VALUE'] :
                                                        implode(', ', $arProperty['DISPLAY_VALUE'])
                                                    ) ?>
                                                </span>
                                            </li>
                                        <?php } ?>
                                        <?php unset($arProperty) ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            <?php if ($bOffers) { ?>
                                <!--noindex-->
                                <?php $vSku($arSkuProps) ?>
                                <!--/noindex-->
                            <?php } ?>
                        </div>
                    </div>
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
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_1_LAZY_TEXT') ?>
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
