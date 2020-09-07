<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arStatuses = Arrays::fromDBResult(CSaleStatus::GetList(['SORT' => 'ASC'], [
    'LID' => LANGUAGE_ID
]))->indexBy('ID');

$bEmpty = true;

foreach ($arResult['ORDER_BY_STATUS'] as $arGroup) {
    if (!empty($arGroup)) {
        $bEmpty = false;
        break;
    }
}

?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-sale-personal-order-list c-sale-personal-order-list-default">
    <div class="sale-personal-order-list-wrapper intec-content">
        <div class="sale-personal-order-list-wrapper-2 intec-content-wrapper">
            <div class="sale-personal-order-list-header">
                <div class="sale-personal-order-list-header-parts intec-grid intec-grid-wrap intec-grid-i-h-5 intec-grid-i-v-3">
                    <div class="sale-personal-order-list-header-part intec-grid-item">
                        <div class="sale-personal-order-list-header-text">
                            <?php if ($arResult['FILTER']['VALUE'] === 'current') { ?>
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FILTER_CURRENT') ?>
                            <?php } else if ($arResult['FILTER']['VALUE'] === 'completed') { ?>
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FILTER_COMPLETED') ?>
                            <?php } else { ?>
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FILTER_CANCELED') ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($arResult['FILTER']['VALUE'] !== 'current') { ?>
                        <div class="sale-personal-order-list-header-part intec-grid-item-auto">
                            <a href="<?= $arResult['FILTER']['URL']['CURRENT'] ?>" class="sale-personal-order-list-header-link">
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FILTER_CURRENT') ?>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['FILTER']['VALUE'] !== 'completed') { ?>
                        <div class="sale-personal-order-list-header-part intec-grid-item-auto">
                            <a href="<?= $arResult['FILTER']['URL']['COMPLETED'] ?>" class="sale-personal-order-list-header-link">
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FILTER_COMPLETED') ?>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['FILTER']['VALUE'] !== 'canceled') { ?>
                        <div class="sale-personal-order-list-header-part intec-grid-item-auto">
                            <a href="<?= $arResult['FILTER']['URL']['CANCELED'] ?>" class="sale-personal-order-list-header-link">
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FILTER_CANCELED') ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if (!$bEmpty) { ?>
                <div class="sale-personal-order-list-items">
                    <div class="sale-personal-order-list-items-wrapper" data-role="items">
                        <?php foreach ($arResult['ORDER_BY_STATUS'] as $arGroup) { ?>
                            <?php foreach($arGroup as $arOrder) { ?>
                            <?php
                                $arStatus = $arStatuses->get($arOrder['ORDER']['STATUS_ID']);
                            ?>
                                <div class="sale-personal-order-list-item" data-role="item">
                                    <div class="sale-personal-order-list-item-header" data-role="button">
                                        <div class="sale-personal-order-list-item-header-wrapper">
                                            <div class="sale-personal-order-list-item-header-text">
                                            <?php
                                                $arHeader = [];
                                                $arHeader[] = Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_ORDER');
                                                $arHeader[] = Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_NUM_SIGN', [
                                                    '#NUMBER#' => $arOrder['ORDER']['ID']
                                                ]);

                                                if (!empty($arOrder['ORDER']['DATE_INSERT_FORMATED'])) {
                                                    $arHeader[] = Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FROM');
                                                    $arHeader[] = $arOrder['ORDER']['DATE_INSERT_FORMATED'];
                                                }

                                                if (!empty($arOrder['ORDER']['FORMATED_PRICE'])) {
                                                    $arHeader[] = Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FROM_SUM');
                                                    $arHeader[] = $arOrder['ORDER']['FORMATED_PRICE'];
                                                }

                                                echo implode(' ', $arHeader);
                                                unset($arHeader);
                                            ?>
                                            </div>
                                            <div class="sale-personal-order-list-item-header-indicator">
                                                <div class="sale-personal-order-list-item-header-icon sale-personal-order-list-item-header-icon-active">
                                                    <i class="fa fa-angle-up"></i>
                                                </div>
                                                <div class="sale-personal-order-list-item-header-icon sale-personal-order-list-item-header-icon-inactive">
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sale-personal-order-list-item-content" data-role="content">
                                        <div class="sale-personal-order-list-item-content-wrapper">
                                            <div class="sale-personal-order-list-item-content-left">
                                                <div class="sale-personal-order-list-item-content-payment">
                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_PAYED') ?>:
                                                    <span class="sale-personal-order-list-item-content-state">
                                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_'.($arOrder['ORDER']['PAYED'] == 'Y' ? 'YES' : 'NO')) ?>
                                                    </span>
                                                </div>
                                                <div class="sale-personal-order-list-item-content-blocks">
                                                    <?php foreach($arOrder['PAYMENT'] as $arPayment) { ?>
                                                        <div class="sale-personal-order-list-item-content-block">
                                                            <div class="sale-personal-order-list-item-content-properties">
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_ORDER_N') ?> <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_NUM_SIGN', [
                                                                        '#NUMBER#' => $arPayment['ACCOUNT_NUMBER']
                                                                    ]) ?>
                                                                    <?php if(!empty($arOrder['ORDER']['DATE_INSERT_FORMATED'])) { ?>
                                                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FROM') ?> <?= $arOrder['ORDER']['DATE_INSERT_FORMATED'] ?>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_TYPE_PAY') ?>: <?= $arPayment['PAY_SYSTEM_NAME'] ?>
                                                                </div>
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_FROM_ORDER') ?> <?= $arPayment['FORMATED_SUM'] ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="sale-personal-order-list-item-content-block">
                                                        <div class="sale-personal-order-list-item-content-properties">
                                                            <?php if (!empty($arStatus)) { ?>
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_STATUS') ?>: <?= $arStatus['NAME'] ?>
                                                                </div>
                                                            <?php } ?>
                                                            <div class="sale-personal-order-list-item-content-property">
                                                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_PAY_SUM') ?> <?= $arOrder['ORDER']['FORMATED_PRICE'] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="sale-personal-order-list-item-content-right">
                                                <div class="sale-personal-order-list-item-content-blocks">
                                                    <?php foreach($arOrder['SHIPMENT'] as $arShipment) { ?>
                                                        <div class="sale-personal-order-list-item-content-block order-list-default-item-content-delivery">
                                                            <div class="sale-personal-order-list-item-content-name">
                                                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_DELIVERY') ?>:
                                                                <span class="sale-personal-order-list-item-content-state">
                                                                    <?= $arShipment['DELIVERY_NAME']?>
                                                                </span>
                                                            </div>
                                                            <div class="sale-personal-order-list-item-content-properties">
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_SNIP') ?>: <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_NUM_SIGN', [
                                                                        '#NUMBER#' => $arShipment['ACCOUNT_NUMBER']
                                                                    ]) ?>
                                                                </div>
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_SUM_SNIPMENT') ?>: <?= $arShipment['FORMATED_DELIVERY_PRICE'] ?>
                                                                </div>
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_SHIPPING_STATUS') ?>: <?= $arShipment['DELIVERY_STATUS_NAME'] ?>
                                                                </div>
                                                                <div class="sale-personal-order-list-item-content-property">
                                                                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_DELIVERY_SERVICE') ?>: <?= $arShipment['DELIVERY_NAME'] ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="intec-ui-clear"></div>
                                        </div>
                                        <div class="sale-personal-order-list-item-content-buttons">
                                            <div class="sale-personal-order-list-item-content-wrapper">
                                                <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                                                    <div class="intec-grid-item-auto intec-grid-item-450-1">
                                                        <a href="<?= $arOrder['ORDER']['URL_TO_DETAIL'] ?>" class="intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-3">
                                                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_BUTTONS_DETAIL') ?>
                                                        </a>
                                                    </div>
                                                    <div class="intec-grid-item intec-grid-item-450-1">
                                                        <a href="<?= Html::encode($arOrder['ORDER']['URL_TO_COPY']) ?>" class="intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-3" style="white-space: nowrap;">
                                                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_BUTTONS_COPY') ?>
                                                        </a>
                                                    </div>
                                                    <div class="intec-grid-item-auto intec-grid-item-450-1">
                                                        <a href="<?= $arOrder['ORDER']['URL_TO_CANCEL'] ?>" class="intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-mod-transparent intec-ui-mod-round-3 intec-ui-size-3">
                                                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_BUTTONS_CANCEL') ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="sale-personal-order-list-message">
                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_MESSAGES_EMPTY') ?>
                </div>
            <?php } ?>
            <script>
                (function ($, api) {
                    var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                    var items = [];

                    root.find('[data-role="items"] [data-role="item"]').each(function () {
                        var item;
                        var nodes;
                        var isOpened;

                        nodes = {};
                        nodes.item = $(this);
                        nodes.button = nodes.item.find('[data-role="button"]');
                        nodes.content = nodes.item.find('[data-role="content"]');

                        isOpened = true;

                        item = {};
                        item.getNode = function () { return nodes.item; };
                        item.getNodeButton = function () { return nodes.button; };
                        item.getNodeContent = function () { return nodes.content; };

                        item.isOpened = function () { return isOpened; };

                        item.open = function (callback, animate) {
                            var complete;

                            if (item.isOpened())
                                return;

                            isOpened = true;
                            nodes.item.trigger('open', item);
                            complete = function () {
                                nodes.item.trigger('opened', item);

                                if ($.isFunction(callback))
                                    callback.apply(item);
                            };

                            if (animate) {
                                nodes.content.stop().slideToggle({
                                    'duration': 300,
                                    'complete': complete
                                });
                            } else {
                                nodes.content.stop().toggle(true);
                                complete();
                            }
                        };
                        item.close = function (callback, animate) {
                            var complete;

                            if (!item.isOpened())
                                return;

                            isOpened = false;
                            nodes.item.trigger('close', item);
                            complete = function () {
                                nodes.item.trigger('closed', item);

                                if ($.isFunction(callback))
                                    callback.apply(item);
                            };

                            if (animate) {
                                nodes.content.stop().slideToggle({
                                    'duration': animate ? 300 : 0,
                                    'complete': complete
                                });
                            } else {
                                nodes.content.stop().toggle(false);
                                complete();
                            }
                        };
                        item.toggle = function (callback, animate) {
                            var state;
                            var handler;

                            handler = function () {
                                nodes.item.trigger('toggled', item, state);

                                if ($.isFunction(callback))
                                    callback.apply(this, arguments);
                            };

                            state = item.isOpened();
                            nodes.item.trigger('toggle', item, state);

                            if (state) {
                                item.close(handler, animate);
                            } else {
                                item.open(handler, animate);
                            }
                        };

                        nodes.button.on('click', function () {
                            item.toggle(null, true);
                        });

                        item.toggle(null, false);
                        items.push(item);
                    }).on('open', function (event, item) {
                        item.getNode().addClass('sale-personal-order-list-item-active');
                    }).on('close', function (event, item) {
                        item.getNode().removeClass('sale-personal-order-list-item-active');
                    });
                })(jQuery, intec)
            </script>
        </div>
    </div>
</div>