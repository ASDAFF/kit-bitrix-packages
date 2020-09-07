<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-widget',
        'c-widget-contact-1'
    ],
    'data' => [
        'wide' => $arResult['WIDE'] ? 'true' : 'false',
        'block-show' => $arResult['BLOCK']['SHOW'] ? 'true' : 'false',
        'block-view' => $arResult['BLOCK']['VIEW']
    ]
]) ?>
    <div class="widget-content intec-content-wrap">
        <?php if (!$arResult['WIDE']) { ?>
            <div class="widget-content-wrapper intec-content">
                <div class="widget-content-wrapper-2 intec-content-wrapper">
        <?php } ?>
        <?php if ($arResult['BLOCK']['SHOW']) { ?>
            <div class="widget-block">
                <div class="widget-block-wrapper">
                    <div class="widget-block-wrapper-2">
                        <?php if (!empty($arResult['BLOCK']['TITLE'])) { ?>
                            <div class="widget-block-title">
                                <?= $arResult['BLOCK']['TITLE'] ?>
                            </div>
                        <?php } ?>
                        <div class="widget-block-items">
                            <?php if ($arResult['ADDRESS']['SHOW']) { ?>
                                <div class="widget-block-item widget-block-item-address">
                                    <div class="widget-block-item-title">
                                        <?= Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_ADDRESS') ?>:
                                    </div>
                                    <?php if (!empty($arResult['ADDRESS']['CITY'])) { ?>
                                        <div class="widget-block-item-value">
                                            <?= $arResult['ADDRESS']['CITY'] ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arResult['ADDRESS']['STREET'])) { ?>
                                        <div class="widget-block-item-value">
                                            <?= $arResult['ADDRESS']['STREET'] ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['PHONE']['SHOW']) { ?>
                                <div class="widget-block-item widget-block-item-phone">
                                    <div class="widget-block-item-title">
                                        <?= Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PHONE') ?>:
                                    </div>
                                    <?php foreach ($arResult['PHONE']['VALUES'] as $arPhone) { ?>
                                        <div class="widget-block-item-value">
                                            <a href="tel:<?= $arPhone['VALUE'] ?>" class="intec-cl-text-hover">
                                                <?= $arPhone['DISPLAY'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['FORM']['SHOW']) { ?>
                            <div class="widget-block-item widget-block-item-form">
                                <div class="intec-button intec-button-md intec-button-cl-common" onclick="(function() {
                                    universe.forms.show(<?= JavaScript::toObject([
                                        'id' => $arResult['FORM']['ID'],
                                        'template' => $arResult['FORM']['TEMPLATE'],
                                        'parameters' => [
                                            'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-form',
                                            'CONSENT_URL' => $arParams['CONSENT_URL']
                                        ],
                                        'settings' => [
                                            'title' => $arResult['FORM']['TITLE']
                                        ]
                                    ]) ?>);

                                    if (window.yandex && window.yandex.metrika) {
                                        window.yandex.metrika.reachGoal('forms.open');
                                        window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['FORMS'][0]['ID'].'.open') ?>);
                                    }
                                })() ">
                                    <?= $arResult['FORM']['BUTTON']['TEXT'] ?>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if ($arResult['EMAIL']['SHOW']) { ?>
                                <div class="widget-block-item widget-block-item-email">
                                    <div class="widget-block-item-title">
                                        <?= Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_EMAIL') ?>:
                                    </div>
                                    <?php foreach ($arResult['EMAIL']['VALUES'] as $sEmail) { ?>
                                        <div class="widget-block-item-value">
                                            <a href="mailto:<?= $sEmail ?>" class="intec-cl-text-hover">
                                                <?= $sEmail ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="widget-map">
            <?php $APPLICATION->IncludeComponent(
                $arResult['MAP']['VENDOR'] === 'google' ? 'bitrix:map.google.view' : 'bitrix:map.yandex.view',
                '.default',
                $arResult['MAP'],
                $component,
                ['HIDE_ICONS' => 'Y']
            ) ?>
        </div>
        <?php if (!$arResult['WIDE']) { ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?= Html::endTag('div') ?>