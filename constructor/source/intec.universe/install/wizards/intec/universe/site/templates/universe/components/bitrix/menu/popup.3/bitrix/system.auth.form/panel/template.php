<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sFormType = $arResult['FORM_TYPE'];

$oFrame = $this->createFrame();

$arVisual = $arResult['VISUAL'];
?>
<div class="menu-authorization" id="<?= $sTemplateId ?>">
    <?php $oFrame->begin() ?>
        <?php if ($sFormType == 'login') { ?>
            <div class="menu-authorization-button" data-action="login">
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'menu-authorization-button-wrapper' => true,
                        'intec-grid' => true,
                        'intec-grid-a-v-center' => true,
                        'intec-grid-i-h-4' => true,
                        'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                    ], true)
                ]) ?>
                    <div class="menu-authorization-button-icon-wrap intec-grid-item-auto">
                        <div class="menu-authorization-button-icon glyph-icon-login_2"></div>
                    </div>
                    <div class="menu-authorization-button-text intec-grid-item-auto">
                        <?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
                    </div>
                <?= Html::endTag('div') ?>
            </div>
        <?php } else { ?>
            <?= Html::beginTag('a', [
                'class' => Html::cssClassFromArray([
                    'menu-authorization-button' => true,
                    'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                ], true),
                'href' => $arResult['PROFILE_URL'],
                'rel' => 'nofollow'
            ]) ?>
                <div class="menu-authorization-button-wrapper intec-grid intec-grid-a-v-center intec-grid-i-h-4">
                    <div class="menu-authorization-button-icon-wrap intec-grid-item-auto">
                        <div class="menu-authorization-button-icon glyph-icon-user_2"></div>
                    </div>
                    <div class="menu-authorization-button-text intec-grid-item-auto">
                        <?= $arResult['USER_LOGIN'] ?>
                    </div>
                </div>
            <?= Html::endTag('a') ?>
            <?= Html::beginTag('a', [
                'class' => Html::cssClassFromArray([
                    'menu-authorization-button' => true,
                    'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                ], true),
                'href' => $arResult['LOGOUT_URL'],
                'rel' => 'nofollow'
            ]) ?>
                <div class="menu-authorization-button-wrapper intec-grid intec-grid-a-v-center intec-grid-i-h-4">
                    <div class="menu-authorization-button-icon-wrap intec-grid-item-auto">
                        <div class="menu-authorization-button-icon glyph-icon-logout_2"></div>
                    </div>
                    <div class="menu-authorization-button-text intec-grid-item-auto">
                        <?= Loc::getMessage('W_HEADER_S_A_F_LOGOUT') ?>
                    </div>
                </div>
            <?= Html::endTag('a') ?>
        <?php } ?>
    <?php $oFrame->beginStub() ?>
        <div class="menu-authorization-button" data-action="login">
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'menu-authorization-button-wrapper' => true,
                    'intec-grid' => true,
                    'intec-grid-a-v-center' => true,
                    'intec-grid-i-h-4' => true,
                    'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                ], true)
            ]) ?>
                <div class="menu-authorization-button-icon-wrap intec-grid-item-auto">
                    <div class="menu-authorization-button-icon glyph-icon-login_2"></div>
                </div>
                <div class="menu-authorization-button-text intec-grid-item-auto">
                    <?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
                </div>
            <?= Html::endTag('div') ?>
        </div>
    <?php $oFrame->end() ?>
    <?php if (!defined('EDITOR')) { ?>
        <div class="menu-authorization-modal" data-role="modal">
            <?php $APPLICATION->IncludeComponent(
                'bitrix:system.auth.authorize',
                'popup.1',
                [
                    'AUTH_URL' => $arParams['LOGIN_URL'],
                    'AUTH_FORGOT_PASSWORD_URL' => $arParams['FORGOT_PASSWORD_URL'],
                    'AUTH_REGISTER_URL' => $arParams['REGISTER_URL'],
                    'AJAX_MODE' => 'N'
                ],
                $component
            ) ?>
        </div>
        <?php if (!defined('EDITOR')) { ?>
            <script type="text/javascript">
                (function ($, api) {
                    var handler;

                    handler = function () {
                        var root = $(<?= JavaScript::toObject('#' . $sTemplateId) ?>);
                        var buttons;
                        var modal;
                        var window;
                        var data;

                        data = <?= JavaScript::toObject([
                            'id' => $sTemplateId . '-modal',
                            'title' => Loc::getMessage('W_HEADER_S_A_F_AUTH_FORM_TITLE')
                        ]) ?>;

                        modal = $('[data-role="modal"]', root);
                        modal.open = function () {
                            window.setContent(modal.clone().get(0));
                            window.show();
                        };

                        window = new BX.PopupWindow(data.id, null, {
                            'content': null,
                            'title': data.title,
                            'closeIcon': {
                                'right': '20px',
                                'top': '22px'
                            },
                            'zIndex': 0,
                            'offsetLeft': 0,
                            'offsetTop': 0,
                            'width': 800,
                            'overlay': true,
                            'titleBar': {
                                'content': BX.create('span', {
                                    'html': data.title,
                                    'props': {
                                        'className': 'access-title-bar'
                                    }
                                })
                            }
                        });

                        buttons = {};
                        buttons.login = $('[data-action="login"]', root);
                        buttons.login.on('click', modal.open);
                    };

                    $(document).on('ready', handler);
                    BX.addCustomEvent('onFrameDataReceived' , handler);
                })(jQuery, intec);
            </script>
        <?php } ?>
    <?php } ?>
</div>