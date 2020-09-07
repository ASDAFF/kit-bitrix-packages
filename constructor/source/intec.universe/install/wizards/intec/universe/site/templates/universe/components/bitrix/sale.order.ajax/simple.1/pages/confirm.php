<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 * @var CBitrixComponentTemplate $this
 */

if ($arParams['SET_TITLE'] == 'Y')
	$APPLICATION->SetTitle(Loc::getMessage('SOA_ORDER_COMPLETE'));

$directory = $this->GetFolder();

?>
<div class="bx-soa-page bx-soa-page-confirm">
    <?php if (!empty($arResult["ORDER"])) { ?>
        <table class="bx-soa-page-table">
            <tr>
                <td class="bx-soa-page-table-icon">
                    <img src="<?= $directory ?>/images/confirm.png" />
                </td>
                <td class="bx-soa-page-table-content">
                    <?= Loc::getMessage('SOA_ORDER_SUC', [
                        '#ORDER_DATE#' => $arResult['ORDER']['DATE_INSERT']->toUserTime()->format('d.m.Y H:i'),
                        '#ORDER_ID#' => $arResult['ORDER']['ACCOUNT_NUMBER']
                    ]) ?>
                    <br/><br/>
                    <?php if (!empty($arResult['ORDER']['PAYMENT_ID'])) { ?>
                        <?= Loc::getMessage('SOA_PAYMENT_SUC', [
                            '#PAYMENT_ID#' => $arResult['PAYMENT'][$arResult['ORDER']['PAYMENT_ID']]['ACCOUNT_NUMBER']
                        ]) ?>
                    <?php } ?>
                    <?php if ($arParams['NO_PERSONAL'] !== 'Y') { ?>
                        <br /><br />
                        <?= Loc::getMessage('SOA_ORDER_SUC1', ['#LINK#' => $arParams['PATH_TO_PERSONAL']]) ?>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <?php if ($arResult['ORDER']['IS_ALLOW_PAY'] === 'Y') { ?>
            <?php if (!empty($arResult['PAYMENT'])) { ?>
                <?php foreach ($arResult['PAYMENT'] as $arPayment) { ?>
                    <?php $arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$arPayment['ID']] ?>
                    <?php if (!empty($arPaySystem) && $arPayment["PAID"] != 'Y') { ?>
                        <div class="bx-soa-page-block">
                            <div class="bx-soa-page-block-title">
                                <?= Loc::getMessage('SOA_PAY') ?>
                            </div>
                            <div class="bx-soa-page-block-content">
                                <?php if (
                                    !empty($arResult['PAY_SYSTEM_LIST']) &&
                                    ArrayHelper::keyExists(
                                        $arPayment['PAY_SYSTEM_ID'],
                                        $arResult['PAY_SYSTEM_LIST']
                                    )
                                ) { ?>
                                    <?php
                                        $arLogotype = CFile::ResizeImageGet($arPaySystem['LOGOTIP'], [
                                            'width' => 100,
                                            'height' => 100
                                        ], BX_RESIZE_IMAGE_PROPORTIONAL);
                                    ?>
                                    <?php if (empty($arPaySystem['ERROR'])) { ?>
                                        <div class="bx-soa-page-payment-information">
                                            <?php if (!empty($arLogotype)) { ?>
                                                <div class="bx-soa-page-payment-logotype">
                                                    <img src="<?= $arLogotype['src'] ?>" alt="<?= $arPaySystem['NAME'] ?>" />
                                                </div>
                                            <?php } ?>
                                            <div class="bx-soa-page-payment-name">
                                                <?= $arPaySystem['NAME'] ?>
                                            </div>
                                        </div>
                                        <?php if (strlen($arPaySystem['ACTION_FILE']) > 0 && $arPaySystem['NEW_WINDOW'] == 'Y' && $arPaySystem['IS_CASH'] != 'Y') { ?>
                                        <?php
                                            $orderAccountNumber = urlencode(urlencode($arResult['ORDER']['ACCOUNT_NUMBER']));
                                            $paymentAccountNumber = $arPayment['ACCOUNT_NUMBER'];
                                        ?>
                                            <div class="bx-soa-page-payment-addition">
                                                <script>
                                                    window.open('<?= $arParams['PATH_TO_PAYMENT'] ?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
                                                </script>
                                                <?= Loc::getMessage('SOA_PAY_LINK', [
                                                    '#LINK#' => $arParams['PATH_TO_PAYMENT'].'?ORDER_ID='.$orderAccountNumber.'&PAYMENT_ID='.$paymentAccountNumber
                                                ]) ?>
                                                <?php if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']) { ?>
                                                    <br/>
                                                    <?= Loc::getMessage('SOA_PAY_PDF', [
                                                        '#LINK#' => $arParams['PATH_TO_PAYMENT'].'?ORDER_ID='.$orderAccountNumber.'&pdf=1&DOWNLOAD=Y'
                                                    ]) ?>
                                                <?php } ?>
                                            </div>
                                        <?php } else if (!empty($arPaySystem['BUFFERED_OUTPUT'])) { ?>
                                            <div class="bx-soa-page-payment-addition">
                                                <?= $arPaySystem['BUFFERED_OUTPUT'] ?>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <span style="color: red;">
                                            <?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?>
                                        </span>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span style="color: red;">
                                        <?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <br /><strong><?=$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']?></strong>
        <?php } ?>
    <?php } else { ?>
        <table class="bx-soa-page-table">
            <tr>
                <td class="bx-soa-page-table-content">
                    <b><?= Loc::getMessage('SOA_ERROR_ORDER') ?></b>
                    <br /><br />
                    <?= Loc::getMessage('SOA_ERROR_ORDER_LOST', [
                        '#ORDER_ID#' => Html::encode($arResult['ACCOUNT_NUMBER'])
                    ]) ?>
                    <?= Loc::getMessage('SOA_ERROR_ORDER_LOST1') ?>
                </td>
            </tr>
        </table>
    <?php } ?>
</div>