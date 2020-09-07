<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use CJSCore;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Security\Sign\Signer;
use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 * @var string $templateFolder
 * @var string $templateName
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(false);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$bDesktop = Core::$app->browser->isDesktop;

$arParams['USE_DYNAMIC_SCROLL'] = isset($arParams['USE_DYNAMIC_SCROLL']) && $arParams['USE_DYNAMIC_SCROLL'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_FILTER'] = isset($arParams['SHOW_FILTER']) && $arParams['SHOW_FILTER'] === 'N' ? 'N' : 'Y';

$arParams['PRICE_DISPLAY_MODE'] = isset($arParams['PRICE_DISPLAY_MODE']) && $arParams['PRICE_DISPLAY_MODE'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['TOTAL_BLOCK_DISPLAY']) || !is_array($arParams['TOTAL_BLOCK_DISPLAY']))
	$arParams['TOTAL_BLOCK_DISPLAY'] = array('top');

if (empty($arParams['PRODUCT_BLOCKS_ORDER']))
	$arParams['PRODUCT_BLOCKS_ORDER'] = 'props,sku,columns';

if (is_string($arParams['PRODUCT_BLOCKS_ORDER']))
	$arParams['PRODUCT_BLOCKS_ORDER'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

CJSCore::Init(['fx', 'popup', 'ajax']);

$this->addExternalJs($templateFolder.'/js/mustache.js');
$this->addExternalJs($templateFolder.'/js/action-pool.js');
$this->addExternalJs($templateFolder.'/js/filter.js');
$this->addExternalJs($templateFolder.'/js/component.js');

$mobileColumns = !empty($arParams['COLUMNS_LIST_MOBILE']) ? $arParams['COLUMNS_LIST_MOBILE'] : $arParams['COLUMNS_LIST'];
$mobileColumns = array_fill_keys($mobileColumns, true);

include(__DIR__.'/js-templates/total.php');
include(__DIR__.'/js-templates/item.php');

?>
<div class="ns-bitrix c-basket c-basket-template-1" id="<?= $sTemplateId ?>">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <?php if (empty($arResult['ERROR_MESSAGE'])) { ?>
                <?php if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP') {
                    include(__DIR__.'/parts/gifts.php');
                } ?>
                <?php if ($arResult['BASKET_ITEM_MAX_COUNT_EXCEEDED']) { ?>
                    <div id="basket-item-message">
                        <?= Loc::getMessage('SBB_BASKET_ITEM_MAX_COUNT_EXCEEDED', [
                            '#PART1#' => Html::beginTag('a', ['href' => $arParams['PATH_TO_BASKET']]),
                            '#PART2#' => Html::endTag('a')
                        ]) ?>
                    </div>
                <?php } ?>
                <?= Html::beginTag('div', [
                    'id' => 'basket-root',
                    'class' => 'basket-body',
                    'style' => [
                        'opacity' => '0'
                    ]
                ]) ?>
                    <?php if (ArrayHelper::isIn('top', $arParams['TOTAL_BLOCK_DISPLAY'])) { ?>
                        <div class="basket-total" data-entity="basket-total-block"></div>
                    <?php } ?>
                    <div class="basket-items" id="basket-items-list-wrapper">
                        <?php include(__DIR__.'/parts/panel.php') ?>
                        <?= Html::beginTag('div', [
                            'id' => 'basket-items-list-container',
                            'class' => 'basket-items-wrapper',
                            'data-overlayed' => 'true'
                        ]) ?>
                            <?= Html::tag('div', null, [
                                'id' => 'basket-items-list-overlay',
                                'class' => 'basket-overlay',
                                'style' => [
                                    'display' => 'none'
                                ]
                            ]) ?>
                            <?php include(__DIR__.'/parts/alert.php') ?>
                            <div class="" id="basket-item-list">
                                <?php if ($arParams['SHOW_FILTER'] === 'Y' && $bDesktop) {
                                    include(__DIR__.'/parts/items.filter.empty.php');
                                } ?>
                                <div class="basket-items-container" id="basket-item-table"></div>
                            </div>
                        <?= Html::endTag('div') ?>
                    </div>
                    <?php if (ArrayHelper::isIn('bottom', $arParams['TOTAL_BLOCK_DISPLAY'])) { ?>
                        <div class="basket-total" data-entity="basket-total-block"></div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
                <?php if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM') {
                    include(__DIR__.'/parts/gifts.php');
                } ?>
                <?php if (!empty($arResult['CURRENCIES']) && Loader::includeModule('currency')) { ?>
                    <?php CJSCore::Init('currency') ?>
                    <script type="text/javascript">
                        BX.Currency.setCurrencies(<?= CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
                    </script>
                <?php } ?>
                <?php

                    $signer = new Signer;
                    $signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
                    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
                    $messages = Loc::loadLanguageFile(__FILE__);

                ?>
                <script type="text/javascript">
                    BX.message(<?= CUtil::PhpToJSObject($messages) ?>);
                    BX.Sale.BasketComponent.init({
                        'result': <?= CUtil::PhpToJSObject($arResult, false, false, true) ?>,
                        'params': <?= CUtil::PhpToJSObject($arParams) ?>,
                        'template': '<?= CUtil::JSEscape($signedTemplate) ?>',
                        'signedParamsString': '<?= CUtil::JSEscape($signedParams) ?>',
                        'siteId': '<?= CUtil::JSEscape($component->getSiteId()) ?>',
                        'siteTemplateId': '<?= CUtil::JSEscape($component->getSiteTemplateId()) ?>',
                        'templateFolder': '<?= CUtil::JSEscape($templateFolder) ?>'
                    });
                </script>
            <?php } else if ($arResult['EMPTY_BASKET']) {
                include(__DIR__.'/empty.php');
            } else {
                ShowError($arResult['ERROR_MESSAGE']);
            } ?>
            <?php if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP') {
                include(__DIR__.'/parts/gifts.php');
            } ?>
        </div>
    </div>
    <?php include(__DIR__.'/parts/script.php') ?>
</div>