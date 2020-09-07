<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);

?>
<div class="ns-bitrix c-system-auth-changepasswd c-system-auth-changepasswd-default">
    <div class="system-auth-changepasswd-wrapper intec-content">
        <div class="system-auth-changepasswd-wrapper-2 intec-content-wrapper">
            <div class="system-auth-changepasswd-block">
                <h2 class="system-auth-changepasswd-header intec-ui-markup-header">
                    <?= Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_TITLE') ?>
                </h2>
                <div class="system-auth-changepasswd-form intec-ui-form">
                    <form method="POST" action="<?= $arResult['URL']['CHANGE'] ?>">
                        <?= bitrix_sessid_post() ?>
                        <?= Html::hiddenInput('AUTH_FORM', 'Y') ?>
                        <?= Html::hiddenInput('TYPE', 'CHANGE_PWD') ?>
                        <?php if (!empty($arResult['URL']['BACK'])) { ?>
                            <?= Html::hiddenInput('backurl', $arResult['URL']['BACK']) ?>
                        <?php } ?>
                        <?php if (!empty($arResult['ERRORS'])) { ?>
                            <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                                <?= implode('<br />', $arResult['ERRORS']) ?>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arResult['MESSAGES'])) { ?>
                            <div class="intec-ui intec-ui-control-alert intec-ui-scheme-blue intec-ui-m-b-20">
                                <?= implode('<br />', $arResult['MESSAGES']) ?>
                            </div>
                        <?php } ?>
                        <div class="system-auth-changepasswd-form-fields intec-ui-form-fields">
                            <div class="system-auth-changepasswd-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_FIELDS_LOGIN') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('USER_LOGIN', $arResult['LAST_LOGIN'], [
                                        'class' => [
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2'
                                            ]
                                        ]
                                    ]) ?>
                                </div>
                            </div>
                            <div class="system-auth-changepasswd-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_FIELDS_CHECKWORD') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('USER_CHECKWORD', $arResult['USER_CHECKWORD'], [
                                        'class' => [
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2'
                                            ]
                                        ]
                                    ]) ?>
                                </div>
                            </div>
                            <div class="system-auth-changepasswd-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_FIELDS_PASSWORD_NEW') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::passwordInput('USER_PASSWORD', $arResult['USER_PASSWORD'], [
                                        'class' => [
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2'
                                            ]
                                        ]
                                    ]) ?>
                                </div>
                                <?php if (!empty($arResult['GROUP_POLICY']['PASSWORD_REQUIREMENTS'])) { ?>
                                    <div class="intec-ui-form-field-description">
                                        <?= $arResult['GROUP_POLICY']['PASSWORD_REQUIREMENTS'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="system-auth-changepasswd-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_FIELDS_PASSWORD_CONFIRM') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::passwordInput('USER_CONFIRM_PASSWORD', $arResult['USER_CONFIRM_PASSWORD'], [
                                        'class' => [
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2'
                                            ]
                                        ]
                                    ]) ?>
                                </div>
                            </div>
                            <?php if ($arResult['USE_CAPTCHA']) { ?>
                                <div class="system-auth-forgotpasswd-form-field intec-ui-form-field intec-ui-form-field-required">
                                    <div class="intec-ui-form-field-title">
                                        <?= Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_FIELDS_CAPTCHA') ?>
                                    </div>
                                    <div class="intec-ui-form-field-content">
                                        <div class="intec-grid intec-grid-nowrap intec-grid-400-wrap intec-grid-i-5">
                                            <div class="intec-grid-item-auto intec-grid-item-400-1">
                                                <?= Html::img('/bitrix/tools/captcha.php?captcha_sid='.$arResult['CAPTCHA_CODE'], [
                                                    'width' => 180,
                                                    'height' => 40,
                                                    'alt' => 'CAPTCHA'
                                                ]) ?>
                                            </div>
                                            <div class="intec-grid-item intec-grid-item-400-1">
                                                <?= Html::hiddenInput('captcha_sid', $arResult['CAPTCHA_CODE']) ?>
                                                <?= Html::textInput('captcha_word', null, [
                                                    'class' => [
                                                        'intec-ui' => [
                                                            '',
                                                            'control-input',
                                                            'mod-block',
                                                            'mod-round-3',
                                                            'size-2'
                                                        ]
                                                    ]
                                                ]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="system-auth-changepasswd-form-buttons">
                            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                                <div class="intec-grid-item-auto">
                                    <?= Html::submitInput(Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_BUTTONS_RESTORE'), [
                                        'name' => 'change_pwd',
                                        'class' => [
                                            'system-auth-changepasswd-form-button',
                                            'intec-ui' => [
                                                '',
                                                'control-button',
                                                'mod-round-3',
                                                'scheme-current',
                                                'size-2'
                                            ]
                                        ]
                                    ]) ?>
                                </div>
                                <div class="intec-grid-item-auto">
                                    <?= Html::a(Loc::getMessage('C_SYSTEM_AUTH_CHANGEPASSWD_DEFAULT_BUTTONS_AUTHORIZE'), $arResult['URL']['AUTHORIZE'], [
                                        'class' => [
                                            'system-auth-changepasswd-form-button',
                                            'intec-ui' => [
                                                '',
                                                'control-button',
                                                'mod-transparent',
                                                'mod-round-3',
                                                'size-2'
                                            ]
                                        ]
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>