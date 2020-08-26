<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

global $arrFilter;

if (is_array($arResult['COLLECTION_LIST']))
{
    $collectionList = $arParams['COLLECTION_LIST'];
    $collectionNames = $arParams['COLLECTION_LIST_NAMES'];
    $isAjax = $arParams['AJAX_MODE'];
    $sectionId = $_REQUEST["SECTION_ID"];
    ?>
    <section class="catalog_section_block catalog_section_block_tabs">
        <ul id="collections" class="tabs_sale_block__caption">
            <?
            $i = 0;
            $collectionParameters = array();
            foreach ($collectionNames as $collectionId => $collectionName)
            {
                $collectionParameters[$collectionId]['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
                $collectionParameters[$collectionId]['FROM_COMPLEX'] = $arParams['FROM_COMPLEX'];
                $collectionParameters[$collectionId]['SECTION_ID'] = $sectionId;
                if ($isAjax == 'Y')
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

            <? if ($collectionNames)
            {
                $i = 0;
                foreach ($collectionNames as $collectionId => $collectionName)
                {
                    ?>
                    <div class="tabs_sale_block__content <?=($i == 0)?'active ':''?>collection-id-<? echo $collectionId?>">
                    <?
                    if (($i == 0) || ($isAjax != 'Y'))
                    {
                        $APPLICATION->IncludeComponent(
                            "kit:crosssell.collection.list",
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
                                "PRICE_CODE" => $arResult['PRICE_CODE'],
                                "PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
                                "PRODUCT_ID_VARIABLE" => $arParams['PRODUCT_ID_VARIABLE'],
                                "PRODUCT_PER_TAB" => $arParams['PRODUCT_PER_TAB'],
                                "PRODUCT_PROPERTIES" => $arParams['PRODUCT_PROPERTIES'],
                                "PRODUCT_PROPS_VARIABLE" => $arParams['PRODUCT_PROPS_VARIABLE'],
                                "PRODUCT_QUANTITY_VARIABLE" => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                "SECTION_ID" => $sectionId,
                                "SECTION_TEMPLATE" => $arParams['SECTION_TEMPLATE'],
                                "ITEM_TEMPLATE" => $arParams['ITEM_TEMPLATE'],
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
                                "SLIDER_ID" => $collectionId,
                                "USE_MAIN_ELEMENT_SECTION" => $arParams['USE_MAIN_ELEMENT_SECTION'],
                                "USE_PRICE_COUNT" => $arParams['USE_PRICE_COUNT'],
                                "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY']
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
$componentPath = $arParams['COMPONENT_PATH'];
?>
<script>
    $( document ).ready(function() {
        $('ul#collections').on('click', 'li:not(.active)', function () {
            if (!$(this).hasClass("collection-loaded")){
                getCollection($(this).data("collection-id"));
            }
            $(this)
                .addClass('active').siblings().removeClass('active')
                .closest('.catalog_section_block_tabs').find('.tabs_sale_block__content').removeClass('active').eq($(this).index()).addClass('active');
        });
    });
    function getCollection(collectionId){
        var collectionTab = $('.collection-id-'+collectionId);
        createLoadersMore(collectionTab);
        $.ajax({
            url: <?= CUtil::PhpToJSObject($componentPath, false, true) ?> + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
            method: 'POST',
            dataType: 'html',
            timeout: 3000,
            data: {
                params : <?=CUtil::PhpToJSObject($arParams, false, true)?>,
                collectionId: collectionId,
                priceCode: <?=CUtil::PhpToJSObject($arResult['PRICE_CODE'], false, true)?>,
            },
            success: function (result) {
                removeLoadersMore(collectionTab);
                $('.collection-id-'+collectionId).html(result);
                if (!collectionTab.hasClass("collection-loaded")){
                    $('li.collection-' + collectionId).addClass("collection-loaded");
                }
            },
            error: function (result) {
                $('.collection-id-'+collectionId).html(<?= CUtil::PhpToJSObject(GetMessage("LOADING_ERROR"), false, true) ?>);
                removeLoadersMore(collectionTab);
            }
        });
    }
</script>
