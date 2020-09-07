<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

        button = $('[data-role="item.button"]', root);

        //button.on('click', function () {
            universe.basket.on('add', function () {
                location.reload();
            });
        //});
    })(jQuery, intec)
</script>
