<?php
use intec\core\helpers\JavaScript;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
?>

<div class="ask-question-container">
    <div class="ask-question-wrap">
        <div class="ask-question-title col-xs-12 col-md-3">
            <div><?= $arResult['WEB_FORM']['NAME'] ?></div>
        </div>
        <div class="ask-question-description col-xs-12 col-md-6">
            <div><?= $arResult['WEB_FORM']['DESCRIPTION'] ?></div>
        </div>
        <div class="ask-question-button-wrap col-xs-12 col-md-3">
            <button id="ask_question_button_<?= $arResult['COMPONENT_HASH'] ?>"
                    class="intec-bt-button button-big"
                    onclick="(function() {
                        universe.forms.show(<?= JavaScript::toObject([
                            'id' => $arResult['WEB_FORM']['ID'],
                            'template' => $arParams['WEB_FORM_SETTINGS']['COMPONENT_TEMPLATE'],
                            'parameters' => [
                                'AJAX_OPTION_ADDITIONAL' => $arResult['COMPONENT_HASH'].'_FORM',
                                'CONSENT_URL' => $arParams['CONSENT_URL']
                            ],
                            'settings' => [
                                'title' => $arResult['WEB_FORM']['NAME']
                            ]
                        ]) ?>);

                        if (window.yandex && window.yandex.metrika) {
                            window.yandex.metrika.reachGoal('forms.open');
                            window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['WEB_FORM']['ID'].'.open') ?>);
                        }
                    })() ">
                <?= $arResult['WEB_FORM']['BUTTON'] ?>
            </button>
        </div>
    </div>
</div>
