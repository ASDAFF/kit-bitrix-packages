<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var string $sTemplateId
 */

$arFormCall = $arResult['FORMS']['CALL'];
$arFormFeedback = $arResult['FORMS']['FEEDBACK'];

?>
<script type="text/javascript">
    (function ($) {
        $(document).on('ready', function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var button = $('[data-role="forms.button"]', root);


            button.on('click', function () {
                var action = $(this).attr('data-action');

                if (action == 'call.open') {
                    universe.forms.show(<?= JavaScript::toObject([
                        'id' => $arFormCall['ID'],
                        'template' => $arFormCall['TEMPLATE'],
                        'parameters' => [
                            'AJAX_OPTION_ADDITIONAL' => $sTemplateId . '_FORM_CALL',
                            'CONSENT_URL' => $arResult['FORMS']['CONSENT_URL']
                        ],
                        'settings' => [
                            'title' => $arFormCall['TITLE']
                        ]
                    ]) ?>);
                } else if (action == 'feedback.open') {
                    universe.forms.show(<?= JavaScript::toObject([
                        'id' => $arFormFeedback['ID'],
                        'template' => $arFormFeedback['TEMPLATE'],
                        'parameters' => [
                            'AJAX_OPTION_ADDITIONAL' => $sTemplateId . '_FORM_CALL',
                            'CONSENT_URL' => $arResult['FORMS']['CONSENT_URL']
                        ],
                        'settings' => [
                            'title' => $arFormFeedback['TITLE']
                        ]
                    ]) ?>);
                }

                if (window.yandex && window.yandex.metrika) {
                    window.yandex.metrika.reachGoal('forms.open');
                    window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arFormCall['ID'].'.open') ?>);
                }
            });
        });
    })(jQuery)
</script>