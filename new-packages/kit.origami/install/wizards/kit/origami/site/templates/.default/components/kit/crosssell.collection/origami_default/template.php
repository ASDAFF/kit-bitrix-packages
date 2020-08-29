<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$APPLICATION->IncludeComponent(
    "bitrix:catalog.compare.list",
    "",
    [
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "NAME" => $arParams["COMPARE_NAME"],
        "DETAIL_URL" => $arResult["FOLDER"]
            . $arResult["URL_TEMPLATES"]["element"],
        "COMPARE_URL" => $arResult["FOLDER"]
            . $arResult["URL_TEMPLATES"]["compare"],
        "ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"])
            ? $arParams["ACTION_VARIABLE"] : "action"),
        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        'POSITION_FIXED' => isset($arParams['COMPARE_POSITION_FIXED'])
            ? $arParams['COMPARE_POSITION_FIXED'] : '',
        'POSITION' => isset($arParams['COMPARE_POSITION'])
            ? $arParams['COMPARE_POSITION'] : '',
    ],
    $component,
    ["HIDE_ICONS" => "Y"]
);

global ${$arParams["FILTER_NAME"]};
global $promotionFilter;
$promotionFilter = $arResult['PROMOTION_FILTER_ARRAY'];
$container = 'collections_'.$this->randString();

