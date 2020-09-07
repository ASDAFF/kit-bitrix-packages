<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

?>
<script type="text/javascript">
    (function (jQuery, api) {
        var data;
        var run;
        var update;

        data = {};
        data.basket = [];
        data.compare = [];

        run = function () {
            $('[data-basket-id]').attr('data-basket-state', 'none');
            $('[data-compare-id]').attr('data-compare-state', 'none');

            api.each(data.basket, function (index, item) {
                $('[data-basket-id=' + item.id + ']').attr('data-basket-state', item.delay ? 'delayed' : 'added');
            });

            api.each(data.compare, function (index, item) {
                $('[data-compare-id=' + item.id + ']').attr('data-compare-state', 'added');
            });
        };

        update = function () {
            $.ajax(<?= JavaScript::toObject($arResult['ACTION']) ?>, {
                'type': 'POST',
                'cache': false,
                'dataType': 'json',
                'data': <?= JavaScript::toObject($arParams) ?>,
                'success': function (response) {
                    data = response;
                    run();
                }
            })
        };

        update();

        $(document).on('click', '[data-basket-id][data-basket-action]', function () {
            var node = $(this);
            var id = node.data('basketId');
            var action = node.data('basketAction');
            var quantity = node.data('basketQuantity');
            var price = node.data('basketPrice');
            var data = node.data('basketData');

            if (id == null)
                return;

            if (action === 'add') {
                $('[data-basket-id=' + id + ']').attr('data-basket-state', 'processing');
                universe.basket.add(api.extend({
                    'quantity': quantity,
                    'price': price
                }, data, {
                    'id': id
                }));
            } else if (action === 'remove') {
                $('[data-basket-id=' + id + ']').attr('data-basket-state', 'processing');
                universe.basket.remove(api.extend({}, data, {
                    'id': id
                }));
            } else if (action === 'delay') {
                $('[data-basket-id=' + id + ']').attr('data-basket-state', 'processing');
                universe.basket.add(api.extend({
                    'quantity': quantity,
                    'price': price
                }, data, {
                    'id': id,
                    'delay': 'Y'
                }));
            } else if (action === 'setQuantity') {
                $('[data-basket-id=' + id + ']').attr('data-basket-state', 'processing');
                universe.basket.setQuantity(api.extend({
                    'quantity': quantity,
                    'price': price
                }, data, {
                    'id': id,
                    'delay': 'Y'
                }));
            }
        });

        $(document).on('click', '[data-compare-id][data-compare-action]', function () {
            var node = $(this);
            var id = node.data('compareId');
            var action = node.data('compareAction');
            var code = node.data('compareCode');
            var iblock = node.data('compareIblock');
            var data = node.attr('compareData');

            if (id == null)
                return;

            if (action === 'add') {
                $('[data-compare-id=' + id + ']').attr('data-compare-state', 'processing');
                universe.compare.add(api.extend({}, data, {
                    'id': id,
                    'code': code,
                    'iblock': iblock
                }));
            } else if (action === 'remove') {
                $('[data-compare-id=' + id + ']').attr('data-compare-state', 'processing');
                universe.compare.remove(api.extend({}, data, {
                    'id': id,
                    'code': code,
                    'iblock': iblock
                }));
            }
        });

        universe.basket.on('update', update);
        universe.compare.on('update', update);

        BX.addCustomEvent('onFrameDataReceived', update);
        BX.ready(update);
    })($, intec);
</script>