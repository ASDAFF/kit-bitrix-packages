<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (!CModule::IncludeModule('intec.startshop'))
    return;

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arFastOrder = [];
$arFastOrder['SHOW'] = $arParams['USE_BUTTON_FAST_ORDER'] === 'Y';
$arFastOrder['TEMPLATE'] = ArrayHelper::getValue($arParams, 'FAST_ORDER_TEMPLATE');
$arFastOrder['PREFIX'] = 'FAST_ORDER_';
$arFastOrder['PARAMETERS'] = [];

foreach ($arParams as $sKey => $sValue) {
    if (!StringHelper::startsWith($sKey, $arFastOrder['PREFIX']))
        continue;

    $sKey = StringHelper::cut($sKey, StringHelper::length($arFastOrder['PREFIX']));
    $arFastOrder['PARAMETERS'][$sKey] = $sValue;
}

$arFastOrder['AJAX_MODE'] = 'Y';
$arFastOrder['AJAX_OPTION_ADDITIONAL'] = $sTemplateId.'-order-fast';

if (empty($arFastOrder['TEMPLATE']))
    $arFastOrder['SHOW'] = false;

//print_r($arFastOrder);die();

unset($sKey);
unset($sValue);

?>
<div class="ns-intec c-startshop-basket-basket c-startshop-basket-basket-default" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php if (!empty($arResult['ITEMS'])) { ?>
                <div class="startshop-basket-basket-filter-wrapper intec-grid intec-grid-650-wrap intec-grid-a-h-650-between intec-grid-i-v-15">
                    <div class="intec-grid-item-auto intec-grid-item-650-1">
                        <div class="startshop-basket-basket-filter-input-wrapper">
                            <?=Html::beginTag('input', [
                                    'id' => 'basket-filter-input',
                                    'class' => [
                                        'startshop-basket-basket-filter-input',
                                        'intec-ui' => [
                                                '',
                                                'control-input',
                                                'view-2',
                                                'size-5'
                                        ]
                                    ],
                                    'placeholder' => Loc::getMessage('SBB_DEFAULT_FILTER_INPUT')
                            ])?>
                            <i class="startshop-basket-basket-filter-loop glyph-icon-loop intec-cl-text"></i>
                        </div>
                    </div>
                    <div class="intec-grid-item"></div>
                    <div class="intec-grid-item-auto startshop-basket-basket-print-wrapper">
                        <?=Html::beginTag('a', [
                            'class' => [
                                'startshop-basket-basket-print-button',
                                'intec-ui' => [
                                    '',
                                    'control-button',
                                    'mod-transparent',
                                    'mod-round-5',
                                    'size-2'
                                ]
                            ]
                        ]);?>
                            <i class="intec-ui-part-icon basket-print-icon"></i>
                        <?=Html::endTag('a');?>
                    </div>
                    <?php if ($arParams['USE_BUTTON_CLEAR'] == 'Y') { ?>
                        <div class="startshop-basket-basket-clear-wrapper intec-grid-item-auto">
                            <?=Html::beginTag('a', [
                                    'class' => [
                                        'startshop-basket-basket-clear-button',
                                        'intec-ui' => [
                                            '',
                                            'control-button',
                                            'mod-transparent',
                                            'mod-round-5',
                                            'size-2'
                                        ]
                                    ],
                                    'href' => $arResult['ACTIONS']['CLEAR']
                            ]);?>
                                <i class="intec-ui-part-icon basket-delete-icon"></i>
                                <span class="intec-ui-part-content">
                                    <?= Loc::GetMessage('SBB_DEFAULT_BUTTON_CLEAR') ?>
                                </span>
                            <?=Html::endTag('a');?>
                        </div>
                    <?php } ?>
                </div>
                <div class="startshop-basket-basket-content">
                    <div class="startshop-basket-basket-table-wrapper">
                        <div class="startshop-basket-basket-table">
                            <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                                <?php
                                    $arSection = ArrayHelper::getValue($arItem, ['SECTION_INFO'], []);
                                ?>
                                <div class="startshop-basket-basket-table-row">
                                    <?php if ($arParams['USE_ITEMS_PICTURES'] == 'Y') { ?>
                                        <div class="startshop-basket-basket-table-cell startshop-basket-column-picture">
                                            <div class="startshop-basket-cell-picture">
                                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="startshop-image">
                                                    <span class="startshop-aligner-vertical"></span>
                                                    <img loading="lazy" src="<?= $arItem['PICTURE']['SRC'] ?>" alt="<?= $arItem['NAME'] ?>" title="<?= $arItem['NAME'] ?>" />
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="startshop-basket-basket-table-cell startshop-basket-column-name">
                                        <div class="startshop-basket-cell">
                                            <?php if (!empty($arSection)) {?>
                                                <div class="startshop-basket-basket-section">
                                                    <a class="startshop-basket-basket-section-link"
                                                    href="<?= $arSection['SECTION_PAGE_URL'] ?>">
                                                        <?= $arSection['NAME'] ?>
                                                    </a>
                                                </div>
                                            <?php }?>
                                            <div class="startshop-basket-basket-product-name">
                                                <a class="startshop-basket-basket-product-name-link intec-cl-text-hover"
                                                   href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                                    <?= $arItem['NAME'] ?>
                                                </a>
                                            </div>
                                            <?php if ($arItem['STARTSHOP']['OFFER']['OFFER']) { ?>
                                                <div class="startshop-basket-basket-mobile-offers">
                                                    <?php foreach ($arItem['STARTSHOP']['OFFER']['PROPERTIES'] as $arProperty) { ?>
                                                        <?php if ($arProperty['TYPE'] == 'TEXT') { ?>
                                                            <div class="startshop-basket-basket-property startshop-basket-basket-property-text">
                                                                <div class="startshop-basket-basket-property-name"><?= $arProperty['NAME'] ?>:</div>
                                                                <div class="startshop-basket-basket-property-value"><?= $arProperty['VALUE']['TEXT'] ?></div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="startshop-basket-basket-property startshop-basket-basket-property-picture">
                                                                <div class="startshop-basket-basket-property-name"><?= $arProperty['NAME'] ?>:</div>
                                                                <div class="startshop-basket-basket-property-value">
                                                                    <div class="startshop-basket-basket-property-value-wrapper">
                                                                        <img src="<?= $arProperty['VALUE']['PICTURE'] ?>"
                                                                             alt="<?= $arProperty['VALUE']['TEXT'] ?>"
                                                                             title="<?= $arProperty['VALUE']['TEXT'] ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="startshop-basket-basket-table-cell startshop-basket-column-offers">
                                        <?php if ($arItem['STARTSHOP']['OFFER']['OFFER']) { ?>
                                            <div class="startshop-basket-cell">
                                                <?php foreach ($arItem['STARTSHOP']['OFFER']['PROPERTIES'] as $arProperty) { ?>
                                                    <?php if ($arProperty['TYPE'] == 'TEXT') { ?>
                                                        <div class="startshop-basket-basket-property startshop-basket-basket-property-text">
                                                            <div class="startshop-basket-basket-property-name"><?= $arProperty['NAME'] ?>:</div>
                                                            <div class="startshop-basket-basket-property-value"><?= $arProperty['VALUE']['TEXT'] ?></div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="startshop-basket-basket-property startshop-basket-basket-property-picture">
                                                            <div class="startshop-basket-basket-property-name"><?= $arProperty['NAME'] ?>:</div>
                                                            <div class="startshop-basket-basket-property-value">
                                                                <div class="startshop-basket-basket-property-value-wrapper">
                                                                    <img src="<?= $arProperty['VALUE']['PICTURE'] ?>"
                                                                         alt="<?= $arProperty['VALUE']['TEXT'] ?>"
                                                                         title="<?= $arProperty['VALUE']['TEXT'] ?>"
                                                                         loading="lazy" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="startshop-basket-basket-table-cell startshop-basket-column-price">
                                        <div class="startshop-basket-basket-price-title">
                                            <?= Loc::GetMessage('SBB_DEFAULT_COLUMN_PRICE') ?>
                                        </div>
                                        <div class="startshop-basket-basket-price-value">
                                            <?= $arItem['STARTSHOP']['BASKET']['PRICE']['PRINT_VALUE'] ?>
                                        </div>
                                    </div>
                                    <div class="startshop-basket-basket-table-cell startshop-basket-column-quantity">
                                        <div class="startshop-basket-cell">
                                            <?php
                                                $arJSNumeric = array(
                                                    'Value' => $arItem['STARTSHOP']['BASKET']['QUANTITY'],
                                                    'Minimum' => $arItem['STARTSHOP']['QUANTITY']['RATIO'],
                                                    'Ratio' => $arItem['STARTSHOP']['QUANTITY']['RATIO'],
                                                    'Maximum' => $arItem['STARTSHOP']['QUANTITY']['VALUE'],
                                                    'Unlimited' => !$arItem['STARTSHOP']['QUANTITY']['USE'],
                                                    'ValueType' => 'Float',
                                                );
                                            ?>
                                            <div class="intec-ui intec-ui-control-numeric intec-ui-view-2 intec-ui-size-3">
                                                <button class="startshop-basket-basket-quantity-button intec-ui-part-decrement QuantityDecrease<?= $arItem['ID'] ?>">-</button>
                                                <input type="text" class="intec-ui-part-input QuantityNumeric<?= $arItem['ID'] ?>"
                                                       value="<?= $arItem['STARTSHOP']['BASKET']['QUANTITY'] ?>" />
                                                <button class="startshop-basket-basket-quantity-button intec-ui-part-increment QuantityIncrease<?= $arItem['ID'] ?>">+</button>
                                            </div>
                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    var Quantity = new Startshop.Controls.NumericUpDown(<?=CUtil::PhpToJSObject($arJSNumeric)?>);
                                                    var QuantityIncrease = $('#<?=$sTemplateId?> .QuantityIncrease<?=$arItem['ID']?>');
                                                    var QuantityDecrease = $('#<?=$sTemplateId?> .QuantityDecrease<?=$arItem['ID']?>');
                                                    var QuantityNumeric = $('#<?=$sTemplateId?> .QuantityNumeric<?=$arItem['ID']?>');

                                                    Quantity.Settings.Events.OnValueChange = function ($oNumeric) {
                                                        QuantityNumeric.val($oNumeric.GetValue());
                                                        Reload();
                                                    };

                                                    QuantityIncrease.click(function () {
                                                        Quantity.Increase();
                                                    });

                                                    QuantityDecrease.click(function () {
                                                        Quantity.Decrease();
                                                    });

                                                    QuantityNumeric.change(function () {
                                                        Quantity.SetValue($(this).val());
                                                    });

                                                    function Reload() {
                                                        window.location.href = Startshop.Functions.stringReplace({'#QUANTITY#': Quantity.GetValue()}, <?=CUtil::PhpToJSObject($arItem['ACTIONS']['SET_QUANTITY'])?>);
                                                    }
                                                })
                                            </script>
                                        </div>
                                    </div>
                                    <div class="startshop-basket-basket-table-cell startshop-basket-column-total">
                                        <div class="startshop-basket-cell">
                                            <?= CStartShopCurrency::FormatAsString($arItem['STARTSHOP']['BASKET']['PRICE']['VALUE'] * $arItem['STARTSHOP']['BASKET']['QUANTITY'], $arParams['CURRENCY']) ?>
                                        </div>
                                    </div>
                                    <a class="startshop-basket-basket-table-cell startshop-basket-column-control"
                                        href="<?= $arItem['ACTIONS']['DELETE']?>">
                                            <i class="startshop-button-custom startshop-button-delete basket-delete-icon"></i>
                                    </a>
                                </div>
                            <?php } ?>
                            <div class="startshop-basket-basket-table-row-total">
                                <?php if ($arParams['USE_ITEMS_PICTURES'] == 'Y') { ?>
                                    <div class="startshop-basket-basket-table-cell"></div>
                                <?php } ?>
                                <div class="startshop-basket-basket-table-cell"></div>
                                <div class="startshop-basket-basket-table-cell  startshop-basket-column-offers"></div>
                                <div class="startshop-basket-basket-table-cell"></div>
                                <div class="startshop-basket-basket-table-cell total-title">
                                    <?=Loc::GetMessage('SBB_DEFAULT_FIELD_SUM');?>
                                </div>
                                <div class="startshop-basket-basket-table-cell startshop-basket-column-total">
                                    <div class="startshop-basket-cell">
                                        <?= $arResult['SUM']['PRINT_VALUE'] ?>
                                    </div>
                                </div>
                                <a class="startshop-basket-basket-table-cell"></a>
                            </div>
                        </div>
                    </div>
                    <div class="startshop-basket-basket-info-result">
                        <?php if ($arParams['USE_BUTTON_ORDER'] == 'Y' || $arFastOrder['SHOW']) { ?>
                            <div class="startshop-basket-basket-buttons">
                                <div class="startshop-basket-buttons-wrapper intec-grid intec-grid intec-grid-a-h-end intec-grid-650-wrap intec-grid-a-h-end intec-grid-a-h-650-center">
                                    <?php if ($arFastOrder['SHOW']) { ?>
                                        <?=Html::beginTag('a', [
                                            'class' => [
                                                'intec-grid-item' => [
                                                    'auto',
                                                    '650-1'
                                                ],
                                                'intec-ui' => [
                                                    '',
                                                    'control-button',
                                                    'mod-transparent',
                                                    'scheme-current',
                                                    'mod-round-5',
                                                    'size-4'
                                                ]
                                            ],
                                            'onclick' => '(function () {
                                                      universe.components.show('.JavaScript::toObject(array(
                                                              'component' => 'intec.universe:sale.order.fast',
                                                              'template' => $arFastOrder['TEMPLATE'],
                                                              'parameters' => $arFastOrder['PARAMETERS'],
                                                              'settings' => [
                                                                  'parameters' => [
                                                                      'width' => null
                                                                  ]
                                                              ]
                                                          )).');
                                                      })()'
                                        ]);?>
                                            <?= Loc::GetMessage('SBB_DEFAULT_FAST_ORDER') ?>
                                        <?=Html::endTag('a');?>
                                    <?php } ?>
                                    <?php if ($arParams['USE_BUTTON_ORDER'] == 'Y') { ?>
                                        <?=Html::beginTag('a', [
                                                'class' => [
                                                    'intec-grid-item' => [
                                                        'auto',
                                                        '650-1'
                                                    ],
                                                    'startshop-basket-basket-order-button',
                                                    'intec-ui' => [
                                                        '',
                                                        'control-button',
                                                        'scheme-current',
                                                        'mod-round-5',
                                                        'size-4'
                                                    ]
                                                ],
                                                'href' => $arParams['URL_ORDER']
                                        ]);?>
                                            <?= Loc::GetMessage('SBB_DEFAULT_BUTTON_ORDER') ?>
                                        <?=Html::endTag('a')?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <script>

                    (function ($, api) {
                        $(function () {

                            $('.startshop-basket-basket-print-button').on('click', function() {

                                var cssString = '<?=$this->GetFolder()?>/style.css';

                                $("#<?= $sTemplateId ?>").printThis({
                                    importCSS: false,
                                    importStyle: true,
                                    loadCSS: cssString,
                                    pageTitle: "",
                                    removeInline: false,
                                    header: null,
                                    formValues: true,
                                    base: true
                                });
                            });

                            $.fn.highlight = function (str, className) {
                                var regex = new RegExp(str, "gi");
                                return this.each(function () {
                                    $(this).contents().filter(function() {
                                        return this.nodeType == 3 && regex.test(this.nodeValue);
                                    }).replaceWith(function() {
                                        return (this.nodeValue || "").replace(regex, function(match) {
                                            return "<span class=\"" + className + "\">" + match + "</span>";
                                        });
                                    });
                                });
                            };

                            var root = $('#<?=$sTemplateId?>');
                            var filterInput = $(' #basket-filter-input', root);

                            var items = $('.startshop-basket-basket-table-row', root);

                            var table = $('.startshop-basket-basket-table', root);

                            filterInput.on('keyup', function () {
                                filterValue = $(this).val().toUpperCase().trim();

                                if (filterValue.length > 0) {
                                    items.addClass('table-row-display-none');
                                    table.addClass('table-filter-not-found');

                                    items.each(function (index, value) {

                                        item = $(this);

                                        var nameBlock = item.find('.startshop-basket-basket-product-name-link');
                                        var name = nameBlock.text();

                                        nameBlock.html(name);

                                        var nameUpCase = name.toUpperCase();

                                        if (nameUpCase.includes(filterValue)) {

                                            table.removeClass('table-filter-not-found');

                                            nameBlock.highlight(filterValue, 'text-highlight');

                                            item.removeClass('table-row-display-none');
                                        }

                                    });
                                } else {
                                    table.removeClass('table-filter-not-found');

                                    items.each(function (index, value) {

                                        item = $(this);

                                        var nameBlock = item.find('.startshop-basket-basket-product-name-link');
                                        var name = nameBlock.text();

                                        nameBlock.html(name);

                                        item.removeClass('table-row-display-none');

                                    });
                                }

                            });
                        });
                    })(jQuery, intec);
                </script>

            <?php } else { ?>
                <div class="startshop-basket-basket-empty intec-no-select">
                    <div class="startshop-basket-basket-empty-image">
                    </div>
                    <div class="startshop-basket-basket-empty-title"><?=Loc::GetMessage("SBB_DEFAULT_EMPTY_BASKET");?></div>
                    <div class="startshop-basket-basket-empty-description"><?=Loc::GetMessage("SBB_DEFAULT_CHOOSE");?></div>

                    <?=Html::beginTag('a', [
                        'class' => [
                            'startshop-basket-basket-empty-button',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'scheme-current',
                                'mod-round-5',
                                'size-4'
                            ]
                        ],
                        'href' => $arParams["URL_CATALOG"]
                    ]);?>
                        <?=Loc::GetMessage("SBB_DEFAULT_CATALOG");?>
                    <?=Html::endTag('a')?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>