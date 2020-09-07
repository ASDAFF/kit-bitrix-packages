<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);
$arPost = Core::$app->request->post();

?>
<!--noindex-->
<div class="ns-bitrix c-system-auth-authorize c-system-auth-authorize-default">
    <div class="system-auth-authorize-wrapper intec-content">
        <div class="system-auth-authorize-wrapper-2 intec-content-wrapper">
            <div class="system-auth-authorize-block">
                <h2 class="system-auth-authorize-header intec-ui-markup-header">
                    <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_TITLE') ?>
                </h2>
                <div class="intec-grid intec-grid-wrap intec-grid-i-h-15 intec-grid-i-v-5 intec-grid-a-v-stretch">
                    <div class="intec-grid-item-2 intec-grid-item-800-1">
                        <div class="system-auth-authorize-form intec-ui-form">
                            <form method="POST" action="<?= $arResult['URL']['AUTHORIZE'] ?>">
                                <?= bitrix_sessid_post() ?>
                                <?= Html::hiddenInput('AUTH_FORM', 'Y') ?>
                                <?= Html::hiddenInput('TYPE', 'AUTH') ?>
                                <?php if (!empty($arResult['URL']['BACK'])) { ?>
                                    <?= Html::hiddenInput('backurl', $arResult['URL']['BACK']) ?>
                                <?php } ?>
                                <?php foreach ($arPost as $sKey => $mValue) { ?>
                                    <?= Html::hiddenInput($sKey, $mValue) ?>
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
                                <div class="system-auth-authorize-form-fields intec-ui-form-fields">
                                    <div class="system-auth-authorize-form-field intec-ui-form-field">
                                        <div class="intec-ui-form-field-title">
                                            <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_FIELDS_LOGIN') ?>
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
                                                ],
                                                'maxlength' => 255
                                            ]) ?>
                                        </div>
                                    </div>
                                    <div class="system-auth-authorize-form-field intec-ui-form-field">
                                        <div class="intec-ui-form-field-title">
                                            <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_FIELDS_PASSWORD') ?>
                                        </div>
                                        <div class="intec-ui-form-field-content">
                                            <?= Html::passwordInput('USER_PASSWORD', null, [
                                                'class' => [
                                                    'intec-ui' => [
                                                        '',
                                                        'control-input',
                                                        'mod-block',
                                                        'mod-round-3',
                                                        'size-2'
                                                    ]
                                                ],
                                                'maxlength' => 255
                                            ]) ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($arResult['CAPTCHA_CODE'])) { ?>
                                        <div class="system-auth-authorize-form-field intec-ui-form-field">
                                            <div class="intec-ui-form-field-title">
                                                <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_FIELDS_CAPTCHA') ?>
                                            </div>
                                            <div class="intec-ui-form-field-content">
                                                <div class="intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                                    <div class="intec-grid-item-auto">
                                                        <?= Html::img('/bitrix/tools/captcha.php?captcha_sid='.$arResult['CAPTCHA_CODE'], [
                                                            'width' => 180,
                                                            'height' => 40,
                                                            'alt' => 'CAPTCHA'
                                                        ]) ?>
                                                    </div>
                                                    <div class="intec-grid-item">
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
                                <?php if ($arParams['NOT_SHOW_LINKS'] !== 'Y' || $arResult['STORE_PASSWORD'] === 'Y') { ?>
                                    <div class="system-auth-authorize-form-additions">
                                        <div class="intec-grid intec-grid-nowrap intec-grid-i-h-5">
                                            <?php if ($arResult['STORE_PASSWORD'] === 'Y') { ?>
                                                <div class="intec-grid-item-auto">
                                                    <label class="system-auth-authorize-form-remember intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                                                        <input type="checkbox" name="USER_REMEMBER" value="Y"/>
                                                        <span class="intec-ui-part-selector"></span>
                                                        <span class="intec-ui-part-content">
                                                            <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_REMEMBER') ?>
                                                        </span>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                            <div class="intec-grid-item"></div>
                                            <?php if ($arParams['NOT_SHOW_LINKS'] !== 'Y' && !empty($arResult['URL']['RESTORE'])) { ?>
                                                <div class="intec-grid-item-auto">
                                                    <a class="system-auth-authorize-form-restore" href="<?= $arResult['URL']['RESTORE'] ?>" rel="nofollow">
                                                        <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_RESTORE') ?>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="system-auth-authorize-form-buttons">
                                    <?= Html::submitInput(Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_BUTTONS_AUTHORIZE'), [
                                        'name' => 'Login',
                                        'class' => [
                                            'system-auth-authorize-form-button',
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
                            </form>
                            <?php if (!empty($arResult['AUTH_SERVICES'])) { ?>
                                <div class="system-auth-authorize-form-socials">
                                    <div class="system-auth-authorize-form-socials-title">
                                        <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_SOCIALS') ?>
                                    </div>
                                    <div class="system-auth-authorize-form-socials-content">
                                        <?php $APPLICATION->IncludeComponent(
                                            'bitrix:socserv.auth.form',
                                            '',
                                            [
                                                'AUTH_SERVICES' => $arResult['AUTH_SERVICES'],
                                                'CURRENT_SERVICE' => $arResult['CURRENT_SERVICE'],
                                                'AUTH_URL' => $arResult['AUTH_URL'],
                                                'POST' => $arResult['POST'],
                                                'SUFFIX' => 'main'
                                            ],
                                            $component
                                        ) ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="intec-grid-item-2 intec-grid-item-800-1">
                        <?php if (!empty($arResult['URL']['REGISTER']) && $arParams['NOT_SHOW_LINKS'] !== 'Y' && $arResult['NEW_USER_REGISTRATION'] === 'Y' && $arParams['AUTHORIZE_REGISTRATION'] !== 'Y') { ?>
                            <div class="system-auth-authorize-delimiter"></div>
                            <div class="system-auth-authorize-registration">
                                <?= Html::a(Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_BUTTONS_REGISTER'), $arResult['URL']['REGISTER'], [
                                    'class' => [
                                        'system-auth-authorize-registration-button',
                                        'intec-ui' => [
                                            '',
                                            'control-button',
                                            'mod-round-3',
                                            'scheme-current',
                                            'size-2'
                                        ]
                                    ]
                                ]) ?>
                                <div class="system-auth-authorize-registration-text">
                                    <?= Loc::getMessage('C_SYSTEM_AUTH_AUTHORIZE_DEFAULT_REGISTER') ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/noindex-->