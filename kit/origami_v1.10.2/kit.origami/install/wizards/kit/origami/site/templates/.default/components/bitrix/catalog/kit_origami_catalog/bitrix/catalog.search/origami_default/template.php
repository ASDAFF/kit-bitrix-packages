<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use Bitrix\Main\Loader;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if(\KitOrigami::isUseRegions())
{
    if($_SESSION["KIT_REGIONS"]["PRICE_CODE"])
    {
        $arParams['PRICE_CODE'] = $_SESSION["KIT_REGIONS"]["PRICE_CODE"];
        $arParams['~PRICE_CODE'] = $_SESSION["KIT_REGIONS"]["PRICE_CODE"];
    }
    if($_SESSION["KIT_REGIONS"]["STORE"])
    {
        $arParams['STORES'] = $_SESSION["KIT_REGIONS"]["STORE"];
    }
}

$labelProps = unserialize(Config::get('LABEL_PROPS'));
if(!is_array($labelProps)){
    $labelProps = [];
}
$arParams['LABEL_PROPS'] = $labelProps;

$t = Config::get('FILTER_TEMPLATE');
if($t)
{
    $arParams['FILTER_VIEW_MODE'] = $t;
}

$isFilter = true;//($arParams['USE_FILTER'] == 'Y');

if ($isFilter)
{
    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
    );

    if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
        $arResult["VARIABLES"]["SECTION_ID"];
    elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
        $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
    {
        $arCurSection = $obCache->GetVars();
    }
    elseif ($obCache->StartDataCache())
    {
        $arCurSection = array();
        if (Loader::includeModule("iblock"))
        {
            $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->Fetch())
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

                $CACHE_MANAGER->EndTagCache();
            }
            else
            {
                if(!$arCurSection = $dbRes->Fetch())
                    $arCurSection = array();
            }
        }
        $obCache->EndDataCache($arCurSection);
    }
    if (!isset($arCurSection))
        $arCurSection = array();
}
//*******************
if (\Bitrix\Main\Loader::includeModule('kit.seosearch'))
{
    $APPLICATION->IncludeComponent(
        "kit:seo.search",
        "",
        Array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
        )
    );
}

$this->SetViewTarget("search_page_input");

$arElements = $APPLICATION->IncludeComponent(
    "bitrix:search.page",
    "origami_default",
    Array(
        "RESTART" => $arParams["RESTART"],
        "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
        "USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
        "CHECK_DATES" => $arParams["CHECK_DATES"],
        "arrFILTER" => array("iblock_" . $arParams["IBLOCK_TYPE"]),
        "arrFILTER_iblock_" . $arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
        "USE_TITLE_RANK" => "N",
        "DEFAULT_SORT" => "rank",
        "FILTER_NAME" => "",
        "SHOW_WHERE" => "N",
        "arrWHERE" => array(),
        "SHOW_WHEN" => "N",
        "PAGE_RESULT_COUNT" => $arParams["PAGE_RESULT_COUNT"],
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "N",
    ),
    $component,
    array('HIDE_ICONS' => 'Y')
);

$this->EndViewTarget();
?>
<?if($request->get('ajaxFilter') != 'Y'):?>
<div id="comp_catalog_content" class="puzzle_block block_main_left_menu catalog-wrapper <?= Config::get('MENU_SIDE') ?>">
<?endif;?>
    <div class="block_main_left_menu__container mo-main">
    <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "origami_filter", [
            "ROOT_MENU_TYPE" => "left",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_TIME" => "36000000",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => [
            ],
            "MAX_LEVEL" => "2",
            "CHILD_MENU_TYPE" => "left",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N",
            'CACHE_SELECTED_ITEMS' => false,
        ],
            false
        );

