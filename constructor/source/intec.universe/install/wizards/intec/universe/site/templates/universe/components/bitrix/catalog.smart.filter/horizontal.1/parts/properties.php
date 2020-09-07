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

$arPropertyTypes = [
    'F',
    'G',
    'H',
    'A'
];

?>
<?php foreach ($arResult['ITEMS'] as $iKey => $arItem) {

    if (empty($arItem['VALUES']) || ArrayHelper::keyExists('PRICE', $arItem))
        continue;

    $sPropertyType = ArrayHelper::fromRange($arPropertyTypes, $arItem['DISPLAY_TYPE']);

    if ($sPropertyType == 'A')
        if (!isset($arItem['VALUES']['MAX']['VALUE'], $arItem['VALUES']['MIN']['VALUE']))
            continue;

    $bExpanded = ArrayHelper::getValue($arItem, 'DISPLAY_EXPANDED', false) == 'Y';

    $sType = ArrayHelper::getValue($arVisual, ['TYPE', $sPropertyType, 'DATA']);

?>
    <div class="<?= Html::cssClassFromArray([
        'intec-grid-item' => [
            $arVisual['COLUMNS'] => true,
            '1000-3' => $arVisual['COLUMNS'] >= 4,
            '800-2' => $arVisual['COLUMNS'] >= 3,
            '500-1' => true
        ],
        'smart-filter-property' => true,
        'bx-filter-parameters-box' => true,
        'bx-active' => $bExpanded
    ], true) ?>">
        <span class="bx-filter-container-modef"></span>
        <div class="smart-filter-property-name" onclick="smartFilter.hideFilterProps(this)">
            <span class="smart-filter-property-title intec-cl-text-hover">
                <?= $arItem['NAME'] ?>
            </span>
            <?= Html::tag('i', '', [
                'class' => Html::cssClassFromArray([
                    'smart-filter-property-angle' => true,
                    'fa-left-element-filter' => true,
                    'far fa-angle-down' => true,
                    'property-expanded' => $bExpanded
                ], true),
                'data-role' => 'prop_angle'
            ]) ?>
        </div>
        <?= Html::beginTag('div', [
            'class' => [
                'smart-filter-property-values'
            ],
            'data-role' => 'bx_filter_block',
            'data-property-type' => $sType
        ]) ?>
            <?php if ($sPropertyType == 'A') {

                $sMaxPrice = $arItem['VALUES']['MAX']['VALUE'];
                $sMinPrice = $arItem['VALUES']['MIN']['VALUE'];
                $sKey = $arItem["ENCODED_ID"].time().'_'.$arItem['CODE'];

                $precision = $arVisual['TYPE']['A']['PRECISION'];
                $precision = $precision <= 0 ? 0 : $precision;

                $bExpanded = ArrayHelper::getValue($arItem, 'DISPLAY_EXPANDED') == 'Y';

                $sMinPriceJS = ArrayHelper::getValue($arItem, ['VALUES', 'MIN', 'HTML_VALUE']);
                $sMinPriceName = $arItem['VALUES']['MIN']['CONTROL_NAME'];
                $sMinPriceId = $arItem['VALUES']['MIN']['CONTROL_ID'];
                $sMaxPriceJS = ArrayHelper::getValue($arItem, ['VALUES', 'MAX', 'HTML_VALUE']);
                $sMaxPriceName = $arItem['VALUES']['MAX']['CONTROL_NAME'];
                $sMaxPriceId = $arItem['VALUES']['MAX']['CONTROL_ID'];

            ?>
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
            <?php } else if ($sPropertyType == 'G') { /** Флажки с картинками */ ?>
                <?php foreach ($arItem['VALUES'] as $arValue) {

                    $sControlId = $arValue['CONTROL_ID'];
                    $sImage = ArrayHelper::getValue($arValue, ['FILE', 'SRC']);

                ?>
                    <div class="smart-filter-property-value mouse-click-effect">
                        <?= Html::beginTag('label', [
                            'class' => Html::cssClassFromArray([
                                'disabled' => $arValue['DISABLED']
                            ], true),
                            'data-role' => "label_$sControlId",
                            'for' => $sControlId
                        ]) ?>
                            <?= Html::input('checkbox', $arValue['CONTROL_NAME'], $arValue['HTML_VALUE'], [
                                'id' => $sControlId,
                                'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                'onclick' => 'smartFilter.click(this)'
                            ]) ?>
                            <?= Html::beginTag('span', [
                                'class' => [
                                    'smart-filter-property-value-picture'
                                ],
                                'title' => $arValue['VALUE'],
                                'data-size' => $arVisual['TYPE']['G']['SIZE'],
                                'style' => [
                                    'background-image' => 'url('.$sImage.')'
                                ]
                            ]) ?>
                                <i class="smart-filter-property-value-icon fal fa-check"></i>
                            <?= Html::endTag('span') ?>
                        <?= Html::endTag('label') ?>
                    </div>
                <?php } ?>
            <?php } else if ($sPropertyType == 'H') { ?>
                <?php foreach ($arItem['VALUES'] as $arValue) {

                    $sControlId = $arValue['CONTROL_ID'];
                    $sImage = ArrayHelper::getValue($arValue, ['FILE', 'SRC']);

                ?>
                    <div class="smart-filter-property-value mouse-click-effect">
                        <?= Html::beginTag('label', [
                            'class' => Html::cssClassFromArray([
                                'disabled' => $arValue['DISABLED']
                            ], true),
                            'data-role' => "label_$sControlId",
                            'for' => $sControlId
                        ]) ?>
                            <?= Html::input('checkbox', $arValue['CONTROL_NAME'], $arValue['HTML_VALUE'], [
                                'id' => $sControlId,
                                'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                'onclick' => 'smartFilter.click(this)'
                            ]) ?>
                            <?= Html::beginTag('span', [
                                'class' => 'smart-filter-property-value-text-picture',
                                'title' => $arValue['VALUE']
                            ]) ?>
                                <?= Html::tag('span', '', [
                                    'class' => 'smart-filter-property-value-text-picture-color',
                                    'style' => [
                                        'background-image' => !empty($sImage) ? "url($sImage)" : null
                                    ]
                                ]) ?>
                                <span class="smart-filter-property-value-text-picture-text">
                                    <?= $arValue['VALUE'] ?>
                                </span>
                            <?= Html::endTag('span') ?>
                        <?= Html::endTag('label') ?>
                    </div>
                <?php } ?>
            <?php } else { /** Остальные типы приведены к обычным флажкам */ ?>
                <?php foreach ($arItem['VALUES'] as $arValue) {

                    $sControlId = $arValue['CONTROL_ID'];

                ?>
                    <div class="smart-filter-property-value mouse-click-effect">
                        <?= Html::beginTag('label', [
                            'class' => Html::cssClassFromArray([
                                'disabled' => $arValue['DISABLED']
                            ], true),
                            'data-role' => "label_$sControlId",
                            'for' => $sControlId
                        ]) ?>
                            <?= Html::input('checkbox', $arValue['CONTROL_NAME'], $arValue['HTML_VALUE'], [
                                'id' => $sControlId,
                                'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                'onclick' => 'smartFilter.click(this)'
                            ]) ?>
                            <?= Html::tag('span', $arValue['VALUE'], [
                                'class' => [
                                    'smart-filter-property-value-text'
                                ],
                                'data-background' => $arVisual['TYPE']['F']['BACKGROUND']
                            ]) ?>
                        <?= Html::endTag('label') ?>
                    </div>
                <?php } ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    </div>
<?php } ?>