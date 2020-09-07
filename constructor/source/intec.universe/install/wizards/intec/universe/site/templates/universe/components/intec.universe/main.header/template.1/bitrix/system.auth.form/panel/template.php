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

?>
<div class="widget-authorization-panel" id="<?= $sTemplateId ?>">
    <?php $oFrame->begin() ?>
        <?php if ($sFormType == 'login') { ?>
            <div class="widget-panel-button" data-action="login">
                <div class="widget-panel-button-wrapper intec-grid intec-grid-a-v-center intec-cl-text-hover">
                    <div class="widget-panel-button-icon intec-grid-item-auto glyph-icon-login_2"></div>
                    <div class="widget-panel-button-text intec-grid-item-auto">
                        <?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <a rel="nofollow" href="<?= $arResult['PROFILE_URL'] ?>" class="widget-panel-button">
                <div class="widget-panel-button-wrapper intec-grid intec-grid-a-v-center intec-cl-text-hover">
                    <div class="widget-panel-button-icon intec-grid-item-auto glyph-icon-user_2"></div>
                    <div class="widget-panel-button-text intec-grid-item-auto">
                        <?= $arResult['USER_LOGIN'] ?>
                    </div>
                </div>
            </a>
            <a rel="nofollow" href="<?= $arResult['LOGOUT_URL'] ?>" class="widget-panel-button">
                <div class="widget-panel-button-wrapper intec-grid intec-grid-a-v-center intec-cl-text-hover">
                    <div class="widget-panel-button-icon intec-grid-item-auto glyph-icon-logout_2"></div>
                    <div class="widget-panel-button-text intec-grid-item-auto">
                        <?= Loc::getMessage('W_HEADER_S_A_F_LOGOUT') ?>
                    </div>
                </div>
            </a>
        <?php } ?>
    <?php $oFrame->beginStub() ?>
        <div class="widget-panel-button" data-action="login">
            <div class="widget-panel-button-wrapper intec-grid intec-grid-a-v-center intec-cl-text-hover">
                <div class="widget-panel-button-icon intec-grid-item-auto glyph-icon-login_2"></div>
                <div class="widget-panel-button-text intec-grid-item-auto">
                    <?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
                </div>
            </div>
        </div>
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