if (!empty($arElements) && is_array($arElements))
{

    $arIblock = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);

    if($arIblock)
    {
        $arSku = \CCatalogSku::getProductList($arElements);

        $arSkuElements = array();

        if($arSku)
        {
            foreach($arSku as $s=> $sku)
            {
                $arSkuElements[] = $sku["ID"];
            }
        }

        $arElements = array_unique(array_merge($arElements, $arSkuElements));
    }

    $arSections = array();

    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "=ID" => $arElements);

    $useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;

    if ($useRegion && $_SESSION['KIT_REGIONS']['ID'])
    {
        $arFilter[] = array(
            "LOGIC" => "OR",
            array("PROPERTY_REGIONS" => $_SESSION['KIT_REGIONS']['ID']),
            array("PROPERTY_REGIONS" => false)
        );
    }

    $arSelect = array("ID", "IBLOCK_SECTION_ID");

    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

    while($arRes = $res->Fetch())
    {
        $arSections[$arRes["IBLOCK_SECTION_ID"]] = $arRes["IBLOCK_SECTION_ID"];
    }
    global $searchFilter;
    $searchFilter = array(
        "ID" => $arElements,
    );

    if($arParams['FILTER_TEMPLATE'] == 'VERTICAL')
    {
        $APPLICATION->IncludeComponent(
            "kit:catalog.smart.filter",
            "origami_vertical",
            [
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => "",
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                "PRICE_CODE" => $arParams["~PRICE_CODE"],
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SAVE_IN_SESSION" => "N",
                "XML_EXPORT" => "N",
                "SECTION_TITLE" => "NAME",
                "SECTION_DESCRIPTION" => "DESCRIPTION",
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                "SEF_MODE" => $arParams["SEF_MODE"],
                "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
                "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                "SHOW_ALL_WO_SECTION" => 'Y',
                "SECTIONS_ID" => $arSections,
                "ELEMENTS_ID" => $arElements,
                "SHOW_SECTIONS" => "Y",
                'FILTER_MODE' => Config::get('FILTER_MODE')
            ],
            $component,
            ['HIDE_ICONS' => 'Y']
        );

        if(\Bitrix\Main\Loader::includeModule('kit.seosearch'))
        {
            $APPLICATION->ShowViewContent('kit_seosearch_add_desc');
        }


    }
    ?>
    </div>

    <div class="block_main_left_menu__content active">
    <?
        $APPLICATION->IncludeComponent(
            'bitrix:breadcrumb',
            'origami_default',
            [
                "START_FROM" => "0",
                "PATH"       => "",
                "SITE_ID"    => "-",
            ],
            false,
            [
                'HIDE_ICONS' => 'N',
            ]
        );

        $APPLICATION->ShowViewContent("search_page_input");

        if($arParams['FILTER_TEMPLATE'] == 'HORIZONTAL')
        {
            $APPLICATION->IncludeComponent(
                "kit:catalog.smart.filter",
                "origami_horizontal",
                [
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => "",
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                    "PRICE_CODE" => $arParams["~PRICE_CODE"],
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SAVE_IN_SESSION" => "N",
                    "XML_EXPORT" => "N",
                    "SECTION_TITLE" => "NAME",
                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    "SEF_MODE" => $arParams["SEF_MODE"],
                    "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
                    "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                    "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                    "SHOW_ALL_WO_SECTION" => 'Y',
                    "SECTIONS_ID" => $arSections,
                    "ELEMENTS_ID" => $arElements,
                    'FILTER_MODE' => Config::get('FILTER_MODE')
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            );
        }

        if(\Bitrix\Main\Loader::includeModule('kit.seosearch'))
        {
            $APPLICATION->IncludeComponent(
                "kit:seo.search.tags",
                "",
                Array(
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CNT_TAGS" => "",
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                    "SORT" => "NAME",
                    "SORT_ORDER" => "asc"
                )
            );

            $APPLICATION->ShowViewContent('kit_seosearch_top_desc');
        }
        ?>

        <div class="mobile_filter_form"></div>
        <div class="panel_filter_sort">
            <div class="mobile_filter_btn">
                 <svg class="icon-filter-mobile" width="12" height="12">
                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_filter_mobile"></use>
                 </svg>
                <span><?=Loc::getMessage("MOBILE_FILTER_TITLE")?></span>
                <span class="mobile_filter-selected_number"></span>
            </div>

             <script>
                            function getTitles() {
                                let titles = [];
                                titles[0] = "<?= GetMessage("CT_BCSF_USED_NOW_TITLE") ?>";
                                titles[1] = "<?= GetMessage("CT_BCSF_NOT_USED_TITLE") ?>";
                                titles[2] = "<?= GetMessage("CT_BCSF_FILTER_SHOW_NEW") ?>";
                                titles[3] = "<?= GetMessage("CT_BCSF_FILTER_NUMBER1") ?>";
                                titles[4] = "<?= GetMessage("CT_BCSF_FILTER_NUMBER2") ?>";
                                titles[5] = "<?= GetMessage("CT_BCSF_FILTER_NUMBER3") ?>";
                                titles[6] = "<?= GetMessage("CT_BCSF_SET_FILTER") ?>";
                                return titles;
                            };
             </script>
            <?
            if(Loader::includeModule('kit.origami')){
                $sort = array();
                $sort = $APPLICATION->IncludeFile( SITE_DIR . "include/kit_origami/sort/sort.php", Array(), Array());
            }
        ?>
        </div>
        <?

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "origami_default",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "ELEMENT_SORT_FIELD" => $sort['by']['by'],
                "ELEMENT_SORT_ORDER" => strtoupper($sort['by']['order']),
                "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                "PAGE_ELEMENT_COUNT" => $sort['limit']['limit'],
                "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                "PROPERTY_CODE_MOBILE" => $arParams["PROPERTY_CODE_MOBILE"],
                "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                "OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                "OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
                "SECTION_URL" => $arParams["SECTION_URL"],
                "DETAIL_URL" => $arParams["DETAIL_URL"],
                "BASKET_URL" => $arParams["BASKET_URL"],
                "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
                "PRICE_CODE" => $arParams["~PRICE_CODE"],
                "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                "FILL_ITEM_ALL_PRICES" => $arParams["FILL_ITEM_ALL_PRICES"],
                "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                "USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
                "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "SECTION_ID" => "",
                "SECTION_CODE" => "",
                "SECTION_USER_FIELDS" => array(),
                "INCLUDE_SUBSECTIONS" => "Y",
                "SHOW_ALL_WO_SECTION" => "Y",
                "META_KEYWORDS" => "",
                "META_DESCRIPTION" => "",
                "BROWSER_TITLE" => "",
                "ADD_SECTIONS_CHAIN" => "N",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "N",

                'LABEL_PROP' => $arParams['LABEL_PROP'],
                'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
                'PRODUCT_ROW_VARIANTS' => $arParams['PRODUCT_ROW_VARIANTS'],
                'ENLARGE_PRODUCT' => $arParams['ENLARGE_PRODUCT'],
                'ENLARGE_PROP' => $arParams['ENLARGE_PROP'],
                'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
                'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
                'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],

                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
                'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
                'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
                'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
                'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
                'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
                'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
                'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
                'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],

                'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],

                'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
                'COMPARE_PATH' => $arParams['COMPARE_PATH'],
                'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                'USE_COMPARE_LIST' => $arParams['USE_COMPARE_LIST'],
                'USE_VOTE_RATING' => "Y", //$arParams['DETAIL_USE_VOTE_RATING'],
                "TEMPLATE_LIST_VIEW_DEFAULT" => $sort["view"]

            ),
            $arResult["THEME_COMPONENT"],
            array('HIDE_ICONS' => 'Y')
        );

        if(\Bitrix\Main\Loader::includeModule('kit.seosearch'))
        {
            $APPLICATION->ShowViewContent('kit_seosearch_bottom_desc');
        }
        ?>

    </div>
<?if($request->get('ajaxFilter') != 'Y'):?>
</div>
<?endif;?>
    <?
}
elseif (is_array($arElements))
{
    ?>

<!--    <div class="puzzle_block">-->
    </div>
    <div class="block_main_left_menu__content active">
    <?
    $APPLICATION->IncludeComponent(
        'bitrix:breadcrumb',
        'origami_default',
        [
            "START_FROM" => "0",
            "PATH"       => "",
            "SITE_ID"    => "-",
        ],
        false,
        [
            'HIDE_ICONS' => 'N',
        ]
    );

    $APPLICATION->ShowViewContent("search_page_input");
	//echo GetMessage("CT_BCSE_NOT_FOUND");
	?>
    </div>
</div>

    <?
}
else //sent form was empty
{
    ?>
    </div>
    <div class="block_main_left_menu__content active no-padding empty_search">
    <?
    $APPLICATION->IncludeComponent(
        'bitrix:breadcrumb',
        'origami_default',
        [
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "-",
        ],
        false,
        [
            'HIDE_ICONS' => 'N',
        ]
    );

    $APPLICATION->ShowViewContent("search_page_input");
    ?>
    </div>
</div>

    <?
}
?>
