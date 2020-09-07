<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php foreach ($arResult['ITEMS'] as $iKey => $arItem) {

    if (!ArrayHelper::keyExists('PRICE', $arItem))
        continue;

    $sMaxPrice = $arItem['VALUES']['MAX']['VALUE'];
    $sMinPrice = $arItem['VALUES']['MIN']['VALUE'];
    $sKey = $arItem["ENCODED_ID"].time().'_'.$arItem['CODE'];

    if ($sMaxPrice - $sMinPrice <= 0)
        continue;

    $precision = 2;

    if (Loader::includeModule('currency')) {
        $arTemp = CCurrencyLang::GetFormatDescription($arItem['VALUES']['MIN']['CURRENCY']);
        $precision = $arTemp['DECIMALS'];
    }

    $bExpanded = ArrayHelper::getValue($arItem, 'DISPLAY_EXPANDED') == 'Y';

    $sMinPriceJS = $arItem['VALUES']['MIN']['HTML_VALUE'];
    $sMinPriceName = $arItem['VALUES']['MIN']['CONTROL_NAME'];
    $sMinPriceId = $arItem['VALUES']['MIN']['CONTROL_ID'];
    $sMaxPriceJS = $arItem['VALUES']['MAX']['HTML_VALUE'];
    $sMaxPriceName = $arItem['VALUES']['MAX']['CONTROL_NAME'];
    $sMaxPriceId = $arItem['VALUES']['MAX']['CONTROL_ID'];

?>
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'intec-grid-item' => [
                'auto' => true,
                '500-1' => true
            ],
            'smart-filter-property-wrap' => true
        ], true),
        'style' => '260px;'
    ]) ?>
        <div class="smart-filter-property bx-filter-parameters-box" data-role="bx_filter_box" id="filter_box_<?= $sKey ?>">
            <span class="bx-filter-container-modef"></span>
            <div class="smart-filter-property-name" data-role="bx_filter_name" data-expanded="false">
                <span class="smart-filter-property-title intec-cl-text-hover">
                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_PRICE') ?>
                </span>
                <div class="smart-filter-property-icon">
                    <?= Html::tag('i', '', [
                        'data-role' => 'prop_angle',
                        'class' => Html::cssClassFromArray([
                            'smart-filter-property-angle' => true,
                            'fa-left-element-filter far' => true,
                            'fa-angle-down' => true
                        ], true)
                    ]) ?>
                    <?= Html::tag('i', '', [
                        'id' => 'clear_prop'.$sKey,
                        'class' => Html::cssClassFromArray([
                            'smart-filter-property-delete' => true,
                            'fa-left-element-filter' => true,
                            'far fa-times' => true
                        ], true),
                        'data-role' => 'prop_del'
                    ]) ?>
                </div>
            </div>
            <div class="smart-filter-property-values" data-role="bx_filter_block" data-property-type="track">
                <div class="smart-filter-track-wrapper clearfix">
                    <div class="bx-ui-slider-track-container smart-filter-track-action">
                        <div class="bx-ui-slider-track" id="drag_track_<?= $sKey ?>">
                            <?= Html::tag('div', '', [
                                'id' => "colorUnavailableActive_$sKey",
                                'class' => 'bx-ui-slider-pricebar-vd',
                                'style' => [
                                    'left' => 0,
                                    'right' => 0
                                ]
                            ]) ?>
                            <?= Html::tag('div', '', [
                                'id' => "colorAvailableInactive_$sKey",
                                'class' => 'bx-ui-slider-pricebar-vn',
                                'style' => [
                                    'left' => 0,
                                    'right' => 0
                                ]
                            ]) ?>
                            <?= Html::tag('div', '', [
                                'id' => "colorAvailableActive_$sKey",
                                'class' => 'bx-ui-slider-pricebar-v intec-cl-background intec-cl-border',
                                'style' => [
                                    'left' => 0,
                                    'right' => 0
                                ]
                            ]) ?>
                            <div class="bx-ui-slider-range" id="drag_tracker_<?= $sKey ?>"  style="left: 0; right: 0;">
                                <?= Html::tag('a', '', [
                                    'id' => "left_slider_$sKey",
                                    'class' => 'bx-ui-slider-handle left',
                                    'href' => 'javascript:void(0)',
                                ]) ?>
                                <?= Html::tag('a', '', [
                                    'id' => "right_slider_$sKey",
                                    'class' => 'bx-ui-slider-handle right',
                                    'href' => 'javascript:void(0)',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="smart-filter-track-value smart-filter-track-min">
                        <label>
                            <?= Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_PRICE_FROM') ?>
                        </label>
                        <?= Html::input('text', $sMinPriceName,
                            $sMinPriceJS ? $sMinPriceJS : '',
                            [
                                'id' => $sMinPriceId,
                                'class' => 'min-price',
                                'style' => [
                                    'border' => 'none'
                                ],
                                'placeholder' => $sMinPriceJS ? $sMinPriceJS : $sMinPrice,
                                'data-role' => 'min.price'
                            ]
                        ) ?>
                    </div>
                    <div class="smart-filter-track-value smart-filter-track-max">
                        <label>
                            <?= Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_PRICE_TO') ?>
                        </label>
                        <?= Html::input('text', $sMaxPriceName,
                            $sMaxPriceJS ? $sMaxPriceJS : '',
                            [
                                'id' => $sMaxPriceId,
                                'class' => 'max-price',
                                'style' => [
                                    'border' => 'none'
                                ],
                                'placeholder' => $sMaxPriceJS ? $sMaxPriceJS : $sMaxPrice,
                                'data-role' => 'max.price'
                            ]
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
    <?php $arJsParams = [
        'variable' => 'smartFilter',
        'leftSlider' => 'left_slider_'.$sKey,
        'rightSlider' => 'right_slider_'.$sKey,
        'tracker' => 'drag_tracker_'.$sKey,
        'trackerWrap' => 'drag_track_'.$sKey,
        'minInputId' => $sMinPriceId,
        'maxInputId' => $sMaxPriceId,
        'minPrice' => $sMinPrice,
        'maxPrice' => $sMaxPrice,
        'curMinPrice' => $arItem['VALUES']['MIN']['HTML_VALUE'],
        'curMaxPrice' => $arItem['VALUES']['MAX']['HTML_VALUE'],
        'fltMinPrice' => $arItem['VALUES']['MIN']['FILTERED_VALUE'] ? $arItem['VALUES']['MIN']['FILTERED_VALUE'] : $arItem['VALUES']['MIN']['VALUE'] ,
        'fltMaxPrice' => $arItem['VALUES']['MAX']['FILTERED_VALUE'] ? $arItem['VALUES']['MAX']['FILTERED_VALUE'] : $arItem['VALUES']['MAX']['VALUE'],
        'precision' => $precision,
        'colorUnavailableActive' => 'colorUnavailableActive_'.$sKey,
        'colorAvailableActive' => 'colorAvailableActive_'.$sKey,
        'colorAvailableInactive' => 'colorAvailableInactive_'.$sKey,
        'blockId' => 'filter_box_'.$sKey,
        'propDel' => 'clear_prop'.$sKey
    ] ?>
    <script>
        BX.ready(function () {
            window['trackBar<?= $sKey ?>'] = new BX.Iblock.SmartFilterHorizontal2(<?= JavaScript::toObject($arJsParams) ?>);
        });
    </script>
<?php } ?>