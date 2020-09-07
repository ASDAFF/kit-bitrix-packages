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

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sTitle = $arResult['TEXT']['TITLE'];
$sDescription = $arResult['TEXT']['DESCRIPTION'];
$sButton = $arResult['TEXT']['BUTTON'];
$sForm = $arResult['TEXT']['FORM'];
?>
<div class="widget-web-form-2 intec-cl-background" id="<?= $sTemplateId ?>">
    <div class="intec-content widget-web-form-2-background intec-cl-background">
        <div class="intec-content-wrapper widget-web-form-2-background-wrapper">
            <div class="widget-web-form-2-section widget-web-form-2-title">
                <div class="intec-aligner"></div>
                <div class="widget-web-form-2-image" style="background-image: url('<?= $this->GetFolder().'/images/question_mark.png' ?>')"></div>
                <div class="widget-web-form-2-text-wrap">
                    <div class="widget-web-form-2-text">
                        <?= $sTitle ?>
                    </div>
                </div>
            </div>
            <div class="widget-web-form-2-section widget-web-form-2-description">
                <div class="intec-aligner"></div>
                <div class="widget-web-form-2-text">
                    <?= $sDescription ?>
                </div>
            </div>
            <div class="widget-web-form-2-section widget-web-form-2-button">
                <div class="intec-aligner"></div>
                <div class="intec-button intec-button-transparent intec-button-lg intec-cl-text-hover" onclick="(function() {
                    universe.forms.show(<?= JavaScript::toObject([
                        'id' => $arResult['WEB_FORM']['ID'],
                        'template' => $arParams['WEB_FORM_TEMPLATE'],
                        'parameters' => [
                            'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM',
                            'CONSENT_URL' => $arParams['CONSENT_URL']
                        ],
                        'settings' => [
                            'title' => $sForm
                        ]
                    ]) ?>);

                    if (window.yandex && window.yandex.metrika) {
                        window.yandex.metrika.reachGoal('forms.open');
                        window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['WEB_FORM']['ID'].'.open') ?>);
                    }
                })()">
                    <?= $sButton ?>
                </div>
            </div>
        </div>
    </div>
</div>
