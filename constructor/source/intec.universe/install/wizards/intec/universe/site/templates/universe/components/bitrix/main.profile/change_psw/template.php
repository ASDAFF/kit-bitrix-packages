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
<div class="ns-bitrix c-main-profile c-main-profile-password">
    <div class="main-profile-wrapper intec-content">
        <div class="main-profile-wrapper-2 intec-content-wrapper">
            <div class="main-profile-block">
                <h2 class="main-profile-header intec-ui-markup-header">
                    <?= Loc::getMessage('C_MAIN_PROFILE_PASSWORD_HEADER') ?>
                </h2>
                <?php if (!empty($arResult['strProfileError'])) { ?>
                    <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                        <?= $arResult['strProfileError'] ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['DATA_SAVED'] === 'Y') { ?>
                    <div class="intec-ui intec-ui-control-alert intec-ui-scheme-green intec-ui-m-b-20">
                        <?= Loc::getMessage('C_MAIN_PROFILE_PASSWORD_MESSAGES_CHANGED') ?>
                    </div>
                <?php } ?>
                <div class="main-profile-form intec-ui-form">
                    <form method="POST" action="<?= $arResult['FORM_TARGET'] ?>" enctype="multipart/form-data">
                        <?= $arResult['BX_SESSION_CHECK'] ?>
                        <?= Html::hiddenInput('lang', LANG) ?>
                        <?= Html::hiddenInput('ID', $arResult['ID']) ?>
                        <?= Html::hiddenInput('LOGIN', $arResult['arUser']['LOGIN']) ?>
                        <?= Html::hiddenInput('EMAIL', $arResult['arUser']['EMAIL']) ?>
                        <div class="main-profile-form-fields intec-ui-form-fields">
                            <div class="main-profile-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PASSWORD_FIELDS_PASSWORD_NEW_TITLE') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::passwordInput('NEW_PASSWORD', null, [
                                        'class' => [
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2'
                                            ]
                                        ],
                                        'autocomplete' => 'off'
                                    ]) ?>
                                </div>
                                <div class="intec-ui-form-field-description">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PASSWORD_FIELDS_PASSWORD_NEW_DESCRIPTION') ?>
                                </div>
                            </div>
                            <div class="main-profile-form-field intec-ui-form-field intec-ui-form-field-required">
                                <div class="intec-ui-form-field-title">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PASSWORD_FIELDS_PASSWORD_CONFIRM_TITLE') ?>
                                </div>
                                <div class="intec-ui-form-field-content">
                                    <?= Html::passwordInput('NEW_PASSWORD_CONFIRM', null, [
                                        'class' => [
                                            'intec-ui' => [
                                                '',
                                                'control-input',
                                                'mod-block',
                                                'mod-round-3',
                                                'size-2'
                                            ]
                                        ],
                                        'autocomplete' => 'off'
                                    ]) ?>
                                </div>
                                <div class="intec-ui-form-field-description">
                                    <?= Loc::getMessage('C_MAIN_PROFILE_PASSWORD_FIELDS_PASSWORD_CONFIRM_DESCRIPTION') ?>
                                </div>
                            </div>
                        </div>
                        <div class="main-profile-form-buttons">
                            <?= Html::submitInput(Loc::getMessage('C_MAIN_PROFILE_PASSWORD_BUTTONS_SAVE'), [
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>