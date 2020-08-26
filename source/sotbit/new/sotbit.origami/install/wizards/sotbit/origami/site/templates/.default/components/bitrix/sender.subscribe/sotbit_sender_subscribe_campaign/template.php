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

use Bitrix\Main\Localization\Loc;

$buttonId = $this->randString();
?>
<div id="sender-subscribe_<?= $buttonId ?>" class="sidebar-item subscribe">
    <?
    $frame = $this->createFrame("sender-subscribe_" . $buttonId, false)->begin();
    ?>
    <? if (isset($arResult['MESSAGE'])): CJSCore::Init(array("popup")); ?>
        <div id="sender-subscribe-response-cont" style="display: none;">
            <div class="bx_subscribe_response_container">
                <div class="sotbit_order__title">
                    <?= GetMessage('subscr_form_title') ?>
                </div>
                <div class="popup-window-message-content">
                    <? if ($arResult['MESSAGE']['TYPE'] == 'ERROR'): ?>
                        <svg class="popup-window-icon_warning_big">
                            <use
                                xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_warning_big"></use>
                        </svg>
                    <? else: ?>
                        <svg class="popup-window-icon-check">
                            <use
                                xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_form"></use>
                        </svg>
                    <? endif; ?>
                    <div>
                        <div class="popup-window-message-title">
                            <?= GetMessage('subscr_form_response_' . $arResult['MESSAGE']['TYPE']) ?>
                        </div>
                        <div class="popup-window-message-text"
                             style="font-size: 16px;"><?= htmlspecialcharsbx($arResult['MESSAGE']['TEXT']) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            createSubscribePopup();
        </script>
    <? endif; ?>
    <form id="bx_subscribe_subform_<?= $buttonId ?>" method="post" action="<?= $arResult["FORM_ACTION"] ?>"
          class="subscribe__form">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="sender_subscription" value="add">
        <div class="subscribe__mobile-wrapper">
            <? if ($arParams["FORM_SUBSCRIBE_TITLE"]): ?>
                <div class="sender__sidebar-item-title">
                    <span><?= $arParams["FORM_SUBSCRIBE_TITLE"] ?></span>
                </div>
            <? endif; ?>
            <div class="subscribe__input-wrapper">
                <input class="subscribe__input" type="email" name="SENDER_SUBSCRIBE_EMAIL"
                       value="<?= $arResult["EMAIL"] ?>" title="<?= GetMessage("subscr_form_email_title") ?>"
                       placeholder="<?= htmlspecialcharsbx(GetMessage('subscr_form_email_title')) ?>">
                <button class="subscribe__button"
                        id="bx_subscribe_btn_<?= $buttonId ?>"
                        title="<?= GetMessage("subscr_form_button") ?>">
                    <svg class="subscribe__button-icon" width="25" height="25">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mail"></use>
                    </svg>
                </button>
            </div>
        </div>
        <div class="subscribe__types-wrapper"
            <? if (count($arParams["MAILING_LISTS"]) < 2): ?> style="display: none;"<? endif; ?>>
            <? if (count($arResult["RUBRICS"]) > 0): ?>
                <div class="subscribe__types-title" onclick="showSubscribtions(this)">
                    <span><?= Loc::GetMessage("subscr_form_title_desc") ?></span><span
                        class="blog-subscribe__handle"></span>
                </div>
                <div class="subscribe__types-size-wrapper">
                    <div class="subscribe__types-size-content">
                        <? foreach ($arResult["RUBRICS"] as $itemID => $itemValue): ?>
                            <? if (in_array($itemValue["ID"], $arParams["MAILING_LISTS"])): ?>
                                <div class="main_checkbox">
                                    <input type="checkbox" name="SENDER_SUBSCRIBE_RUB_ID[]"
                                           id="SENDER_SUBSCRIBE_RUB_ID_<?= $itemValue["ID"] ?>"
                                           value="<?= $itemValue["ID"] ?>"<? if ($itemValue["CHECKED"]) echo " checked" ?>>
                                    <label for="SENDER_SUBSCRIBE_RUB_ID_<?= $itemValue["ID"] ?>">
                                        <span></span>
                                        <span><?= htmlspecialcharsbx($itemValue["NAME"]) ?></span>
                                    </label>
                                </div>
                            <? endif; ?>
                        <? endforeach; ?>
                    </div>
                </div>
            <? endif; ?>
        </div>
        <? if ($arParams['USER_CONSENT'] == 'Y'): ?>
            <div class="main_checkbox bx-sender-subscribe-agreement">
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
    <script>
        initSubscribe("<?=GetMessage("subscr_form_button_sent")?>", "<?=$buttonId?>");
    </script>
    <?
    $frame->end();
    ?>
</div>
