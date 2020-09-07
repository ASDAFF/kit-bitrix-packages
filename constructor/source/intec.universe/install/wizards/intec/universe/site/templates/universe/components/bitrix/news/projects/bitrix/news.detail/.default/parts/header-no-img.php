<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 * @var string $sTemplateId
 * @var array $arForms
 */
?>
<div class="project-header header-no-img">
    <div class="intec-content">
        <div class="intec-content-wrapper clearfix">
            <div class="project-description">
                <div class="project-text-wrapper">
                    <?php if(!empty($arResult['PREVIEW_TEXT'])) { ?>
                        <div class="project-description-text intec-ui-markup-text">
                            <?= $arResult['PREVIEW_TEXT'] ?>
                        </div>
                    <?php } ?>
                    <div class="project-description-items">
                        <div class="project-description-items-wrapper">
                            <?php if (Type::isArray($arDescriptionProperties)) { ?>
                                <?php $iCount = 0 ?>
                                <?php foreach ($arDescriptionProperties as $sPropertyCode) { ?>
                                    <?php
                                    if ($iCount > 4)
                                        break;

                                    $arProperty = ArrayHelper::getValue($arResult, ['PROPERTIES', $sPropertyCode]);

                                    if (empty($arProperty))
                                        continue;

                                    $sName = ArrayHelper::getValue($arProperty, 'NAME');
                                    $sValue = ArrayHelper::getValue($arProperty, 'VALUE');

                                    if (empty($sValue))
                                        continue;

                                    $sName = Html::encode($sName);

                                    if (RegExp::isMatchBy('/^http(s)?\\:\\/\\//', $sValue)) {
                                        $sValue = Html::a($sValue, $sValue, [
                                            'target' => '_blank'
                                        ]);
                                    } else {
                                        $sValue = Html::encode($sValue);
                                    }
                                    ?>
                                    <div class="project-description-item">
                                        <div class="project-description-item-title">
                                            <?= $sName ?>
                                        </div>
                                        <div class="project-description-item-text">
                                            <?= $sValue ?>
                                        </div>
                                    </div>
                                    <?php $iCount++ ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="project-description-buttons">
                    <div class="project-description-buttons-wrapper">
                        <?php if (!empty($arForms['ORDER'])) { ?>
                            <div class="project-description-button-wrap">
                                <div class="<?= Html::cssClassFromArray([
                                    'intec-ui' => [
                                        '',
                                        'control-button',
                                        'mod-block',
                                        'mod-round-3',
                                        'scheme-current',
                                        'size-2'
                                    ]
                                ]) ?>" onclick="(function() {
                                        universe.forms.show(<?= JavaScript::toObject($arForms['ORDER']) ?>);

                                        if (window.yandex && window.yandex.metrika) {
                                            window.yandex.metrika.reachGoal('forms.open');
                                            window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arForms['ORDER']['id'].'.open') ?>);
                                        }
                                    })()">
                                    <?= Loc::getMessage('N_PROJECTS_N_D_DEFAULT_BUTTON_ORDER') ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arForms['ASK'])) { ?>
                            <div class="project-description-button-wrap">
                                <div class="<?= Html::cssClassFromArray([
                                    'intec-ui' => [
                                        '',
                                        'control-button',
                                        'mod-block',
                                        'mod-transparent',
                                        'mod-round-3',
                                        'scheme-gray',
                                        'size-2'
                                    ]
                                ]) ?>" onclick="(function() {
                                        universe.forms.show(<?= JavaScript::toObject($arForms['ASK']) ?>);

                                        if (window.yandex && window.yandex.metrika) {
                                            window.yandex.metrika.reachGoal('forms.open');
                                            window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arForms['ASK']['id'].'.open') ?>);
                                        }
                                    })()">
                                    <?= Loc::getMessage('N_PROJECTS_N_D_DEFAULT_BUTTON_ASK') ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
