<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\constructor\models\Build;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$oBuild = Build::getCurrent();

if (!empty($oBuild)) {
    $oPage = $oBuild->getPage();
    $oProperties = $oPage->getProperties();
    $personal_data = $oProperties->get('base-consent');
}

$request = Core::$app->request;

?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-form-result-new c-form-result-new-contacts intec-ui-form">
    <?php if ($arResult['isFormNote'] == 'Y') { ?>
        <div class="form-result-new-note">
            <?= $arResult["FORM_NOTE"] ?>
        </div>
    <?php } else { ?>
        <?= $arResult['FORM_HEADER'] ?>
        <?php if ($arResult['isFormTitle'] == 'Y') { ?>
            <div class="form-result-new-header">
                <?= $arResult["FORM_TITLE"] ?>
            </div>
        <?php } ?>
        <?php if ($arResult["isFormErrors"] == 'Y') { ?>
            <div class="form-result-new-error">
                <?= $arResult["FORM_ERRORS_TEXT"] ?>
            </div>
        <?php } ?>
        <div class="form-result-new-fields intec-ui-form-fields">
            <?php foreach ($arResult["QUESTIONS"] as $iFieldId => $arField) { ?>
            <?php
                $iId = $arField['STRUCTURE'][0]['ID'];
                $sType = $arField['STRUCTURE'][0]['FIELD_TYPE'];
                $sName = 'form_'.$sType.'_'.$iId;
                $sTitle = $arField['CAPTION'];

                if ($sType != 'text' && $sType != 'textarea' && $sType != 'email')
                    continue;
            ?>
                <?= Html::beginTag('label', [
                    'class' => Html::cssClassFromArray([
                        'form-result-new-field' => true,
                        'form-result-new-field-'.$sType => true,
                        'intec-ui-form-field' => true,
                        'intec-ui-form-field-required' => $arField['REQUIRED'] === 'Y'
                    ], true)
                ]) ?>
                    <span class="form-result-new-field-title intec-ui-form-field-title">
                        <?= $sTitle ?>
                    </span>
                    <span class="form-result-new-field-content intec-ui-form-field-content">
                        <?php if ($sType == 'text' || $sType == 'email') { ?>
                            <input type="text" class="intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2" value="<?= Html::encode($request->post($sName)) ?>" name="<?= $sName ?>" />
                        <?php } else { ?>
                            <textarea class="intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2" name="<?= $sName ?>"><?= Html::encode($request->post($sName)) ?></textarea>
                        <?php } ?>
                    </span>
                <?= Html::endTag('label') ?>
            <?php } ?>
            <?php if ($arResult['isUseCaptcha'] == 'Y') { ?>
                <div class="form-result-new-field form-result-new-field-captcha intec-ui-form-field intec-ui-form-field-required">
                    <div class="form-result-new-field-title intec-ui-form-field-title">
                        <?= Loc::getMessage('C_FORM_RESULT_NEW_CONTACTS_FIELD_CAPTCHA') ?>
                    </div>
                    <div class="form-result-new-field-content intec-form-value">
                        <input type="hidden" name="captcha_sid" value="<?= Html::encode($arResult["CAPTCHACode"]) ?>" />
                        <div class="startshop-form-result-new-captcha intec-grid intec-grid-nowrap intec-grid-i-h-5">
                            <div class="startshop-form-result-new-captcha-image intec-grid-item-auto">
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= Html::encode($arResult["CAPTCHACode"]) ?>" width="180" height="40" />
                            </div>
                            <div class="startshop-form-result-new-captcha-input intec-grid-item">
                                <input type="text" name="captcha_word" class="intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2" size="30" maxlength="50" value="" />
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="intec-ui-clear"></div>
        <div class="form-result-new-footer">
            <div class="intec-grid intec-grid-wrap intec-grid-i-h-10 intec-grid-i-v-5 intec-grid-a-v-center intec-grid-a-h-end">
                <?php if ($arResult['CONSENT']['SHOW']) { ?>
                    <div class="intec-grid-item intec-grid-item-450-1">
                        <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                            <input type="checkbox" checked="checked" onchange="this.checked = !this.checked" />
                            <span class="intec-ui-part-selector"></span>
                            <span class="intec-ui-part-content">
                            <?= Loc::getMessage('C_FORM_RESULT_NEW_CONTACTS_CONSENT', [
                                '#URL#' => $arResult['CONSENT']['URL']
                            ]) ?>
                        </span>
                        </label>
                    </div>
                <? } ?>
                <div class="intec-grid-item-auto intec-grid-item-450-1">
                    <input type="hidden" name="web_form_apply" value="Y" />
                    <input type="submit" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-2" value="<?= Loc::getMessage('C_FORM_RESULT_NEW_CONTACTS_BUTTON_SEND') ?>" />
                </div>
            </div>
        </div>
        <?= $arResult["FORM_FOOTER"] ?>
        <script type="text/javascript">
            (function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var form = $('form', root);

                form.on('submit', function () {
                    if (window.yandex && window.yandex.metrika) {
                        window.yandex.metrika.reachGoal('forms');
                        window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['arForm']['ID']) ?>);
                    }
                });
            })();
        </script>
    <?php } ?>
</div>