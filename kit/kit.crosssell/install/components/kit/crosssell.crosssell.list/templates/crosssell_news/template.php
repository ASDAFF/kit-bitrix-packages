<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
// FILTER // ------------------------------------------------------------------------------------------------------------------------------------------------------------------
global ${$arParams["FILTER_NAME"]};

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (isset($arParams['SECTION_TEMPLATE']) && (strlen($arParams['SECTION_TEMPLATE']) > 0)){
    $sectionTemplate = $arParams['SECTION_TEMPLATE'];
} else {
    $sectionTemplate = '';
}

//if works in section mode (from complex component)
if ( is_array($arResult['CROSSSELL_ARRAY']))
{
    $isAjax = $arParams['AJAX_MODE'];
    if ($arParams['SHOW_TABS'] == 'Y')
    {
        $crosssells = $arResult['CROSSSELL_ARRAY'];
        $container = "crosssells_".$this->randString();
        ?>
        <section class="catalog_section_block catalog_section_block_tabs">
            <ul id="<?=$container?>" class="tabs_sale_block__caption">
                <?
                $i = 0;
                $collectionParameters = array();
                foreach ($crosssells as $tabId => $crosssell)
                {
                    $collectionParameters[$tabId]['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
                    $collectionParameters[$tabId]['FROM_COMPLEX'] = $arParams['FROM_COMPLEX'];
                    $collectionParameters[$tabId]['SECTION_ID'] = $sectionId;
                    if ($isAjax == 'Y') {
                        ?>
                        <li class="collection-<?= $tabId; ?><?= ($i == 0) ? " active collection-loaded" : '' ?>"
                            data-collection-id="<? echo $tabId ?>"><?= $crosssell['NAME'] ?></li>
                        <?
                    } else {
                        ?>
                        <li class="collection-<?= $tabId; ?>  collection-loaded<?= ($i == 0) ? " active" : '' ?>"
                            data-collection-id="<? echo $tabId ?>"><?= $crosssell['NAME'] ?></li>
                        <?
                    }
                    $i++;}?>
            </ul>
            <div class="tabs_sale_block">
                <? if ($crosssells)
                {
                    $i = 0;
                    foreach ($crosssells as $tabId => $crosssell)
                    {
                        ${$arParams["FILTER_NAME"]} = $crosssell['FILTER'];

                        ?>
                        <div class="tabs_sale_block__content <?=($i == 0)?'active ':''?>collection-id-<? echo $tabId?>">
                            <?
                            if (($i == 0) || ($isAjax != 'Y'))
                            {
                                $APPLICATION->IncludeComponent(
                                    "bitrix:news.list",
                                    'crosssell_news',
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
        $componentPath = $arResult['COMPONENT_PATH'];
        ?>
        <script>
            let _this = null;
            $( document ).ready(function() {
                $('ul#<?=$container?>').on('click', 'li:not(.active)', function ()
                {
                    if (!$(this).hasClass("collection-loaded")) {
                        getCrosssell($(this).data("collection-id"));
                        _this = this;
                    } else {
                        toggleTabs(this);
                    }
                });
            });

            function toggleTabs(item) {
                $(item)
                    .addClass('active').siblings().removeClass('active')
                    .closest('.catalog_section_block_tabs').find('.tabs_sale_block__content').removeClass('active').eq($(item).index()).addClass('active');
            }
            function getCrosssell(crosssellId)
            {

                var crosssellTab = $('.collection-id-' + crosssellId);
                var crosssellTabsWrapper = $(crosssellTab).parent();
                createMainLoaderInner(crosssellTabsWrapper);


                BX.ajax({
                    url: <?= CUtil::PhpToJSObject($componentPath, false, true) ?> + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
                    method: 'POST',
                    dataType: 'html',
                    timeout: 60,
                    async: true,
                    data: {
                        params : <?=CUtil::PhpToJSObject($arParams, false, true)?>,
                        siteId: '<?=SITE_ID?>',
                        crosssellId: crosssellId,
                        crosssellArray: <?=CUtil::PhpToJSObject($arResult['CROSSSELL_ARRAY'], false, false)?>
                    },
                    onsuccess: function (result) {
                        toggleTabs(_this);
                        removeMainLoaderInner(crosssellTabsWrapper);


                        crosssellTab.html(result).html(result).promise().done(function () {
                            let crossselTabsID = '#' + document.querySelector('.collection-id-' + crosssellId + ' .recommended-poducts').getAttribute('id');

                            if(document.querySelector(crossselTabsID + '.recommended-products__slider')) {
                                let items = document.querySelectorAll(crossselTabsID + '.product_card__block_item_inner-wrapper');
                                let widthSlide = calcWidthSlide();
                                for (let i = 0; i < items.length; i++) {
                                    items[i].style.width = widthSlide + 'px';
                                }
                                if(document.querySelector(crossselTabsID + '.recommended-products__slider:not(.slick-slider)')) {
                                    inicialSlider(crossselTabsID + '.recommended-products__slider:not(.slick-slider)', settingSlider.itemSetting);
                                }
                            };
                        });
                        if (!crosssellTab.hasClass("collection-loaded")){
                            $('li.collection-' + crosssellId).addClass("collection-loaded");
                        }
                    },

                    onfailure: function(type, e){
                        // on error do nothing
                    }
                });
            }
        </script>

        <?
    } else {
        foreach ($arResult['CROSSSELL_ARRAY'] as $crosssell)
        {
            ${$arParams["FILTER_NAME"]} = $crosssell['FILTER'];
            ?>
            <?
            if ($arResult['SAFE'] && $crosssell['FILTER'])
            {
                $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    'crosssell_news',
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

            }
        }
    }
} else {
    //standard behavior (if called not section mode)
    ${$arParams["FILTER_NAME"]} = $arResult['FILTER'];

    $iblockId = $arResult['IBLOCK_ID'];
    ?>
    <?
    if ($arResult['SAFE'] && $arResult['IBLOCK_ID'])
    {
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            'crosssell_news',
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

    }

}

