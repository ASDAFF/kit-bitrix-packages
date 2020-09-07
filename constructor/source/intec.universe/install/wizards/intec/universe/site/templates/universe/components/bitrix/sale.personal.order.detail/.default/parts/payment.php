<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 * @var array $arPaymentData
 */

?>
<div class="sale-personal-order-detail-block sale-personal-order-detail-block-splitted sale-personal-order-detail-payment" data-block="payment">
    <h2 class="sale-personal-order-detail-block-header intec-ui-markup-header">
        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_TITLE') ?>
    </h2>
    <div class="sale-personal-order-detail-block-content">
        <div class="sale-personal-order-detail-payment-information intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-10">
            <div class="sale-personal-order-detail-payment-information-icon intec-grid-item-auto">
                <i></i>
            </div>
            <div class="sale-personal-order-detail-payment-information-content intec-grid-item">
                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_INFORMATION', [
                    '#NUMBER#'=> Html::encode($arResult['ACCOUNT_NUMBER']),
                    '#DATE#'=> $arResult['DATE_INSERT_FORMATED'],
                    '#STATUS#' => $arResult['CANCELED'] !== 'Y' && !empty($arResult['STATUS']) ? Html::encode($arResult['STATUS']['NAME']) : Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_COMMON_FIELDS_STATE_VALUE_CANCELED'),
                    '#SUM#' => $arResult['PRICE_FORMATED']
                ]) ?>
            </div>
        </div>
    </div>
    <?php if (!empty($arResult['PAYMENT'])) { ?>
        <div class="sale-personal-order-detail-block-content">
            <div class="sale-personal-order-detail-payment-items">
                <?php foreach ($arResult['PAYMENT'] as $arPayment) { ?>
                <?php
                    $sState = 'unpaid';

                    if ($arPayment['PAID'] === 'Y') {
                        $sState = 'paid';
                    } else if ($arResult['IS_ALLOW_PAY'] !== 'Y') {
                        $sState = 'restricted';
                    }

                    $arPaymentData[$arPayment['ACCOUNT_NUMBER']] = [
                        'payment' => $arPayment['ACCOUNT_NUMBER'],
                        'order' => $arResult['ACCOUNT_NUMBER'],
                        'allow_inner' => $arParams['ALLOW_INNER'],
                        'only_inner_full' => $arParams['ONLY_INNER_FULL']
                    ];
                ?>
                    <div class="sale-personal-order-detail-payment-item">
                        <div class="sale-personal-order-detail-payment-item-wrapper intec-grid intec-grid-nowrap intec-grid-600-wrap intec-grid-a-v-start intec-grid-i-h-20 intec-grid-i-v-10">
                            <div class="sale-personal-order-detail-payment-item-icon intec-grid-item-auto">
                                <?= Html::tag('i', null, [
                                    'style' => [
                                        'background-image' => 'url(\''.(!empty($arPayment['PAY_SYSTEM']['SRC_LOGOTIP']) ?
                                            Html::encode($arPayment['PAY_SYSTEM']['SRC_LOGOTIP']) :
                                            '/bitrix/images/sale/nopaysystem.gif'
                                        ).'\')'
                                    ]
                                ]) ?>
                            </div>
                            <div class="sale-personal-order-detail-payment-item-content intec-grid-item intec-grid-item-600-1">
                                <div class="sale-personal-order-detail-payment-item-content-part sale-personal-order-detail-payment-item-content-part-common">
                                    <div>
                                        <span class="sale-personal-order-detail-payment-item-name">
                                        <?php
                                            echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_NAME_1', [
                                                '#NUMBER#'=> $arPayment['ACCOUNT_NUMBER']
                                            ]);

                                            if (!empty($arPayment['DATE_BILL'])) {
                                                echo ' ';
                                                echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_NAME_2', [
                                                    '#DATE#' => $arPayment['DATE_BILL']->format($arParams['ACTIVE_DATE_FORMAT'])
                                                ]);
                                            }

                                            echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_NAME_3', [
                                                '#PAY_SYSTEM#'=> $arPayment['PAY_SYSTEM_NAME']
                                            ]);
                                        ?>
                                        </span>
                                        <span class="sale-personal-order-detail-payment-item-state" data-state="<?= $sState ?>">
                                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_STATE_'.StringHelper::toUpperCase($sState)) ?>
                                        </span>
                                    </div>
                                    <div class="sale-personal-order-detail-payment-item-sum">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_SUM', [
                                            '#SUM#' => $arPayment['PRICE_FORMATED']
                                        ]) ?>
                                    </div>
                                    <?php if (!empty($arPayment['CHECK_DATA'])) { ?>
                                    <?php
                                        $arChecks = [];

                                        foreach ($arPayment['CHECK_DATA'] as $arCheck) {
                                            if (empty($arCheck['LINK']))
                                                continue;

                                            $arChecks[] = Html::tag(
                                                'div',
                                                Html::tag('a', Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_CHECKS_ITEM', [
                                                    '#NUMBER#' => $arCheck['ID'],
                                                    '#TYPE#' => Html::encode($arCheck['TYPE_NAME'])
                                                ]), [
                                                    'href' => $arCheck['LINK']
                                                ]), [
                                                'class' => 'sale-personal-order-detail-payment-item-checks-item'
                                            ]);
                                        }
                                    ?>
                                        <?php if (!empty($arChecks)) { ?>
                                            <div class="sale-personal-order-detail-payment-item-checks">
                                                <div class="sale-personal-order-detail-payment-item-checks-title">
                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_CHECKS_TITLE') ?>:
                                                </div>
                                                <div class="sale-personal-order-detail-payment-item-checks-items">
                                                    <?= implode('', $arChecks) ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (
                                        $arPayment['PAID'] !== 'Y' &&
                                        $arResult['CANCELED'] !== 'Y' &&
                                        $arParams['GUEST_MODE'] !== 'Y' &&
                                        $arResult['LOCK_CHANGE_PAYSYSTEM'] !== 'Y'
                                    ) { ?>
                                        <a href="#" id="<?= $arPayment['ACCOUNT_NUMBER'] ?>" class="sale-personal-order-detail-payment-item-change intec-ui-mod-dashed">
                                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_CHANGE') ?>
                                        </a>
                                    <?php } ?>
                                    <?php if ($arResult['IS_ALLOW_PAY'] !== 'Y' && $arPayment['PAID'] !== 'Y') { ?>
                                        <div class="intec-ui intec-ui-control-alert intec-ui-scheme-orange intec-ui-m-t-20">
                                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_RESTRICTED_MESSAGE') ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if ($arPayment['PAY_SYSTEM']['IS_CASH'] !== 'Y' && $arPayment['PAY_SYSTEM']['ACTION_FILE'] !== 'cash') { ?>
                                    <div class="sale-personal-order-detail-payment-item-content-part sale-personal-order-detail-payment-item-content-part-buttons">
                                        <div class="sale-personal-order-detail-payment-item-buttons">
                                            <?php if ($arPayment['PAY_SYSTEM']['PSA_NEW_WINDOW'] === 'Y' && $arResult['IS_ALLOW_PAY'] === 'Y') { ?>
                                                <?= Html::tag('a', Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_BUTTONS_PAY'), [
                                                    'href' => Html::encode($arPayment['PAY_SYSTEM']['PSA_ACTION_FILE']),
                                                    'target' => '_blank',
                                                    'class' => [
                                                        'sale-personal-order-detail-payment-item-button',
                                                        'sale-personal-order-detail-payment-item-button-pay',
                                                        'intec-ui' => [
                                                            '',
                                                            'control-button',
                                                            'mod-round-3',
                                                            'scheme-current',
                                                            'm-t-20'
                                                        ]
                                                    ]
                                                ]) ?>
                                            <?php } else {
                                                if ($arPayment['PAID'] === 'Y' || $arResult['CANCELED'] === 'Y' || $arResult['IS_ALLOW_PAY'] === 'N') { ?>
                                                    <?= Html::tag('a', Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_BUTTONS_PAY'), [
                                                        'class' => [
                                                            'sale-personal-order-detail-payment-item-button',
                                                            'sale-personal-order-detail-payment-item-button-pay',
                                                            'intec-ui' => [
                                                                '',
                                                                'control-button',
                                                                'mod-round-3',
                                                                'scheme-current',
                                                                'state-disabled',
                                                                'm-t-20'
                                                            ]
                                                        ]
                                                    ]) ?>
                                                <?php } else { ?>
                                                    <?= Html::tag('a', Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_BUTTONS_PAY'), [
                                                        'class' => [
                                                            'sale-personal-order-detail-payment-item-button',
                                                            'sale-personal-order-detail-payment-item-button-pay',
                                                            'sale-personal-order-detail-payment-item-switch',
                                                            'intec-ui' => [
                                                                '',
                                                                'control-button',
                                                                'mod-round-3',
                                                                'scheme-current',
                                                                'm-t-20'
                                                            ]
                                                        ]
                                                    ]) ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="sale-personal-order-detail-payment-item-content-part">
                                    <a class="sale-personal-order-detail-payment-item-cancel intec-ui-mod-dashed intec-ui-m-t-20" style="display: none">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_PAYMENT_ITEM_CANCEL') ?>
                                    </a>
                                </div>
                                <?php if (
                                    $arPayment['PAID'] !== 'Y' &&
                                    $arPayment['PAY_SYSTEM']['IS_CASH'] !== 'Y' &&
                                    $arPayment['PAY_SYSTEM']['PSA_NEW_WINDOW'] !== 'Y' &&
                                    $arResult['CANCELED'] !== 'Y' &&
                                    $arResult['IS_ALLOW_PAY'] !== 'N'
                                ) { ?>
                                    <div class="sale-personal-order-detail-payment-item-content-part sale-personal-order-detail-payment-item-content-part-form">
                                        <div class="sale-personal-order-detail-payment-item-form intec-ui-m-t-20">
                                            <div class="sale-personal-order-detail-payment-item-form-close sale-personal-order-detail-payment-item-switch">
                                                <i class="fa fa-times"></i>
                                            </div>
                                            <div class="sale-personal-order-detail-payment-item-form-content">
                                                <?= $arPayment['BUFFERED_OUTPUT'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php unset($sState) ?>
                <?php } ?>
                <?php unset($arPayment) ?>
            </div>
        </div>
    <?php } ?>
</div>