<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

global ${$arParams["FILTER_NAME"]};
global $promotionFilter;
$promotionFilter = $arResult['PROMOTION_FILTER_ARRAY'];
$container = 'collections_'.$this->randString();

if (is_array($arResult['COLLECTION_LIST']))
{
    ?>
    <section class="catalog_section_block catalog_section_block_tabs main-container">
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
                        "bitrix:news.list",
                        '',
                        Array(
                            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                            "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                            "NEWS_COUNT" => $arParams['LINE_ELEMENT_COUNT'],

                            "SORT_BY1" => $arParams["ELEMENT_SORT_FIELD"],
                            "SORT_BY2" => $arParams["ELEMENT_SORT_FIELD2"],
                            "SORT_ORDER1" => $arParams["ELEMENT_SORT_ORDER"],
                            "SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                            "FILTER_NAME" => $arParams["FILTER_NAME"],
                            "FIELD_CODE" => $arParams['FIELD_CODE'],
                            "PROPERTY_CODE" => $arParams['PROPERTY_CODE'],

                            "AJAX_MODE" => $arParams['AJAX_MODE'],
                            "AJAX_OPTION_JUMP" => $arParams['AJAX_OPTION_JUMP'],
                            "AJAX_OPTION_STYLE" => $arParams['AJAX_OPTION_STYLE'],
                            "AJAX_OPTION_HISTORY" => $arParams['AJAX_OPTION_HISTORY'],

                            "PREVIEW_TRUNCATE_LEN" => $arParams['PREVIEW_TRUNCATE_LEN'],
                            "SET_BROWSER_TITLE" => $arParams['SET_BROWSER_TITLE'],
                            "SET_LAST_MODIFIED" => $arParams['PRODUCT_PROPS_VARIABLE'],
                            "SET_META_DESCRIPTION" => $arParams['SET_META_DESCRIPTION'],
                            "SET_META_KEYWORDS" => $arParams['SET_META_KEYWORDS'],
                            "SET_LAST_MODIFIED" => $arParams['SET_LAST_MODIFIED'],
                            "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams['INCLUDE_IBLOCK_INTO_CHAIN'],
                            "ADD_SECTIONS_CHAIN" => $arParams['ADD_SECTIONS_CHAIN'],
                            "HIDE_LINK_WHEN_NO_DETAIL" => $arParams['HIDE_LINK_WHEN_NO_DETAIL'],
                            "ACTIVE_DATE_FORMAT" => $arParams['ACTIVE_DATE_FORMAT'],
                            "SET_TITLE" => $arParams['SET_TITLE'],
                            "PARENT_SECTION" => $arParams['PARENT_SECTION'],
                            "PARENT_SECTION_CODE" => $arParams['PARENT_SECTION_CODE'],
                            "INCLUDE_SUBSECTIONS" => $arParams['INCLUDE_SUBSECTIONS'],
                            "DISPLAY_DATE" => $arParams['DISPLAY_DATE'],
                            "DISPLAY_NAME" => $arParams['DISPLAY_NAME'],
                            "DISPLAY_PICTURE" => $arParams['DISPLAY_PICTURE'],
                            "DISPLAY_PREVIEW_TEXT" => $arParams['DISPLAY_PREVIEW_TEXT'],

                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

                            "PAGER_TEMPLATE" => $arParams['PAGER_TEMPLATE'],
                            "DISPLAY_TOP_PAGER" => $arParams['DISPLAY_TOP_PAGER'],
                            "DISPLAY_BOTTOM_PAGER" => $arParams['DISPLAY_BOTTOM_PAGER'],
                            "PAGER_TITLE" => $arParams['PAGER_TITLE'],
                            "PAGER_SHOW_ALWAYS" => $arParams['PAGER_SHOW_ALWAYS'],
                            "PAGER_DESC_NUMBERING" => $arParams['PAGER_DESC_NUMBERING'],
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
                            "PAGER_SHOW_ALL" => $arParams['PAGER_SHOW_ALL'],
                            "PAGER_BASE_LINK_ENABLE" => $arParams['PAGER_BASE_LINK_ENABLE'],

                            "SET_STATUS_404" => $arParams['SET_STATUS_404'],
                            "SHOW_404" => $arParams['SHOW_404'],

                            "LAZY_LOAD" => $arParams['LAZY_LOAD'],
//        "ITEM_TEMPLATE" => $crosssell['ITEM_TEMPLATE'],
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
	"crosssell_news",
	array(
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => $arParams["AJAX_OPTION_ADDITIONAL"],
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"BACKGROUND_IMAGE" => $arParams["BACKGROUND_IMAGE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_TYPE" => "A",
		"COLLECTION_ID" => "1",
		"COMPATIBLE_MODE" => "N",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => $arParams["DETAIL_URL"],
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"FROM_COMPLEX" => $arResult["FROM_COMPLEX"],
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => $arParams["OFFERS_FIELD_CODE"],
			2 => "",
		),
		"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
		"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(),
		"PRICE_VAT_INCLUDE" => "N",
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_TEMPLATE" => "",
		"SECTION_URL" => $arParams["SECTION_URL"],
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"SHOW_SLIDER" => $arParams["SHOW_SLIDER"],
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
		"PRODUCT_DISPLAY_MODE" => $arParams["PRODUCT_DISPLAY_MODE"],
		"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
		"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
		"USE_VOTE_RATING" => $arParams["USE_VOTE_RATING"],
		"COMPARE_PATH" => $arParams["COMPARE_PATH"],
		"COMPARE_NAME" => $arParams["COMPARE_PATH"],
		"USE_COMPARE_LIST" => $arParams["USE_COMPARE_LIST"],
		"ACTION_PRODUCTS" => array(
		),
		"VARIANT_LIST_VIEW" => "ADMIN",
		"COMPONENT_TEMPLATE" => ""
	),
	false
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

    function changeTab (btn) {
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
