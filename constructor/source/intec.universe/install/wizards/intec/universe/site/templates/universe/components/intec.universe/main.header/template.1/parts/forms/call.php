<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var string $sTemplateId
 */

$arFormCall = $arResult['FORMS']['CALL'];

?>
<script type="text/javascript">
    (function ($) {
        $(document).on('ready', function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var button = $('[data-action="forms.call.open"]', root);

            button.on('click', function () {
                universe.forms.show(<?= JavaScript::toObject([
                    'id' => $arFormCall['ID'],
                    'template' => $arFormCall['TEMPLATE'],
                    'parameters' => [
                        'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_CALL',
                        'CONSENT_URL' => $arResult['URL']['CONSENT']
                    ],
                    'settings' => [
                        'title' => $arFormCall['TITLE']
                    ]
                ]) ?>);

                if (window.yandex && window.yandex.metrika) {
                    window.yandex.metrika.reachGoal('forms.open');
                    window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arFormCall['ID'].'.open') ?>);
                }
            });
        });
    })(jQuery)
</script>
