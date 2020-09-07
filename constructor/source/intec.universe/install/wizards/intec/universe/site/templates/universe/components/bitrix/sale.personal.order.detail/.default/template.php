<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

if (!Loader::includeModule('intec.core'))
    return;

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arPaymentData = [];

if ($arParams['GUEST_MODE'] !== 'Y') {
	Asset::getInstance()->addJs('/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js');
	Asset::getInstance()->addCss('/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css');
}

CJSCore::Init(['clipboard', 'fx']);

?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-sale-personal-order-detail c-sale-personal-order-detail-default">
    <div class="sale-personal-order-detail-wrapper intec-content">
        <div class="sale-personal-order-detail-wrapper-2 intec-content-wrapper">
            <?php if (!empty($arResult['ERRORS']['FATAL'])) { ?>
                <div class="sale-personal-order-detail-errors intec-ui intec-ui-control-alert intec-ui-scheme-red">
                    <?php foreach ($arResult['ERRORS']['FATAL'] as $sError) echo Html::tag('div', $sError) ?>
                </div>
                <?php if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) { ?>
                    <div class="sale-personal-order-detail-authorize intec-ui-m-t-20">
                        <?php $APPLICATION->AuthForm('', false, false, 'N', false) ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php if ($arParams['GUEST_MODE'] !== 'Y' || $arResult['CAN_CANCEL'] === 'Y') { ?>
                    <div class="intec-ui-m-b-20">
                        <div class="intec-grid intec-grid-wrap intec-grid-a-h-start intec-grid-a-v-start intec-grid-i-h-10 intec-grid-i-v-5">
                            <div class="intec-grid-item intec-grid-item-450-1">
                                <?php if ($arParams['GUEST_MODE'] !== 'Y') { ?>
                                    <a href="<?= Html::encode($arResult['URL_TO_LIST']) ?>" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent intec-ui-size-2">
                                        <span class="intec-ui-part-icon">
                                            <i class="far fa-angle-left"></i>
                                        </span>
                                        <span class="intec-ui-part-content">
                                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BUTTONS_RETURN') ?>
                                        </span>
                                    </a>
                                <?php } ?>
                            </div>
                            <?php if ($arResult['CAN_CANCEL'] === 'Y') { ?>
                                <div class="intec-grid-item-auto intec-grid-item-450-1">
                                    <a href="<?= $arResult['URL_TO_CANCEL'] ?>" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent intec-ui-size-2">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BUTTONS_CANCEL') ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($arResult['ERRORS']['NOFATAL'])) { ?>
                    <div class="sale-personal-order-detail-errors intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                        <?php foreach ($arResult['ERRORS']['NOFATAL'] as $sError) echo Html::tag('div', $sError) ?>
                    </div>
                <?php } ?>
                <div class="sale-personal-order-detail-header intec-ui-m-b-15">
                    <div class="intec-grid intec-grid-wrap intec-grid-a-h-start intec-grid-a-v-center intec-grid-i-h-10 intec-grid-i-v-5 intec-ui-m-b-20">
                        <div class="intec-grid-item intec-grid-item-500-1">
                            <div class="sale-personal-order-detail-header-title">
                            <?php
                                $iProductsCount = count($arResult['BASKET']);

                                echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_TITLE', [
                                    '#NUMBER#' => Html::encode($arResult['ACCOUNT_NUMBER']),
                                    '#DATE#' => $arResult['DATE_INSERT_FORMATED']
                                ]).', ';

                                echo $iProductsCount.' ';

                                if ($iProductsCount === 1) {
                                    echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_TITLE_QUANTITY_POSTFIX_1');
                                } else if ($iProductsCount >= 2 && $iProductsCount <= 4) {
                                    echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_TITLE_QUANTITY_POSTFIX_2');
                                } else {
                                    echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_TITLE_QUANTITY_POSTFIX_3');
                                }

                                echo ' ';
                                echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_TITLE_SUM').' ';
                                echo $arResult['PRICE_FORMATED'];

                                unset($iProductsCount);
                            ?>
                            </div>
                        </div>
                        <?php if ($arParams['GUEST_MODE'] !== 'Y') { ?>
                            <div class="intec-grid-item-auto intec-grid-item-500-1">
                                <a href="<?= $arResult['URL_TO_COPY'] ?>" class="intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-2">
                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BUTTONS_REPEAT') ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="sale-personal-order-detail-blocks">
                    <?php include(__DIR__.'/parts/common.php') ?>
                    <?php if (!empty($arResult['USER'])) { ?>
                        <?php include(__DIR__.'/parts/user.php') ?>
                    <?php } ?>
                    <?php if (!empty($arResult['ORDER_PROPS']) || !empty($arResult['USER_DESCRIPTION'])) { ?>
                        <?php include(__DIR__.'/parts/information.php') ?>
                    <?php } ?>
                    <?php include(__DIR__.'/parts/payment.php') ?>
                    <?php if (!empty($arResult['SHIPMENT'])) { ?>
                        <?php include(__DIR__.'/parts/shipment.php') ?>
                    <?php } ?>
                    <?php if (!empty($arResult['BASKET'])) { ?>
                        <?php include(__DIR__.'/parts/basket.php') ?>
                    <?php } ?>
                </div>
            <?php if ($arParams['GUEST_MODE'] !== 'Y') { ?>
                <div class="intec-ui-m-t-20">
                    <div class="intec-grid intec-grid-wrap intec-grid-a-h-start intec-grid-a-v-start intec-grid-i-h-10 intec-grid-i-v-5">
                        <div class="intec-grid-item intec-grid-item-450-1">
                            <a href="<?= Html::encode($arResult['URL_TO_LIST']) ?>" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent intec-ui-size-2">
                                <span class="intec-ui-part-icon">
                                    <i class="far fa-angle-left"></i>
                                </span>
                                <span class="intec-ui-part-content">
                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BUTTONS_RETURN') ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
                <script>
                    BX.Sale.PersonalOrderComponent.PersonalOrderDetail.init(<?= JavaScript::toObject([
                        'url' => $this->__component->GetPath() .'/ajax.php',
                        'templateFolder' => $this->GetFolder(),
                        'paymentList' => $arPaymentData
                    ]) ?>);
                </script>
            <?php } ?>
        </div>
    </div>
</div>