if (is_array($arResult['COLLECTION_LIST']))
{
    ?>
    <section class="puzzle_block catalog_section_block catalog_section_block_tabs main-container size">
        <ul id="<?=$container?>" class="tabs_sale_block__caption">
            <?
            $i = 0;
            foreach ($arResult['COLLECTION_LIST_NAMES'] as $collectionId => $collectionName)
            {
                if ($arParams['AJAX_MODE'] == 'Y')
                {
                    ?>
                    <li class="collection-<?= $collectionId; ?><?= ($i == 0) ? " active collection-loaded" : '' ?>"
                        data-collection-id="<? echo $collectionId ?>"><?= $collectionName ?></li>
                    <?
                } else {
                    ?>
                    <li class="collection-<?= $collectionId; ?>  collection-loaded<?= ($i == 0) ? " active" : '' ?>"
                        data-collection-id="<? echo $collectionId ?>"><?= $collectionName ?></li>
                    <?
                }
            $i++;}?>
        </ul>

        <div class="tabs_sale_block">

            <? if ($arResult['COLLECTION_LIST_NAMES'])
            {
                $i = 0;
                foreach ($arResult['COLLECTION_LIST_NAMES'] as $collectionId => $collectionName)
                {
                    ?>
                    <div class="tabs_sale_block__content <?=($i == 0)?'active ':''?>collection-id-<? echo $collectionId?>">
                    <?
                    if (is_array($arResult['PROMOTION_FILTER_ARRAY']) && ($collectionId == 'PROMOTION_ID'))
                    {
                        $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section",
                            $arParams['SECTION_TEMPLATE'],
                            Array(
                                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                                "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                                "SHOW_ALL_WO_SECTION" => "Y",
                                "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                                "ELEMENT_SORT_FIELD" => $arParams['ELEMENT_SORT_FIELD'],
                                "ELEMENT_SORT_ORDER" => $arParams['ELEMENT_SORT_ORDER'],
                                "PAGE_ELEMENT_COUNT" => $arParams['PAGE_ELEMENT_COUNT'],
                                "ELEMENT_SORT_ORDER2" => $arParams['ELEMENT_SORT_ORDER2'],
                                "FILTER_NAME" => "promotionFilter",
                                "USE_FILTER" => "Y",
                                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "LABEL_PROP" => $arParams['LABEL_PROP'],
                                "LINE_ELEMENT_COUNT" => $arParams['LINE_ELEMENT_COUNT'],
                                "ADD_PROPERTIES_TO_BASKET" => $arParams['ADD_PROPERTIES_TO_BASKET'],
                                "ADD_SECTIONS_CHAIN" => $arParams['ADD_SECTIONS_CHAIN'],
                                "ADD_TO_BASKET_ACTION" => $arParams['ADD_TO_BASKET_ACTION'],
                                "AJAX_MODE" => $arParams['AJAX_MODE'],
                                "AJAX_OPTION_ADDITIONAL" => $arParams['AJAX_OPTION_ADDITIONAL'],
                                "AJAX_OPTION_HISTORY" => $arParams['AJAX_OPTION_HISTORY'],
                                "AJAX_OPTION_JUMP" => $arParams['AJAX_OPTION_JUMP'],
                                "AJAX_OPTION_STYLE" => $arParams['AJAX_OPTION_STYLE'],
                                "BACKGROUND_IMAGE" => $arParams['BACKGROUND_IMAGE'],
                                "BASKET_URL" => $arParams['BASKET_URL'],
                                "BROWSER_TITLE" => $arParams['BROWSER_TITLE'],
                                "COMPATIBLE_MODE" => $arParams['COMPATIBLE_MODE'],
                                "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
                                "CURRENCY_ID" => $arParams['CURRENCY_ID'],
                                "DETAIL_URL" => $arParams['DETAIL_URL'],
                                "DISABLE_INIT_JS_IN_COMPONENT" => $arParams['DISABLE_INIT_JS_IN_COMPONENT'],
                                "DISPLAY_COMPARE" => $arParams['DISPLAY_COMPARE'],
                                "ENLARGE_PRODUCT" => $arParams['ENLARGE_PRODUCT'],
                                "HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE'],
                                "HIDE_NOT_AVAILABLE_OFFERS" => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
                                "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                                "LAZY_LOAD" => $arParams['LAZY_LOAD'],
                                "LOAD_ON_SCROLL" => $arParams['LOAD_ON_SCROLL'],
                                "MESSAGE_404" => $arParams['MESSAGE_404'],
                                "MESS_BTN_ADD_TO_BASKET" => $arParams['MESS_BTN_ADD_TO_BASKET'],
                                "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
                                "MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
                                "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
                                "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
                                "META_DESCRIPTION" => $arParams['META_DESCRIPTION'],
                                "META_KEYWORDS" => $arParams['META_KEYWORDS'],
                                "OFFERS_LIMIT" => $arParams['OFFERS_LIMIT'],
                                "PAGER_BASE_LINK_ENABLE" => $arParams['PAGER_BASE_LINK_ENABLE'],
                                "PAGER_DESC_NUMBERING" => $arParams['PAGER_DESC_NUMBERING'],
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
                                "PAGER_SHOW_ALL" => $arParams['PAGER_SHOW_ALL'],
                                "PAGER_SHOW_ALWAYS" => $arParams['PAGER_SHOW_ALWAYS'],
                                "PAGER_TEMPLATE" => $arParams['PAGER_TEMPLATE'],
                                "PAGER_TITLE" => $arParams['PAGER_TITLE'],
                                "PARTIAL_PRODUCT_PROPERTIES" => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
                                "PRICE_CODE" => $arParams['PRICE_CODE'],
                                "PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
                                "PRODUCT_BLOCKS_ORDER" => $arParams['PRODUCT_BLOCKS_ORDER'],
                                "PRODUCT_ID_VARIABLE" => $arParams['PRODUCT_ID_VARIABLE'],
                                "PRODUCT_PROPERTIES" => $arParams['PRODUCT_PROPERTIES'],
                                "PRODUCT_PROPS_VARIABLE" => $arParams['PRODUCT_PROPS_VARIABLE'],
                                "PRODUCT_QUANTITY_VARIABLE" => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                "PRODUCT_ROW_VARIANTS" => $arParams['PRODUCT_ROW_VARIANTS'],
                                "PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
                                "PROPERTY_CODE" => $arParams['PROPERTY_CODE'],
                                "RCM_PROD_ID" => $arParams['RCM_PROD_ID'],
                                "RCM_TYPE" => $arParams['RCM_TYPE'],
                                "SEF_MODE" => $arParams['SEF_MODE'],
                                "SET_BROWSER_TITLE" => $arParams['SET_BROWSER_TITLE'],
                                "SET_LAST_MODIFIED" => $arParams['PRODUCT_PROPS_VARIABLE'],
                                "SET_META_DESCRIPTION" => $arParams['SET_META_DESCRIPTION'],
                                "SET_META_KEYWORDS" => $arParams['SET_META_KEYWORDS'],
                                "SET_STATUS_404" => $arParams['SET_STATUS_404'],
                                "SET_TITLE" => $arParams['SET_TITLE'],
                                "SHOW_404" => $arParams['SHOW_404'],
                                "SHOW_CLOSE_POPUP" => $arParams['SHOW_CLOSE_POPUP'],
                                "SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
                                "SHOW_FROM_SECTION" => $arParams['SHOW_FROM_SECTION'],
                                "SHOW_MAX_QUANTITY" => $arParams['SHOW_MAX_QUANTITY'],
                                "SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
                                "SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
                                "SHOW_SLIDER" => $arParams['SHOW_SLIDER'],
                                "SECTION_TEMPLATE" => $arParams['SECTION_TEMPLATE'],
                                "TEMPLATE_THEME" => $arParams['TEMPLATE_THEME'],
                                "USE_ENHANCED_ECOMMERCE" => $arParams['USE_ENHANCED_ECOMMERCE'],
                                "USE_MAIN_ELEMENT_SECTION" => $arParams['USE_MAIN_ELEMENT_SECTION'],
                                "USE_PRICE_COUNT" => $arParams['USE_PRICE_COUNT'],
                                "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                                "OFFERS_FIELD_CODE" => $arParams['OFFERS_FIELD_CODE'],
                                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                                "PRODUCT_DISPLAY_MODE" => $arParams['PRODUCT_DISPLAY_MODE'],
                                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                                'USE_VOTE_RATING' => $arParams['USE_VOTE_RATING'],
                                'COMPARE_PATH' => $arParams['COMPARE_PATH'],
                                'COMPARE_NAME' => $arParams['COMPARE_PATH'],
                                'USE_COMPARE_LIST' => $arParams['USE_COMPARE_LIST'],
                                "ACTION_PRODUCTS" => $arParams["ACTION_PRODUCTS"],
                                "VARIANT_LIST_VIEW" => $arParams["VARIANT_LIST_VIEW"],
                            ),
                            $component
                        );

                    ?>
                    </div>
                    <?
                    $i++;

                        continue 1;
                    }

                    if (($i == 0) || ($arParams['AJAX_MODE'] != 'Y'))
                    {
                        $APPLICATION->IncludeComponent(
                            "sotbit:crosssell.collection.list",
                            $arParams["COLLECTION_LIST_TEMPLATE"],
                            Array(
                                "ACTION_VARIABLE" => $arParams['ACTION_VARIABLE'],
                                "ADD_PROPERTIES_TO_BASKET" => $arParams['ADD_PROPERTIES_TO_BASKET'],
                                "ADD_SECTIONS_CHAIN" => $arParams['ADD_SECTIONS_CHAIN'],
                                "AJAX_MODE" => $arParams['AJAX_MODE'],
                                "AJAX_OPTION_ADDITIONAL" => $arParams['AJAX_OPTION_ADDITIONAL'],
                                "AJAX_OPTION_HISTORY" => $arParams['AJAX_OPTION_HISTORY'],
                                "AJAX_OPTION_JUMP" => $arParams['AJAX_OPTION_JUMP'],
                                "AJAX_OPTION_STYLE" => $arParams['AJAX_OPTION_STYLE'],
                                "BACKGROUND_IMAGE" => $arParams['BACKGROUND_IMAGE'],
                                "BASKET_URL" => $arParams['BASKET_URL'],
                                "BROWSER_TITLE" => $arParams['BROWSER_TITLE'],
                                "CACHE_FILTER" => $arParams['CACHE_FILTER'],
                                "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                                "CACHE_TIME" => $arParams['CACHE_TIME'],
                                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                                "COLLECTION_ID" => $collectionId,
                                "COMPATIBLE_MODE" => $arParams['COMPATIBLE_MODE'],
                                "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
                                "CURRENCY_ID" => $arParams['CURRENCY_ID'],
                                "DETAIL_URL" => $arParams['DETAIL_URL'],
                                "DISABLE_INIT_JS_IN_COMPONENT" => $arParams['DISABLE_INIT_JS_IN_COMPONENT'],
                                "DISPLAY_BOTTOM_PAGER" => $arParams['DISPLAY_BOTTOM_PAGER'],
                                "DISPLAY_COMPARE" => $arParams['DISPLAY_COMPARE'],
                                "DISPLAY_TOP_PAGER" => $arParams['DISPLAY_TOP_PAGER'],
                                "ELEMENT_SORT_FIELD" => $arParams['ELEMENT_SORT_FIELD'],
                                "ELEMENT_SORT_FIELD2" => $arParams['ELEMENT_SORT_FIELD2'],
                                "ELEMENT_SORT_ORDER" => $arParams['ELEMENT_SORT_ORDER'],
                                "ELEMENT_SORT_ORDER2" => $arParams['ELEMENT_SORT_ORDER2'],
                                "FILTER_NAME" => $arParams['FILTER_NAME'],
                                "FROM_COMPLEX" => $arResult['FROM_COMPLEX'],
                                "HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE'],
                                "HIDE_NOT_AVAILABLE_OFFERS" => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
                                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                                "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                                "INCLUDE_SUBSECTIONS" => $arParams['INCLUDE_SUBSECTIONS'],
                                "LINE_ELEMENT_COUNT" => $arParams['LINE_ELEMENT_COUNT'],
                                "MESSAGE_404" => $arParams['MESSAGE_404'],
                                "META_DESCRIPTION" => $arParams['META_DESCRIPTION'],
                                "META_KEYWORDS" => $arParams['META_KEYWORDS'],
                                "OFFERS_CART_PROPERTIES" => $arParams['OFFERS_CART_PROPERTIES'],
                                "OFFERS_FIELD_CODE" => $arParams['OFFERS_FIELD_CODE'],
                                "OFFERS_LIMIT" => $arParams['OFFERS_LIMIT'],
                                "OFFERS_PROPERTY_CODE" => $arParams['OFFERS_PROPERTY_CODE'],
                                "OFFERS_SORT_FIELD" => $arParams['OFFERS_SORT_FIELD'],
                                "OFFERS_SORT_FIELD2" => $arParams['OFFERS_SORT_FIELD2'],
                                "OFFERS_SORT_ORDER" => $arParams['OFFERS_SORT_ORDER'],
                                "OFFERS_SORT_ORDER2" => $arParams['OFFERS_SORT_ORDER2'],
                                "PAGER_BASE_LINK_ENABLE" => $arParams['PAGER_BASE_LINK_ENABLE'],
                                "PAGER_DESC_NUMBERING" => $arParams['PAGER_DESC_NUMBERING'],
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
                                "PAGER_SHOW_ALL" => $arParams['PAGER_SHOW_ALL'],
                                "PAGER_SHOW_ALWAYS" => $arParams['PAGER_SHOW_ALWAYS'],
                                "PAGER_TEMPLATE" => $arParams['PAGER_TEMPLATE'],
                                "PAGER_TITLE" => $arParams['PAGER_TITLE'],
                                "PARTIAL_PRODUCT_PROPERTIES" => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
                                "PRICE_CODE" => $arParams['PRICE_CODE'],
                                "PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
                                "PRODUCT_ID_VARIABLE" => $arParams['PRODUCT_ID_VARIABLE'],
                                "PAGE_ELEMENT_COUNT" => $arParams['PAGE_ELEMENT_COUNT'],
                                "PRODUCT_PROPERTIES" => $arParams['PRODUCT_PROPERTIES'],
                                "PRODUCT_PROPS_VARIABLE" => $arParams['PRODUCT_PROPS_VARIABLE'],
                                "PRODUCT_QUANTITY_VARIABLE" => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                "SECTION_ID" => $_REQUEST["SECTION_ID"],
                                "SECTION_TEMPLATE" => $arParams['SECTION_TEMPLATE'],
                                "SECTION_URL" => $arParams['SECTION_URL'],
                                "SEF_MODE" => $arParams['SEF_MODE'],
                                "SET_BROWSER_TITLE" => $arParams['SET_BROWSER_TITLE'],
                                "SET_LAST_MODIFIED" => $arParams['SET_LAST_MODIFIED'],
                                "SET_META_DESCRIPTION" => $arParams['SET_META_DESCRIPTION'],
                                "SET_META_KEYWORDS" => $arParams['SET_META_KEYWORDS'],
                                "SET_STATUS_404" => $arParams['SET_STATUS_404'],
                                "SET_TITLE" => $arParams['SET_TITLE'],
                                "SHOW_404" => $arParams['SHOW_404'],
                                "SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
                                "SHOW_SLIDER" => $arParams['SHOW_SLIDER'],
                                "USE_MAIN_ELEMENT_SECTION" => $arParams['USE_MAIN_ELEMENT_SECTION'],
                                "USE_PRICE_COUNT" => $arParams['USE_PRICE_COUNT'],
                                "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],

                                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                                "PRODUCT_DISPLAY_MODE" => $arParams['PRODUCT_DISPLAY_MODE'],
                                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                                'USE_VOTE_RATING' => $arParams['USE_VOTE_RATING'],
                                'COMPARE_PATH' => $arParams['COMPARE_PATH'],
                                'COMPARE_NAME' => $arParams['COMPARE_PATH'],
                                'USE_COMPARE_LIST' => $arParams['USE_COMPARE_LIST'],
                                "ACTION_PRODUCTS" => $arParams["ACTION_PRODUCTS"],
                                "VARIANT_LIST_VIEW" => $arParams["VARIANT_LIST_VIEW"],
                            )
                        );
                    }
                    ?>
                    </div>
                    <?
                    $i++;
                }
            }
            ?>
        </div>
    </section>
    <?
}
$componentPath = $arResult['COMPONENT_PATH'];
?>
<script>

   $( document ).ready(function() {
       var currentItem = null;
        $('ul#<?=$container?>').on('click', 'li:not(.active)', function ()
        {
            currentItem = $(this);

            var collectionId = $(this).data("collection-id");
            if (!$(this).hasClass("collection-loaded"))
            {
                if (collectionId.toString() !== 'PROMOTION_ID'){
                    getCollection($(this).data("collection-id"), currentItem);
                }
            } else {
                changeTab(currentItem);
            }
        });
    });

    function  changeTab (btn) {
        $(btn)
                .addClass('active').siblings().removeClass('active')
                .closest('.catalog_section_block_tabs').find('.tabs_sale_block__content').removeClass('active').eq($(btn).index()).addClass('active');
    }


    function getCollection(collectionId, btnTab)
    {
        var collectionTab = $('.collection-id-'+collectionId);
        var currentTab = $(btnTab).closest('.catalog_section_block_tabs').find('.tabs_sale_block__content.active');

        createMainLoaderInner(currentTab);
        BX.ajax({
            url: <?= CUtil::PhpToJSObject($componentPath, false, true) ?> + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
            method: 'POST',
            dataType: 'html',
            timeout: 60,
            async: true,
            data: {
                params : <?=CUtil::PhpToJSObject($arParams, false, true)?>,
                collectionId: collectionId,
                siteId: '<?=SITE_ID?>',
            },
            onsuccess: function (result)
            {
                removeMainLoaderInner(currentTab);

                changeTab(btnTab);
                $('.collection-id-'+collectionId).html(result).promise().done(function ()
                {
                    if(document.querySelector('.recommended-products__slider')) {
                        let items = document.querySelectorAll('.product_card__block_item_inner-wrapper');
                        let widthSlide = calcWidthSlide();
                        for (let i = 0; i < items.length; i++) {
                            items[i].style.width = widthSlide + 'px';
                        }
                        if(document.querySelector('.recommended-products__slider:not(.slick-slider)')) {
                                inicialSlider('.recommended-products__slider:not(.slick-slider)', settingSlider.itemSetting);
                        }
                    };

                });

                if (!collectionTab.hasClass("collection-loaded"))
                {
                    $('li.collection-' + collectionId).addClass("collection-loaded");
                }
            },
            error: function (result)
            {
                $('.collection-id-'+collectionId).html(<?= CUtil::PhpToJSObject(GetMessage("LOADING_ERROR"), false, true) ?>);
                removeMainLoaderInner(currentTab);
            }
        });
    }
</script>
