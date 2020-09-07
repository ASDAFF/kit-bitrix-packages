<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
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

?>
<div class="ns-bitrix c-main-profile c-main-profile-personal">
    <div class="main-profile-wrapper intec-content">
        <div class="main-profile-wrapper-2 intec-content-wrapper">
            <div class="main-profile-block">
                <h2 class="main-profile-header intec-ui-markup-header">
                    <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_HEADER') ?>
                </h2>
                <?php if (!empty($arResult['strProfileError'])) { ?>
                    <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                        <?= $arResult['strProfileError'] ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['DATA_SAVED'] === 'Y') { ?>
                    <div class="intec-ui intec-ui-control-alert intec-ui-scheme-green intec-ui-m-b-20">
                        <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_MESSAGES_CHANGED') ?>
                    </div>
                <?php } ?>
                <div class="main-profile-form intec-ui-form">
                    <form method="POST" action="<?= $arResult['FORM_TARGET'] ?>?" enctype="multipart/form-data">
                        <?= $arResult['BX_SESSION_CHECK'] ?>
                        <?= Html::hiddenInput('lang', LANG) ?>
                        <?= Html::hiddenInput('ID', $arResult['ID']) ?>
                        <div class="main-profile-form-fields intec-ui-form-fields">
                            <div class="main-profile-form-field intec-ui-form-field">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_FIELDS_NAME_TITLE') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('NAME', $arResult['arUser']['NAME'], [
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
                            <div class="main-profile-form-field intec-ui-form-field">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_FIELDS_LAST_NAME_TITLE') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('LAST_NAME', $arResult['arUser']['LAST_NAME'], [
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
                            <div class="main-profile-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_FIELDS_LOGIN_TITLE') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('LOGIN', $arResult['arUser']['LOGIN'], [
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
                            <div class="main-profile-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_FIELDS_EMAIL_TITLE') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('EMAIL', $arResult['arUser']['EMAIL'], [
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
                            <div class="main-profile-form-field intec-ui-form-field">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_FIELDS_PERSONAL_PHONE_TITLE') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::textInput('PERSONAL_PHONE', $arResult['arUser']['PERSONAL_PHONE'], [
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
                        <div class="main-profile-form-buttons intec-grid intec-grid-wrap intec-grid-i-5">
                            <div class="intec-grid-item-auto">
                                <?= Html::submitInput(Loc::getMessage('C_MAIN_PROFILE_PERSONAL_BUTTONS_SAVE'), [
                                    'name' => 'save',
                                    'class' => [
                                        'main-profile-form-button',
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
                                <?php if (!empty($arParams['USER_URL_CHANGE_PSW'])) { ?>
                                    <a href="<?= $arParams['USER_URL_CHANGE_PSW'] ?>" class="main-profile-form-button intec-ui intec-ui-control-button intec-ui-mod-transparent intec-ui-mod-round-3 intec-ui-size-2">
                                        <?= Loc::getMessage('C_MAIN_PROFILE_PERSONAL_BUTTONS_PASSWORD_CHANGE') ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>