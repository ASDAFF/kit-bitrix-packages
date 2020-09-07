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

$arPropertyTypes = ['F', 'G', 'H', 'A', 'B', 'K'];

?>
<?php foreach ($arResult['ITEMS'] as $iKey => $arItem) {

    if (empty($arItem['VALUES']) || ArrayHelper::keyExists('PRICE', $arItem))
        continue;

    $sPropertyType = ArrayHelper::fromRange($arPropertyTypes, $arItem['DISPLAY_TYPE']);

    if ($sPropertyType == 'A' || $sPropertyType == 'B')
        if (!isset($arItem['VALUES']['MAX']['VALUE'], $arItem['VALUES']['MIN']['VALUE']))
            continue;

    $sType = ArrayHelper::getValue($arVisual, ['TYPE', $sPropertyType, 'DATA']);

    $iCountValue = null;
    foreach($arItem['VALUES'] as $arValue) {
        if ($arValue['CHECKED'])
            $iCountValue ++;
    }
?>
    <div class="<?= Html::cssClassFromArray([
        'intec-grid-item' => [
            'auto' => true,
            '500-1' => true
        ],
        'smart-filter-property-wrap' => true
    ], true) ?>">
        <div class="smart-filter-property bx-filter-parameters-box" data-role="bx_filter_box">
            <span class="bx-filter-container-modef"></span>
            <div class="smart-filter-property-name" data-role="bx_filter_name" data-expanded="false">
                <span class="smart-filter-property-title">
                    <?= $arItem['NAME'] ?>
                </span>
                <span class="smart-filter-property-counter" data-role="bx_filter_counter"></span>
                <div class="smart-filter-property-icon">
                    <?= Html::tag('i', '', [
                        'class' => Html::cssClassFromArray([
                            'smart-filter-property-angle' => true,
                            'fa-left-element-filter' => true,
                            'far fa-angle-down' => true
                        ], true),
                        'data-role' => 'prop_angle'
                    ]) ?>
                    <?= Html::tag('i', '', [
                        'class' => Html::cssClassFromArray([
                            'smart-filter-property-delete' => true,
                            'fa-left-element-filter' => true,
                            'far fa-times' => true
                        ], true),
                        'data-role' => 'prop_del'
                    ]) ?>
                </div>
            </div>
            <?= Html::beginTag('div', [
                'class' => [
                    'smart-filter-property-values'
                ],
                'data-role' => 'bx_filter_block',
                'data-property-type' => $sType
            ]) ?>
                <?php if ($sPropertyType == 'A' || $sPropertyType == 'B') {

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
                        <?php if ($sPropertyType == 'A') { ?>
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
                        <?php } ?>

                        <?php if ($sPropertyType == 'B') { ?>
                            <div class="smart-filter-property-value">
                        <?php } ?>
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
                                    'data-role' => $sPropertyType == 'B' ? 'property.item.value' : null
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
                                    'data-role' => $sPropertyType == 'B' ? 'property.item.value' : null
                                ]
                            ) ?>
                        </div>
                        <div class="clearfix"></div>
                        <?php if ($sPropertyType == 'B') { ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($sPropertyType == 'A') { ?>
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
                                window['trackBar<?= $sKey ?>'] = new BX.Iblock.SmartFilterHorizontal2(<?= JavaScript::toObject($arJsParams) ?>);
                            });
                        </script>
                    <?php } ?>
                <?php } else if ($sPropertyType == 'G') { /** Флажки с картинками */ ?>
                    <div class="intec-grid intec-grid-i-3 intec-grid-wrap">
                        <?php foreach ($arItem['VALUES'] as $arValue) {

                            $sControlId = $arValue['CONTROL_ID'];
                            $sImage = ArrayHelper::getValue($arValue, ['FILE', 'SRC']);

                        ?>
                            <div class="smart-filter-property-value-wrap intec-grid-item-5">
                                <div class="smart-filter-property-value">
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
                                            'onclick' => 'smartFilter.click(this)',
                                            'data-role' => 'property.item.value'
                                        ]) ?>
                                        <span class="smart-filter-property-value-picture-wrap intec-cl-border-hover">
                                            <?= Html::beginTag('span', [
                                                'class' => [
                                                    'smart-filter-property-value-picture'
                                                ],
                                                'title' => $arValue['VALUE'],
                                                'style' => [
                                                    'background-image' => 'url('.$sImage.')'
                                                ]
                                            ]) ?>
                                                <i class="smart-filter-property-value-icon fal fa-check"></i>
                                            <?= Html::endTag('span') ?>
                                        </span>
                                    <?= Html::endTag('label') ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else if ($sPropertyType == 'H') { ?>
                    <?php foreach ($arItem['VALUES'] as $arValue) {

                        $sControlId = $arValue['CONTROL_ID'];
                        $sImage = ArrayHelper::getValue($arValue, ['FILE', 'SRC']);

                    ?>
                        <div class="smart-filter-property-value">
                            <?= Html::beginTag('label', [
                                'class' => Html::cssClassFromArray([
                                    'disabled' => $arValue['DISABLED']
                                ], true),
                                'data-role' => "label_$sControlId",
                                'for' => $sControlId
                            ]) ?>
                                <span class="intec-grid intec-grid-a-v-center">
                                    <span class="intec-grid-item-auto">
                                        <?= Html::input('checkbox', $arValue['CONTROL_NAME'], $arValue['HTML_VALUE'], [
                                            'id' => $sControlId,
                                            'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                            'onclick' => 'smartFilter.click(this)',
                                            'data-role' => 'property.item.value'
                                        ]) ?>
                                        <span class="smart-filter-property-value-picture-wrap intec-cl-border-hover">
                                            <?= Html::beginTag('span', [
                                                'class' => 'smart-filter-property-value-picture',
                                                'title' => $arValue['VALUE'],
                                                'style' => [
                                                        'background-image' => !empty($sImage) ? "url($sImage)" : null
                                                    ]
                                            ]) ?>
                                                <i class="smart-filter-property-value-icon fal fa-check"></i>
                                            <?= Html::endTag('span') ?>
                                        </span>
                                    </span>
                                    <span class="intec-grid-item">
                                        <span class="smart-filter-property-value-picture-text">
                                            <?= $arValue['VALUE'] ?>
                                        </span>
                                    </span>
                                </span>
                            <?= Html::endTag('label') ?>
                        </div>
                    <?php } ?>
                <?php } else if ($sPropertyType == 'K') { ?>
                    <?php
                        $arValueCurrent = current($arItem['VALUES']);
                    ?>
                    <div class="smart-filter-property-value">
                        <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current intec-ui-size-2">
                            <?= Html::input('radio', $arValueCurrent['CONTROL_NAME_ALT'], '', [
                                'id' => 'all_'.$arValueCurrent['CONTROL_ID'],
                                'onclick' => 'smartFilter.click(this)',
                                'data-role' => 'property.item.value'
                            ]) ?>
                            <span class="intec-ui-part-selector"></span>
                            <span class="intec-ui-part-content">
                                <?= Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_ANSWERS_ALL') ?>
                            </span>
                        </label>
                    </div>
                    <?php foreach ($arItem['VALUES'] as $arValue) {?>
                        <div class="smart-filter-property-value">
                            <?= Html::beginTag('label', [
                                'class' => Html::cssClassFromArray([
                                    'disabled' => $arValue['DISABLED'],
                                    'intec-ui' => [
                                        '' => true,
                                        'control-radiobox' => true,
                                        'scheme-current' => true,
                                        'size-2' => true,
                                    ]
                                ], true),
                                'data-role' => "label_".$arValue['CONTROL_ID'],
                                'for' => $arValue['CONTROL_ID']
                            ]) ?>
                                <?= Html::input('radio', $arValue['CONTROL_NAME_ALT'], $arValue['HTML_VALUE_ALT'], [
                                    'id' => $arValue['CONTROL_ID'],
                                    'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                    'onclick' => 'smartFilter.click(this)',
                                    'data-role' => 'property.item.value'
                                ]) ?>
                                <span class="intec-ui-part-selector"></span>
                                <span class="intec-ui-part-content">
                                    <?= $arValue['VALUE'] ?>
                                </span>
                            <?= Html::endTag('label') ?>
                        </div>
                    <?php } ?>
                <?php } else { /** Остальные типы приведены к обычным флажкам */ ?>
                    <?php foreach ($arItem['VALUES'] as $arValue) { ?>
                        <div class="smart-filter-property-value">
                            <?= Html::beginTag('label', [
                                'class' => Html::cssClassFromArray([
                                    'disabled' => $arValue['DISABLED'],
                                    'intec-ui' => [
                                        '' => true,
                                        'control-checkbox' => true,
                                        'scheme-current' => true,
                                        'size-2' => true,
                                    ]
                                ], true),
                                'data-role' => "label_".$arValue['CONTROL_ID'],
                                'for' => $arValue['CONTROL_ID']
                            ]) ?>
                                <?= Html::input('checkbox', $arValue['CONTROL_NAME'], $arValue['HTML_VALUE'], [
                                    'id' => $arValue['CONTROL_ID'],
                                    'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                    'onclick' => 'smartFilter.click(this)',
                                    'data-role' => 'property.item.value'
                                ]) ?>
                                <span class="intec-ui-part-selector"></span>
                                <span class="intec-ui-part-content">
                                        <?= $arValue['VALUE'] ?>
                                    </span>
                            <?= Html::endTag('label') ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
<?php } ?>