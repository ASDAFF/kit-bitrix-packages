<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var print = $('[data-role="print"]', root);
        var clear = $('[data-role="clear"]', root);

        print.on('click', function () {
            var cssPath = [
                <?= JavaScript::toObject(SITE_TEMPLATE_PATH.'/css/interface.css') ?>,
                <?= JavaScript::toObject(SITE_TEMPLATE_PATH.'/css/grid.css') ?>,
                <?= JavaScript::toObject($this->GetFolder().'/style.css') ?>
            ];

            root.printThis({
                'importCSS': false,
                'importStyle': true,
                'loadCSS': cssPath,
                'pageTitle': "",
                'removeInline': false,
                'header': null,
                'formValues': true,
                'base': true
            });
        });

        clear.on('click', function () {
            universe.basket.clear(function(){
                location.reload();
            });
        });
    })(jQuery, intec);
</script>