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
                $arVisual['COLUMNS'] => true,
                '1000-3' => $arVisual['COLUMNS'] >= 4,
                '800-2' => $arVisual['COLUMNS'] >= 3,
                '500-1' => true
            ],
            'smart-filter-property' => true,
            'bx-filter-parameters-box' => true,
            'bx-active' => $bExpanded
        ], true)
    ]) ?>
        <span class="bx-filter-container-modef"></span>
        <div class="smart-filter-property-name" onclick="smartFilter.hideFilterProps(this)">
            <span class="smart-filter-property-title intec-cl-text-hover">
                <?= Loc::getMessage('FILTER_TEMP_HORIZONTAL_PRICE') ?>
            </span>
            <?= Html::tag('i', '', [
                'data-role' => 'prop_angle',
                'class' => Html::cssClassFromArray([
                    'smart-filter-property-angle' => true,
                    'fa-left-element-filter far' => true,
                    'fa-angle-down' => true,
                    'property-expanded' => $bExpanded
                ], true)
            ]) ?>
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
                        <?= Loc::getMessage('FILTER_TEMP_HORIZONTAL_PRICE_FROM') ?>
                    </label>
                    <?= Html::input('text', $sMinPriceName,
                        $sMinPriceJS ? $sMinPriceJS : '',
                        [
                            'id' => $sMinPriceId,
                            'class' => 'min-price',
                            'style' => [
                                'border' => 'none'
                            ],
                            'placeholder' => $sMinPriceJS ? $sMinPriceJS : $sMinPrice
                        ]
                    ) ?>
                </div>
                <div class="smart-filter-track-value smart-filter-track-max">
                    <label>
                        <?= Loc::getMessage('FILTER_TEMP_HORIZONTAL_PRICE_TO') ?>
                    </label>
                    <?= Html::input('text', $sMaxPriceName,
                        $sMaxPriceJS ? $sMaxPriceJS : '',
                        [
                            'id' => $sMaxPriceId,
                            'class' => 'max-price',
                            'style' => [
                                'border' => 'none'
                            ],
                            'placeholder' => $sMaxPriceJS ? $sMaxPriceJS : $sMaxPrice
                        ]
                    ) ?>
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
    ] ?>
    <script>
        BX.ready(function () {
            window['trackBar<?= $sKey ?>'] = new BX.Iblock.SmartFilterVertical1(<?= JavaScript::toObject($arJsParams) ?>);
        });
    </script>
<?php } ?>