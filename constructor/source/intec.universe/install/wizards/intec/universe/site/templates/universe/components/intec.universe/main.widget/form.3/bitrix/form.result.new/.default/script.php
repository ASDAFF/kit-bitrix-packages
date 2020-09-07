<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
   * @var string $sTemplateId
*/
?>

<script>
(function ($, api) {
    var root = $('#<?= $sTemplateId ?>');
    var field = $('[data-role="field"]', root);
    var input = $('[data-role="input"]', root);

    field.click(function(){
        console.log('sfsdfsdf');
        $(this).attr('data-active', 'true');
    });

    field.on("focusin", function() {
        $(this).attr('data-active', 'true');
    });

    input.on("focusout", function() {
        var field = $(this).closest('[data-role="field"]');
        var input = $(this);

        if (input.length){
            if (input.val().length == 0) {
                field.attr('data-active', 'false');
            }
        }
    });

})(jQuery, intec);
</script>