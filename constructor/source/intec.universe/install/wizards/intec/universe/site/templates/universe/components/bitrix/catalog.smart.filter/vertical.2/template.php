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

$vItem = include(__DIR__.'/parts/item.php');

Loc::loadMessages(__FILE__);

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-smart-filter' => [
            '',
            'vertical-2'
        ]
    ],
    'data' => [
        'role' => 'filter',
        'ajax' => $arResult['AJAX'] ? 'true' : 'false',
        'mobile' => $arVisual['MOBILE'] ? 'true' : 'false'
    ]
]) ?>
    <div class="catalog-smart-filter-wrapper">
        <?php if (!$arVisual['MOBILE']) { ?>
            <div class="catalog-smart-filter-toggle intec-cl-text-hover" data-role="filter.toggle">
                <i class="catalog-smart-filter-toggle-icon far fa-filter" data-role="prop_angle"></i>
                <span class="catalog-smart-filter-toggle-text">
                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_NAME') ?>
                </span>
            </div>
        <?php } else { ?>
            <div class="catalog-smart-filter-toggle">
                <i class="catalog-smart-filter-toggle-icon fas fa-bars" data-role="prop_angle"></i>
                <span class="catalog-smart-filter-toggle-text">
                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_NAME') ?>
                </span>
            </div>
        <?php } ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-smart-filter-content',
            'data' => [
                'role' => 'filter.content',
                'expanded' => !$arVisual['COLLAPSED'] ? 'true' : 'false'
            ],
            'style' => [
                'display' => $arVisual['COLLAPSED'] ? 'none' : null,
                'height' => $arVisual['COLLAPSED'] ? '0px' : null
            ]
        ]) ?>
            <div class="catalog-smart-filter-content-wrapper">
                <?= Html::beginForm($arResult['FORM_ACTION'], 'get', [
                    'name' => $arResult['FILTER_NAME'].'_form',
                    'data' => [
                        'role' => 'filter.form'
                    ]
                ]) ?>
                    <?php if (!empty($arResult['HIDDEN'])) { ?>
                        <?php foreach ($arResult['HIDDEN'] as $arControl) { ?>
                            <?= Html::hiddenInput($arControl['CONTROL_NAME'], $arControl['HTML_VALUE'], [
                                'id' => $arControl['CONTROL_ID'].($arVisual['MOBILE'] ? '_mobile' : null)
                            ]) ?>
                        <?php } ?>
                    <?php } ?>
                    <div class="catalog-smart-filter-items">
                        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                        <?php if (!isset($arItem['PRICE']) || !$arItem['PRICE']) continue ?>
                            <?php $vItem($arItem) ?>
                        <?php } ?>
                        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                        <?php if (isset($arItem['PRICE']) && $arItem['PRICE']) continue ?>
                            <?php $vItem($arItem) ?>
                        <?php } ?>
                    </div>
                    <div class="catalog-smart-filter-buttons">
                        <?php if (!$arResult['AJAX']) { ?>
                            <?= Html::button(Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_BUTTONS_APPLY'), [
                                'id' => 'set_filter'.($arVisual['MOBILE'] ? '_mobile' : null),
                                'name' => 'set_filter',
                                'type' => 'submit',
                                'class' => [
                                    'catalog-smart-filter-button',
                                    'catalog-smart-filter-button-apply',
                                    'intec-ui' => [
                                        '',
                                        'control-button',
                                        'mod-block',
                                        'scheme-current'
                                    ]
                                ]
                            ]) ?>
                        <?php } else { ?>
                            <?= Html::input('hidden', 'set_filter', '', [
                                'id' => 'set_filter'
                            ]) ?>
                        <?php } ?>
                        <?= Html::beginTag('button', [
                            'id' => 'det_filter'.($arVisual['MOBILE'] ? '_mobile' : null),
                            'name' => 'del_filter',
                            'type' => 'submit',
                            'class' => [
                                'catalog-smart-filter-button',
                                'catalog-smart-filter-button-reset',
                                'intec-ui' => [
                                    '',
                                    'control-button',
                                    'mod-block',
                                    'mod-transparent'
                                ]
                            ]
                        ]) ?>
                            <div class="catalog-smart-filter-button-icon intec-ui-part-icon">
                                <i class="far fa-sync"></i>
                            </div>
                            <div class="catalog-smart-filter-button-content intec-ui-part-content">
                                <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_BUTTONS_RESET') ?>
                            </div>
                        <?= Html::endTag('button') ?>
                    </div>
                    <?= Html::beginTag('span', [
                        'id' => 'SmartFilterVertical2-modef',
                        'class' => Html::cssClassFromArray([
                            'catalog-smart-filter-popup' => true,
                            'catalog-smart-filter-popup-hidden' => !$arVisual['POPUP']['USE']
                        ], true),
                        'data' => [
                            'role' => 'popup'
                        ]
                    ]) ?>
                        <span class="catalog-smart-filter-popup-text">
                            <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_PANEL_COUNT', [
                                '#ELEMENT_COUNT#' => '<span id="SmartFilterVertical2-modef-num">'.$arResult['ELEMENT_COUNT'].'</span>'
                            ]) ?>
                        </span>
                        <a href="<?= $arResult['FILTER_URL'] ?>" class="catalog-smart-filter-popup-link intec-cl-background intec-cl-background-light-hover">
                            <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_PANEL_SHOW') ?>
                        </a>
                        <span class="catalog-smart-filter-popup-close" data-role="popup.close">
                            <i class="far fa-times"></i>
                        </span>
                    <?= Html::endTag('span') ?>
                <?= Html::endForm() ?>
            </div>
        <?= Html::endTag('div') ?>
    </div>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>