<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Html;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (empty($arResult['WEB_FORM']))
    return;

$arVisual = $arResult['VISUAL'];
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-widget',
        'c-widget-form-5'
    ]
]) ?>
    <?php if (!$arVisual['WIDE']) { ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
    <?php } ?>
        <div class="widget-form" data-borders="<?= $arVisual['BORDERS'] ?>">
            <div class="intec-content widget-form-wrapper">
                <div class="intec-content-wrapper widget-form-wrapper-2">
                    <div class="widget-form-title-wrap intec-grid intec-grid-a-v-center intec-grid-a-h-center">
                        <div class="widget-form-image" style="background-image: url('<?= $this->GetFolder().'/images/question_mark.png' ?>')"></div>
                        <div class="widget-form-title intec-cl-border">
                            <div class="widget-form-title-wrapper">
                                <?= $arResult['FORM']['TITLE'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="widget-form-description-wrap intec-grid intec-grid-a-v-center intec-grid-a-h-center">
                        <div class="widget-form-description">
                            <?= $arResult['FORM']['DESCRIPTION'] ?>
                        </div>
                    </div>
                    <div class="widget-form-button-wrap intec-grid intec-grid-a-v-center intec-grid-a-h-center">
                        <div class="widget-form-button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-2" onclick="(function() {
                            universe.forms.show(<?= JavaScript::toObject([
                                'id' => $arResult['WEB_FORM']['ID'],
                                'template' => $arParams['FORM_TEMPLATE'],
                                'parameters' => [
                                    'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM',
                                    'CONSENT_URL' => $arParams['CONSENT_URL']
                                ],
                                'settings' => [
                                    'title' => $arResult['FORM']['POPUP_TITLE']
                                ]
                            ]) ?>);

                            if (window.yandex && window.yandex.metrika) {
                                window.yandex.metrika.reachGoal('forms.open');
                                window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['WEB_FORM']['ID'].'.open')?>);
                            }
                        })()">
                            <?= $arResult['FORM']['BUTTON_TEXT'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php if (!$arVisual['WIDE']) { ?>
        </div>
    </div>
    <?php } ?>
<?= Html::endTag('div') ?>
