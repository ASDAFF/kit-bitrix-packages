<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script>
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var gallery = $('[data-role="items"]', root);

        gallery.lightGallery({
            'selector': '[data-role="item"]',
            'exThumbImage': 'data-preview-src'
        });
    })(jQuery, intec);
</script>