<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$iItemsCount = null;
$iItemsCurrent = 0;

if ($arVisual['LINES'] !== null)
    $iItemsCount = $arVisual['COLUMNS']['DESKTOP'] * $arVisual['LINES'];

?>
<?= Html::beginTag('div', [
    'class' => [
        'widget-items',
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
    <?php foreach ($arItems as $arItem) {

        if ($iItemsCount !== null)
            if ($iItemsCurrent >= $iItemsCount)
                break;

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
                'widget-item' => true,
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
            <div class="widget-item-wrapper">
                <div class="widget-item-base">
                    <div class="widget-item-image-block">
                        <div class="widget-item-image-wrap">
                            <?php $vImage($arItem) ?>
                            <?php if ($arResult['QUICK_VIEW']['USE'] && !$arResult['QUICK_VIEW']['DETAIL']) { ?>
                                <div class="widget-item-quick-view">
                                    <div class="intec-aligner"></div>
                                    <div class="widget-item-quick-view-button" data-role="quick.view">
                                        <div class="widget-item-quick-view-button-icon">
                                            <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                                        </div>
                                        <div class="widget-item-quick-view-button-text">
                                            <?= Loc::getMessage('C_WIDGET_PRODUCTS_3_QUICK_VIEW') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($arVisual['MARKS']['SHOW']) { ?>
                            <!--noindex-->
                            <div class="widget-item-marks">
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
                        <div class="widget-item-vote" data-align="<?= $arVisual['VOTE']['ALIGN'] ?>">
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
                        <div class="widget-item-quantity-wrap">
                            <?php $vQuantity($arItem) ?>
                        </div>
                    <?php } ?>
                    <!--/noindex-->
                    <div class="widget-item-name" data-align="<?= $arVisual['NAME']['ALIGN'] ?>">
                        <?= Html::tag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', $arItem['NAME'], [
                            'class' => [
                                'intec-cl-text-hover'
                            ],
                            'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arItem['DETAIL_PAGE_URL'] : null,
                            'data' => [
                                'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                            ]
                        ]) ?>
                    </div>
                    <?php if ($arVisual['WEIGHT']['SHOW']) { ?>
                        <?= Html::tag('div', '', [
                            'class' => [
                                'widget-item-weight',
                                'intec-cl-text'
                            ],
                            'data' => [
                                'role' => 'item.weight',
                                'align' => $arVisual['WEIGHT']['ALIGN']
                            ]
                        ]) ?>
                    <?php } ?>
                    <?php if ($arVisual['SECTION']['SHOW'] && !empty($arItem['SECTION'])) { ?>
                        <div class="widget-item-section" data-align="<?= $arVisual['SECTION']['ALIGN'] ?>">
                            <a class="intec-cl-text-hover" href="<?= $arItem['SECTION']['SECTION_PAGE_URL'] ?>">
                                <?= $arItem['SECTION']['NAME'] ?>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) { ?>
                        <div class="widget-item-description" data-align="<?= $arVisual['DESCRIPTION']['ALIGN'] ?>">
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
                <div class="widget-item-advanced">
                    <?php $vPrice($arPrice) ?>
                    <?php if ($arItem['ACTION'] !== 'none') { ?>
                        <div class="widget-item-purchase-block intec-grid intec-grid-a-v-center">
                            <?php if ($arVisual['COUNTER']['SHOW'] && $arItem['ACTION'] === 'buy') { ?>
                                <div class="widget-item-counter-block intec-grid-item intec-grid-item-shrink-1">
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
        <?php $iItemsCurrent++ ?>
    <?php } ?>
<?= Html::endTag('div') ?>