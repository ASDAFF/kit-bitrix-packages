<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
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
 * @var Closure $vButtons
 * @var Closure $vCounter
 * @var Closure $dData
 * @var Closure $vImage
 * @var Closure $vPrice
 * @var Closure $vPurchase
 * @var Closure $vQuantity
 * @var Closure $vSku
 * @var Closure $vQuickView
 */
include(__DIR__.'/parts/buttons.php');
include(__DIR__.'/parts/counter.php');
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
        'c-catalog-section-catalog-tile-3'
    ],
    'data' => [
        'properties' => !empty($arResult['SKU_PROPS']) ? Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
        'columns-mobile' => $arVisual['COLUMNS']['MOBILE'],
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
        <?php foreach ($arResult['ITEMS'] as $arItem) {

            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

            $sData = Json::encode($dData($arItem), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true);
            $arPrice = null;
            $bOffers = $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']);
            $bQuantity = $arVisual['QUANTITY']['SHOW'] && ($bOffers || empty($arItem['OFFERS']));

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
                        '550-1' => $arVisual['COLUMNS']['DESKTOP'] >= 2 && $arVisual['COLUMNS']['MOBILE'] == 1,
                        '850-2' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] >= 3,
                        '1200-3' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] >= 4,
                        '720-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] >= 2,
                        '900-1' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] >= 2,
                        '1200-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] >= 3
                    ]
                ], true),
                'data' => [
                    'id' => $arItem['ID'],
                    'role' => 'item',
                    'data' => $sData,
                    'entity' => 'items-row',
                    'expanded' => 'false',
                    'available' => $arItem['CAN_BUY'] ? 'true' : 'false'
                ]
            ]) ?>
                <div class="catalog-section-item-wrapper">
                    <div class="catalog-section-item-base">
                        <div class="catalog-section-item-image-block">
                            <div class="catalog-section-item-image-wrap">
                                <?php $vImage($arItem) ?>
                                <?php if ($arResult['QUICK_VIEW']['USE'] && !$arResult['QUICK_VIEW']['DETAIL']) { ?>
                                    <div class="catalog-section-item-quick-view">
                                        <div class="intec-aligner"></div>
                                        <div class="catalog-section-item-quick-view-button" data-role="quick.view">
                                            <div class="catalog-section-item-quick-view-button-icon">
                                                <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                                            </div>
                                            <div class="catalog-section-item-quick-view-button-text">
                                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_3_QUICK_VIEW') ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($arVisual['MARKS']['SHOW']) { ?>
                                <!--noindex-->
                                <div class="catalog-section-item-marks">
                                    <?php $APPLICATION->IncludeComponent(
                                        'intec.universe:main.markers',
                                        'template.1', [
                                            'HIT' => $arItem['MARKS']['HIT'] ? 'Y' : 'N',
                                            'NEW' => $arItem['MARKS']['NEW'] ? 'Y' : 'N',
                                            'RECOMMEND' => $arItem['MARKS']['RECOMMEND'] ? 'Y' : 'N',
                                            'ORIENTATION' => $arVisual['MARKS']['ORIENTATION']
                                        ],
                                        $component,
                                        ['HIDE_ICONS' => 'Y']
                                    ) ?>
                                </div>
                                <!--/noindex-->
                            <?php } ?>
                            <?php if ($arResult['COMPARE']['USE'] || $arResult['DELAY']['USE']) {
                                $vButtons($arItem);
                            } ?>
                        </div>
                        <!--noindex-->
                        <?php if ($arVisual['VOTE']['SHOW']) { ?>
                            <div class="catalog-section-item-vote" data-align="<?= $arVisual['VOTE']['ALIGN'] ?>">
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
                        <?php if ($bQuantity) { ?>
                            <div class="catalog-section-item-quantity-wrap">
                                <?php $vQuantity($arItem) ?>
                            </div>
                        <?php } ?>
                        <!--/noindex-->
                        <div class="catalog-section-item-name" data-align="<?= $arVisual['NAME']['ALIGN'] ?>">
                            <?= Html::tag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', $arItem['NAME'], [
                                'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arItem['DETAIL_PAGE_URL'] : null,
                                'class' => [
                                    'intec-cl-text-hover',
                                ],
                                'data' => [
                                    'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                                ]
                            ]) ?>
                        </div>
                        <?php if ($arVisual['WEIGHT']['SHOW']) { ?>
                            <?= Html::tag('div', '', [
                                'class' => [
                                    'catalog-section-item-weight',
                                    'intec-cl-text'
                                ],
                                'data' => [
                                    'role' => 'item.weight',
                                    'align' => $arVisual['WEIGHT']['ALIGN']
                                ]
                            ]) ?>
                        <?php } ?>
                        <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) { ?>
                            <div class="catalog-section-item-description" data-align="<?= $arVisual['DESCRIPTION']['ALIGN'] ?>">
                                <?= $arItem['PREVIEW_TEXT'] ?>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arItem['OFFERS']) && !empty($arSkuProps) && $arVisual['OFFERS']['USE']) { ?>
                            <!--noindex-->
                            <?php $vSku($arSkuProps) ?>
                            <!--/noindex-->
                        <?php } ?>
                    </div>
                    <!--noindex-->
                    <div class="catalog-section-item-advanced">
                        <?php $vPrice($arPrice) ?>
                        <?php if ($arItem['ACTION'] !== 'none') { ?>
                            <div class="catalog-section-item-purchase-block intec-grid intec-grid-a-v-center">
                                <?php if ($arVisual['COUNTER']['SHOW'] && $arItem['ACTION'] === 'buy') { ?>
                                    <div class="catalog-section-item-counter-block intec-grid-item intec-grid-item-shrink-1">
                                        <?php $vCounter() ?>
                                    </div>
                                <?php } ?>
                                <div class="intec-grid-item">
                                    <?php $vPurchase($arItem) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!--/noindex-->
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON']) { ?>
        <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
            <div class="catalog-section-more-button">
                <div class="catalog-section-more-icon intec-cl-background">
                    <i class="glyph-icon-show-more"></i>
                </div>
                <div class="catalog-section-more-text intec-cl-text">
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_3_LAZY_TEXT') ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($arVisual['NAVIGATION']['BOTTOM']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-bottom" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult['NAV_STRING'] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
<?= Html::endTag('div') ?>
<?php include(__DIR__.'/parts/script.php') ?>