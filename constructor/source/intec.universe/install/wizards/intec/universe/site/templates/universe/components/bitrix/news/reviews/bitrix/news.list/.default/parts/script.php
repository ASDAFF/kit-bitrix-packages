<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script>
    (function ($, api) {
        $(document).ready(function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var gallery = $('[data-role="items"]', root);

            gallery.lightGallery({
                'selector': '[data-role="document"]'
            });

        });
    })(jQuery, intec);
</script>