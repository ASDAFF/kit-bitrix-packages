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
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-smart-filter' => [
            '',
            'vertical-1'
        ]
    ],
    'data' => [
        'mobile' => $arVisual['MOBILE'] ? 'true' : 'false'
    ]
]) ?>
    <div class="smart-filter-wrap">
        <?php if (!$arVisual['MOBILE']) { ?>
            <div class="smart-filter-toggle intec-cl-text-hover" data-role="filter.toggle">
                <i class="intec-ui-icon intec-ui-icon-filter-1" data-role="prop_angle"></i>
                <span>
                    <?php if ($arVisual['COLLAPSED']) { ?>
                        <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TOGGLE_DOWN') ?>
                    <?php } else { ?>
                        <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TOGGLE_UP') ?>
                    <?php } ?>
                </span>
            </div>
        <?php } else { ?>
            <div class="smart-filter-toggle intec-cl-text-hover">
                <i class="fas fa-bars" data-role="prop_angle"></i>
                <span>
                    <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_NAME') ?>
                </span>
            </div>
        <?php } ?>
        <?= Html::beginForm($arResult['FORM_ACTION'], 'get', [
            'name' => $arResult['FILTER_NAME'].'_form',
            'data-role' => 'filter',
            'data-expanded' => $arVisual['COLLAPSED'] ? 'false' : 'true',
            'style' => $arVisual['COLLAPSED'] ? 'height: 0px; display: none;' : null
        ]) ?>
            <?php if (!empty($arResult['HIDDEN'])) { ?>
                <?php foreach ($arResult['HIDDEN'] as $arItem) { ?>
                <?php
                    $sControlId = $arItem['CONTROL_ID'];

                    if ($arVisual['MOBILE'])
                        $sControlId .= '_mobile';
                ?>
                    <?= Html::hiddenInput($arItem['CONTROL_NAME'], $arItem['HTML_VALUE'], [
                        'id' => $sControlId
                    ]) ?>
                <?php } ?>
            <?php } ?>
            <div class="smart-filter-properties">
                <?php include(__DIR__.'/parts/prices.php') ?>
                <?php include(__DIR__.'/parts/properties.php') ?>
            </div>
            <div class="smart-filter-controls">
                <div class="smart-filter-controls-buttons">
                    <div class="intec-grid intec-grid-i-h-5">
                        <div class="intec-grid-item-2">
                            <?= Html::input('submit', 'set_filter',
                                Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_APPLY'),
                                [
                                    'class' => [
                                        'smart-filter-controls-button',
                                        'intec-button' => [
                                            '',
                                            's-6',
                                            'r-3',
                                            'cl-common'
                                        ],
                                        'mouse-effect'
                                    ],
                                    'id' => $arVisual['MOBILE'] ? 'set_filter_mobile' : 'set_filter'
                                ]
                            ) ?>
                        </div>
                        <div class="intec-grid-item-2">
                            <?= Html::input('submit', 'del_filter',
                                Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_RESET'),
                                [
                                    'class' => [
                                        'smart-filter-controls-button',
                                        'intec-button' => [
                                            '',
                                            's-6',
                                            'r-3',
                                            'cl-common',
                                            'transparent'
                                        ],
                                        'mouse-effect'
                                    ],
                                    'id' => $arVisual['MOBILE'] ? 'del_filter_mobile' : 'del_filter'
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
                <?= Html::beginTag('span', [
                    'id' => 'modef',
                    'class' => Html::cssClassFromArray([
                        'smart-filter-controls-popup' => true,
                        'smart-filter-controls-popup-hidden' => !$arVisual['POPUP']['USE']
                    ], true),
                    'data' => [
                        'role' => 'popup'
                    ]
                ]) ?>
                    <span class="smart-filter-controls-popup-text">
                        <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_PANEL_COUNT', [
                            '#ELEMENT_COUNT#' => '<span id="modef_num">'.$arResult['ELEMENT_COUNT'].'</span>'
                        ]) ?>
                    </span>
                    <a href="<?= $arResult['FILTER_URL']?>" class="smart-filter-controls-popup-link intec-cl-background intec-cl-background-light-hover">
                        <?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_PANEL_SHOW')?>
                    </a>
                    <span class="smart-filter-controls-popup-close far fa-times" data-role="popup.close"></span>
                <?= Html::endTag('span') ?>
            </div>
        <?= Html::endForm() ?>
    </div>
    <?php include(__DIR__.'/parts/script.php'); ?>
<?= Html::endTag('div') ?>
