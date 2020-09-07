<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var container = $('[data-role="container"]', root);

        container.lightGallery({
            'selector': '[data-role="video"]'
        });
    })();
</script>
