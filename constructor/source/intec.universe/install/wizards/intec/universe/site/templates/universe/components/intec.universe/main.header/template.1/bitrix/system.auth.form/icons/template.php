<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

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

?>
<div class="widget-authorization-icons" id="<?= $sTemplateId ?>">
    <?php $oFrame->begin() ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-authorization-items',
                'intec-grid' => [
                    '',
                    'nowrap',
                    'i-h-15',
                    'a-v-center'
                ]
            ]
        ]) ?>
            <?php if ($sFormType == 'login') { ?>
                <div class="widget-authorization-item-wrap intec-grid-item-auto">
                    <div class="widget-authorization-item intec-cl-text-hover" data-action="login">
                        <i class="glyph-icon-login_2"></i>
                    </div>
                </div>
            <?php } else { ?>
                <div class="widget-authorization-item-wrap intec-grid-item-auto">
                    <a href="<?= $arResult['PROFILE_URL'] ?>" class="widget-authorization-item intec-cl-text-hover">
                        <i class="glyph-icon-user_2"></i>
                    </a>
                </div>
                <div class="widget-authorization-item-wrap intec-grid-item-auto">
                    <a href="<?= $arResult['LOGOUT_URL'] ?>" class="widget-authorization-item intec-cl-text-hover">
                        <i class="glyph-icon-logout_2"></i>
                    </a>
                </div>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php $oFrame->beginStub() ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-authorization-items',
                'intec-grid' => [
                    '',
                    'nowrap',
                    'i-h-5',
                    'a-v-center'
                ]
            ]
        ]) ?>
            <div class="widget-authorization-item-wrap intec-grid-item-auto">
                <div class="widget-authorization-item intec-cl-text-hover" data-action="login">
                    <i class="glyph-icon-login_2"></i>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?php $oFrame->end() ?>
    <?php if (!defined('EDITOR')) { ?>
        <div class="widget-authorization-modal" data-role="modal">
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
        <script type="text/javascript">
            (function ($, api) {
                (function ($, api) {
                    var handler;

                    handler = function () {
                        var root = $(<?= JavaScript::toObject('#' . $sTemplateId) ?>);
                        var buttons;
                        var modal;

                        modal = $('[data-role="modal"]', root);
                        modal.open = function () {
                            var window;
                            var data;

                            data = <?= JavaScript::toObject([
                                'id' => $sTemplateId . 'modal',
                                'title' => Loc::getMessage('W_HEADER_S_A_F_AUTH_FORM_TITLE')
                            ]) ?>;

                            window = new BX.PopupWindow(data.id, null, {
                                'content': modal.clone().get(0),
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

                            window.show();
                        };

                        buttons = {};
                        buttons.login = $('[data-action="login"]', root);
                        buttons.login.on('click', modal.open);
                    };

                    $(document).on('ready', handler);

                    BX.addCustomEvent("onFrameDataReceived" , handler);
                })(jQuery, intec);
            })(jQuery, intec);
        </script>
    <?php } ?>
</div>