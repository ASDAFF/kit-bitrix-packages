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
        var area = $(window);

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

            update = function (tab, animate) {
                if (update.disabled)
                    return;

                update.disabled = true;

                universe.components.get(data, function (result) {
                    root.replaceWith(result);
                });
            };

            update.disabled = false;
            universe.basket.once('update', function() { update(); });
            universe.compare.once('update', function() { update(); });
        });

    })(jQuery, intec)
</script>