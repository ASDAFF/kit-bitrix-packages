<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var array $arData
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<div class="subscribe-edit-inputs">
    <div class="subscribe-edit-input">
        <div class="subscribe-edit-input-text">
            <?= Html::textInput('EMAIL', $arData['EMAIL'], [
                'class' => 'intec-cl-border',
                'placeholder' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_EMAIL_PLACEHOLDER'),
            ]) ?>
        </div>
    </div>
    <div class="subscribe-edit-input">
        <div class="subscribe-edit-input-header">
            <?= Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_FORMAT_HEADER') ?>
        </div>
        <div class="subscribe-edit-input-radiobox">
            <div class="subscribe-edit-input-radiobox-item">
                <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                    <?= Html::radio('FORMAT', $arData['FORMAT'] === 'html', [
                        'value' => 'html'
                    ]) ?>
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">
                        <?= Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_FORMAT_HTML') ?>
                    </span>
                </label>
            </div>
            <div class="subscribe-edit-input-radiobox-item">
                <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                    <?= Html::radio('FORMAT', $arData['FORMAT'] === 'text', [
                        'value' => 'text'
                    ]) ?>
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">
                        <?= Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_FORMAT_TEXT') ?>
                    </span>
                </label>
            </div>
        </div>
    </div>
    <?php if (!empty($arData['RUBRICS']['SELECTED']) && count($arData['RUBRICS']['SELECTED']) > 1) { ?>
        <div class="subscribe-edit-input">
            <div class="subscribe-edit-input-header">
                <?= Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_RUBRIC_HEADER') ?>
            </div>
            <div class="subscribe-edit-input-checkbox-group">
                <?php foreach ($arData['RUBRICS']['SELECTED'] as $arRubric) { ?>
                    <div class="subscribe-edit-input-checkbox-group-item">
                        <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                            <?= Html::checkbox('RUB_ID[]', true, [
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
        </div>
    <?php } else { ?>
        <?php foreach ($arData['RUBRICS']['SELECTED'] as $arRubric) { ?>
            <?= Html::hiddenInput('RUB_ID[]', $arRubric['ID']) ?>
        <?php } ?>
    <?php } ?>
    <?php if (!empty($arData['RUBRICS']['ACTIVE'])) { ?>
        <?php foreach ($arData['RUBRICS']['ACTIVE'] as $arRubric) { ?>
            <?= Html::hiddenInput('RUB_ID[]', $arRubric['ID']) ?>
        <?php } ?>
    <?php } ?>
    <?php if (!empty($arData['RUBRICS']['HIDDEN'])) { ?>
        <?php foreach ($arData['RUBRICS']['HIDDEN'] as $arRubric) { ?>
            <?= Html::hiddenInput('RUB_ID[]', $arRubric['ID']) ?>
        <?php } ?>
    <?php } ?>
    <?php if ($arVisual['AUTHORISATION']['SHOW']) {
        include(__DIR__.'/authorisation.php');
    } ?>
    <div class="subscribe-edit-input">
        <div class="subscribe-edit-input-button">
            <?= Html::submitButton(Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_SUBMIT_SUBSCRIBE'), [
                'class' => [
                    'intec-ui' => [
                        '',
                        'control-button',
                        'mod-round-half',
                        'mod-block',
                        'size-3',
                        'scheme-current'
                    ]
                ]
            ]) ?>
        </div>
    </div>
    <?php if ($arVisual['CONSENT']['SHOW']) { ?>
        <div class="subscribe-edit-input">
            <div class="subscribe-edit-input-consent">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:main.userconsent.request',
                    '.default', [
                        'ID' => $arVisual['CONSENT']['ID'],
                        'IS_CHECKED' => $arVisual['CONSENT']['CHECKED'],
                        'IS_LOADED' => $arVisual['CONSENT']['LOADED']
                    ],
                    $component
                ) ?>
            </div>
        </div>
    <?php } ?>
</div>
