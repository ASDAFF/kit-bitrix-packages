<?php

use Kit\Origami\Helper\Config, \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
\Bitrix\Main\Loader::includeModule('currency');
CJSCore::Init(array('currency'));

Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/news/kit_origami_news/style.css");

$this->setFrameMode(true);

global $arNewsProductsFilter;


$ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "origami_news",
    [
        "DISPLAY_DATE"              => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME"              => $arParams["DISPLAY_NAME"],
        "DISPLAY_PICTURE"           => $arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT"      => $arParams["DISPLAY_PREVIEW_TEXT"],
        "IBLOCK_TYPE"               => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID"                 => $arParams["IBLOCK_ID"],
        "FIELD_CODE"                => $arParams["DETAIL_FIELD_CODE"],
        "PROPERTY_CODE"             => $arParams["DETAIL_PROPERTY_CODE"],
        "DETAIL_URL"                => $arResult["FOLDER"]
            .$arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL"               => $arResult["FOLDER"]
            .$arResult["URL_TEMPLATES"]["section"],
        "META_KEYWORDS"             => $arParams["META_KEYWORDS"],
        "META_DESCRIPTION"          => $arParams["META_DESCRIPTION"],
        "BROWSER_TITLE"             => $arParams["BROWSER_TITLE"],
        "SET_CANONICAL_URL"         => $arParams["DETAIL_SET_CANONICAL_URL"],
        "DISPLAY_PANEL"             => $arParams["DISPLAY_PANEL"],
        "SET_LAST_MODIFIED"         => $arParams["SET_LAST_MODIFIED"],
        "SET_TITLE"                 => $arParams["SET_TITLE"],
        "MESSAGE_404"               => $arParams["MESSAGE_404"],
        "SET_STATUS_404"            => $arParams["SET_STATUS_404"],
        "SHOW_404"                  => $arParams["SHOW_404"],
        "FILE_404"                  => $arParams["FILE_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "ADD_SECTIONS_CHAIN"        => $arParams["ADD_SECTIONS_CHAIN"],
        "ACTIVE_DATE_FORMAT"        => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
        "CACHE_TYPE"                => $arParams["CACHE_TYPE"],
        "CACHE_TIME"                => $arParams["CACHE_TIME"],
        "CACHE_GROUPS"              => $arParams["CACHE_GROUPS"],
        "USE_PERMISSIONS"           => $arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS"         => $arParams["GROUP_PERMISSIONS"],
        "DISPLAY_TOP_PAGER"         => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER"      => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE"               => $arParams["DETAIL_PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS"         => "N",
        "PAGER_TEMPLATE"            => $arParams["DETAIL_PAGER_TEMPLATE"],
        "PAGER_SHOW_ALL"            => $arParams["DETAIL_PAGER_SHOW_ALL"],
        "CHECK_DATES"               => $arParams["CHECK_DATES"],
        "ELEMENT_ID"                => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE"              => $arResult["VARIABLES"]["ELEMENT_CODE"],
        "SECTION_ID"                => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE"              => $arResult["VARIABLES"]["SECTION_CODE"],
        "IBLOCK_URL"                => $arResult["FOLDER"]
            .$arResult["URL_TEMPLATES"]["news"],
        "USE_SHARE"                 => $arParams["USE_SHARE"],
        "SHARE_HIDE"                => $arParams["SHARE_HIDE"],
        "SHARE_TEMPLATE"            => $arParams["SHARE_TEMPLATE"],
        "SHARE_HANDLERS"            => $arParams["SHARE_HANDLERS"],
        "SHARE_SHORTEN_URL_LOGIN"   => $arParams["SHARE_SHORTEN_URL_LOGIN"],
        "SHARE_SHORTEN_URL_KEY"     => $arParams["SHARE_SHORTEN_URL_KEY"],
        "ADD_ELEMENT_CHAIN"         => (isset($arParams["ADD_ELEMENT_CHAIN"])
            ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
        'STRICT_SECTION_CHECK'      => (isset($arParams['STRICT_SECTION_CHECK'])
            ? $arParams['STRICT_SECTION_CHECK'] : ''),
    ],
    $component
);


    $APPLICATION->IncludeComponent(
	"kit:crosssell.crosssell.list",
	"origami_default",
    array(
            "ACTION_VARIABLE" => "action",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "ADD_SECTIONS_CHAIN" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "BACKGROUND_IMAGE" => "-",
            "BASKET_URL" => "/personal/basket.php",
            "BROWSER_TITLE" => "-",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "COMPATIBLE_MODE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "CROSSSELL_LIST" => array('e9'),
            "DETAIL_URL" => "",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "DISPLAY_COMPARE" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_SORT_FIELD" => "sort",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_ORDER2" => "desc",
            "FILTER_NAME" => "arrFilter",
            "HIDE_NOT_AVAILABLE" => "N",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "IBLOCK_ID"                 => Config::get("IBLOCK_ID"),
            "IBLOCK_TYPE"               => Config::get("IBLOCK_TYPE"),
            "INCLUDE_SUBSECTIONS" => "Y",
            "LINE_ELEMENT_COUNT" => "4",
            "MESSAGE_404" => "",
            "META_DESCRIPTION" => "-",
            "META_KEYWORDS" => "-",
            "OFFERS_SORT_FIELD"      => "sort",
            "OFFERS_SORT_ORDER"      => "id",
            "OFFERS_SORT_FIELD2"     => "desc",
            "OFFERS_SORT_ORDER2"     => "desc",
            "OFFERS_LIMIT"           => 0,
            'OFFER_TREE_PROPS' => array(
                1 => "PROTSESSOR",
                2 => "OBEM_OPERATICHNOY_PAMYATI",
                3 => "OBEM_PAMYATI",
                4 => "RAZMER",
                5 => "CHASTOTA_PROTSESSORA",
                6 => "TIP_VIDEOKARTY",
                7 => "TSVET",
                8 => "KOLICHESTVO_YADER_PROTSESORA",
                9 => "OBEM_VIDEOPAMYATI",
                10 => "TSVET_1",
                11 => "USTANOVLENNAYA_OS",
                12 => "CML2_MANUFACTURER",
            ),
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "������",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRICE_CODE" => \KitOrigami::GetComponentPrices(["BASE","OPT","SMALL_OPT"]),
            "OFFERS_FIELD_CODE"      => [
                0 => "NAME",
                1 => "PREVIEW_PICTURE",
                2 => "DETAIL_PICTURE",
                3 => "DETAIL_PAGE_URL",
            ],
            "OFFERS_PROPERTY_CODE"   => [
                1  => "CML2_BAR_CODE",
                2  => "CML2_ARTICLE",
                5  => "CML2_BASE_UNIT",
                7  => "MORE_PHOTO",
                8  => "FILES",
                9  => "CML2_MANUFACTURER",
                10 => "PROTSESSOR",
                11 => "CHASTOTA_PROTSESSORA",
                12 => "KOLICHESTVO_YADER_PROTSESORA",
                13 => "OBEM_OPERATICHNOY_PAMYATI",
                14 => "TIP_VIDEOKARTY",
                15 => "OBEM_VIDEOPAMYATI",
                16 => "USTANOVLENNAYA_OS",
                17 => "OBEM_PAMYATI",
                18 => "RAZMER",
                19 => "TSVET",
                20 => "TSVET_1",
                21 => "VIDEOKARTA",
            ],
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_ID" => $ElementID,
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_PROPERTIES" => array(
            ),
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "SECTION_ID" => $_REQUEST["SECTION_ID"],
            "SECTION_URL" => "",
            "SEF_MODE" => "N",
            "SET_BROWSER_TITLE" => "Y",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "Y",
            "SHOW_404" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "SHOW_TABS" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "USE_PRICE_COUNT" => "N",
            "USE_PRODUCT_QUANTITY" => "N",
            "COMPONENT_TEMPLATE" => ".default",
            "SECTION_MODE" => "Y",
            "INTERRUPT_MODE" => "N",
            "SECTION_TEMPLATE" => "origami_section",
            "SHOW_SLIDER" => "N",
            "PAGE_ELEMENT_COUNT" => "5",
            "PRODUCT_DISPLAY_MODE" => "Y",
            'ADD_PICT_PROP'               => "MORE_PHOTO",
            'OFFER_ADD_PICT_PROP'         => "MORE_PHOTO",
            'PRODUCT_SUBSCRIPTION'        => "Y",
            'SHOW_DISCOUNT_PERCENT'       => "Y",
            'SHOW_OLD_PRICE'              => "Y",
            'SHOW_MAX_QUANTITY'           => "Y",
            'USE_VOTE_RATING'             => "Y",
            'COMPARE_PATH'                => Config::get('COMPARE_PAGE'),
            'COMPARE_NAME'                => "CATALOG_COMPARE_LIST",
            'USE_COMPARE_LIST'            => 'Y',
            'SECTION_NAME' => Loc::getMessage('SECT_NEWS_BLOCK_NAME')
        ),
	false
);

$this->SetViewTarget('news_detail');
?>

<div class="detail_blog__banner">
    <?
    global $bannerFilter;
    $bannerFilter = [
        'ACTIVE' => 'Y',
        'PROPERTY_BANNER_TYPE' => Config::getBanner(['INNER']),
        array(
            "LOGIC" => "OR",
            array("PROPERTY_SHOW_SECTIONS" => $APPLICATION->GetCurPage(false)),
            array("PROPERTY_SHOW_SECTIONS" => false)
        ),
    ];
    $useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
    if ($useRegion && $_SESSION['KIT_REGIONS']['ID']) {
        $bannerFilter['PROPERTY_REGIONS'] = [
            false,
            $_SESSION['KIT_REGIONS']['ID']
        ];
    }
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "origami_inner_banner",
        [
            "IBLOCK_TYPE"                     => Config::get('IBLOCK_TYPE_BANNERS'),
            "IBLOCK_ID"                       => Config::get('IBLOCK_ID_BANNERS'),
            "NEWS_COUNT"                      => 3,
            "SORT_BY1"                        => 'sort',
            "SORT_ORDER1"                     => 'desc',
            "SORT_BY2"                        => 'id',
            "SORT_ORDER2"                     => 'asc',
            "FIELD_CODE"                      => [
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE',
            ],
            "PROPERTY_CODE"                   => [
                'BUTTON_TEXT',
                'URL',
                'NEW_TAB',
            ],
            "DETAIL_URL"                      => $arResult["FOLDER"]
                .$arResult["URL_TEMPLATES"]["detail"],
            "SECTION_URL"                     => $arResult["FOLDER"]
                .$arResult["URL_TEMPLATES"]["section"],
            "IBLOCK_URL"                      => $arResult["FOLDER"]
                .$arResult["URL_TEMPLATES"]["news"],
            "DISPLAY_PANEL"                   => $arParams["DISPLAY_PANEL"],
            "SET_TITLE"                       => 'N',
            "SET_LAST_MODIFIED"               => $arParams["SET_LAST_MODIFIED"],
            "MESSAGE_404"                     => $arParams["MESSAGE_404"],
            "SET_STATUS_404"                  => $arParams["SET_STATUS_404"],
            "SHOW_404"                        => $arParams["SHOW_404"],
            "FILE_404"                        => $arParams["FILE_404"],
            "INCLUDE_IBLOCK_INTO_CHAIN"       => 'N',
            "CACHE_TYPE"                      => $arParams["CACHE_TYPE"],
            "CACHE_TIME"                      => $arParams["CACHE_TIME"],
            "CACHE_FILTER"                    => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS"                    => $arParams["CACHE_GROUPS"],
            "DISPLAY_TOP_PAGER"               => $arParams["DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER"            => $arParams["DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE"                     => $arParams["PAGER_TITLE"],
            "PAGER_TEMPLATE"                  => $arParams["PAGER_TEMPLATE"],
            "PAGER_SHOW_ALWAYS"               => $arParams["PAGER_SHOW_ALWAYS"],
            "PAGER_DESC_NUMBERING"            => $arParams["PAGER_DESC_NUMBERING"],
            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
            "PAGER_SHOW_ALL"                  => $arParams["PAGER_SHOW_ALL"],
            "PAGER_BASE_LINK_ENABLE"          => $arParams["PAGER_BASE_LINK_ENABLE"],
            "PAGER_BASE_LINK"                 => $arParams["PAGER_BASE_LINK"],
            "PAGER_PARAMS_NAME"               => $arParams["PAGER_PARAMS_NAME"],
            "DISPLAY_DATE"                    => $arParams["DISPLAY_DATE"],
            "DISPLAY_NAME"                    => "Y",
            "DISPLAY_PICTURE"                 => $arParams["DISPLAY_PICTURE"],
            "DISPLAY_PREVIEW_TEXT"            => $arParams["DISPLAY_PREVIEW_TEXT"],
            "PREVIEW_TRUNCATE_LEN"            => $arParams["PREVIEW_TRUNCATE_LEN"],
            "ACTIVE_DATE_FORMAT"              => $arParams["LIST_ACTIVE_DATE_FORMAT"],
            "USE_PERMISSIONS"                 => $arParams["USE_PERMISSIONS"],
            "GROUP_PERMISSIONS"               => $arParams["GROUP_PERMISSIONS"],
            "FILTER_NAME"                     => 'bannerFilter',
            "HIDE_LINK_WHEN_NO_DETAIL"        => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
            "CHECK_DATES"                     => $arParams["CHECK_DATES"],
        ],
        $component
    );
    ?>
</div>
<?
$this->EndViewTarget();
?>
