<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arData
 */

?>
<div class="subscribe-edit-inputs">
    <?= Html::hiddenInput('EMAIL', $arData['EMAIL']) ?>
    <?= Html::hiddenInput('FORMAT', $arData['FORMAT']) ?>
    <?php if (!empty($arData['RUBRICS']['ACTIVE'])) { ?>
        <?php foreach ($arData['RUBRICS']['ACTIVE'] as $arRubric) { ?>
            <?= Html::hiddenInput('RUB_ID[]', $arRubric['ID']) ?>
        <?php } ?>
    <?php } ?>
    <div class="subscribe-edit-input">
        <div class="subscribe-edit-input-text">
            <?= Html::textInput('CONFIRM_CODE', null, [
                'class' => 'intec-cl-border',
                'placeholder' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_CONFIRM_PLACEHOLDER')
            ]) ?>
        </div>
    </div>
    <div class="subscribe-edit-input">
        <div class="subscribe-edit-input-button">
            <?= Html::submitButton(Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_SUBMIT_CONFIRM'), [
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
</div>
