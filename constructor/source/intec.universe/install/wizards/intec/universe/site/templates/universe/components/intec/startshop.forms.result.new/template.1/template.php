<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));

if ($arParams['AJAX_MODE'] == 'Y') {
    $sAjaxID = CAjax::GetComponentID($component->__name, $component->__template->__name, '');
    $APPLICATION->AddHeadString("<style>#wait_comp_".$sAjaxID." {display: none !important;}</style>");
}
?>
<?php if (($arResult['ERROR']['CODE'] == 0 || $arResult['ERROR']['CODE'] >= 4) && !$arResult['SENT']) { ?>
    <form method="post" action="<?= $APPLICATION->GetCurPageParam() ?>" id="<?= $sTemplateId ?>" class="startshop-forms-result-new template-1">
        <div class="startshop-forms-result-new-wrapper">
            <?php if ($arParams["SHOW_TITLE"] === "Y") { ?>
                <div class="startshop-forms-result-new-caption">
                    <?= Html::encode($arResult['LANG'][LANGUAGE_ID]['NAME']) ?>
                </div>
            <?php } ?>
            <input type="hidden" name="<?= Html::encode($arParams['REQUEST_VARIABLE_ACTION']) ?>" value="send" />
            <?php foreach ($arResult['PROPERTIES'] as $iPropertyID => $arProperty) { ?>
                <?php $sFieldId = $sTemplateId.'_'.$arProperty['CODE']; ?>
                <?php if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_TEXT) { ?>
                    <div class="startshop-forms-result-new-row">
                        <label for="<?= $sFieldId ?>" class="startshop-forms-result-new-row-name startshop-forms-result-new-table-cell-name">
                            <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                            <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                                <span class="startshop-forms-result-new-required">*</span>
                            <?php } ?>
                        </label>
                        <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                            <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                <div class="startshop-forms-result-new-message-error">
                                    <?= Loc::getMessage('SFRN_FIELD_EMPTY') ?>
                                </div>
                            <?php } ?>
                            <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['INVALID'])) { ?>
                                <div class="startshop-forms-result-new-message-error">
                                    <?= Loc::getMessage('SFRN_FIELD_INVALID') ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="startshop-forms-result-new-row-control">
                            <?
                            $propValue = "";
                            if($_REQUEST[$arProperty['CODE']]){
                                $propValue = $_REQUEST[$arProperty['CODE']];
                            } else {
                                if($arParams["FIELDS"]){
                                    foreach($arParams["FIELDS"] as $key => $val){
                                        if($key == $arProperty["ID"]){
                                            $propValue = $val;
                                            break;
                                        }
                                    }
                                }
                            } ?>
                            <?= Html::textInput($arProperty['CODE'], $propValue, [
                                'id' => $sFieldId,
                                'class' => 'startshop-input-text intec-input intec-input-block',
                                'data' => [
                                        'role' => 'input'
                                ],
                                'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                            ]) ?>
                            <?php if (!empty($arProperty['DATA']['MASK'])) { ?>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('#<?= $sTemplateId ?>')
                                            .find('input[name="<?= Html::encode($arProperty['CODE']) ?>"]')
                                            .mask(<?= JavaScript::toObject($arProperty['DATA']['MASK']) ?>, {
                                                'placeholder': <?= JavaScript::toObject($arProperty['DATA']['MASK_PLACEHOLDER']) ?>
                                            });
                                    })
                                </script>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_TEXTAREA) { ?>
                    <div class="startshop-forms-result-new-row">
                        <label for="<?= $sFieldId ?>" class="startshop-forms-result-new-row-name startshop-forms-result-new-table-cell-name">
                            <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                            <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                                <span class="startshop-forms-result-new-required">*</span>
                            <?php } ?>
                        </label>
                        <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                            <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                <div class="startshop-forms-result-new-message-error">
                                    <?= Loc::getMessage('SFRN_FIELD_EMPTY') ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="startshop-forms-result-new-row-control">
                            <?= Html::textarea($arProperty['CODE'], $_REQUEST[$arProperty['CODE']], [
                                'id' => $sFieldId,
                                'class' => 'startshop-input-textarea intec-input intec-input-block',
                                'data' => [
                                    'role' => 'input'
                                ],
                                'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                            ]) ?>
                        </div>
                    </div>
                <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_RADIO) { ?>
                    <div class="startshop-forms-result-new-row">
                        <div class="startshop-forms-result-new-row-name startshop-forms-result-new-table-cell-name">
                            <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                            <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                                <span class="startshop-forms-result-new-required">*</span>
                            <?php } ?>
                        </div>
                        <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                            <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                <div class="startshop-forms-result-new-message-error">
                                    <?= Loc::getMessage('SFRN_FIELD_EMPTY') ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="startshop-forms-result-new-row-control">
                            <div class="startshop-forms-result-new-row-control-box startshop-input-box">
                                <div class="startshop-forms-result-new-row-control-box-wrapper">
                                    <?php foreach($arProperty['DATA']['VALUES'] as $iValueID => $arValue) { ?>
                                        <div class="startshop-forms-result-new-row-control-box-line">
                                            <label class="intec-input intec-input-radio">
                                                <?= Html::radio($arProperty['CODE'], $_REQUEST[$arProperty['CODE']] == $arValue['VALUE'], [
                                                    'value' => $arValue['VALUE'],
                                                    'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                                                ]) ?>
                                                <div class="intec-input-selector"></div>
                                                <div class="intec-input-text">
                                                    <?= Html::encode($arValue['VALUE']) ?>
                                                </div>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_CHECKBOX) { ?>
                    <div class="startshop-forms-result-new-row">
                        <div class="startshop-forms-result-new-row-control">
                            <div class="startshop-forms-result-new-row-control-box-line">
                                <input type="hidden" name="<?=htmlspecialcharsbx($arProperty['CODE'])?>" value="N" />
                                <label class="intec-input intec-input-checkbox">
                                    <?= Html::checkbox($arProperty['CODE'], $_REQUEST[$arProperty['CODE']] == 'Y', [
                                        'value' => 'Y',
                                        'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                                    ]) ?>
                                    <div class="intec-input-selector"></div>
                                    <div class="intec-input-text">
                                        <?= Html::encode(ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME'], '')) ?>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_SELECT) { ?>
                    <div class="startshop-forms-result-new-row">
                        <label for="<?= $sFieldId ?>" class="startshop-forms-result-new-row-name startshop-forms-result-new-table-cell-name">
                            <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                            <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                                <span class="startshop-forms-result-new-required">*</span>
                            <?php } ?>
                        </label>
                        <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                            <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                <div class="startshop-forms-result-new-message-error">
                                    <?= Loc::getMessage('SFRN_FIELD_EMPTY') ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="startshop-forms-result-new-row-control">
                            <select id="<?= $sFieldId ?>" name="<?= Html::encode($arProperty['CODE']) ?>" class="startshop-input-select startshop-input-select-standart"<?= $arProperty['READONLY'] == 'Y' ? ' disabled="disabled"' : '' ?>>
                                <?php foreach($arProperty['DATA']['VALUES'] as $iValueID => $arValue) { ?>
                                    <option value="<?= Html::encode($arValue['VALUE']) ?>"<?= $_REQUEST[$arProperty['CODE']] == $arValue['VALUE'] ? ' selected="selected"' : '' ?>>
                                        <?= Html::encode($arValue['VALUE']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_MULTISELECT) { ?>
                    <div class="startshop-forms-result-new-row">
                        <label for="<?= $sFieldId ?>" class="startshop-forms-result-new-row-name startshop-forms-result-new-table-cell-name">
                            <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                            <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                                <span class="startshop-forms-result-new-required">*</span>
                            <?php } ?>
                        </label>
                        <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                            <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                <div class="startshop-forms-result-new-message-error">
                                    <?= Loc::getMessage('SFRN_FIELD_EMPTY') ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="startshop-forms-result-new-row-control">
                            <select id="<?= $sFieldId ?>" name="<?=htmlspecialcharsbx($arProperty['CODE'])?>[]" multiple="multiple" class="intec-input"<?=$arProperty['READONLY'] == 'Y' ? ' disabled="disabled"' : ''?>>
                                <?php foreach($arProperty['DATA']['VALUES'] as $iValueID => $arValue) { ?>
                                <?php
                                    $bSelected = false;

                                    if (Type::isArray($_REQUEST[$arProperty['CODE']])) {
                                        $bSelected = ArrayHelper::isIn($arValue['VALUE'], $_REQUEST[$arProperty['CODE']]);
                                    } else {
                                        $bSelected = $_REQUEST[$arProperty['CODE']] == $arValue['VALUE'];
                                    }
                                ?>
                                    <option value="<?= Html::encode($arValue['VALUE']) ?>"<?= $bSelected ? ' selected="selected"' : '' ?>>
                                        <?= Html::encode($arValue['VALUE']) ?>
                                    </option>
                                <?php unset($bSelected) ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_PASSWORD) { ?>
                    <div class="startshop-forms-result-new-row">
                        <label for="<?= $sFieldId ?>" class="startshop-forms-result-new-row-name startshop-forms-result-new-table-cell-name">
                            <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                            <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                                <span class="startshop-forms-result-new-required">*</span>
                            <?php } ?>
                        </label>
                        <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                            <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                <div class="startshop-forms-result-new-message-error">
                                    <?= Loc::getMessage('SFRN_FIELD_EMPTY') ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="startshop-forms-result-new-row-control">
                            <?= Html::passwordInput($arProperty['CODE'], $_REQUEST[$arProperty['CODE']], [
                                'id' => $sFieldId,
                                'class' => 'startshop-input-text intec-input intec-input-block',
                                'data' => [
                                    'role' => 'input'
                                ],
                                'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                            ]) ?>
                        </div>
                    </div>
                <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_HIDDEN) { ?>
                    <?= Html::hiddenInput($arProperty['CODE'], $_REQUEST[$arProperty['CODE']], [
                        'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                    ]) ?>
                <?php } ?>
            <?php } ?>
            <?php if ($arResult['USE_CAPTCHA'] == 'Y') { ?>
                <div class="startshop-forms-result-new-captcha">
                    <?php $sCaptchaSID = $APPLICATION->CaptchaGetCode() ?>
                    <input type="hidden" name="<?= Html::encode($arParams['FORM_VARIABLE_CAPTCHA_SID']) ?>" value="<?= $sCaptchaSID ?>" />
                    <label for="<?= $sTemplateId.'_captcha' ?>" class="startshop-forms-result-new-captcha-caption">
                        <?= Loc::getMessage('SFRN_CAPTCHA_CAPTION') ?> <span class="startshop-forms-result-new-required">*</span>
                    </label>
                    <?php if ($arResult['ERROR']['CODE'] == 4) { ?>
                        <div class="startshop-forms-result-new-message-error">
                            <?= Loc::getMessage('SFRN_CAPTCHA_INVALID') ?>
                        </div>
                    <?php } ?>
                    <div class="intec-grid intec-grid-wrap intec-grid-i-10">
                        <div class="intec-grid-item startshop-forms-result-new-captcha-image">
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $sCaptchaSID ?>" alt="CAPTCHA" />
                        </div>
                        <div class="intec-grid-item startshop-forms-result-new-captcha-code">
                            <input id="<?= $sTemplateId.'_captcha' ?>" type="text" class="intec-input intec-input-block" name="<?= Html::encode($arParams['FORM_VARIABLE_CAPTCHA_CODE']) ?>" value="<?= Html::encode($_REQUEST[$arParams['FORM_VARIABLE_CAPTCHA_CODE']]) ?>" />
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if($arResult['CONSENT']['SHOW']) { ?>
                <div class="startshop-forms-result-new-consent">
                    <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                        <input type="checkbox" readonly="readonly" checked="checked" />
                        <label class="intec-ui-part-selector"></label>
                        <label class="intec-ui-part-content">
                            <?= Loc::getMessage("SFRN_CONSENT", [
                                "#URL#" => $arResult['CONSENT']['URL']
                            ]);?>
                        </label>
                    </label>
                </div>
            <?php } ?>
            <div class="submit-block">
                <input type="submit" class="startshop-forms-result-new-form-button intec-button intec-button-cl-common intec-button-s-5" value="<?=$arResult['LANG'][LANGUAGE_ID]['BUTTON']?>"/>
            </div>
        </div>
        <script type="text/javascript">
            (function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

                root.on('submit', function () {
                    if (window.yandex && window.yandex.metrika) {
                        window.yandex.metrika.reachGoal('forms');
                        window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['ID']) ?>);
                    }
                });
            })();
        </script>
    </form>
<?php } else if ($arResult['SENT']) { ?>
    <div class="startshop-forms-result-new default">
        <div class="startshop-forms-result-new-wrapper">
            <div class="startshop-forms-result-new-sent">
                <?= nl2br(ArrayHelper::getValue($arResult, ['LANG', LANGUAGE_ID, 'SENT'], '')) ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="startshop-forms-result-new default">
        <div class="startshop-forms-result-new-wrapper">
            <?php if ($arResult['ERROR']['CODE'] == 1) { ?>
                <div class="startshop-forms-result-new-message-error">
                    <?= Loc::getMessage('SFRN_ERROR_FORM_NOT_EXISTS') ?>
                </div>
            <?php } else if ($arResult['ERROR']['CODE'] == 2) { ?>
                <div class="startshop-forms-result-new-message-error">
                    <?= Loc::getMessage('SFRN_ERROR_FORM_INBOUND_SITE') ?>
                </div>
            <?php } else if ($arResult['ERROR']['CODE'] == 3) { ?>
                <div class="startshop-forms-result-new-message-error">
                    <?= Loc::getMessage('SFRN_ERROR_FORM_FIELDS_NOT_EXISTS') ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<script>
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

        var inputs = $('[data-role="input"]', root);
        var update;

        update = function() {
            var self = $(this);

            if (self.val() != '') {
                self.addClass('completed');
            } else {
                self.removeClass('completed');
            }
        };

        $(document).ready(function () {
            inputs.each(function () {
                update.call(this);
            });
        });

        inputs.on('change', function () {
            update.call(this);
        });
    })(jQuery, intec)
</script>