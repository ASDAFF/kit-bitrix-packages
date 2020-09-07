<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-products-additional-1'
    ],
    'data' => [
        'trigger' => $arResult['TRIGGER']
    ]
]) ?>
    <div class="catalog-section-items" data-role="items">
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <?php
            if (!$arItem['CAN_BUY'])
                continue;

            $arPrice = null;

            if (!empty($arItem['ITEM_PRICES']))
                $arPrice = $arItem['ITEM_PRICES'][0];
        ?>
            <?= Html::beginTag('div', [
                'class' => 'catalog-section-item',
                'data' => [
                    'role' => 'item',
                    'basket-id' => $arItem['ID'],
                    'basket-state' => 'none',
                    'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                ]
            ]) ?>
                <div class="catalog-section-item-wrapper intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-5">
                    <div class="catalog-section-item-switch intec-grid-item-auto">
                        <div class="catalog-section-item-switch-control">
                            <input type="checkbox" data-role="item.input" />
                        </div>
                        <div class="catalog-section-item-switch-state">
                            <input type="checkbox" checked="checked" disabled="disabled" readonly="readonly" />
                        </div>
                    </div>
                    <div class="catalog-section-item-name intec-grid-item intec-grid-item-shrink-1">
                        <?= $arItem['NAME'] ?>
                        <?php if (!empty($arPrice)) { ?>
                            + <?= $arPrice['PRINT_PRICE'] ?>
                        <?php } ?>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var items = $('[data-role="items"] [data-role="item"]', root);
            var trigger = root.data('trigger');
            var checkboxes = $('input[type="checkbox"]', root);

            checkboxes.each(function () {
                var checkbox = $(this);
                new api.ui.controls.switch(checkbox, {
                    'classes': {
                        'control': 'api-ui-switch-control'
                    }
                });
            });

            universe.basket.on('add', function (event, data) {
                if (data[trigger] === true && data.delay !== 'Y') {
                    items.each(function () {
                         var item = $(this);
                         var input = $('[data-role="item.input"]', item);
                         var id = item.attr('data-basket-id');
                         var price = item.attr('data-basket-price');
                         var state = item.attr('data-basket-state');

                         if (state === 'none' && input.prop('checked')) {
                             universe.basket.add({
                                 'id': id,
                                 'price': price
                             });
                         }
                    });
                }
            });
        })(jQuery, intec)
    </script>
<?= Html::endTag('div') ?>