<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use Bitrix\Main\Localization\Loc;

CJSCore::Init(array("popup"));

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if (!empty($arResult["errorMessage"])) {
	if (!is_array($arResult["errorMessage"])) {
		ShowError($arResult["errorMessage"]);
	} else {
		foreach ($arResult["errorMessage"] as $errorMessage) {
			ShowError($errorMessage);
		}
	}
} else  {

    $wrapperId = str_shuffle(substr($arResult['SIGNED_PARAMS'],0,10));
    ?>
    <div class="ns-bitrix c-sale-account-pay c-sale-account-pay-default" id="<?= $sTemplateId ?>">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="sale-account-pay-title">
                    <?=Loc::GetMessage('SAP_TITLE');?>
                </div>
                <div class="bx-sap sale-account-pay-wrapper" id="bx-sap<?=$wrapperId?>">
                    <?php if ($arParams['SELL_VALUES_FROM_VAR'] != 'Y') {?>
                        <div class="sale-account-pay-block intec-grid intec-grid-wrap intec-grid-i-v-15">
                            <?php if ($arParams['SELL_SHOW_FIXED_VALUES'] === 'Y') { ?>
                            <div class="sale-account-pay-fixed-block intec-grid-item-auto intec-grid-item-600-1">
                                <div class="sale-account-pay-fixed-title"><?= Loc::getMessage("SAP_FIXED_PAYMENT") ?></div>
                                <div class="sale-account-pay-fixed-container">
                                    <div class="sale-account-pay-fixed-list intec-grid intec-grid-a-v-center intec-grid-wrap intec-grid-i-v-5">
                                        <?php foreach ($arParams["SELL_TOTAL"] as $valueChanging) { ?>
                                            <div class="intec-grid-item-auto">
                                                <div class="sale-account-pay-fixed-item intec-cl-background-hover">
                                                    <?=CUtil::JSEscape(htmlspecialcharsbx($valueChanging))?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="sale-account-pay-fixed-item-or"><?=Loc::GetMessage('SAP_OR')?></div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="sale-account-pay-static-block intec-grid-item-auto intec-grid-item-600-1">
                                <div class="sale-account-pay-static-title">
                                    <?=Loc::getMessage("SAP_SUM")?>
                                </div>
                                <div class="sale-account-pay-static-input-wrap intec-grid intec-grid-a-v-center" style="margin-bottom: 0;">
                                    <?php
                                    $inputElement = "
                                        
                                        <input type='text' placeholder='0.00' 
                                        class='sale-account-pay-static-input intec-grid-item' value='0.00' "
                                        ."name=".CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"]))." "
                                        .($arParams['SELL_USER_INPUT'] === 'N' ? "disabled" :"").
                                        ">
                                       ";
                                    $tempCurrencyRow = trim(str_replace("#", "", $arResult['FORMATED_CURRENCY']));
                                    $labelWrapper = "<span class='sale-account-pay-static-currency intec-grid-item-auto'>".$tempCurrencyRow."</span>";
                                    $currencyRow = str_replace($tempCurrencyRow, $labelWrapper, $arResult['FORMATED_CURRENCY']);
                                    $currencyRow = str_replace("#", $inputElement, $currencyRow);
                                    echo $currencyRow;
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } else {
                        if ($arParams['SELL_SHOW_RESULT_SUM'] === 'Y') {?>
                            <div class="sale-account-pay-variable-block">
                                <div class="sale-account-pay-variable-title">
                                    <?=Loc::getMessage("SAP_SUM")?>
                                </div>
                                <div class="sale-account-pay-variable-value"><?=SaleFormatCurrency($arResult["SELL_VAR_PRICE_VALUE"], $arParams['SELL_CURRENCY'])?></div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <input type="hidden" name="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"]))?>"
                                class="form-control input-lg sale-acountpay-input"
                                value="<?=CUtil::JSEscape(htmlspecialcharsbx($arResult["SELL_VAR_PRICE_VALUE"]))?>">
                        </div>
                    <?php } ?>
                    <div class="sale-account-pay-systems-wrap">
                        <div class="sale-account-pay-systems-title">
                            <?=Loc::getMessage("SAP_TYPE_PAYMENT_TITLE")?>
                        </div>
                        <div class="sale-account-pay-systems-list-wrap">
                            <div class="sale-account-pay-systems-list intec-grid intec-grid-wrap intec-grid-i-v-15">
                                <?php foreach ($arResult['PAYSYSTEMS_LIST'] as $key => $paySystem) { ?>
                                    <div class="sale-account-pay-system-item intec-grid-item-2 intec-grid-item-768-1 <?= ($key == 0) ? 'bx-selected' :""?>">
                                        <div class="sale-acountpay-pp-company-graf-container">
                                            <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current intec-ui-size-1">
                                                <input type="radio"
                                                       checked="checked"
                                                       name="PAY_SYSTEM_ID"
                                                       value="<?=$paySystem['ID']?>"
                                                       class="sale-account-pay-system-item-checkbox"
                                                       <?= ($key == 0) ? "checked='checked'" :""?>
                                                >
                                                <span class="intec-ui-part-selector"></span>
                                                <span class="intec-ui-part-content sale-account-pay-system-name"><?=CUtil::JSEscape(htmlspecialcharsbx($paySystem['NAME']))?></span>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="sale-account-pay-button-wrap">
                        <a class="intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-4 intec-ui-mod-round-5 sale-account-pay-button"><?=Loc::getMessage("SAP_BUTTON")?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
    $javascriptParams = array(
        "alertMessages" => array("wrongInput" => Loc::getMessage('SAP_ERROR_INPUT')),
        "url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
        "templateFolder" => CUtil::JSEscape($templateFolder),
        "templateName" => $this->__component->GetTemplateName(),
        "signedParams" => $arResult['SIGNED_PARAMS'],
        "wrapperId" => $wrapperId
    );
    $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
    ?>
    <script>
        var sc = new BX.saleAccountPay(<?=$javascriptParams?>);
    </script>
<?php } ?>

