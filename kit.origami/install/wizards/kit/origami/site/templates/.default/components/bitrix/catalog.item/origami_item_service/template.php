<?
/**
 * Copyright (c) 27/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
?>

<?
$showSKU = true;
$showSkuBlock = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y';
if(!$showSkuBlock || $arResult['TEMPLATE'] == 'template_4' || $arResult['TEMPLATE'] == 'template_5')
    $showSKU = false;

CJSCore::Init(array('currency'));
$this->setFrameMode(true);

if (isset($arResult['ITEM']))
{
    $item = $arResult['ITEM'];
    $areaId = $arResult['AREA_ID'] . '__' . rand(1000, 9999);
    $itemIds = array(

        'ID' => $areaId,
        'PICT' => $areaId.'_pict',
        'SECOND_PICT' => $areaId.'_secondpict',
        //'PICT_SLIDER' => $areaId.'_pict_slider',
        'STICKER_ID' => $areaId.'_sticker',
        'SECOND_STICKER_ID' => $areaId.'_secondsticker',
        'QUANTITY' => $areaId.'_quantity',
        'QUANTITY_DOWN' => $areaId.'_quant_down',
        'QUANTITY_UP' => $areaId.'_quant_up',
        'QUANTITY_MEASURE' => $areaId.'_quant_measure',
        'QUANTITY_LIMIT' => $areaId.'_quant_limit',
        'BUY_LINK' => $areaId.'_buy_link',
        'BASKET_ACTIONS' => $areaId.'_basket_actions',
        'NOT_AVAILABLE_MESS' => $areaId.'_not_avail',
        'SUBSCRIBE_LINK' => $areaId.'_subscribe',
        'COMPARE_LINK' => $areaId.'_compare_link',
        'WISH_LINK' => $areaId.'_wish_link',
        'PRICE' => $areaId.'_price',
        'PRICE_OLD' => $areaId.'_price_old',
        'PRICE_TOTAL' => $areaId.'_price_total',
        'PRICE_SAVE' => $areaId.'_price_save',
        'DSC_PERC' => $areaId.'_dsc_perc',
        'SECOND_DSC_PERC' => $areaId.'_second_dsc_perc',
        'PROP_DIV' => $areaId.'_sku_tree',
        'PROP' => $areaId.'_prop_',
        'DISPLAY_PROP_DIV' => $areaId.'_sku_prop',
        'BASKET_PROP_DIV' => $areaId.'_basket_prop',
        'ALL_PRICES' => $areaId.'_all_prices',
        'OC' => $areaId.'_oc'
    );

    $obName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $areaId);
    $isBig = isset($arResult['BIG']) && $arResult['BIG'] === 'Y';

    $productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
        ? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
        : $item['NAME'];

    $imgTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
        ? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
        : $item['NAME'];

    $imgAlt = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'] != ''
        ? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']
        : $item['NAME'];

    $skuProps = array();

    $haveOffers = !empty($item['OFFERS']);

    $allPrices = $arOffersID = [];

    if ($haveOffers)
    {
        $actualItem = isset($item['OFFERS'][$item['OFFERS_SELECTED']])
            ? $item['OFFERS'][$item['OFFERS_SELECTED']]
            : reset($item['OFFERS']);
        foreach($item['OFFERS'] as $offer)
        {
            if($arParams["FILL_ITEM_ALL_PRICES"] == "Y" && isset($offer['ITEM_ALL_PRICES']))
                $allPrices[$offer['ID']] = $offer['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"];
            $arOffersID[] = $offer["ID"];
        }
    }
    else
    {
        $actualItem = $item;
        if($arParams["FILL_ITEM_ALL_PRICES"] == "Y" && isset($offer['ITEM_ALL_PRICES']))
            $allPrices[$item['ID']] = $actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
    }

    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
    {
        $price = $item['ITEM_START_PRICE'];
        $minOffer = $item['OFFERS'][$item['ITEM_START_PRICE_SELECTED']];
        $measureRatio = $minOffer['ITEM_MEASURE_RATIOS'][$minOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
        $morePhoto = $item['MORE_PHOTO'];
    }
    else
    {
        $price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
        $measureRatio = $price['MIN_QUANTITY'];
        $morePhoto = $actualItem['MORE_PHOTO'];
    }

    $strLazyLoad = '';

    if($arResult['LAZY_LOAD'])
    {
        $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$morePhoto[0]['SRC'].'" class="lazy"';
    }else
    {
        $strLazyLoad = 'src="'.$morePhoto[0]['SRC'].'"';
    }


    if(Config::get('OFFER_LANDING') == 'Y')
        $item['DETAIL_PAGE_URL'] = isset($actualItem['DETAIL_PAGE_URL']) ? $actualItem['DETAIL_PAGE_URL']  : $item['DETAIL_PAGE_URL'];

    $showSlider = is_array($morePhoto) && count($morePhoto) > 1;
    $showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($item['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

    $discountPositionClass = isset($arResult['BIG_DISCOUNT_PERCENT']) && $arResult['BIG_DISCOUNT_PERCENT'] === 'Y'
        ? 'product-item-label-big'
        : 'product-item-label-small';
    $discountPositionClass .= $arParams['DISCOUNT_POSITION_CLASS'];

    $labelPositionClass = isset($arResult['BIG_LABEL']) && $arResult['BIG_LABEL'] === 'Y'
        ? 'product-item-label-big'
        : 'product-item-label-small';
    $labelPositionClass .= $arParams['LABEL_POSITION_CLASS'];

    $buttonSizeClass = isset($arResult['BIG_BUTTONS']) && $arResult['BIG_BUTTONS'] === 'Y' ? 'btn-md' : 'btn-sm';

    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
    ?>
<div class="service-detail-item-wrapper" id="<?=$areaId?>" data-entity="item">
    <div id="<?=$this->GetEditAreaID($item['ID'])?>" class="check_basket_<?=$item['ID']?> ">
		<div class="service-detail-item" id="<?=$areaId?>" data-entity="item">
            <?
            $documentRoot = Main\Application::getDocumentRoot();
            // ----------------------------------------------------------------------------------------------------------------------------------
             $templatePath = strtolower($arResult['TYPE']).'/'.(isset($arResult['TEMPLATE'])?$arResult['TEMPLATE']:'template_3').'.php';

            // -----------------------------------------------------------------------------------------------------------------------------
            $file = new Main\IO\File($documentRoot.$templateFolder.'/'.$templatePath);
            if ($file->isExists())
            {
                include($file->getPath());
            }

            if($arParams['SKU_PROPS'])
                foreach ($arParams['SKU_PROPS'] as $skuProperty)
                {
                    if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
                        continue;

                    $skuProps[] = array(
                        'ID' => $skuProperty['ID'],
                        'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                        'VALUES' => $skuProperty['VALUES'],
                        'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
                    );
                }

            $jsParams = array(

                'THANKS' => Loc::getMessage('THANKS'),
                'SUCCESS_MESSAGE' => Loc::getMessage('SUCCESS_MESSAGE'),
                'PRODUCT_TYPE' => $item['CATALOG_TYPE'],
                'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                'SHOW_ADD_BASKET_BTN' => false,
                'SHOW_BUY_BTN' => true,
                'SHOW_ABSENT' => true,
                'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
                'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
                'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
                'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
                'BIG_DATA' => $item['BIG_DATA'],
                'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                'VIEW_MODE' => $arResult['TYPE'],
                'USE_SUBSCRIBE' => $showSubscribe,
                //'WISHES' => $arResult['WISHES'],
                'PRODUCT' => array(
                    'ID' => $item['ID'],
                    'NAME' => $productTitle,
                    'DETAIL_PAGE_URL' => $item['DETAIL_PAGE_URL'],
                    //'PICT' => $item['DETAIL_PICT'] ? $item['DETAIL_PICT'] : $item['PREVIEW_PICTURE'],
                    'CAN_BUY' => $item['CAN_BUY'],
                    'CHECK_QUANTITY' => $item['CHECK_QUANTITY'],
                    'MAX_QUANTITY' => $item['CATALOG_QUANTITY'],
                    'STEP_QUANTITY' => $item['ITEM_MEASURE_RATIOS'][$item['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
                    'QUANTITY_FLOAT' => is_float($item['ITEM_MEASURE_RATIOS'][$item['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
                    'ITEM_PRICE_MODE' => $item['ITEM_PRICE_MODE'],
                    'ITEM_PRICES' => $item['ITEM_PRICES'],
                    'ITEM_PRICE_SELECTED' => $item['ITEM_PRICE_SELECTED'],
                    'ITEM_QUANTITY_RANGES' => $item['ITEM_QUANTITY_RANGES'],
                    'ITEM_QUANTITY_RANGE_SELECTED' => $item['ITEM_QUANTITY_RANGE_SELECTED'],
                    'ITEM_MEASURE_RATIOS' => $item['ITEM_MEASURE_RATIOS'],
                    'ITEM_MEASURE_RATIO_SELECTED' => $item['ITEM_MEASURE_RATIO_SELECTED'],
                    'MORE_PHOTO' => $item['MORE_PHOTO'],
                    'MORE_PHOTO_COUNT' => $item['MORE_PHOTO_COUNT'],
                    'ALL_PRICES' => $allPrices,
                    'IBLOCK_ID' => $item["IBLOCK_ID"]
                ),
                'BASKET' => array(
                    'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
                    'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                    'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                    'EMPTY_PROPS' => empty($item['PRODUCT_PROPERTIES']),
                    'BASKET_URL' => $arParams['BASKET_URL'],
                    'BASKET_URL_AJAX' => SITE_DIR.'include/ajax/buy.php',
                    'ADD_URL_TEMPLATE' => $arParams['~ADD_URL_TEMPLATE'],
                    'BUY_URL_TEMPLATE' => $arParams['~BUY_URL_TEMPLATE'],
                    'OC_URL' => SITE_DIR.'include/ajax/oc.php'
                ),
                'VISUAL' => array(
                    'ID' => $itemIds['ID'],
                    'PICT_ID' => $item['SECOND_PICT'] ? $itemIds['SECOND_PICT'] : $itemIds['PICT'],
                    //'PICT_SLIDER_ID' => $itemIds['PICT_SLIDER'],
                    'QUANTITY_ID' => $itemIds['QUANTITY'],
                    'QUANTITY_UP_ID' => $itemIds['QUANTITY_UP'],
                    'QUANTITY_DOWN_ID' => $itemIds['QUANTITY_DOWN'],
                    'PRICE_ID' => $itemIds['PRICE'],
                    'PRICE_OLD_ID' => $itemIds['PRICE_OLD'],
                    'PRICE_TOTAL_ID' => $itemIds['PRICE_TOTAL'],
                    'PRICE_SAVE' => $itemIds['PRICE_SAVE'],
                    'BUY_ID' => $itemIds['BUY_LINK'],
                    'BASKET_PROP_DIV' => $itemIds['BASKET_PROP_DIV'],
                    'BASKET_ACTIONS_ID' => $itemIds['BASKET_ACTIONS'],
                    'NOT_AVAILABLE_MESS' => $itemIds['NOT_AVAILABLE_MESS'],
                    'COMPARE_LINK_ID' => $itemIds['COMPARE_LINK'],
                    'WISH_LINK_ID' => $itemIds['WISH_LINK'],
                    'SUBSCRIBE_ID' => $itemIds['SUBSCRIBE_LINK'],
                    'ALL_PRICES' => $itemIds['ALL_PRICES'],
                    'OC_ID' => $itemIds['OC']
                )
            );
            $jsParams["SITE_DIR"] = SITE_ID;

            $jsParams['ADD_PRODUCT_TO_BASKET_MODE'] = (Config::get('SHOW_POPUP_ADD_BASKET') == 'Y') ? 'popup' : 'no-popup';

            if ($item['BIG_DATA'])
            {
                $jsParams['PRODUCT']['RCM_ID'] = $item['RCM_ID'];
            }
            $jsParams['PRODUCT_DISPLAY_MODE'] = $arParams['PRODUCT_DISPLAY_MODE'];
            $jsParams['USE_ENHANCED_ECOMMERCE'] = $arParams['USE_ENHANCED_ECOMMERCE'];
            $jsParams['DATA_LAYER_NAME'] = $arParams['DATA_LAYER_NAME'];
            $jsParams['BRAND_PROPERTY'] = !empty($item['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $item['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null;

            $templateData = array(
                'JS_OBJ' => $obName,
                'ITEM' => array(
                    'ID' => $item['ID'],
                    'IBLOCK_ID' => $item['IBLOCK_ID'],
                    'OFFERS_SELECTED' => $item['OFFERS_SELECTED'],
                    'JS_OFFERS' => $item['JS_OFFERS']
                ),
                'OFFERS_ID' => $arOffersID,

            );

            $messages = array(
                'WISH_TO' => Loc::getMessage('WISH_TO'),
                'WISH_IN' => Loc::getMessage('WISH_IN'),
                'COMPARE_TO' => Loc::getMessage('COMPARE_TO'),
                'COMPARE_IN' => Loc::getMessage('COMPARE_IN'),
                'RELATIVE_QUANTITY_NO_RECOMMEND' => $arParams['MESS_RELATIVE_QUANTITY_NO'],
                'RELATIVE_QUANTITY_MANY' => $arParams['MESS_RELATIVE_QUANTITY_MANY'],
                'RELATIVE_QUANTITY_FEW' => $arParams['MESS_RELATIVE_QUANTITY_FEW'],
            );

            \KitOrigami::clearJSParams($jsParams, $arParams, $showSKU);

            ?>
			<script>
                window.addEventListener('DOMContentLoaded', function () {
                    showOnHover();
                });
                function showOnHover() {
                    let items = document.querySelectorAll('.service-detail-item');

                    for (let i = 0; i < items.length; i++) {
                        items[i].addEventListener('mouseenter', function () {

                            if (items[i].querySelector('.service-detail-item__content-hidden-wrapper')) {
                                let hiddenWrapper = items[i].querySelector('.service-detail-item__content-hidden-wrapper');

                                hiddenWrapper.style.height = hiddenWrapper
                                        .querySelector('.service-detail-item__content-hidden')
                                        .clientHeight
                                    + 'px';
                            }

                        });

                        items[i].addEventListener('mouseleave', function () {

                            if (items[i].querySelector('.service-detail-item__content-hidden-wrapper')) {
                                items[i]
                                    .querySelector('.service-detail-item__content-hidden-wrapper')
                                    .style
                                    .height = 0 + 'px';
                            }
                        })
                    }
                }

                BX.message(<?=CUtil::PhpToJSObject($messages)?>);
                if ( !<?=$obName?>) {
                    var <?=$obName?> = new JCCatalogItemTab(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
                }
			</script>
		</div>
	</div>
</div>
    <?
    // close in component_epilog
    unset($item, $actualItem, $minOffer, $itemIds, $jsParams);
}
