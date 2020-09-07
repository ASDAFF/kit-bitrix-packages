<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$oFrame = $this->createFrame();

?>
<div class="widget-authorization-panel" id="<?= $sTemplateId ?>">
    <?php $oFrame->begin() ?>
        <?php if ($arResult['FORM_TYPE'] === 'login') { ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'widget-authorization-buttons',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center',
                        'i-h-8'
                    ]
                ]
            ]) ?>
                <div class="intec-grid-item-auto">
                    <div class="widget-authorization-button">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'intec-grid' => [
                                    '',
                                    'nowrap',
                                    'a-v-center',
                                    'i-h-4'
                                ]
                            ],
                            'data-action' => 'login'
                        ]) ?>
                            <div class="intec-grid-item-auto">
                                <div class="widget-authorization-button-icon">
                                    <?php include(__DIR__.'/svg/icon.login.svg') ?>
                                </div>
                            </div>
                            <div class="intec-grid-item-auto">
                                <div class="widget-authorization-button-content">
                                    <?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } else { ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'widget-authorization-buttons',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center',
                        'i-h-8'
                    ]
                ]
            ]) ?>
                <div class="intec-grid-item-auto">
                    <div class="widget-authorization-button">
                        <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-4">
                            <div class="intec-grid-item-auto">
                                <?= Html::tag('a', $arResult['USER_LOGIN'], [
                                    'class' => [
                                        'widget-authorization-button-content',
                                        'widget-authorization-button-content-decorated',
                                        'intec-cl-text'
                                    ],
                                    'href' => $arResult['PROFILE_URL']
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intec-grid-item-auto">
                    <div class="widget-authorization-button">
                        <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-4">
                            <div class="intec-grid-item-auto">
                                <?= Html::tag('a', Loc::getMessage('W_HEADER_S_A_F_LOGOUT'), [
                                    'class' => [
                                        'widget-authorization-button-content'
                                    ],
                                    'href' => $arResult['LOGOUT_URL']
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?php $oFrame->beginStub() ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-authorization-buttons',
                'intec-grid' => [
                    '',
                    'nowrap',
                    'a-v-center',
                    'i-h-8'
                ]
            ]
        ]) ?>
            <div class="intec-grid-item-auto">
                <div class="widget-authorization-button">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'intec-grid' => [
                                '',
                                'nowrap',
                                'a-v-center',
                                'i-h-4'
                            ]
                        ],
                        'data-action' => 'login'
                    ]) ?>
                        <div class="intec-grid-item-auto">
                            <div class="widget-authorization-button-icon">
                                <?php include(__DIR__.'/svg/icon.login.svg') ?>
                            </div>
                        </div>
                        <div class="intec-grid-item-auto">
                            <div class="widget-authorization-button-content">
                                <?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?php $oFrame->end() ?>
    <?php if (!defined('EDITOR')) { ?>
        <div class="widget-authorization-modal" data-role="modal">
            <?php $APPLICATION->IncludeComponent(
                'bitrix:system.auth.authorize',
                'popup.1', [
                    'AUTH_URL' => $arParams['LOGIN_URL'],
                    'AUTH_FORGOT_PASSWORD_URL' => $arParams['FORGOT_PASSWORD_URL'],
                    'AUTH_REGISTER_URL' => $arParams['REGISTER_URL'],
                    'AJAX_MODE' => 'N'
                ],
                $component
            ) ?>
        </div>
        <?php include(__DIR__.'/parts/script.php') ?>
    <?php } ?>
</div>