<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

global $USER;

if (!Loader::includeModule('intec.core'))
    return;

$arSubscription = ArrayHelper::getValue($arResult, 'SUBSCRIPTION');
$arRubrics = ArrayHelper::getValue($arResult, 'RUBRICS');
$bSubscribed = ArrayHelper::getValue($arSubscription, 'ID') != 0;
$bConfirmed = ArrayHelper::getValue($arSubscription, 'CONFIRMED') === 'Y';
$bFormatHtml = ArrayHelper::getValue($arSubscription, 'FORMAT') === 'html';
$sEmail = ArrayHelper::getValue($arSubscription, 'EMAIL');
$sEmail = !empty($sEmail) ? $sEmail : ArrayHelper::getValue($arResult, ['REQUEST', 'EMAIL']);

?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <?php if ($arResult['ALLOW_ANONYMOUS'] == 'N' && !$USER->IsAuthorized()) { ?>
            <?= Html::tag('div', Loc::getMessage('SE_DEFAULT_ERROR'), [
                'class' => [
                    'intec-ui' => [
                        '',
                        'control-alert',
                        'scheme-red',
                        'm-b-20'
                    ]
                ]
            ]) ?>
        <?php } else { ?>
            <div class="subscribe-edit">
                <div class="subscribe-edit-wrapper">
                    <h2 class="subscribe-edit-header"><?= Loc::getMessage('SE_DEFAULT_TITLE') ?></h2>
                    <?php if (!empty($arResult['ERROR'])) { ?>
                        <?= Html::tag('div', implode('<br />', $arResult['ERROR']), [
                            'class' => [
                                'intec-ui' => [
                                    '',
                                    'control-alert',
                                    'scheme-red',
                                    'm-b-20'
                                ]
                            ]
                        ]) ?>
                    <?php } ?>
                    <?php if (!empty($arResult['MESSAGE'])) { ?>
                        <?= Html::tag('div', implode('<br />', $arResult['MESSAGE']), [
                            'class' => [
                                'intec-ui' => [
                                    '',
                                    'control-alert',
                                    'scheme-blue',
                                    'm-b-20'
                                ]
                            ]
                        ]) ?>
                    <?php } ?>
                    <div class="subscribe-edit-form">
                        <form action="<?= $arResult['FORM_ACTION'] ?>" method="POST">
                            <?= bitrix_sessid_post() ?>
                            <?= Html::hiddenInput('PostAction', $bSubscribed ? 'Update' : 'Add') ?>
                            <?= Html::hiddenInput('ID', $arSubscription['ID']) ?>
                            <?= Html::hiddenInput('RUB_ID[]', 0) ?>
                            <? if ($bSubscribed && !$bConfirmed) { ?>
                                <div class="subscribe-edit-confirm">
                                    <div class="subscribe-edit-confirm-title">
                                        <?= Loc::getMessage('SE_DEFAULT_CONFIRM_TITLE') ?>
                                    </div>
                                    <div class="subscribe-edit-confirm-description">
                                        <?= Loc::getMessage('SE_DEFAULT_CONFIRM_DESCRIPTION')?>
                                    </div>
                                    <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                                        <div class="intec-grid-item-auto">
                                            <?= Html::textInput('CONFIRM_CODE', null, [
                                                'class' => 'subscribe-edit-confirm-input intec-ui intec-ui-control-input intec-ui-mod-round-3 intec-ui-size-2',
                                                'placeholder' => Loc::getMessage('SE_DEFAULT_CONFIRM_INPUT')
                                            ]) ?>
                                        </div>
                                        <div class="intec-grid-item-auto">
                                            <input class="subscribe-edit-confirm-button intec-button intec-button-cl-common" type="submit" name="confirm" value="<?= Loc::getMessage('SE_DEFAULT_CONFIRM_BUTTON') ?>" />
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                            <div class="subscribe-edit-information">
                                <div class="subscribe-edit-information-wrapper intec-grid intec-grid-nowrap intec-grid-800-wrap intec-grid-a-v-center intec-grid-i-v-7 intec-grid-i-h-10">
                                    <div class="subscribe-edit-information-email intec-grid-item-3 intec-grid-item-800-1">
                                        <div class="subscribe-edit-information-email-title">
                                            <?= Loc::getMessage('SE_DEFAULT_INFORMATION_EMAIL') ?>:
                                        </div>
                                        <?= Html::textInput('EMAIL', $sEmail, [
                                            'class' => 'subscribe-edit-information-email-input intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2',
                                            'placeholder' => Loc::getMessage('SE_DEFAULT_INFORMATION_EMAIL')
                                        ]) ?>
                                    </div>
                                    <div class="subscribe-edit-information-description intec-grid-item intec-grid-item-800-1">
                                        <div class="subscribe-edit-information-description-icon intec-cl-text">!</div>
                                        <div class="subscribe-edit-information-description-text">
                                            <?= Loc::getMessage('SE_DEFAULT_INFORMATION_DESCRIPTION') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="subscribe-edit-settings">
                                <?php if (!empty($arRubrics)) { ?>
                                    <div class="subscribe-edit-settings-rubrics">
                                        <div class="subscribe-edit-settings-title">
                                            <?= Loc::getMessage('SE_DEFAULT_SETTINGS_RUBRICS') ?>:
                                        </div>
                                        <?php foreach ($arRubrics as $arRubric) { ?>
                                            <div class="subscribe-edit-settings-rubric">
                                                <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                                                    <?= Html::checkbox('RUB_ID[]', $arRubric['CHECKED'], [
                                                        'value' => $arRubric['ID']
                                                    ]) ?>
                                                    <span class="intec-ui-part-selector"></span>
                                                    <span class="intec-ui-part-content">
                                                        <?= $arRubric['NAME'] ?>
                                                    </span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="subscribe-edit-settings-format">
                                    <div class="subscribe-edit-settings-title">
                                        <?= Loc::getMessage('SE_DEFAULT_SETTINGS_FORMAT') ?>:
                                    </div>
                                    <div class="subscribe-edit-settings-format-options">
                                        <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current" style="margin-right: 10px;">
                                            <?= Html::radio('FORMAT', !$bFormatHtml, [
                                                'value' => 'text'
                                            ]) ?>
                                            <span class="intec-ui-part-selector"></span>
                                            <span class="intec-ui-part-content">
                                                <?= Loc::getMessage('SE_DEFAULT_SETTINGS_FORMAT_TEXT') ?>
                                            </span>
                                        </label>
                                        <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                                            <?= Html::radio('FORMAT', $bFormatHtml, [
                                                'value' => 'html'
                                            ]) ?>
                                            <span class="intec-ui-part-selector"></span>
                                            <span class="intec-ui-part-content">
                                                <?= Loc::getMessage('SE_DEFAULT_SETTINGS_FORMAT_HTML') ?>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="subscribe-edit-buttons">
                                <div class="intec-grid intec-grid-wrap intec-grid-i-h-10 intec-grid-i-v-5 intec-grid-a-v-center">
                                    <div class="intec-grid-item-auto">
                                        <input type="submit" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-2" value="<?= $bSubscribed ? Loc::getMessage('SE_DEFAULT_BUTTONS_EDIT') : Loc::getMessage('SE_DEFAULT_BUTTONS_ADD') ?>" />
                                    </div>
                                    <?php if ($arResult['CONSENT']['SHOW']) { ?>
                                        <div class="intec-grid-item-auto intec-grid-item-shrink-1">
                                            <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                                                <?= Html::checkbox(null, true, [
                                                    'onchange' => 'this.checked = !this.checked'
                                                ]) ?>
                                                <span class="intec-ui-part-selector"></span>
                                                <span class="intec-ui-part-content">
                                                    <?= Loc::getMessage('SE_DEFAULT_CONSENT', [
                                                        '#URL#' => $arResult['CONSENT']['URL']
                                                    ]) ?>
                                                </span>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>