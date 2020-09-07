<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
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

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));

?>
<div id="<?= $sTemplateId ?>" class="ns-intec c-startshop-form-result-new c-startshop-form-result-new-contacts intec-ui-form">
    <?php if (($arResult['ERROR']['CODE'] == 0 || $arResult['ERROR']['CODE'] >= 4) && !$arResult['SENT']) { ?>
        <div class="startshop-form-result-new-form intec-form">
            <div class="startshop-form-result-new-header"><?=htmlspecialcharsbx($arResult['LANG'][LANGUAGE_ID]['NAME'])?></div>
            <form method="post" action="<?=$APPLICATION->GetCurPage()?>">
                <div class="startshop-form-result-new-fields intec-ui-form-fields">
                    <input type="hidden" name="<?=htmlspecialcharsbx($arParams['REQUEST_VARIABLE_ACTION'])?>" value="send" />
                    <?php foreach ($arResult['PROPERTIES'] as $iPropertyID => $arProperty) { ?>
                        <?php $sFieldId = $sTemplateId.'_'.$arProperty['CODE']; ?>
                        <?php if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_TEXT) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'startshop-form-result-new-field' => true,
                                    'intec-ui-form-field' => true,
                                    'intec-ui-form-field-required' => $arProperty['REQUIRED'] === 'Y'
                                ], true)
                            ]) ?>
                                <label for="<?= $sFieldId ?>" class="startshop-form-result-new-title intec-ui-form-field-title">
                                    <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                                </label>
                                <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                    <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                        <div class="startshop-form-result-new-message-error">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_FIELD_EMPTY') ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['INVALID'])) { ?>
                                        <div class="startshop-form-result-new-message-error">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_FIELD_INVALID') ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="startshop-form-result-new-content intec-ui-form-field-content">
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
                                        'class' => [
                                            'textinput',
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2',
                                            ]
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
                            <?= Html::endTag('div') ?>
                        <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_TEXTAREA) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'startshop-form-result-new-field' => true,
                                    'startshop-form-result-new-field-textarea' => true,
                                    'intec-ui-form-field' => true,
                                    'intec-ui-form-field-required' => $arProperty['REQUIRED'] === 'Y'
                                ], true)
                            ]) ?>
                                <label for="<?= $sFieldId ?>" class="startshop-form-result-new-title intec-ui-form-field-title">
                                    <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                                </label>
                                <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                    <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                        <div class="startshop-form-result-new-message-error">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_FIELD_EMPTY') ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="startshop-form-result-new-content intec-ui-form-field-content">
                                    <?= Html::textarea($arProperty['CODE'], $_REQUEST[$arProperty['CODE']], [
                                        'id' => $sFieldId,
                                        'class' => [
                                            'textarea',
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2',
                                            ]
                                        ],
                                        'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                                    ]) ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_RADIO) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'startshop-form-result-new-field' => true,
                                    'intec-ui-form-field' => true,
                                    'intec-ui-form-field-required' => $arProperty['REQUIRED'] === 'Y'
                                ], true)
                            ]) ?>
                                <div class="startshop-form-result-new-title intec-ui-form-field-title">
                                    <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                                </div>
                                <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                    <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                        <div class="startshop-form-result-new-message-error">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_FIELD_EMPTY') ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="form-result-new-field-content intec-ui-form-field-content">
                                    <?php foreach($arProperty['DATA']['VALUES'] as $iValueID => $arValue) { ?>
                                        <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                                            <?= Html::radio($arProperty['CODE'], $_REQUEST[$arProperty['CODE']] == $arValue['VALUE'], [
                                                'value' => $arValue['VALUE'],
                                                'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                                            ]) ?>
                                            <div class="intec-ui-part-selector"></div>
                                            <div class="intec-ui-part-content">
                                                <?= Html::encode($arValue['VALUE']) ?>
                                            </div>
                                        </label>
                                        <br>
                                    <?php } ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_CHECKBOX) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'startshop-form-result-new-field' => true,
                                    'intec-ui-form-field' => true,
                                    'intec-ui-form-field-required' => $arProperty['REQUIRED'] === 'Y'
                                ], true)
                            ]) ?>
                                <div class="form-result-new-field-content intec-ui-form-field-content">
                                    <input type="hidden" name="<?=htmlspecialcharsbx($arProperty['CODE'])?>" value="N" />
                                    <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                                        <?= Html::checkbox($arProperty['CODE'], $_REQUEST[$arProperty['CODE']] == 'Y', [
                                            'value' => 'Y',
                                            'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                                        ]) ?>
                                        <div class="intec-ui-part-selector"></div>
                                        <div class="intec-ui-part-content">
                                            <?= Html::encode(ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME'], '')) ?>
                                        </div>
                                    </label>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_SELECT) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'startshop-form-result-new-field' => true,
                                    'intec-ui-form-field' => true,
                                    'intec-ui-form-field-required' => $arProperty['REQUIRED'] === 'Y'
                                ], true)
                            ]) ?>
                                <label for="<?= $sFieldId ?>" class="startshop-form-result-new-title intec-ui-form-field-title">
                                    <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                                </label>
                                <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                    <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                        <div class="startshop-form-result-new-message-error">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_FIELD_EMPTY') ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="form-result-new-field-content intec-ui-form-field-content">
                                    <?= Html::beginTag('select', [
                                        'id' => $sFieldId,
                                        'name' => Html::encode($arProperty['CODE']),
                                        'class' => [
                                            'inputselect',
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2',
                                            ]
                                        ],
                                        'disabled' => $arProperty['READONLY'] == 'Y' ? ' disabled="disabled"' : null
                                    ]) ?>
                                    <?php foreach($arProperty['DATA']['VALUES'] as $iValueID => $arValue) { ?>
                                        <option value="<?= Html::encode($arValue['VALUE']) ?>"<?= $_REQUEST[$arProperty['CODE']] == $arValue['VALUE'] ? ' selected="selected"' : '' ?>>
                                            <?= Html::encode($arValue['VALUE']) ?>
                                        </option>
                                    <?php } ?>
                                    <?= Html::endTag('select'); ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_MULTISELECT) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'startshop-form-result-new-field' => true,
                                    'intec-ui-form-field' => true,
                                    'intec-ui-form-field-required' => $arProperty['REQUIRED'] === 'Y'
                                ], true)
                            ]) ?>
                                <label for="<?= $sFieldId ?>" class="startshop-form-result-new-title intec-ui-form-field-title">
                                    <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                                </label>
                                <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                    <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                        <div class="startshop-form-result-new-message-error">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_FIELD_EMPTY') ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="form-result-new-field-content intec-ui-form-field-content">
                                    <?= Html::beginTag('select', [
                                        'id' => $sFieldId,
                                        'name' => htmlspecialcharsbx($arProperty['CODE'])."[]",
                                        'multiple' => 'multiple',
                                        'class' => [
                                            'inputselect',
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2',
                                            ]
                                        ],
                                        'disabled' => $arProperty['READONLY'] == 'Y' ? ' disabled="disabled"' : null
                                    ]) ?>
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
                                    <?= Html::endTag('select'); ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_PASSWORD) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'startshop-form-result-new-field' => true,
                                    'intec-ui-form-field' => true,
                                    'intec-ui-form-field-required' => $arProperty['REQUIRED'] === 'Y'
                                ], true)
                            ]) ?>
                                <label for="<?= $sFieldId ?>" class="startshop-form-result-new-title intec-ui-form-field-title">
                                    <?= ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']) ?>
                                </label>
                                <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                    <?php if (ArrayHelper::keyExists($arProperty['CODE'], $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                        <div class="startshop-form-result-new-message-error">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_FIELD_EMPTY') ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="form-result-new-field-content intec-ui-form-field-content">
                                    <?= Html::passwordInput($arProperty['CODE'], $_REQUEST[$arProperty['CODE']], [
                                        'id' => $sFieldId,
                                        'class' => [
                                            'inputtext',
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2',
                                            ],
                                        ],
                                        'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                                    ]) ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } else if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_HIDDEN) { ?>
                            <?= Html::hiddenInput($arProperty['CODE'], $_REQUEST[$arProperty['CODE']], [
                                'disabled' => $arProperty['READONLY'] == 'Y' ? 'disabled' : null
                            ]) ?>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($arResult['USE_CAPTCHA'] == 'Y') { ?>
                        <div class="startshop-form-result-new-field intec-ui-form-field intec-ui-form-field-required">
                            <?php $sCaptchaSID = $APPLICATION->CaptchaGetCode() ?>
                            <label for="<?= $sTemplateId.'_captcha' ?>" class="startshop-form-result-new-field-title intec-ui-form-field-title">
                                <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_CAPTCHA_CAPTION') ?>
                            </label>
                            <?php if ($arResult['ERROR']['CODE'] == 4) { ?>
                                <div class="startshop-form-result-new-message-error">
                                    <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_CAPTCHA_INVALID') ?>
                                </div>
                            <?php } ?>
                            <div class="startshop-form-result-new-field-content intec-ui-form-field-content">
                                <input type="hidden" name="<?= Html::encode($arParams['FORM_VARIABLE_CAPTCHA_SID']) ?>" value="<?= $sCaptchaSID ?>" />
                                <div class="startshop-form-result-new-captcha intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                    <div class="startshop-form-result-new-captcha-image intec-grid-item-auto">
                                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $sCaptchaSID ?>" alt="CAPTCHA" width="180" height="40" />
                                    </div>
                                    <div class="startshop-form-result-new-captcha-input intec-grid-item">
                                        <input id="<?= $sTemplateId.'_captcha' ?>" type="text" class="intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2" name="<?= Html::encode($arParams['FORM_VARIABLE_CAPTCHA_CODE']) ?>" value="<?= Html::encode($_REQUEST[$arParams['FORM_VARIABLE_CAPTCHA_CODE']]) ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="intec-ui-clear"></div>
                <div class="startshop-form-result-new-footer">
                    <div class="intec-grid intec-grid-wrap intec-grid-i-h-10 intec-grid-i-v-5 intec-grid-a-v-center intec-grid-a-h-end">
                        <?php if ($arResult['CONSENT']['SHOW']) { ?>
                            <div class="intec-grid-item intec-grid-item-450-1">
                                <div class="startshop-form-result-new-consent">
                                    <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                                        <input type="checkbox" readonly="readonly" checked="checked" />
                                        <label class="intec-ui-part-selector"></label>
                                        <label class="intec-ui-part-content">
                                            <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_CONSENT', [
                                                '#URL#' => $arResult['CONSENT']['URL']
                                            ]) ?>
                                        </label>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="intec-grid-item-auto intec-grid-item-450-1">
                            <input type="submit" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-2" value="<?=$arResult['LANG'][LANGUAGE_ID]['BUTTON']?>" />
                        </div>
                    </div>
                </div>
            </form>
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
        </div>
    <?php } elseif ($arResult['SENT']) { ?>
        <div class="startshop-forms-result-new-wrapper">
            <div class="startshop-forms-result-new-sent">
                <?= nl2br(ArrayHelper::getValue($arResult, ['LANG', LANGUAGE_ID, 'SENT'], '')) ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="startshop-forms-result-new-wrapper">
            <?php if ($arResult['ERROR']['CODE'] == 1) { ?>
                <div class="startshop-form-result-new-message-error intec-ui intec-ui-control-alert intec-ui-scheme-red">
                    <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_ERROR_FORM_NOT_EXISTS') ?>
                </div>
            <?php } else if ($arResult['ERROR']['CODE'] == 2) { ?>
                <div class="startshop-form-result-new-message-error intec-ui intec-ui-control-alert intec-ui-scheme-red">
                    <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_ERROR_FORM_INBOUND_SITE') ?>
                </div>
            <?php } else if ($arResult['ERROR']['CODE'] == 3) { ?>
                <div class="startshop-form-result-new-message-error intec-ui intec-ui-control-alert intec-ui-scheme-red">
                    <?= Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_ERROR_FORM_FIELDS_NOT_EXISTS') ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>