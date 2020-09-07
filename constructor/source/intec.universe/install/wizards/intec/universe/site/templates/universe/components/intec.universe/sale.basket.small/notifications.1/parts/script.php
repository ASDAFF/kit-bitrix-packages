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
        var container = $('[data-role="container"]', root);

        $(function () {
            var data;
            var add;

            data = <?= JavaScript::toObject(array(
                'component' => $component->getName(),
                'template' => $this->getName(),
                'parameters' => ArrayHelper::merge($arParams, [
                    'AJAX_MODE' => 'N'
                ])
            )) ?>;

            add = function (id) {
                data.parameters['ID'] = id;

                universe.components.get(data, function (result) {
                    var item = $(result);
                    var element;

                    container.append(item);

                    element = $('[data-product-id="'+id+'"]', container);

                    element.attr('data-active', 'true');
                    element.find('[data-role="close"]').on('click', function () {
                        element.attr('data-active', 'false');

                        setTimeout(function () {
                            item.remove();
                        }, 300);
                    });

                    setTimeout(function () {
                        element.attr('data-active', 'false');

                        setTimeout(function () {
                            item.remove();
                        }, 300);

                    }, 5000);
                });
            };

            universe.basket.on('add', function(event, data) {
                if (data.delay !== 'Y')
                    add(data.id);
            });
        });
    })(jQuery, intec)
</script>