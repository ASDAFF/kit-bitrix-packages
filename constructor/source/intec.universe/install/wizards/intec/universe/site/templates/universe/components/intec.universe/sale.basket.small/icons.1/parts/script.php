<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplate
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var bTabs = $('[data-role="tabs"]', root);
        var tabs = $('[data-role="tab"]', bTabs);
        var products = $('[data-role="product"]', root);
        var buttons = $('[data-role="button"]', root);
        var current = null;

        tabs.each(function () {
            var tab = $(this);
            var icon = $('[data-role="tab.icon"]', tab);

            icon.on('mouseenter', function () {
                var code;
                tab.attr('data-active', 'true');

                code = tab.data('tab');
                current = code;
            });

            tab.on('mouseleave', function () {
                tab.attr('data-active', 'false');
                current = null;
            });

        });

        buttons.on('click', function () {
            var button = $(this);
            var action = button.data('action');

            if (action === 'basket.clear') {
                universe.basket.clear({'basket': 'Y'});
            } else if (action === 'delayed.clear') {
                universe.basket.clear({'delay': 'Y'});
            } else if (action === 'close') {
                tabs.attr('data-active', 'false');
            }
        });

        $(function () {
            var data;
            var update;

            data = <?= JavaScript::toObject(array(
                'component' => $component->getName(),
                'template' => $this->getName(),
                'parameters' => ArrayHelper::merge($arParams, [
                    'AJAX_MODE' => 'N'
                ])
            )) ?>;

            update = function () {
                if (update.disabled)
                    return;

                update.disabled = true;

                if (current === true || !api.isDeclared(current)) {
                    current = current;
                } else if (current === false) {
                    current = null;
                }

                data.parameters['TAB'] = current;

                universe.components.get(data, function (result) {
                    root.replaceWith(result);
                });
            };

            update.disabled = false;
            universe.basket.once('update', function() { update(); });
            universe.compare.once('update', function() { update(); });

            products.each(function () {
                var product = $(this);
                var id = product.data('id');
                var buttons = $('[data-role="button"]', product);

                buttons.on('click', function () {
                    var button = $(this);
                    var action = button.data('action');
                    var data = {'id': id};

                    if (action === 'product.add') {
                        data.delay = 'N';
                        universe.basket.add(data);
                    } else if (action === 'product.delay') {
                        data.delay = 'Y';
                        universe.basket.add(data);
                    } else if (action === 'product.remove') {
                        universe.basket.remove(data);
                    }
                });
            });

        });

        <?php if (!empty($arResult['TAB'])) { ?>
        bTabs.find('[data-tab="<?= $arResult['TAB'] ?>"]').attr('data-active', 'true');
        <?php } ?>

    })(jQuery, intec)
</script>