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
<div class="ns-bitrix c-system-auth-forgotpasswd c-system-auth-forgotpasswd-default">
    <div class="system-auth-forgotpasswd-wrapper intec-content">
        <div class="system-auth-forgotpasswd-wrapper-2 intec-content-wrapper">
            <div class="system-auth-forgotpasswd-block">
                <h2 class="system-auth-forgotpasswd-header intec-ui-markup-header">
                    <?= Loc::getMessage('C_SYSTEM_AUTH_FORGOTPASSWD_DEFAULT_TITLE') ?>
                </h2>
                <div class="system-auth-forgotpasswd-form intec-ui-form">
                    <form method="POST" action="<?= $arResult['URL']['RESTORE'] ?>">
                        <?= bitrix_sessid_post() ?>
                        <?= Html::hiddenInput('AUTH_FORM', 'Y') ?>
                        <?= Html::hiddenInput('TYPE', 'SEND_PWD') ?>
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
                        <div class="system-auth-forgotpasswd-form-description intec-ui-markup-blockquote">
                            <?= Loc::getMessage('C_SYSTEM_AUTH_FORGOTPASSWD_DEFAULT_DESCRIPTION') ?>
                        </div>
                        <div class="system-auth-forgotpasswd-form-fields intec-ui-form-fields">
                            <div class="system-auth-forgotpasswd-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_SYSTEM_AUTH_FORGOTPASSWD_DEFAULT_FIELDS_EMAIL') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('USER_EMAIL', null, [
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
                                        <?= Loc::getMessage('C_SYSTEM_AUTH_FORGOTPASSWD_DEFAULT_FIELDS_CAPTCHA') ?>
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
                        <div class="system-auth-forgotpasswd-form-buttons">
                            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                                <div class="intec-grid-item-auto">
                                    <?= Html::submitInput(Loc::getMessage('C_SYSTEM_AUTH_FORGOTPASSWD_DEFAULT_BUTTONS_RESTORE'), [
                                        'name' => 'send_account_info',
                                        'class' => [
                                            'system-auth-forgotpasswd-form-button',
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
                                    <?= Html::a(Loc::getMessage('C_SYSTEM_AUTH_FORGOTPASSWD_DEFAULT_BUTTONS_AUTHORIZE'), $arResult['URL']['AUTHORIZE'], [
                                        'class' => [
                                            'system-auth-forgotpasswd-form-button',
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