<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplate
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var panel = $('[data-role="panel"]', root);
        var buttons = $('[data-role="button"]', root);
        var area = $(window);
        var scrollPrev = 0;

        buttons.on('click', function () {
            var button = $(this);
            var action = button.data('action');

            if (action === 'form') {
                universe.forms.show(<?= JavaScript::toObject([
                    'id' => $arResult['FORM']['ID'],
                    'template' => '.default',
                    'parameters' => [
                        'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-FORM-POPUP',
                        'CONSENT_URL' => $arResult['URL']['CONSENT']
                    ],
                    'settings' => [
                        'title' => $arResult['FORM']['TITLE']
                    ]
                ]) ?>);

                if (window.yandex && window.yandex.metrika) {
                    window.yandex.metrika.reachGoal('forms.open');
                    window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arForm['PARAMETERS']['id'].'.open') ?>);
                }
            } else if (action === 'personal') {
                universe.components.show(
                    <?=JavaScript::toObject([
                        'component' => 'bitrix:system.auth.form',
                        'template' => 'template.1',
                        'parameters' => [
                            "COMPONENT_TEMPLATE" => "template.1",
                            "REGISTER_URL" => $arResult['URL']['REGISTER'],
                            "FORGOT_PASSWORD_URL" => $arResult['URL']['FORGOT_PASSWORD'],
                            "PROFILE_URL" => $arResult['URL']['PROFILE'],
                            "SHOW_ERRORS" => "N"
                        ]
                    ])?>
                );
            }
        });

        $(function () {
            var data;
            var update;

            data = <?= JavaScript::toObject(array(
                'component' => $component->getName(),
                'template' => $this->getName(),
                'parameters' => ArrayHelper::merge($arParams, [
                    'AJAX_MODE' => 'N'
                ])
            )) ?>;

            update = function (tab, animate) {
                if (update.disabled)
                    return;

                update.disabled = true;

                universe.components.get(data, function (result) {
                    root.replaceWith(result);
                });
            };

            update.disabled = false;
            universe.basket.once('update', function() { update(); });
            universe.compare.once('update', function() { update(); });
        });

        <?php if ($arVisual['PANEL']['HIDDEN']) { ?>
            area.on('scroll', function () {
                var scrolled = area.scrollTop();

                if ( scrolled > 100 && scrolled > scrollPrev ) {
                    panel.addClass('sale-basket-small-panel-out');
                } else {
                    panel.removeClass('sale-basket-small-panel-out');
                }
                scrollPrev = scrolled;
            });
        <?php } ?>

    })(jQuery, intec)
</script>