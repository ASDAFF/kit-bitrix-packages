<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$buttonId = $this->randString();
?>
<div id="sender-subscribe">
    <?if($arParams["FORM_SUBSCRIBE_TITLE"]):?>
        <div class="sender__sidebar-item-title">
            <span><?=$arParams["FORM_SUBSCRIBE_TITLE"]?></span>
        </div>
    <?endif;?>
    <?
    $frame = $this->createFrame("sender-subscribe", false)->begin();
    ?>
    <? if (isset($arResult['MESSAGE'])): CJSCore::Init(array("popup")); ?>
        <div id="sender-subscribe-response-cont" style="display: none;">
            <div class="bx_subscribe_response_container">
                <div class="kit_order__title">
                    <?=GetMessage('subscr_form_title')?>
                </div>
                <div class="popup-window-message-content">
                    <? if ($arResult['MESSAGE']['TYPE'] == 'ERROR'): ?>
                        <svg class="popup-window-icon_warning_big">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_warning_big"></use>
                        </svg>
                    <? else: ?>
                        <svg class="popup-window-icon-check">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_form"></use>
                        </svg>
                    <? endif; ?>
                    <div>
                        <div class="popup-window-message-title"><?= GetMessage('subscr_form_response_' . $arResult['MESSAGE']['TYPE']) ?>
                        </div>
                        <div style="font-size: 16px;"><?= htmlspecialcharsbx($arResult['MESSAGE']['TEXT']) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            BX.ready(function () {
                var oPopup = BX.PopupWindowManager.create('sender_subscribe_component', window.body, {
                    autoHide: true,
                    offsetTop: 1,
                    offsetLeft: 0,
                    lightShadow: true,
                    closeIcon: true,
                    closeByEsc: true,
                    overlay: {
                        backgroundColor: 'rgba(57,60,67,0.82)', opacity: '80'
                    }
                });
                oPopup.setContent(BX('sender-subscribe-response-cont'));
                oPopup.show();

                popupCentering();

                window.addEventListener("resize", popupCentering);

                function popupCentering() {
                    let popUp = document.getElementById("sender_subscribe_component");
                    let overlay = document.getElementById("popup-window-overlay-sender_subscribe_component");

                    popUp.style.position = "fixed";
                    popUp.style.top = "calc(50% - " + popUp.clientHeight / 2 + "px)";
                    popUp.style.left = "calc(50% - " + popUp.clientWidth / 2 + "px)";
                    overlay.style.position = "fixed";
                    overlay.style.height = "100%";
                    overlay.style.width = "100%";
                }

                function unfixBody() {
//                        if (document.body.hasAttribute("class")) {
//                            document.body.setAttribute("class", "");
//                        }
                }
            });
        </script>
    <? endif; ?>
    <script>
        (function () {
            var btn = BX('bx_subscribe_btn_<?=$buttonId?>');
            var form = BX('bx_subscribe_subform_<?=$buttonId?>');

            if (!btn) {
                return;
            }

            function mailSender() {
                setTimeout(function () {
                    if (!btn) {
                        return;
                    }

                    var btn_span = btn.querySelector("span");
                    var btn_subscribe_width = btn_span.style.width;
                    BX.addClass(btn, "send");
                    btn_span.outterHTML = "<span><i class='fa fa-check'></i> <?=GetMessage("subscr_form_button_sent")?></span>";
                    if (btn_subscribe_width) {
                        btn.querySelector("span").style["min-width"] = btn_subscribe_width + "px";
                    }
                }, 400);
            }

            BX.ready(function () {
                BX.bind(btn, 'click', function () {
                    setTimeout(mailSender, 250);
                    return false;
                });
            });

            BX.bind(form, 'submit', function () {
                btn.disabled = true;
                setTimeout(function () {
                    btn.disabled = false;
                }, 2000);

                return true;
            });
        })();
    </script>

    <form id="bx_subscribe_subform_<?= $buttonId ?>" method="post" action="<?= $arResult["FORM_ACTION"] ?>">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="sender_subscription" value="add">
        <input type="checkbox" name="SENDER_SUBSCRIBE_RUB_ID[]" id="SENDER_SUBSCRIBE_RUB_ID_<?=$arParams['CAMPANY']?>" value="<?=$arParams['CAMPANY']?>" checked>
        <div class="footer-block__follow_input">
            <input class="footer-block__follow_input_email vlog" type="email" name="SENDER_SUBSCRIBE_EMAIL"
                   value="<?= $arResult["EMAIL"] ?>" title="<?= GetMessage("subscr_form_email_title") ?>"
                   placeholder="<?= htmlspecialcharsbx(GetMessage('subscr_form_email_title')) ?>">
            <button class="footer-block__follow_input_submit" id="bx_subscribe_btn_<?= $buttonId ?>"
                    title="<?= GetMessage("subscr_form_button") ?>">
                <svg class="footer-block__follow_input_icon" width="25" height="25">
                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_mail"></use>
                </svg>
            </button>
        </div>

        <? if ($arParams['USER_CONSENT'] == 'Y'): ?>
            <div class="bx_subscribe_checkbox_container bx-sender-subscribe-agreement">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.userconsent.request",
                    "",
                    array(
                        "ID" => $arParams["USER_CONSENT_ID"],
                        "IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
                        "AUTO_SAVE" => "Y",
                        "IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
                        "ORIGIN_ID" => "sender/sub",
                        "ORIGINATOR_ID" => "",
                        "REPLACE" => array(
                            "button_caption" => GetMessage("subscr_form_button"),
                            "fields" => array(GetMessage("subscr_form_email_title"))
                        ),
                    )
                ); ?>
            </div>
        <? endif; ?>
    </form>
    <?
    $frame->beginStub();
    ?>
    <? if (isset($arResult['MESSAGE'])): CJSCore::Init(array("popup")); ?>
        <div id="sender-subscribe-response-cont" style="display: none;">
            <div class="bx_subscribe_response_container">
                <table>
                    <tr>
                        <td style="padding-right: 40px; padding-bottom: 0px;"><img
                                    src="<?= ($this->GetFolder() . '/images/' . ($arResult['MESSAGE']['TYPE'] == 'ERROR' ? 'icon-alert.png' : 'icon-ok.png')) ?>"
                                    alt=""></td>
                        <td>
                            <div style="font-size: 22px;"><?= GetMessage('subscr_form_response_' . $arResult['MESSAGE']['TYPE']) ?></div>
                            <div style="font-size: 16px;"><?= htmlspecialcharsbx($arResult['MESSAGE']['TEXT']) ?></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <script>
            BX.ready(function () {
                var oPopup = BX.PopupWindowManager.create('sender_subscribe_component', window.body, {
                    autoHide: true,
                    offsetTop: 0,
                    offsetLeft: 0,
                    lightShadow: true,
                    closeIcon: true,
                    closeByEsc: true,
                    overlay: {
                        backgroundColor: 'rgba(57,60,67,0.82)', opacity: '80'
                    }
                });
                oPopup.setContent(BX('sender-subscribe-response-cont'));
                oPopup.show();
            });
        </script>
    <? endif; ?>

    <script>
        (function () {
            var btn = BX('bx_subscribe_btn_<?=$buttonId?>');
            var form = BX('bx_subscribe_subform_<?=$buttonId?>');

            if (!btn) {
                return;
            }

            function mailSender() {
                setTimeout(function () {
                    if (!btn) {
                        return;
                    }

                    var btn_span = btn.querySelector("span");
                    var btn_subscribe_width = btn_span.style.width;
                    BX.addClass(btn, "send");
                    btn_span.outterHTML = "<span><i class='fa fa-check'></i> <?=GetMessage("subscr_form_button_sent")?></span>";
                    if (btn_subscribe_width) {
                        btn.querySelector("span").style["min-width"] = btn_subscribe_width + "px";
                    }
                }, 400);
            }

            BX.ready(function () {
                BX.bind(btn, 'click', function () {
                    setTimeout(mailSender, 250);
                    return false;
                });
            });

            BX.bind(form, 'submit', function () {
                btn.disabled = true;
                setTimeout(function () {
                    btn.disabled = false;
                }, 2000);

                return true;
            });
        })();
    </script>

    <form id="bx_subscribe_subform_<?= $buttonId ?>" method="post" action="<?= $arResult["FORM_ACTION"] ?>">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="sender_subscription" value="add">

        <div class="footer-block__follow_input">
            <input class="footer-block__follow_input_email" type="email" name="SENDER_SUBSCRIBE_EMAIL"
                   value="<?= $arResult["EMAIL"] ?>" title="<?= GetMessage("subscr_form_email_title") ?>"
                   placeholder="<?= htmlspecialcharsbx(GetMessage('subscr_form_email_title')) ?>">
            <button class="footer-block__follow_input_submit" id="bx_subscribe_btn_<?= $buttonId ?>"
                    title="<?= GetMessage("subscr_form_button") ?>">
                <svg class="footer-block__follow_input_icon" width="25" height="25">
                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_mail"></use>
                </svg>
            </button>
        </div>

        <? if ($arParams['USER_CONSENT_USE'] == 'Y'): ?>
            <div class="bx_subscribe_checkbox_container bx-sender-subscribe-agreement">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.userconsent.request",
                    "",
                    array(
                        "ID" => $arParams["USER_CONSENT_ID"],
                        "IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
                        "AUTO_SAVE" => "Y",
                        "IS_LOADED" => "N",
                        "ORIGIN_ID" => "sender/sub",
                        "ORIGINATOR_ID" => "",
                        "REPLACE" => array(
                            "button_caption" => GetMessage("subscr_form_button"),
                            "fields" => array(GetMessage("subscr_form_email_title"))
                        ),
                    )
                ); ?>
            </div>
        <? endif; ?>
    </form>
    <?
    $frame->end();
    ?>
</div>
