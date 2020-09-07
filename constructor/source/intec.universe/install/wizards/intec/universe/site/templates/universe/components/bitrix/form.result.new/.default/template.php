<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-form-result-new c-form-result-new-default intec-ui-form">
    <?php if ($arResult['isFormNote'] === 'Y') { ?>
        <div class="form-result-new-message form-result-new-message-note">
            <?= $arResult['FORM_NOTE'] ?>
        </div>
    <?php } else { ?>
        <?= $arResult['FORM_HEADER'] ?>
            <?php if ($arResult["isFormErrors"] == 'Y') { ?>
                <div class="form-result-new-message intec-ui intec-ui-control-alert intec-ui-scheme-red">
                    <?= $arResult['FORM_ERRORS_TEXT'] ?>
                </div>
            <?php } ?>
            <?php if (!empty($arResult['arForm']['DESCRIPTION'])) { ?>
                <div class="form-result-new-description">
                    <?= $arResult['arForm']['DESCRIPTION'] ?>
                </div>
            <?php } ?>
            <?php if (!empty($arResult['QUESTIONS']) || $arResult['isUseCaptcha']) { ?>
                <div class="form-result-new-fields intec-ui-form-fields">
                    <?php foreach ($arResult['QUESTIONS'] as $arQuestion) { ?>
                        <?= Html::beginTag('label', [
                            'class' => Html::cssClassFromArray([
                                'form-result-new-field' => true,
                                'intec-ui-form-field' => true,
                                'intec-ui-form-field-required' => $arQuestion['REQUIRED'] === 'Y'
                            ], true)
                        ]) ?>
                            <span class="form-result-new-field-title intec-ui-form-field-title">
                                <?= $arQuestion['CAPTION'] ?>
                            </span>
                            <span class="form-result-new-field-content intec-ui-form-field-content">
                                <?= $arQuestion['HTML_CODE'] ?>
                            </span>
                        <?= Html::endTag('label') ?>
                    <?php } ?>
                    <?php if ($arResult['isUseCaptcha'] == 'Y') { ?>
                        <div class="form-result-new-field form-result-new-field-captcha intec-ui-form-field intec-ui-form-field-required">
                            <div class="form-result-new-field-title intec-ui-form-field-title">
                                <?= Loc::getMessage('C_FORM_RESULT_NEW_DEFAULT_FIELDS_CAPTCHA') ?>
                            </div>
                            <div class="form-result-new-field-content intec-ui-form-field-content">
                                <?= Html::hiddenInput('captcha_sid', $arResult['CAPTCHACode']) ?>
                                <div class="form-result-new-captcha intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                    <div class="form-result-new-captcha-image intec-grid-item-auto">
                                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?= Html::encode($arResult['CAPTCHACode']) ?>" width="180" height="40" />
                                    </div>
                                    <div class="form-result-new-captcha-input intec-grid-item">
                                        <input type="text" class=" intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2" name="captcha_word" size="30" maxlength="50" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if ($arResult['CONSENT']['SHOW']) { ?>
                <div class="form-result-new-consent">
                    <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                        <input type="checkbox" checked="checked" onchange="this.checked = !this.checked" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content"><?= Loc::getMessage('C_FORM_RESULT_NEW_DEFAULT_CONSENT', [
                            '#URL#' => $arResult['CONSENT']['URL']
                        ]) ?></span>
                    </label>
                </div>
            <?php } ?>
            <div class="form-result-new-buttons">
                <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                    <div class="intec-grid-item-auto">
                        <?= Html::hiddenInput('web_form_sent', 'Y') ?>
                        <?= Html::submitButton(!empty($arResult['arForm']['BUTTON']) ? $arResult['arForm']['BUTTON'] : Loc::getMessage('C_FORM_RESULT_NEW_DEFAULT_BUTTONS_SUBMIT'), [
                            'class' => [
                                'intec-ui' => [
                                    '',
                                    'control-button',
                                    'mod-round-3',
                                    'scheme-current',
                                    'size-1'
                                ]
                            ],
                            'name' => 'web_form_submit',
                            'value' => 'Y',
                            'disabled' => Type::toInteger($arResult['F_RIGHT']) < 10 ? 'disabled' : null
                        ]) ?>
                    </div>
                    <div class="intec-grid-item-auto">
                        <input class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent intec-ui-size-1" type="reset" value="<?= Loc::getMessage('C_FORM_RESULT_NEW_DEFAULT_BUTTONS_RESET') ?>" />
                    </div>
                </div>
            </div>
        <?= $arResult['FORM_FOOTER'] ?>
    <?php } ?>
    <script type="text/javascript">
        (function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var form = $('form', root);

            form.on('submit', function () {
                if (window.yandex && window.yandex.metrika) {
                    window.yandex.metrika.reachGoal('forms');
                    window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['arForm']['ID']) ?>);
                    window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['arForm']['ID'].'.send') ?>);
                }
            });
        })();
    </script>
</div>