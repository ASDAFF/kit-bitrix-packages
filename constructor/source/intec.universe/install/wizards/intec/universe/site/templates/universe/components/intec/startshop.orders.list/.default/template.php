<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\bitrix\Component;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 */

$this->setFrameMode(false);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-intec',
        'c-startshop-orders-list',
        'c-startshop-orders-list-default'
    ]
]) ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php if (!empty($arResult['ORDERS'])) { ?>
                <div class="startshop-orders-list-filter intec-grid intec-grid-wrap intec-grid-i-5">
                    <div class="startshop-orders-list-filter-item-wrap intec-grid-item-auto">
                        <div class="startshop-orders-list-filter-item intec-cl-background intec-cl-border" data-status="ALL">
                            <?= Loc::getMessage('SOL_DEFAULT_FILTER_ALL') ?>
                        </div>
                    </div>
                    <?php foreach ($arResult['STATUSES'] as $arStatus) { ?>
                        <div class="startshop-orders-list-filter-item-wrap intec-grid-item-auto">
                            <?= Html::beginTag('div', [
                                'class' => 'startshop-orders-list-filter-item',
                                'data-status' => $arStatus['CODE']
                            ]) ?>
                                <?= $arStatus['NAME'] ?>
                            <?= Html::endTag('div') ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="startshop-orders-list-items">
                    <?php foreach ($arResult['ORDERS'] as $arOrder) { ?>
                        <?= Html::beginTag('div', [
                            'class' => 'startshop-orders-list-item active',
                            'data-status' => $arOrder['STATUS']['CODE']
                        ]) ?>
                            <div class="startshop-orders-list-item-header intec-grid intec-grid-400-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                <div class="startshop-orders-list-item-header-wrapper intec-grid-item intec-grid-item-400-1">
                                    <?= Loc::getMessage("SOL_DEFAULT_HEADER_ORDER") ?><?= $arOrder["ID"] ?> <?= Loc::getMessage("SOL_DEFAULT_HEADER_FROM") ?> <?= $arOrder["DATE_CREATE"] ?> <?= Loc::getMessage("SOL_DEFAULT_HEADER_SUM") ?> <?= $arOrder["AMOUNT"]["PRINT_VALUE"] ?>
                                </div>
                                <div class="startshop-orders-list-item-header-payment intec-grid-item-auto">
                                    <?php if($arOrder["PAYED"] == "Y") { ?>
                                        <div class="startshop-orders-list-item-header-payment-wrapper green">
                                            <?= Loc::getMessage("SOL_DEFAULT_STATUS_PAYMENT_TRUE") ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="startshop-orders-list-item-header-payment-wrapper red">
                                            <?= Loc::getMessage("SOL_DEFAULT_STATUS_PAYMENT_FALSE") ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="startshop-orders-list-item-header-icon-wrap intec-grid-item-auto">
                                    <div class="startshop-orders-list-item-header-icon fal fa-chevron-down active"></div>
                                    <div class="startshop-orders-list-item-header-icon fal fa-chevron-up"></div>
                                </div>
                            </div>
                            <div class="startshop-orders-list-item-content">
                                <div class="startshop-orders-list-item-content-status-wrap intec-grid intec-grid-a-h-between">
                                    <div class="startshop-orders-list-item-content-status-payment intec-grid-item-2 intec-grid-item-600-auto">
                                        <div class="startshop-orders-list-item-content-status-payment-name">
                                            <?= Loc::getMessage("SOL_DEFAULT_STATUS_PAYMENT") ?>
                                        </div>
                                        <?php if($arOrder["PAYED"] == "Y") { ?>
                                            <div class="startshop-orders-list-item-content-status-payment-wrapper green">
                                                <?= Loc::getMessage("SOL_DEFAULT_STATUS_PAYMENT_TRUE") ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="startshop-orders-list-item-content-status-payment-wrapper red">
                                                <?= Loc::getMessage("SOL_DEFAULT_STATUS_PAYMENT_FALSE") ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="startshop-orders-list-item-content-status-order intec-grid-item-2 intec-grid-item-600-auto">
                                        <div class="startshop-orders-list-item-content-status-order-name">
                                            <?= Loc::getMessage("SOL_DEFAULT_STATUS_ORDER") ?>
                                        </div>
                                        <div class="startshop-orders-list-item-content-status-order-wrapper">
                                            <?= $arOrder["STATUS"]["LANG"][LANGUAGE_ID]['NAME'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="startshop-orders-list-item-information">
                                    <?php if (!empty($arOrder["PAYMENT"])) { ?>
                                        <div class="startshop-orders-list-item-information-payment">
                                            <span class="startshop-orders-list-item-information-name">
                                                <?= Loc::getMessage("SOL_DEFAULT_INFORMATION_PAYMENT") ?>
                                            </span>
                                            <span class="startshop-orders-list-item-information-value">
                                                <?= $arOrder["PAYMENT"]["LANG"][LANGUAGE_ID]['NAME'] ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arOrder["PAYMENT"])) { ?>
                                        <div class="startshop-orders-list-item-information-delivery">
                                            <span class="startshop-orders-list-item-information-name">
                                                <?= Loc::getMessage("SOL_DEFAULT_INFORMATION_DELIVERY") ?>
                                            </span>
                                            <span class="startshop-orders-list-item-information-value">
                                                <?= $arOrder["DELIVERY"]["LANG"][LANGUAGE_ID]['NAME'] ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arOrder["DELIVERY"]["PRICE"]['VALUE'] != 0) { ?>
                                        <div class="startshop-orders-list-item-information-delivery-price">
                                            <span class="startshop-orders-list-item-information-name">
                                                <?= Loc::getMessage("SOL_DEFAULT_INFORMATION_DELIVERY_PRICE") ?>
                                            </span>
                                            <span class="startshop-orders-list-item-information-value">
                                                <?= $arOrder["DELIVERY"]["PRICE"]['PRINT_VALUE'] ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <div class="startshop-orders-list-item-information-sum">
                                        <span class="startshop-orders-list-item-information-name">
                                            <?= Loc::getMessage("SOL_DEFAULT_INFORMATION_SUM") ?>
                                        </span>
                                        <span class="startshop-orders-list-item-information-value">
                                            <?= $arOrder["AMOUNT"]["PRINT_VALUE"] ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="startshop-orders-list-item-button-wrap">
                                    <a href="<?= $arOrder['ACTIONS']['VIEW'] ?>" class="intec-button intec-button-s-7 intec-button-cl-common">
                                        <?= Loc::getMessage("SOL_DEFAULT_BUTTON_ORDER") ?>
                                    </a>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
                <script type="text/javascript">
                   $(document).ready(function() {
                       var $root = $('#<?= $sTemplateId ?>');
                       var $items = $('.startshop-orders-list-item', $root);
                       var $buttonFilter = $('.startshop-orders-list-filter-item', $root);
                       var $buttonItem = $('.startshop-orders-list-item-header', $root);

                       $buttonFilter.click(function() {
                           $buttonFilter.removeClass('intec-cl-background intec-cl-border');
                           $(this).addClass('intec-cl-background intec-cl-border');

                           var $activeStatus = $(this).attr('data-status');
                           $items.removeClass('active');

                           if ($activeStatus === 'ALL') {
                               $items.addClass('active');
                           } else {
                               $items.filter(function() {
                                   return $(this).attr('data-status') === $activeStatus;
                               }).addClass('active');
                           }
                       });

                       $buttonItem.click(function() {
                            $(this).next().slideToggle();
                            $(this).find('.startshop-orders-list-item-header-icon').toggleClass('active');
                        });
                    });
                </script>
            <?php } else { ?>
                <div class="startshop-orders-list-notification intec-ui intec-ui-control-alert">
                    <?= Loc::getMessage("SOL_DEFAULT_NOTIFICATION_EMPTY") ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div') ?>