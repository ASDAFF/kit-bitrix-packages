<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$iItemsCount = null;
$iItemsCurrent = 0;

if ($arVisual['LINES'] !== null)
    $iItemsCount = $arVisual['COLUMNS']['DESKTOP'] * $arVisual['LINES'];

?>
<?= Html::beginTag('div', [
    'class' => Html::cssClassFromArray([
        'widget-items' => true,
        'owl-carousel' => $arVisual['SLIDER']['USE'],
        'intec-grid' => $arVisual['SLIDER']['USE'] ? false : [
            '' => true,
            'wrap' => true,
            'a-v-stretch' => true,
            'a-h-start' => true
        ]
    ], true),
    'data-role' => 'items'
]) ?>
    <?php foreach ($arItems as $arItem) {

        $sId = $sTemplateId.'_'.$arItem['ID'];
        $sAreaId = $this->GetEditAreaId($sId);
        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

        $sData = Json::encode($dData($arItem), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true);
        $sLink = Html::decode($arItem['DETAIL_PAGE_URL']);
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
                'widget-item' => true,
                'intec-grid-item' => $arVisual['SLIDER']['USE'] ? null : [
                    $arVisual['COLUMNS']['DESKTOP'] => true,
                    '500-1' => ($arVisual['COLUMNS']['DESKTOP'] <= 4) && $arVisual['COLUMNS']['MOBILE'] == 1,
                    '500-2' => ($arVisual['COLUMNS']['DESKTOP'] <= 4) && $arVisual['COLUMNS']['MOBILE'] == 2,
                    '800-2' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                    '1000-3' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 3,
                    '700-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                    '720-3' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                    '950-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                    '1200-3' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 3
                ]
            ],  true),
            'data' => [
                'id' => $arItem['ID'],
                'role' => 'item',
                'data' => $sData,
                'expanded' => 'false',
                'available' => $arItem['CAN_BUY'] ? 'true' : 'false'
            ]
        ]) ?>
            <div class="widget-item-wrapper" data-borders-style="<?= $arVisual['BORDERS']['STYLE'] ?>">
                <div class="widget-item-substrate"></div>
                <div class="widget-item-base">
                    <div class="widget-item-image-container">
                        <?php $vImage($arItem) ?>
                        <div class="widget-item-action-container-wrap">
                            <div class="intec-aligner"></div>
                            <div class="widget-item-action-container">
                                <?php if ($arItem['ACTION'] !== 'none') { ?>
                                    <div class="widget-item-purchase-container">
                                        <div class="widget-item-purchase">
                                            <?php $vPurchase($arItem) ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['QUICK_VIEW']['USE'] && !$arResult['QUICK_VIEW']['DETAIL']) { ?>
                                    <div class="widget-item-quick-view">
                                        <div class="intec-aligner"></div>
                                        <div class="widget-item-quick-view-button" data-role="quick.view">
                                            <div class="widget-item-quick-view-button-icon">
                                                <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                                            </div>
                                            <div class="widget-item-quick-view-button-text">
                                                <?= Loc::getMessage('C_WIDGET_PRODUCTS_1_QUICK_VIEW') ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!--noindex-->
                        <div class="widget-item-marks">
                            <?php $APPLICATION->includeComponent(
                                'intec.universe:main.markers',
                                'template.1', [
                                'HIT' => $arItem['MARKS']['HIT'] ? 'Y' : 'N',
                                'NEW' => $arItem['MARKS']['NEW'] ? 'Y' : 'N',
                                'RECOMMEND' => $arItem['MARKS']['RECOMMEND'] ? 'Y' : 'N',
                                'ORIENTATION' => $arVisual['MARKS']['ORIENTATION']
                            ],
                                $component
                            ) ?>
                        </div>
                        <?php $vButtons($arItem) ?>
                        <!--/noindex-->
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
                                $component
                            ) ?>
                        </div>
                    <?php } ?>
                    <?php if ($arVisual['QUANTITY']['SHOW']) { ?>
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
                            'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $sLink : null,
                            'data' => [
                                'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                            ]
                        ]) ?>
                    </div>
                    <?php if ($arVisual['SECTION']['SHOW'] && !empty($arItem['SECTION'])) { ?>
                        <div class="widget-item-section" data-align="<?= $arVisual['SECTION']['ALIGN'] ?>">
                            <a class="intec-cl-text-hover" href="<?= $arItem['SECTION']['SECTION_PAGE_URL'] ?>">
                                <?= $arItem['SECTION']['NAME'] ?>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arPrice)) {
                        $vPrice($arPrice);
                    } ?>
                </div>
                <!--noindex-->
                <div class="widget-item-advanced">
                    <?php if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']) && !empty($arSkuProps)) {
                        $vSku($arSkuProps);
                    } ?>
                </div>
                <!--/noindex-->
            </div>
        <?= Html::endTag('div') ?>
        <?php $iItemsCurrent++; ?>
    <?php } ?>
<?= Html::endTag('div') ?>
<?php if ($arVisual['SLIDER']['USE']) { ?>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var area = root;
            var data = <?= JavaScript::toObject([
                'columns' => [
                    'desktop' => $arVisual['COLUMNS']['DESKTOP'],
                    'mobile' => $arVisual['COLUMNS']['MOBILE']
                ],
                'navigation' => $arVisual['SLIDER']['NAVIGATION'],
                'dots' => $arVisual['SLIDER']['DOTS']
            ]) ?>;

            <?php if (!empty($arCategory)) { ?>
            area = $(<?= JavaScript::toObject('#'.$sTemplateId.'-tab-'.$iCounter) ?>, root);
            <?php } ?>

            handler = function () {
                var items = area.find('.owl-stage:first');

                items.children('.owl-item').css('visibility', 'collapse');
                items.children('.owl-item.active').css('visibility', '');
            };

            var slider = $('.widget-items', area);
            var responsive = {
                0: {
                    'items': <?= $arVisual['COLUMNS']['MOBILE'] ?>
                }
            };

            if (data.columns.desktop > 2)
                responsive[500] = {
                    'items': 2
                };

            if (data.columns.desktop > 3)
                responsive[820] = {
                    'items': 3
                };

            if (data.columns.desktop > 4)
                responsive[1100] = {
                    'items': 4
                };

            responsive[1200] = {'items': data.columns.desktop};
            slider.owlCarousel({
                'center': false,
                'loop': false,
                'nav': data.navigation,
                'navText': [
                    '<i class="fa fa-arrow-left intec-cl-text-hover"></i>',
                    '<i class="fa fa-arrow-right intec-cl-text-hover"></i>'
                ],
                'stagePadding': 5,
                'dots': data.dots,
                'responsive': responsive,
                'onResized': handler,
                'onRefreshed': handler,
                'onInitialized': handler,
                'onTranslated': handler
            });
        })(jQuery, intec);
    </script>
<?php } ?>