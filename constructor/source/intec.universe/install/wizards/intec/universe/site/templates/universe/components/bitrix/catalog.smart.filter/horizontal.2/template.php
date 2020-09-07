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
<div class="ns-bitrix c-smart-filter c-smart-filter-horizontal-2" id="<?= $sTemplateId ?>">
    <?php if (!$arVisual['WIDE']['USE']) { ?>
        <div class="intec-content intec-content-visible clearfix">
            <div class="intec-content-wrapper">
    <?php } ?>
    <?= Html::beginTag('div', [
        'class' => 'smart-filter-container',
        'data-role' => 'filter.container'
    ]) ?>
        <?php if ($arVisual['WIDE']['USE']) { ?>
                <div class="intec-content intec-content-visible">
                    <div class="intec-content-wrapper">
            <?php } ?>
            <div class="smart-filter-wrapper">
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
                                'i-5'
                            ]
                        ],
                        'data-role' => 'filter'
                    ]) ?>
                        <?php include(__DIR__.'/parts/prices.php') ?>
                        <?php include(__DIR__.'/parts/properties.php') ?>

                        <div class="intec-grid-item-auto smart-filter-controls">
                            <div class="smart-filter-controls-buttons" id="button-filter">
                                <?php if ($arParams['AJAX'] !== 'Y') { ?>
                                    <?= Html::button(
                                        '<i class="far fa-check"></i>', [
                                        'type' => 'submit',
                                        'class' => 'mouse-effect intec-cl-background  intec-cl-background-light-hover',
                                        'id' => 'set_filter',
                                        'name' => 'set_filter'
                                    ]) ?>
                                <?php } else { ?>
                                    <?= Html::input('hidden', 'set_filter', '', [
                                        'id' => 'set_filter'
                                    ])?>
                                <?php } ?>
                                <?= Html::button(
                                    '<i class="far fa-times"></i>', [
                                    'type' => 'submit',
                                    'class' => 'mouse-effect',
                                    'id' => 'del_filter',
                                    'name' => 'del_filter'
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
                                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_FILTER_COUNT', [
                                        '#ELEMENT_COUNT#' => '<span id="modef_num">'.$arResult['ELEMENT_COUNT'].'</span>'
                                    ]) ?>
                                </div>
                                <a class="smart-filter-controls-popup-link" href="<?= $arResult["FILTER_URL"]?>">
                                    <?= GetMessage("C_CATALOG_SMART_FILTER_HORIZONTAL_2_FILTER_SHOW")?>
                                </a>
                            <?= Html::endTag('div') ?>
                        </div>
                    <?= Html::endTag('div') ?>
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