<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var string $sTemplateId
 */

$arGet = Core::$app->request->get();

?>
<form action="" method="get" data-role="form">
    <?php if (!empty($arGet)) { ?>
        <?php foreach ($arGet as $key => $sValue) {

            if ($key === $arResult['TAGS']['VARIABLE'] || $key === 'PAGEN_'.$arResult['NAVIGATION']['NUMBER'])
                continue;
            ?>
            <?= Html::hiddenInput($key, $sValue) ?>
        <?php } ?>
        <?php unset($key, $sValue) ?>
    <?php } ?>
    <?php foreach ($arResult['TAGS']['LIST'] as $key => $arTag) {

        $bActive = ArrayHelper::isIn($key, $arResult['TAGS']['ACTIVE']);

        ?>
        <?= Html::checkbox($arResult['TAGS']['VARIABLE'].'[]', $bActive, [
            'value' => $key,
            'data-role' => 'form.input'
        ]) ?>
    <?php } ?>
</form>
<?php unset($arGet, $key, $arTag, $bActive) ?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var items = $('[data-role="items"]', root);
        var form = $('[data-role="form"]', root);
        var locked = false;
        var descriptor = null;

        items.inputs = $('[data-role="items.input"]', items);
        form.inputs = $('[data-role="form.input"]', form);

        var changeState = function (needle, haystack) {
            haystack.each(function () {
                var item = $(this);

                if (needle.val() === item.val())
                    item.prop('checked', needle.prop('checked'));
            });
        };

        var submit = function () {
            if (descriptor !== null)
                clearTimeout(descriptor);

            descriptor = setTimeout(function () {
                locked = true;
                form.trigger('submit');
                items.inputs.prop('disabled', true);
                form.inputs.prop('disabled', true);
            }, 1000);
        };

        items.inputs.on('change', function () {
            var input = $(this);

            changeState(input, items.inputs);
            changeState(input, form.inputs);

            if (locked)
                return;

            submit();
        });
    })(jQuery, intec);
</script>