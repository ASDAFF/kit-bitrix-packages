<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 */

return function (&$arItem) use (&$arResult, &$arVisual, &$sTemplateId) {
    $sType = isset($arItem['DISPLAY_TYPE']) ? $arItem['DISPLAY_TYPE'] : null;
    $sType = ArrayHelper::fromRange(['F', 'K', 'A', 'B', 'G', 'H', 'P', 'R'], $sType);
    $arValues = $arItem['VALUES'];

    if (empty($arValues))
        return;

    if ($sType === 'A' || $sType === 'B')
        if (
            !isset($arValues['MIN']['VALUE']) ||
            !isset($arValues['MAX']['VALUE']) ||
            $arValues['MIN']['VALUE'] === $arValues['MAX']['VALUE']
        ) return;

?>
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-smart-filter-item',
            'bx-filter-parameters-box'
        ],
        'data' => [
            'expanded' => $arItem['DISPLAY_EXPANDED'] === 'Y' ? 'true' : 'false',
            'type' => $sType
        ]
    ]) ?>
        <div class="bx-filter-container-modef"></div>
        <div class="catalog-smart-filter-item-header" onclick="smartFilter<?= $arVisual['MOBILE'] ? 'Mobile' : null ?>.hideFilterProps(this)">
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-smart-filter-item-header-wrapper',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center',
                        'i-h-8'
                    ]
                ]
            ]) ?>
                <div class="catalog-smart-filter-item-header-text intec-grid-item">
                    <?= $arItem['NAME'] ?>
                </div>
                <div class="catalog-smart-filter-item-header-icon intec-grid-item-auto">
                    <?= Html::tag('i', '', [
                        'class' => [
                            'far fa-angle-down'
                        ],
                        'data' => [
                            'role' => 'prop_angle'
                        ]
                    ]) ?>
                </div>
            <?= Html::endTag('div') ?>
        </div>
        <div class="catalog-smart-filter-item-content" data-role="bx_filter_block">
            <div class="catalog-smart-filter-item-content-wrapper">
                <?php if ($sType === 'F') { ?>
                    <div class="catalog-smart-filter-item-values">
                        <?php foreach ($arValues as $arValue) { ?>
                            <div class="catalog-smart-filter-item-value">
                                <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current intec-ui-size-2">
                                    <?= Html::input('checkbox', $arValue['CONTROL_NAME'], $arValue['HTML_VALUE'], [
                                        'id' => $arValue['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                        'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                        'onclick' => 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null).'.click(this)'
                                    ]) ?>
                                    <span class="intec-ui-part-selector"></span>
                                    <span class="intec-ui-part-content">
                                        <?= $arValue['VALUE'] ?>
                                    </span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else if ($sType === 'G' || $sType === 'H') { ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'catalog-smart-filter-item-values' => true,
                            'intec-grid' => $sType === 'G' ? [
                                '' => true,
                                'wrap' => true,
                                'a-h-start' => true,
                                'a-v-start' => true,
                                'i-4' => true
                            ] : null
                        ], true)
                    ]) ?>
                        <?php foreach ($arValues as $arValue) { ?>
                        <?php
                            $sPicture = $arValue['FILE'];

                            if (!empty($sPicture)) {
                                $sPicture = CFile::ResizeImageGet($sPicture, [
                                    'width' => 22,
                                    'height' => 22
                                ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                if (!empty($sPicture))
                                    $sPicture = $sPicture['src'];
                            }

                            if (empty($sPicture))
                                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                        ?>
                            <?= Html::beginTag('label', [
                                'class' => Html::cssClassFromArray([
                                    'catalog-smart-filter-item-value' => true,
                                    'intec-grid-item-auto' => $sType === 'G',
                                    'intec-grid' => $sType === 'H' ? [
                                        '' => true,
                                        'important' => true,
                                        'nowrap' => true,
                                        'a-v-center' => true,
                                        'i-h-4' => true
                                    ] : null
                                ], true)
                            ]) ?>
                                <?php if ($sType === 'H') { ?>
                                    <?= Html::beginTag('span', [
                                        'class' => [
                                            'catalog-smart-filter-item-value-part',
                                            'intec-grid-item-auto'
                                        ]
                                    ]) ?>
                                <?php } ?>
                                <?= Html::input('checkbox', $arValue['CONTROL_NAME'], $arValue['HTML_VALUE'], [
                                    'id' => $arValue['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                    'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                    'onclick' => 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null).'.click(this)'
                                ]) ?>
                                <?= Html::beginTag('span', [
                                    'class' => [
                                        'catalog-smart-filter-item-value-checkbox',
                                        'intec-cl-border-hover'
                                    ]
                                ]) ?>
                                    <span class="catalog-smart-filter-item-value-checkbox-picture" style="background-image: url('<?= $sPicture ?>')"></span>
                                    <span class="catalog-smart-filter-item-value-checkbox-icon">
                                        <i class="fal fa-check"></i>
                                    </span>
                                <?= Html::endTag('span') ?>
                                <?php if ($sType === 'H') { ?>
                                    <?= Html::endTag('span') ?>
                                <?php } ?>
                                <?php if ($sType === 'H') { ?>
                                    <span class="catalog-smart-filter-item-value-part intec-grid-item">
                                        <span class="catalog-smart-filter-item-value-text">
                                            <?= $arValue['VALUE'] ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?= Html::endTag('label') ?>
                        <?php } ?>
                    <?= Html::endTag('div') ?>
                <?php } else if ($sType === 'K') { ?>
                <?php
                    $arValueCurrent = current($arValues);
                ?>
                    <div class="catalog-smart-filter-item-values">
                        <div class="catalog-smart-filter-item-value">
                            <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current intec-ui-size-2">
                                <?= Html::input('radio', $arValueCurrent['CONTROL_NAME_ALT'], '', [
                                    'id' => 'all_'.$arValueCurrent['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                    'onclick' => 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null).'.click(this)'
                                ]) ?>
                                <span class="intec-ui-part-selector"></span>
                                <span class="intec-ui-part-content">
                                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_ANSWERS_ALL') ?>
                                </span>
                            </label>
                        </div>
                        <?php foreach ($arValues as $arValue) { ?>
                            <div class="catalog-smart-filter-item-value">
                                <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current intec-ui-size-2">
                                    <?= Html::input('radio', $arValue['CONTROL_NAME_ALT'], $arValue['HTML_VALUE_ALT'], [
                                        'id' => $arValue['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                        'checked' => $arValue['CHECKED'] ? 'checked' : null,
                                        'onclick' => 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null).'.click(this)'
                                    ]) ?>
                                    <span class="intec-ui-part-selector"></span>
                                    <span class="intec-ui-part-content">
                                        <?= $arValue['VALUE'] ?>
                                    </span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else if ($sType === 'A' || $sType === 'B') { ?>
                    <div class="catalog-smart-filter-item-values intec-grid intec-grid-nowrap intec-grid-i-h-6">
                        <div class="catalog-smart-filter-item-value catalog-smart-filter-item-value-minimum intec-grid-item-2">
                            <?php if ($sType === 'B') { ?>
                                <div class="catalog-smart-filter-item-value-title">
                                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_TYPE_B_MIN') ?>
                                </div>
                            <?php } ?>
                            <?= Html::input('text', $arValues['MIN']['CONTROL_NAME'], $arValues['MIN']['HTML_VALUE'], [
                                'id' => $arValues['MIN']['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                'class' => [
                                    'intec-ui' => [
                                        '',
                                        'control-input',
                                        'mod-block',
                                        'mod-round-2',
                                        'view-1'
                                    ]
                                ],
                                'placeholder' => $arValues['MIN']['VALUE'],
                                'onkeyup' => 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null).'.keyup(this)'
                            ]) ?>
                        </div>
                        <div class="catalog-smart-filter-item-value catalog-smart-filter-item-value-maximum intec-grid-item-2">
                            <?php if ($sType === 'B') { ?>
                                <div class="catalog-smart-filter-item-value-title">
                                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_TYPE_B_MAX') ?>
                                </div>
                            <?php } ?>
                            <?= Html::input('text', $arValues['MAX']['CONTROL_NAME'], $arValues['MAX']['HTML_VALUE'], [
                                'id' => $arValues['MAX']['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                'class' => [
                                    'intec-ui' => [
                                        '',
                                        'control-input',
                                        'mod-block',
                                        'mod-round-2',
                                        'view-1'
                                    ]
                                ],
                                'placeholder' => $arValues['MAX']['VALUE'],
                                'onkeyup' => 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null).'.keyup(this)'
                            ]) ?>
                        </div>
                    </div>
                    <?php if ($sType === 'A') { ?>
                    <?php
                        $sTrackId = $sTemplateId.'-slider-';

                        if (isset($arItem['PRICE']) && $arItem['PRICE']) {
                            $sTrackId .= 'price-'.$arItem['ID'];
                        } else {
                            $sTrackId .= 'property-'.$arItem['ID'];
                        }
                    ?>
                        <div class="catalog-smart-filter-item-slider" id="<?= $sTrackId ?>">
                            <?= Html::tag('div', null, [
                                'id' => $sTrackId.'-track-ai',
                                'class' => [
                                    'catalog-smart-filter-item-slider-track',
                                    'catalog-smart-filter-item-slider-track-inactive'
                                ]
                            ]) ?>
                            <?= Html::tag('div', null, [
                                'id' => $sTrackId.'-track-aa',
                                'class' => [
                                    'catalog-smart-filter-item-slider-track',
                                    'catalog-smart-filter-item-slider-track-active'
                                ]
                            ]) ?>
                            <?= Html::tag('div', null, [
                                'id' => $sTrackId.'-track-ua',
                                'class' => [
                                    'catalog-smart-filter-item-slider-track',
                                    'catalog-smart-filter-item-slider-track-filtered'
                                ]
                            ]) ?>
                            <div class="catalog-smart-filter-item-slider-tracker" id="<?= $sTrackId.'-tracker' ?>">
                                <div class="catalog-smart-filter-item-slider-tracker-drag catalog-smart-filter-item-slider-tracker-drag-left" id="<?= $sTrackId.'-tracker-drag-left' ?>">
                                    <div class="catalog-smart-filter-item-slider-tracker-drag-point intec-cl-background"></div>
                                </div>
                                <div class="catalog-smart-filter-item-slider-tracker-drag catalog-smart-filter-item-slider-tracker-drag-right" id="<?= $sTrackId.'-tracker-drag-right' ?>">
                                    <div class="catalog-smart-filter-item-slider-tracker-drag-point intec-cl-background"></div>
                                </div>
                            </div>
                        </div>
                        <div class="catalog-smart-filter-item-bounds intec-grid intec-grid-nowrap intec-grid-a-h-center intec-grid-i-h-5">
                            <div class="catalog-smart-filter-item-bound catalog-smart-filter-item-bound-minimum intec-grid-item-2">
                                <?= $arValues['MIN']['VALUE'] ?>
                            </div>
                            <div class="catalog-smart-filter-item-bound catalog-smart-filter-item-bound-maximum intec-grid-item-2">
                                <?= $arValues['MAX']['VALUE'] ?>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function() {
                                var tracker = new BX.Iblock.SmartFilterVertical2(<?= JavaScript::toObject([
                                    'variable' => 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null),
                                    'leftSlider' => $sTrackId.'-tracker-drag-left',
                                    'rightSlider' => $sTrackId.'-tracker-drag-right',
                                    'tracker' => $sTrackId.'-tracker',
                                    'trackerWrap' => $sTrackId,
                                    'minInputId' => $arValues['MIN']['CONTROL_ID'],
                                    'maxInputId' => $arValues['MAX']['CONTROL_ID'],
                                    'minPrice' => $arValues['MIN']['VALUE'],
                                    'maxPrice' => $arValues['MAX']['VALUE'],
                                    'curMinPrice' => isset($arValues['MIN']['HTML_VALUE']) ? $arValues['MIN']['HTML_VALUE'] : null,
                                    'curMaxPrice' => isset($arValues['MAX']['HTML_VALUE']) ? $arValues['MAX']['HTML_VALUE'] : null,
                                    'fltMinPrice' => !empty($arValues['MIN']['FILTERED_VALUE']) ? $arValues['MIN']['FILTERED_VALUE'] : $arValues['MIN']['VALUE'] ,
                                    'fltMaxPrice' => !empty($arValues['MAX']['FILTERED_VALUE']) ? $arValues['MAX']['FILTERED_VALUE'] : $arValues['MAX']['VALUE'],
                                    'precision' => $arItem['DECIMALS'] ? Type::toInteger($arItem['DECIMALS']) : 0,
                                    'colorUnavailableActive' => $sTrackId.'-track-ua',
                                    'colorAvailableActive' => $sTrackId.'-track-aa',
                                    'colorAvailableInactive' => $sTrackId.'-track-ai'
                                ]) ?>);
                            });
                        </script>
                    <?php } ?>
                <?php } else if ($sType === 'P' || $sType === 'R') { ?>
                <?php
                    $arValueCurrent = current($arValues);
                ?>
                    <div class="catalog-smart-filter-item-select" onclick="smartFilter.showDropDownPopup(this, <?= JavaScript::toObject($arItem['ID']) ?>)">
                        <div class="catalog-smart-filter-item-select-wrapper">
                            <div class="catalog-smart-filter-item-select-selection">
                                <div class="catalog-smart-filter-item-select-selection-wrapper" data-role="currentOption">
                                    <?php $bSelected = false ?>
                                    <?php foreach ($arValues as $arValue) { ?>
                                    <?php
                                        if (isset($arValue['CHECKED']) && $arValue['CHECKED']) {
                                            $bSelected = true;
                                        } else {
                                            continue;
                                        }
                                    ?>
                                        <span class="popup-window-item intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                            <?php if ($sType === 'R') { ?>
                                            <?php
                                                $sPicture = $arValue['FILE'];

                                                if (!empty($sPicture)) {
                                                    $sPicture = CFile::ResizeImageGet($sPicture, [
                                                        'width' => 20,
                                                        'height' => 20
                                                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                                    if (!empty($sPicture))
                                                        $sPicture = $sPicture['src'];
                                                }

                                                if (empty($sPicture))
                                                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                                            ?>
                                                <span class="popup-window-item-picture intec-grid-item-auto">
                                                    <span class="popup-window-item-picture-wrapper" style="background-image: url('<?= $sPicture ?>')"></span>
                                                </span>
                                            <?php } ?>
                                            <span class="popup-window-item-text intec-grid-item">
                                                <?= $arValue['VALUE'] ?>
                                            </span>
                                        </span>
                                        <?php break ?>
                                    <?php } ?>
                                    <?php if (!$bSelected) { ?>
                                        <span class="popup-window-item popup-window-item-all intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                            <?php if ($sType === 'R') { ?>
                                                <span class="popup-window-item-picture intec-grid-item-auto">
                                                    <span class="popup-window-item-picture-wrapper"></span>
                                                </span>
                                            <?php } ?>
                                            <span class="popup-window-item-text intec-grid-item">
                                                <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_ANSWERS_ALL') ?>
                                            </span>
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="catalog-smart-filter-item-select-arrow">
                                <div class="catalog-smart-filter-item-select-arrow-wrapper intec-grid intec-grid-nowrap intec-grid-a-h-center intec-grid-a-v-center">
                                    <div class="catalog-smart-filter-item-select-arrow-wrapper-2 intec-grid-item-auto">
                                        <i class="far fa-angle-down"></i>
                                    </div>
                                </div>
                            </div>
                            <?= Html::input('radio', $arValueCurrent['CONTROL_NAME_ALT'], '', [
                                'id' => 'all_'.$arValueCurrent['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                'style' => [
                                    'display' => 'none'
                                ]
                            ]) ?>
                            <?php foreach ($arValues as $arValue) { ?>
                                <?= Html::input('radio', $arValue['CONTROL_NAME_ALT'], $arValue['HTML_VALUE_ALT'], [
                                    'id' => $arValue['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                    'checked' => isset($arValue['CHECKED']) && $arValue['CHECKED'] ? 'checked' : null,
                                    'style' => [
                                        'display' => 'none'
                                    ]
                                ]) ?>
                            <?php } ?>
                            <div class="catalog-smart-filter-item-select-popup">
                                <div class="popup-window-items" data-role="dropdownContent">
                                    <div class="popup-window-item-wrap">
                                        <?= Html::beginTag('label', [
                                            'class' => 'popup-window-item-wrap-2',
                                            'for' => 'all_'.$arValueCurrent['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                            'data' => [
                                                'role' => 'label_all_'.$arValueCurrent['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                                'selected' => 'false',
                                                'disabled' => 'false'
                                            ],
                                            'onclick' => 'smartFilter.selectDropDownItem(this, '.JavaScript::toObject('all_'.$arValueCurrent['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null)).')'
                                        ]) ?>
                                            <span class="popup-window-item popup-window-item-all intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                                <?php if ($sType === 'R') { ?>
                                                    <span class="popup-window-item-picture intec-grid-item-auto">
                                                        <span class="popup-window-item-picture-wrapper"></span>
                                                    </span>
                                                <?php } ?>
                                                <span class="popup-window-item-text intec-grid-item">
                                                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_ANSWERS_ALL') ?>
                                                </span>
                                            </span>
                                        <?= Html::endTag('label') ?>
                                    </div>
                                    <?php foreach ($arValues as $arValue) { ?>
                                        <div class="popup-window-item-wrap">
                                            <?= Html::beginTag('label', [
                                                'class' => 'popup-window-item-wrap-2',
                                                'for' => $arValue['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                                'data' => [
                                                    'role' => 'label_'.$arValue['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null),
                                                    'selected' => isset($arValue['CHECKED']) && $arValue['CHECKED'] ? 'true' : 'false',
                                                    'disabled' => isset($arValue['DISABLED']) && $arValue['DISABLED'] ? 'true' : 'false'
                                                ],
                                                'onclick' => 'smartFilter.selectDropDownItem(this, '.JavaScript::toObject($arValue['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null)).')'
                                            ]) ?>
                                                <span class="popup-window-item intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                                    <?php if ($sType === 'R') { ?>
                                                    <?php
                                                        $sPicture = $arValue['FILE'];

                                                        if (!empty($sPicture)) {
                                                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                                                'width' => 20,
                                                                'height' => 20
                                                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                                            if (!empty($sPicture))
                                                                $sPicture = $sPicture['src'];
                                                        }

                                                        if (empty($sPicture))
                                                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                                                    ?>
                                                        <span class="popup-window-item-picture intec-grid-item-auto">
                                                            <span class="popup-window-item-picture-wrapper" style="background-image: url('<?= $sPicture ?>')"></span>
                                                        </span>
                                                    <?php } ?>
                                                    <span class="popup-window-item-text intec-grid-item">
                                                        <?= $arValue['VALUE'] ?>
                                                    </span>
                                                </span>
                                            <?= Html::endTag('label') ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?= Html::endTag('div') ?>
<?php };