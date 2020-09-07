<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main;
use Bitrix\Main\Loader;
use	Bitrix\Main\Localization\Loc;
use Bitrix\Sale\PropertyValueCollection;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$scheme = $request->isHttps() ? 'https' : 'http';

switch (LANGUAGE_ID) {
	case 'ru':
		$locale = 'ru-RU'; break;
	case 'ua':
		$locale = 'ru-UA'; break;
	case 'tk':
		$locale = 'tr-TR'; break;
	default:
		$locale = 'en-US'; break;
}

$APPLICATION->SetAdditionalCSS($templateFolder.'/style.css', true);
$this->addExternalJs($templateFolder.'/order_ajax.js');
PropertyValueCollection::initJs();
$this->addExternalJs($templateFolder.'/script.js');
?>
<noscript>
    <div style="color:red"><?=Loc::getMessage('SOA_NO_JS')?></div>
</noscript>
<?

$hideDelivery = empty($arResult['DELIVERY']);

?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-sale-order-ajax c-sale-order-ajax-simple-1">
    <div class="sale-order-ajax-wrapper intec-content">
        <div class="sale-order-ajax-wrapper-2 intec-content-wrapper">
            <?php if (strlen($request->get('ORDER_ID')) > 0) { ?>
                <?php include(__DIR__.'/pages/confirm.php') ?>
            <?php } else if ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET']) { ?>
                <?php include(__DIR__.'/pages/empty.php') ?>
            <?php } else { ?>
                <form action="<?= POST_FORM_ACTION_URI ?>" method="POST" name="ORDER_FORM" id="bx-soa-order-form" enctype="multipart/form-data">
                    <?= bitrix_sessid_post() ?>
                    <?= strlen($arResult['PREPAY_ADIT_FIELDS']) > 0 ? $arResult['PREPAY_ADIT_FIELDS'] : null ?>
                    <input type="hidden" name="<?=$arParams['ACTION_VARIABLE']?>" value="saveOrderAjax">
                    <input type="hidden" name="location_type" value="code">
                    <input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult['BUYER_STORE']?>">
                    <div id="bx-soa-order" class="row bx-<?=$arParams['TEMPLATE_THEME']?>" style="opacity: 0">
                        <!--	MAIN BLOCK	-->
                        <div class="col-sm-9 bx-soa">
                            <div id="bx-soa-main-notifications">
                                <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red" style="display:none"></div>
                                <div data-type="informer" style="display:none"></div>
                            </div>
                            <!--	AUTH BLOCK	-->
                            <div id="bx-soa-auth" class="bx-soa-section bx-soa-auth" style="display:none">
                                <div class="bx-soa-section-title-container">
                                    <h2 class="bx-soa-section-title">
                                        <?=$arParams['MESS_AUTH_BLOCK_NAME']?>
                                    </h2>
                                </div>
                                <div class="bx-soa-section-content container-fluid"></div>
                            </div>

                            <!--	DUPLICATE MOBILE ORDER SAVE BLOCK	-->
                            <div id="bx-soa-total-mobile" class="bx-soa-sidebar-mobile"></div>

                            <? if ($arParams['BASKET_POSITION'] === 'before'): ?>
                                <!--	BASKET ITEMS BLOCK	-->
                                <div id="bx-soa-basket" data-visited="false" class="bx-soa-section bx-active">
                                    <div class="bx-soa-section-title-container">
                                        <h2 class="bx-soa-section-title">
                                            <?=$arParams['MESS_BASKET_BLOCK_NAME']?>
                                        </h2>
                                        <a href="javascript:void(0)" class="bx-soa-editstep"><?=$arParams['MESS_EXTEND']?></a>
                                    </div>
                                    <div class="bx-soa-section-content container-fluid"></div>
                                </div>
                            <? endif ?>

                            <!--	REGION BLOCK	-->
                            <div id="bx-soa-region" data-visited="false" class="bx-soa-section bx-active">
                                <div class="bx-soa-section-title-container">
                                    <h2 class="bx-soa-section-title">
                                        <?=$arParams['MESS_REGION_BLOCK_NAME']?>
                                    </h2>
                                    <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                </div>
                                <div class="bx-soa-section-content container-fluid"></div>
                            </div>

                            <? if ($arParams['DELIVERY_TO_PAYSYSTEM'] === 'p2d'): ?>
                                <div id="bx-soa-paysystem-and-delivery" class="intec-grid intec-grid-i-10 intec-grid-a-v-stretch intec-grid-nowrap">
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'intec-grid-item-2' => !$hideDelivery,
                                            'intec-grid-item-900-1' => !$hideDelivery,
                                            'intec-grid-item-1' => $hideDelivery
                                        ], true)
                                    ]) ?>
                                        <!--	PAY SYSTEMS BLOCK	-->
                                        <div id="bx-soa-paysystem" data-visited="false" class="bx-soa-section bx-active">
                                            <div class="bx-soa-section-title-container">
                                                <h2 class="bx-soa-section-title">
                                                    <?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
                                                </h2>
                                                <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                            </div>
                                            <div class="bx-soa-section-content container-fluid"></div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'intec-grid-item-2',
                                            'intec-grid-item-900-1'
                                        ],
                                        'style' => [
                                            'display' => $hideDelivery ? 'none' : null
                                        ]
                                    ]) ?>
                                        <!--	DELIVERY BLOCK	-->
                                        <div id="bx-soa-delivery" data-visited="false" class="bx-soa-section bx-active">
                                            <div class="bx-soa-section-title-container">
                                                <h2 class="bx-soa-section-title">
                                                    <?=$arParams['MESS_DELIVERY_BLOCK_NAME']?>
                                                </h2>
                                                <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                            </div>
                                            <div class="bx-soa-section-content container-fluid"></div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                </div>
                                <!--	PICKUP BLOCK	-->
                                <div id="bx-soa-pickup" data-visited="false" class="bx-soa-section" style="display:none">
                                    <div class="bx-soa-section-title-container">
                                        <h2 class="bx-soa-section-title">
                                        </h2>
                                        <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                    </div>
                                    <div class="bx-soa-section-content container-fluid"></div>
                                </div>
                            <? else: ?>
                                <div id="bx-soa-paysystem-and-delivery" class="intec-grid intec-grid-i-10 intec-grid-a-v-stretch intec-grid-wrap">
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'intec-grid-item-2',
                                            'intec-grid-item-900-1'
                                        ],
                                        'style' => [
                                            'display' => $hideDelivery ? 'none' : null
                                        ]
                                    ]) ?>
                                        <!--	DELIVERY BLOCK	-->
                                        <div id="bx-soa-delivery" data-visited="false" class="bx-soa-section bx-active">
                                            <div class="bx-soa-section-title-container">
                                                <h2 class="bx-soa-section-title">
                                                    <?=$arParams['MESS_DELIVERY_BLOCK_NAME']?>
                                                </h2>
                                                <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                            </div>
                                            <div class="bx-soa-section-content container-fluid"></div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'intec-grid-item-2' => !$hideDelivery,
                                            'intec-grid-item-900-1' => !$hideDelivery,
                                            'intec-grid-item-1' => $hideDelivery
                                        ], true)
                                    ]) ?>
                                        <!--	PAY SYSTEMS BLOCK	-->
                                        <div id="bx-soa-paysystem" data-visited="false" class="bx-soa-section bx-active">
                                            <div class="bx-soa-section-title-container">
                                                <h2 class="bx-soa-section-title">
                                                    <?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
                                                </h2>
                                                <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                            </div>
                                            <div class="bx-soa-section-content container-fluid"></div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                </div>
                                <!--	PICKUP BLOCK	-->
                                <div id="bx-soa-pickup" data-visited="false" class="bx-soa-section" style="display:none">
                                    <div class="bx-soa-section-title-container">
                                        <h2 class="bx-soa-section-title">
                                        </h2>
                                        <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                    </div>
                                    <div class="bx-soa-section-content container-fluid"></div>
                                </div>
                            <? endif ?>
                            <!--	BUYER PROPS BLOCK	-->
                            <div id="bx-soa-properties" data-visited="false" class="bx-soa-section bx-active">
                                <div class="bx-soa-section-title-container">
                                    <h2 class="bx-soa-section-title">
                                        <?=$arParams['MESS_BUYER_BLOCK_NAME']?>
                                    </h2>
                                    <div class="col-xs-12 col-sm-3 text-right" style="display: none;"><a href="" class="bx-soa-editstep"><?=$arParams['MESS_EDIT']?></a></div>
                                </div>
                                <div class="bx-soa-section-content container-fluid"></div>
                            </div>

                            <? if ($arParams['BASKET_POSITION'] === 'after'): ?>
                                <!--	BASKET ITEMS BLOCK	-->
                                <div id="bx-soa-basket" data-visited="false" class="bx-soa-section bx-active">
                                    <div class="bx-soa-section-title-container">
                                        <h2 class="bx-soa-section-title">
                                            <?=$arParams['MESS_BASKET_BLOCK_NAME']?>
                                        </h2>
                                        <a href="javascript:void(0)" class="bx-soa-editstep"><?=$arParams['MESS_EXTEND']?></a>
                                    </div>
                                    <div class="bx-soa-section-content container-fluid"></div>
                                </div>
                            <? endif ?>

                            <!--	ORDER SAVE BLOCK	-->
                            <div id="bx-soa-orderSave">
                                <div class="intec-grid intec-grid-i-10 intec-grid-a-v-center intec-grid-nowrap intec-grid-700-wrap">
                                    <?php if ($arParams['USER_CONSENT'] === 'Y') { ?>
                                        <div class="intec-grid-item intec-grid-item-shrink-1 intec-grid-item-700-1">
                                            <?php $APPLICATION->IncludeComponent(
                                                'bitrix:main.userconsent.request',
                                                '',
                                                array(
                                                    'ID' => $arParams['USER_CONSENT_ID'],
                                                    'IS_CHECKED' => $arParams['USER_CONSENT_IS_CHECKED'],
                                                    'IS_LOADED' => $arParams['USER_CONSENT_IS_LOADED'],
                                                    'AUTO_SAVE' => 'N',
                                                    'SUBMIT_EVENT_NAME' => 'bx-soa-order-save',
                                                    'REPLACE' => array(
                                                        'button_caption' => isset($arParams['~MESS_ORDER']) ? $arParams['~MESS_ORDER'] : $arParams['MESS_ORDER'],
                                                        'fields' => $arResult['USER_CONSENT_PROPERTY_DATA']
                                                    )
                                                )
                                            ) ?>
                                        </div>
                                    <?php } ?>
                                    <div class="intec-grid-item-auto intec-grid-item-700-1">
                                        <a href="javascript:void(0)" style="margin: 10px 0" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-3" data-save-button="true">
                                            <?=$arParams['MESS_ORDER']?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div style="display: none;">
                                <div id='bx-soa-basket-hidden' class="bx-soa-section"></div>
                                <div id='bx-soa-region-hidden' class="bx-soa-section"></div>
                                <div id='bx-soa-paysystem-hidden' class="bx-soa-section"></div>
                                <div id='bx-soa-delivery-hidden' class="bx-soa-section"></div>
                                <div id='bx-soa-pickup-hidden' class="bx-soa-section"></div>
                                <div id="bx-soa-properties-hidden" class="bx-soa-section"></div>
                                <div id="bx-soa-auth-hidden" class="bx-soa-section">
                                    <div class="bx-soa-section-content container-fluid reg"></div>
                                </div>
                            </div>
                        </div>

                        <!--	SIDEBAR BLOCK	-->
                        <div id="bx-soa-total" class="col-sm-3 bx-soa-sidebar">
                            <div class="bx-soa-cart-total-ghost"></div>
                            <div class="bx-soa-cart-total"></div>
                        </div>
                    </div>
                </form>

                <div id="bx-soa-saved-files" style="display:none"></div>
                <div id="bx-soa-soc-auth-services" style="display:none">
                    <?
                    $arServices = false;
                    $arResult['ALLOW_SOCSERV_AUTHORIZATION'] = Main\Config\Option::get('main', 'allow_socserv_authorization', 'Y') != 'N' ? 'Y' : 'N';
                    $arResult['FOR_INTRANET'] = false;

                    if (Main\ModuleManager::isModuleInstalled('intranet') || Main\ModuleManager::isModuleInstalled('rest'))
                        $arResult['FOR_INTRANET'] = true;

                    if (Main\Loader::includeModule('socialservices') && $arResult['ALLOW_SOCSERV_AUTHORIZATION'] === 'Y')
                    {
                        $oAuthManager = new CSocServAuthManager();
                        $arServices = $oAuthManager->GetActiveAuthServices(array(
                            'BACKURL' => $this->arParams['~CURRENT_PAGE'],
                            'FOR_INTRANET' => $arResult['FOR_INTRANET'],
                        ));

                        if (!empty($arServices))
                        {
                            $APPLICATION->IncludeComponent(
                                'bitrix:socserv.auth.form',
                                'flat',
                                array(
                                    'AUTH_SERVICES' => $arServices,
                                    'AUTH_URL' => $arParams['~CURRENT_PAGE'],
                                    'POST' => $arResult['POST'],
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                        }
                    }
                    ?>
                </div>
                <div style="display: none">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:sale.location.selector.steps',
                        '.default',
                        array(),
                        false
                    ) ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:sale.location.selector.search',
                        '.default',
                        array(),
                        false
                    ) ?>
                </div>
                <?php
                $signer = new Main\Security\Sign\Signer;
                $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order.ajax');
                $messages = Loc::loadLanguageFile(__FILE__);
                ?>
                <script>
                    BX.message(<?= JavaScript::toObject($messages) ?>);
                    BX.Sale.OrderAjaxComponent.init({
                        result: <?=CUtil::PhpToJSObject($arResult['JS_DATA'])?>,
                        locations: <?=CUtil::PhpToJSObject($arResult['LOCATIONS'])?>,
                        params: <?=CUtil::PhpToJSObject($arParams)?>,
                        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
                        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
                        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
                        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
                        propertyValidation: true,
                        showWarnings: true,
                        pickUpMap: {
                            defaultMapPosition: {
                                lat: 55.76,
                                lon: 37.64,
                                zoom: 7
                            },
                            secureGeoLocation: false,
                            geoLocationMaxTime: 5000,
                            minToShowNearestBlock: 3,
                            nearestPickUpsToShow: 3
                        },
                        propertyMap: {
                            defaultMapPosition: {
                                lat: 55.76,
                                lon: 37.64,
                                zoom: 7
                            }
                        },
                        orderBlockId: 'bx-soa-order',
                        authBlockId: 'bx-soa-auth',
                        basketBlockId: 'bx-soa-basket',
                        regionBlockId: 'bx-soa-region',
                        paySystemBlockId: 'bx-soa-paysystem',
                        deliveryBlockId: 'bx-soa-delivery',
                        pickUpBlockId: 'bx-soa-pickup',
                        propsBlockId: 'bx-soa-properties',
                        totalBlockId: 'bx-soa-total'
                    });
                </script>
                <script>
                    <?
                    // spike: for children of cities we place this prompt
                    $city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
                    ?>
                    BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
                        'source' => $component->getPath().'/get.php',
                        'cityTypeId' => intval($city['ID']),
                        'messages' => array(
                            'otherLocation' => '--- '.Loc::getMessage('SOA_OTHER_LOCATION'),
                            'moreInfoLocation' => '--- '.Loc::getMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
                            'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.Loc::getMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.Loc::getMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
                                    '#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
                                    '#ANCHOR_END#' => '</a>'
                                )).'</div>'
                        )
                    ))?>);
                </script>
                <?php if ($arParams['SHOW_PICKUP_MAP'] === 'Y' || $arParams['SHOW_MAP_IN_PROPS'] === 'Y') { ?>
                    <?php if ($arParams['PICKUP_MAP_TYPE'] === 'yandex') { ?>
                        <?php $this->addExternalJs($templateFolder.'/scripts/yandex_maps.js') ?>
                        <script src="<?=$scheme?>://api-maps.yandex.ru/2.1.50/?load=package.full&lang=<?=$locale?>"></script>
                        <script>
                            (function bx_ymaps_waiter(){
                                if (typeof ymaps !== 'undefined' && BX.Sale && BX.Sale.OrderAjaxComponent)
                                    ymaps.ready(BX.proxy(BX.Sale.OrderAjaxComponent.initMaps, BX.Sale.OrderAjaxComponent));
                                else
                                    setTimeout(bx_ymaps_waiter, 100);
                            })();
                        </script>
                    <?php } else if ($arParams['PICKUP_MAP_TYPE'] === 'google') { ?>
                        <?php $this->addExternalJs($templateFolder.'/scripts/google_maps.js') ?>
                        <script async defer src="<?=$scheme?>://maps.googleapis.com/maps/api/js?key=<?= htmlspecialcharsbx(Main\Config\Option::get('fileman', 'google_map_api_key', '')) ?>&callback=bx_gmaps_waiter">
                        </script>
                        <script>
                            function bx_gmaps_waiter() {
                                if (BX.Sale && BX.Sale.OrderAjaxComponent) {
                                    BX.Sale.OrderAjaxComponent.initMaps();
                                } else {
                                    setTimeout(bx_gmaps_waiter, 100);
                                }
                            }
                        </script>
                    <?php } ?>
                <?php } ?>
                <?php if ($arParams['USE_YM_GOALS'] === 'Y') { ?>
                    <script>
                        (function bx_counter_waiter(i){
                            i = i || 0;
                            if (i > 50)
                                return;

                            if (typeof window['yaCounter<?=$arParams['YM_GOALS_COUNTER']?>'] !== 'undefined')
                                BX.Sale.OrderAjaxComponent.reachGoal('initialization');
                            else
                                setTimeout(function(){bx_counter_waiter(++i)}, 100);
                        })();
                    </script>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>