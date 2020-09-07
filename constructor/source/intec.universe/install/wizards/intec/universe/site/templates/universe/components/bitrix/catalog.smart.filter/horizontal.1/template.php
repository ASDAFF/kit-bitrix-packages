<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!$arResult['VISUAL']['DISPLAY'])
    return;

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];

?>
<div class="ns-bitrix c-smart-filter c-smart-filter-horizontal-1" id="<?= $sTemplateId ?>">
    <?php if (!$arVisual['WIDE']['USE']) { ?>
        <div class="intec-content intec-content-visible clearfix">
            <div class="intec-content-wrapper">
    <?php } ?>
    <?= Html::beginTag('div', [
        'class' => 'smart-filter-container',
        'data-type' => $arVisual['WIDE']['USE'] ? 'wide' : 'narrow',
        'data-color' => $arVisual['WIDE']['USE'] ? $arVisual['WIDE']['BACKGROUND'] : null,
        'data-role' => 'filter.container',
        'data-expanded' => $arVisual['COLLAPSED'] ? 'false' : 'true'
    ]) ?>
        <?php if ($arVisual['WIDE']['USE']) { ?>
                <div class="intec-content intec-content-visible">
                    <div class="intec-content-wrapper">
            <?php } ?>
            <div class="smart-filter-wrapper">
                <div class="smart-filter-toggle" data-role="filter.toggle">
                    <?php if ($arVisual['BUTTONS']['TOGGLE']['TYPE'] == 'text-arrow') { ?>
                        <span class="smart-filter-toggle-title">
                            <?php if ($arVisual['COLLAPSED']) { ?>
                                <?= Loc::getMessage('FILTER_TEMP_HORIZONTAL_TOGGLE_DOWN') ?>
                            <?php } else { ?>
                                <?= Loc::getMessage('FILTER_TEMP_HORIZONTAL_TOGGLE_UP') ?>
                            <?php } ?>
                        </span>
                    <?php } ?>
                    <i class="smart-filter-toggle-icon far fa-angle-up"></i>
                </div>
                <?= Html::beginForm($arResult['FORM_ACTION'], 'get', [
                    'name' => $arResult['FILTER_NAME'].'_form',
                ]) ?>
                    <?php if (!empty($arResult['HIDDEN'])) { ?>
                        <?php foreach ($arResult['HIDDEN'] as $arItem) { ?>
                        <?php
                            $sControlId = $arItem['CONTROL_ID'];
                        ?>
                            <?= Html::hiddenInput($arItem['CONTROL_NAME'], $arItem['HTML_VALUE'], [ /** Скрытые инпуты */
                                'id' => $sControlId
                            ]) ?>
                        <?php } ?>
                    <?php } ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'smart-filter-grid',
                            'intec-grid' => [
                                '',
                                'wrap',
                                'i-h-20'
                            ]
                        ],
                        'data-role' => 'filter',
                        'style' => $arVisual['COLLAPSED'] ? 'height: 40px' : null
                    ]) ?>
                        <?php include(__DIR__.'/parts/prices.php') ?>
                        <?php include(__DIR__.'/parts/properties.php') ?>
                    <?= Html::endTag('div') ?>
                    <div class="smart-filter-controls">
                        <div class="smart-filter-controls-buttons" id="button-filter">
                            <?= Html::input('submit', 'set_filter', Loc::getMessage('FILTER_TEMP_HORIZONTAL_APPLY'), [
                                'id' => 'set_filter',
                                'class' => [
                                    'mouse-effect'
                                ]
                            ]) ?>
                            <?= Html::input('submit', 'del_filter', Loc::getMessage('FILTER_TEMP_HORIZONTAL_RESET'), [
                                'id' => 'del_filter',
                                'class' => [
                                    'mouse-effect'
                                ]
                            ]) ?>
                        </div>
                        <?= Html::beginTag('div', [
                            'id' => 'modef',
                            'class' => Html::cssClassFromArray([
                                'smart-filter-controls-popup' => true,
                                'smart-filter-controls-popup-hidden' => !$arVisual['POPUP']['SHOW']
                            ], true)
                        ]) ?>
                            <div class="smart-filter-controls-popup-text">
                                <?= Loc::getMessage('CT_BCSF_FILTER_COUNT', [
                                    '#ELEMENT_COUNT#' => '<span id="modef_num">'.$arResult['ELEMENT_COUNT'].'</span>'
                                ]) ?>
                            </div>
                            <a class="smart-filter-controls-popup-link" href="<?= $arResult["FILTER_URL"]?>">
                                <?= GetMessage("CT_BCSF_FILTER_SHOW")?>
                            </a>
                        <?= Html::endTag('div') ?>
                    </div>
                <?= Html::endForm() ?>
            </div>
        <?php if ($arVisual['WIDE']['USE']) { ?>
                    </div>
                </div>
            <?php } ?>
    <?= Html::endTag('div') ?>
    <?php include(__DIR__.'/parts/script.php') ?>
    <?php if (!$arVisual['WIDE']['USE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>