<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var form = $('[data-role="form"]', root);
        var container = $('[data-role="items"]', form);
        var items = $('[data-role="item"]', container);
        var locked = false;

        var descriptor = null;
        var submit = function () {
            if (descriptor !== null)
                clearTimeout(descriptor);

            descriptor = setTimeout(function () {
                locked = true;
                form.trigger('submit');
                items.find('input[type="checkbox"]').prop('disabled', true);
            }, 2000);
        };

        items.each(function () {
            var item = $(this);

            item.on('click', function () {
                if (locked)
                    return;

                submit();
            });
        })
    })(jQuery, intec);
</script>