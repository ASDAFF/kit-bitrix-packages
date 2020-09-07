<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Html;


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
 */

$authUrl = $arResult['AUTH_URL'] ? $arResult['AUTH_URL'] : SITE_DIR .'auth/';
if ($arParams['AUTH_URL']) {
    $authUrl = $arParams['AUTH_URL'];
}

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));
?>
<!--noindex-->
<div class="ns-bitrix c-system-auth-authorize c-system-auth-authorize-template-1 main" id="<?= $sTemplateId ?>">
    <div class="">
        <div class="">
            <?
            ShowMessage($arParams["~AUTH_RESULT"]);
            ShowMessage($arResult['ERROR_MESSAGE']);
            ?>
        </div>
        <form name="form_auth"
              method="post"
              target="_top"
              action="<?= $authUrl ?>"
              class="bx_auth_form intec-form">
            <input type="hidden" name="AUTH_FORM" value="Y" />
            <input type="hidden" name="TYPE" value="AUTH" />
            <?php if (strlen($arParams['BACKURL']) > 0 || strlen($arResult['BACKURL']) > 0) { ?>
                <input type="hidden" name="backurl" value="<?=($arParams['BACKURL'] ? $arParams['BACKURL'] : $arResult['BACKURL'])?>" />
            <?php } ?>
            <?php foreach ($arResult['POST'] as $key => $value) { ?>
                <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
            <?php } ?>

            <div class="system-auth-authorize-field">
                <div class="system-auth-authorize-caption">
                    <?= Loc::getMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_LOGIN') ?>
                </div>
                <div class="system-auth-authorize-value">
                    <input class="system-auth-authorize-input login-input"
                           type="text"
                           name="USER_LOGIN"
                           maxlength="255"
                           data-role="input"
                           value="<?= $arResult['LAST_LOGIN'] ?>"/>
                </div>
            </div>
            <div class="system-auth-authorize-field">
                <div class="system-auth-authorize-caption-wrap intec-grid intec-grid-nowrap intec-grid-a-v-center">
                    <div class="system-auth-authorize-caption intec-grid-item">
                        <?= Loc::getMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_PASSWORD') ?>
                    </div>
                    <?php if ($arParams['NOT_SHOW_LINKS'] != 'Y') { ?>
                        <div class="system-auth-authorize-forgot-psw-wrap intec-grid-item-auto">
                            <a href="<?= $arParams['AUTH_FORGOT_PASSWORD_URL'] ? $arParams['AUTH_FORGOT_PASSWORD_URL'] : $arResult['AUTH_FORGOT_PASSWORD_URL'] ?>"
                               rel="nofollow" class=".system-auth-authorize-forgot-psw intec-cl-text">
                                <?= GetMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_FORGOT_PASSWORD_2') ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>

                <div class="system-auth-authorize-value">
                    <input class="system-auth-authorize-input password-input"
                           type="password"
                           name="USER_PASSWORD"
                           data-role="input"
                           maxlength="255"/>
                </div>
            </div>

            <?php if ($arResult['SECURE_AUTH']) { ?>
                <span class="bx-auth-secure" id="bx_auth_secure" title="<?= GetMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_SECURE_NOTE') ?>" style="display:none;">
                            <div class="bx-auth-secure-icon"></div>
                        </span>
                <noscript>
                            <span class="bx-auth-secure" title="<?= GetMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_NONSECURE_NOTE') ?>">
                                <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                            </span>
                </noscript>
                <script type="text/javascript">
                    document.getElementById('bx_auth_secure').style.display = 'inline-block';
                </script>
            <?php } ?>
            <?php if ($arResult['CAPTCHA_CODE']) { ?>
                <input type="hidden" name="captcha_sid" value="<?= $arResult['CAPTCHA_CODE'] ?>" />
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult['CAPTCHA_CODE'] ?>"
                     width="180"
                     height="40"
                     alt="CAPTCHA" />
                <?= GetMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_CAPTCHA_PROMT') ?>:
                <input class="intec-input intec-input-block bx-auth-input"
                       type="text"
                       name="captcha_word"
                       maxlength="50"
                       value=""
                       size="15" />
            <?php } ?>

            <?php if ($arResult['STORE_PASSWORD'] == 'Y') { ?>
                <div class="system-auth-authorize-button-block intec-grid intec-grid-nowrap intec-grid-a-v-center">
                    <div class="intec-grid-item">
                        <input type="submit"
                               name="Login"
                               class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-size-2 intec-ui-scheme-current system-auth-authorize-login-button"
                               value="<?= GetMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_AUTHORIZE') ?>" />
                    </div>
                    <div class="system-auth-authorize-remember intec-grid-item-auto">
                        <label for="USER_REMEMBER_D" class="USER_REMEMBER system-auth-authorize-remember-checkbox">
                            <input type="checkbox" id="USER_REMEMBER_D" name="USER_REMEMBER" value="Y"/>
                            <label for="USER_REMEMBER_D" class="system-auth-authorize-remember-selector"></label>
                            <label for="USER_REMEMBER_D" class="system-auth-authorize-remember-text"><?= GetMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_REMEMBER_ME') ?></a></label>
                        </label>
                    </div>
                </div>
            <?php } ?>

        </form>
    </div>
    <?php if ($arResult['AUTH_SERVICES']) { ?>
        <div class="login-page_socserv_form">
            <div class="login-page_socserv_form_title"><?= GetMessage('SYSTEM_AUTH_AUTHORIZE_TEMPLATE1_SOCSERV_FORM_TITLE') ?></div>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:socserv.auth.form',
                '',
                array(
                    'AUTH_SERVICES' => $arResult['AUTH_SERVICES'],
                    'CURRENT_SERVICE' => $arResult['CURRENT_SERVICE'],
                    'AUTH_URL' => $arResult['AUTH_URL'],
                    'POST' => $arResult['POST'],
                    'SUFFIX' => 'main'
                ),
                $component,
                array()
            ); ?>
        </div>
    <?php } ?>
</div>

<script type="text/javascript">
    <?php if (strlen($arResult['LAST_LOGIN']) > 0) { ?>
    try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
    <?php } else { ?>
    try{document.form_auth.USER_LOGIN.focus();}catch(e){}
    <?php } ?>


    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

        var inputs = $('[data-role="input"]', root);
        var update;

        update = function() {
            var self = $(this);

            if (self.val() != '') {
                self.addClass('completed');
            } else {
                self.removeClass('completed');
            }
        };

        $(document).ready(function () {
            inputs.each(function () {
                update.call(this);
            });
        });

        inputs.on('change', function () {
            update.call(this);
        });
    })(jQuery, intec)
</script>
<!--/noindex-->