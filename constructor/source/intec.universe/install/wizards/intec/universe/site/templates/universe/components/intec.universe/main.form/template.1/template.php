<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<div class="widget c-form c-form-template-1" id="<?= $sTemplateId ?>">
    <?= Html::beginTag('div', [
        'class' => 'widget-form-body',
        'data-lazyload-use' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
        'data-original' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['LAZYLOAD']['USE'] ? $arResult['BACKGROUND']['IMAGE']['PATH'] : null,
        'data-theme' => $arResult['THEME'],
        'data-view' => $arResult['VIEW'],
        'data-align-horizontal' => $arResult['VIEW'] === 'vertical' ? $arResult['ALIGN']['HORIZONTAL'] : null,
        'data-stellar-background-ratio' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['BACKGROUND']['IMAGE']['PARALLAX']['USE'] ?
            $arResult['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'] : null,
        'style' => [
            'background-color' => !empty($arResult['BACKGROUND']['COLOR']) ? $arResult['BACKGROUND']['COLOR'] : null,
            'background-image' => $arResult['BACKGROUND']['IMAGE']['USE'] ?
                (!$arResult['LAZYLOAD']['USE'] ? 'url(\''.$arResult['BACKGROUND']['IMAGE']['PATH'].'\')' : null) : null,
            'background-position' => $arResult['BACKGROUND']['IMAGE']['USE'] ?
                $arResult['BACKGROUND']['IMAGE']['HORIZONTAL'].' '.$arResult['BACKGROUND']['IMAGE']['VERTICAL'] : null,
            'background-size' => $arResult['BACKGROUND']['IMAGE']['USE'] ? $arResult['BACKGROUND']['IMAGE']['SIZE'] : null,
            'background-repeat' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['BACKGROUND']['IMAGE']['SIZE'] === 'contain' ?
                $arResult['BACKGROUND']['IMAGE']['REPEAT'] : null,
        ]
    ]) ?>
        <div class="intec-content intec-content-primary intec-content-visible">
            <div class="intec-content-wrapper">
                <div class="widget-form-content intec-grid intec-grid-a-v-center intec-grid-wrap">
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'intec-grid-item' => [
                                '' => $arResult['VIEW'] === 'left' || $arResult['VIEW'] === 'right',
                                '1024-1' => $arResult['VIEW'] === 'left' || $arResult['VIEW'] === 'right',
                                '1' => $arResult['VIEW'] === 'vertical'
                            ]
                        ], true)
                    ]) ?>
                        <div class="widget-form-text">
                            <?php if (!empty($arResult['TITLE'])) { ?>
                                <div class="widget-form-name">
                                    <?= $arResult['TITLE'] ?>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['DESCRIPTION']['SHOW'] && !empty($arResult['DESCRIPTION']['TEXT'])) { ?>
                                <div class="widget-form-description">
                                    <?= $arResult['DESCRIPTION']['TEXT'] ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?= Html::endTag('div') ?>
                    <?php if ($arResult['BUTTON']['SHOW']) {

                        $sForm = JavaScript::toObject([
                            'id' => $arResult['ID'],
                            'template' => $arResult['TEMPLATE'],
                            'parameters' => [
                                'AJAX_OPTION_ADDITIONAL' => $sTemplateId . '_FORM_TEMPLATE_1',
                                'CONSENT_URL' => $arResult['CONSENT']
                            ],
                            'settings' => [
                                'title' => $arResult['NAME']
                            ]
                        ]);

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-form-buttons' => true,
                                'intec-grid-item' => [
                                    'auto' => $arResult['VIEW'] === 'left' || $arResult['VIEW'] === 'right',
                                    '1024-1' => $arResult['VIEW'] === 'left' || $arResult['VIEW'] === 'right',
                                    '1' => $arResult['VIEW'] === 'vertical'
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-form-buttons-wrap">
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'widget-form-button',
                                        'intec-cl-background',
                                        'intec-cl-background-light-hover'
                                    ],
                                    'onclick' => '(function() {
                                        universe.forms.show('.$sForm.');
                                        if (window.yandex && window.yandex.metrika) {
                                           window.yandex.metrika.reachGoal(\'forms.open\');
                                           window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arResult['ID'].'.open').');
                                       }
                                    })()'
                                ]) ?>
                                    <?php if (!empty($arResult['BUTTON']['TEXT'])) { ?>
                                        <?= $arResult['BUTTON']['TEXT'] ?>
                                    <?php } else { ?>
                                        <?= Loc::getMessage('C_FORM_TEMPLATE_1_TEMPLATE_BUTTON_TEXT_DEFAULT') ?>
                                    <?php } ?>
                                <?= Html::endTag('div') ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
</div>
