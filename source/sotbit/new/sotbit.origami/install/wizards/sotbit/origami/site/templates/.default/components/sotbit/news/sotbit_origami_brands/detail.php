
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;

CJSCore::Init(array('currency'));
$this->setFrameMode(true);

Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/sotbit/news/sotbit_origami_brands/style.css");

global $brandFilter;
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;

global $brandFilter;
$brandFilter = array(
    "=ID" => false,
);
$iblockProd = \Sotbit\Origami\Helper\Config::get('IBLOCK_ID');
$arSections = $arElements = $arSectionsInfo = array();

$t = Config::get('FILTER_TEMPLATE');
if($t)
{
    $arParams['FILTER_VIEW_MODE'] = $t;
}

if(isset($arResult['VARIABLES']['ELEMENT_CODE']) && $arResult['VARIABLES']['ELEMENT_CODE'] && $arParams['BRAND_PROP_CODE'])
{

    $arFilter = array("IBLOCK_ID" => $iblockProd, "ACTIVE" => "Y", 'PROPERTY_'.$arParams['BRAND_PROP_CODE'].'.CODE' => $arResult['VARIABLES']['ELEMENT_CODE']);
    $arSelect = array("ID", "IBLOCK_SECTION_ID");

    if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID'])
    {
        $arFilter[] = array(
            "LOGIC" => "OR",
            array("PROPERTY_REGIONS" => $_SESSION['SOTBIT_REGIONS']['ID']),
            array("PROPERTY_REGIONS" => false)
        );
    }

    $cacheId = md5(serialize($arFilter));

    $cache = \Bitrix\Main\Data\Cache::createInstance();

    if ($cache->initCache(36000000, $cacheId, '/sotbit.origami')) {
        $arData = $cache->getVars();
    } elseif ($cache->startDataCache()) {

        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

        while($arRes = $res->Fetch())
        {
            $arSections[$arRes["IBLOCK_SECTION_ID"]] = $arRes["IBLOCK_SECTION_ID"];
            $arElements[] = $arRes["ID"];
        }

        if($arElements)
        {
            $arData["arElements"] = $arElements;
            $arData["arSections"] = $arSections;
        }


        $cache->endDataCache($arData);
    }

    $arElements = $arData["arElements"];
    $arSections = $arData["arSections"];

    if($arElements)
        $brandFilter = array(
            "ID" => $arElements,
        );

}
?>
<div class="brand-detail main-container">
	<div class="block_main_left_menu">
		<div class="block_main_left_menu__container mo-main">
            <?
            $APPLICATION->IncludeComponent(
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
            ?>
        <div class="d-xs-none"></div>
        <?
        if (\SotbitOrigami::isUseRegions()) {
            if ($_SESSION["SOTBIT_REGIONS"]["PRICE_CODE"]) {
                $arParams['~PRICE_CODE']
                    = $_SESSION["SOTBIT_REGIONS"]["PRICE_CODE"];
            }
            if ($_SESSION["SOTBIT_REGIONS"]["STORE"]) {
                $arParams['STORES'] = $_SESSION["SOTBIT_REGIONS"]["STORE"];
            }
        }
        if($arParams['FILTER_VIEW_MODE'] == "VERTICAL")
            $APPLICATION->IncludeComponent(
                "sotbit:catalog.smart.filter",
                "origami_vertical",
                [
                    "IBLOCK_TYPE"         => Config::get("IBLOCK_TYPE"),
                    "IBLOCK_ID"           => Config::get("IBLOCK_ID"),
                    "SECTION_ID"          => '',
                    "SECTIONS_ID" => $arSections,
                    "ELEMENTS_ID" => $arElements,
                    "FILTER_NAME"         => "brandFilter",
                    "PRICE_CODE"          => $arParams["~PRICE_CODE"],
                    "CACHE_TYPE"          => $arParams["CACHE_TYPE"],
                    "CACHE_TIME"          => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS"        => $arParams["CACHE_GROUPS"],
                    "SAVE_IN_SESSION"     => "N",
                    "FILTER_VIEW_MODE"    => "",
                    "XML_EXPORT"          => "N",
                    "SECTION_TITLE"       => "NAME",
                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                    'HIDE_NOT_AVAILABLE'  => Config::get('HIDE_NOT_AVAILABLE'),
                    'CONVERT_CURRENCY'    => "N",
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    "SEF_MODE"            => "Y",
                    "INSTANT_RELOAD"      => "N",
	                "SHOW_ALL_WO_SECTION" => 'N',
	                "SEF_RULE" => $arResult["FOLDER"].$arResult['VARIABLES']['ELEMENT_CODE'].'/'.$arResult["URL_TEMPLATES"]["smart_filter"],
                    "SMART_FILTER_PATH"   => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                    "SHOW_SECTIONS" => "Y"
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            );

        if (\Bitrix\Main\Loader::includeModule('sotbit.seometa')) {
            $APPLICATION->IncludeComponent(
                'sotbit:seo.meta',
                'origami_default',
                [
                    'FILTER_NAME' => "brandFilter",
                    'SECTION_ID'  => '',
                    'CACHE_TYPE'  => $arParams['CACHE_TYPE'],
                    'CACHE_TIME'  => $arParams['CACHE_TIME'],
                ]);
        }
        ?>
    </div>
		<div class="block_main_left_menu__content active">
			<div class="row personal_block_component">
				<div class="col-sm-12">
                    <?php
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
                    ?>
				</div>
				<div class="col-sm-12 personal_title_block">
					<h1>
                        <?php $APPLICATION->ShowTitle(false); ?>
					</h1>
				</div>
			</div>
            <?
            $ElementID = $APPLICATION->IncludeComponent(
                "bitrix:news.detail",
                "origami_brand_detail_2",
                [
                    "DISPLAY_DATE"              => $arParams["DISPLAY_DATE"],
                    "DISPLAY_NAME"              => $arParams["DISPLAY_NAME"],
                    "DISPLAY_PICTURE"           => $arParams["DISPLAY_PICTURE"],
                    "DISPLAY_PREVIEW_TEXT"      => $arParams["DISPLAY_PREVIEW_TEXT"],
                    "IBLOCK_TYPE"               => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID"                 => $arParams["IBLOCK_ID"],
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
                    "FIELD_CODE"              => $arParams["DETAIL_FIELD_CODE"],
                    "SECTION_ID"                => $arResult["VARIABLES"]["SECTION_ID"],
                    "SECTION_CODE"              => $arResult["VARIABLES"]["SECTION_CODE"],
                    "IBLOCK_URL"                => $arResult["FOLDER"]
                        .$arResult["URL_TEMPLATES"]["news"],
                    "USE_SHARE"                 => $arParams["USE_SHARE"],
                    'BRAND_PROP_CODE' => $arParams['BRAND_PROP_CODE'],
                    "SHARE_HIDE"                => $arParams["SHARE_HIDE"],
                    "SHARE_TEMPLATE"            => $arParams["SHARE_TEMPLATE"],
                    "SHARE_HANDLERS"            => $arParams["SHARE_HANDLERS"],
                    "SHARE_SHORTEN_URL_LOGIN"   => $arParams["SHARE_SHORTEN_URL_LOGIN"],
                    "SHARE_SHORTEN_URL_KEY"     => $arParams["SHARE_SHORTEN_URL_KEY"],
                    "ADD_ELEMENT_CHAIN"         => (isset($arParams["ADD_ELEMENT_CHAIN"])
                        ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
                ],
                $component
            );

            if($arParams['FILTER_VIEW_MODE'] == "HORIZONTAL")
                $APPLICATION->IncludeComponent(
                    "sotbit:catalog.smart.filter",
                    "origami_horizontal",
                    [
                        "IBLOCK_TYPE"         => Config::get("IBLOCK_TYPE"),
                        "IBLOCK_ID"           => Config::get("IBLOCK_ID"),
                        "SECTION_ID"          => '',
                        "SECTIONS_ID" => $arSections,
                        "ELEMENTS_ID" => $arElements,
                        "FILTER_NAME"         => "brandFilter",
                        "PRICE_CODE"          => $arParams["~PRICE_CODE"],
                        "CACHE_TYPE"          => $arParams["CACHE_TYPE"],
                        "CACHE_TIME"          => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS"        => $arParams["CACHE_GROUPS"],
                        "SAVE_IN_SESSION"     => "N",
                        "FILTER_VIEW_MODE"    => $arParams['FILTER_VIEW_MODE'],
                        "XML_EXPORT"          => "N",
                        "SECTION_TITLE"       => "NAME",
                        "SECTION_DESCRIPTION" => "DESCRIPTION",
                        'HIDE_NOT_AVAILABLE'  => Config::get('HIDE_NOT_AVAILABLE'),
                        'CONVERT_CURRENCY'    => "N",
                        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                        "SEF_MODE"            => "Y",
                        "INSTANT_RELOAD"      => "N",
                        "SHOW_ALL_WO_SECTION" => 'N',
                        "SEF_RULE" => $arResult["FOLDER"].$arResult['VARIABLES']['ELEMENT_CODE'].'/'.$arResult["URL_TEMPLATES"]["smart_filter"],
                        "SMART_FILTER_PATH"   => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                        "SHOW_SECTIONS" => "Y"
                    ],
                    $component,
                    ['HIDE_ICONS' => 'Y']
                );

            ?>
			<div class="mobile_filter_form"></div>
			<div class="panel_filter_sort">
				<div class="mobile_filter_btn">
                 <svg class="icon-filter-mobile" width="12" height="12">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_filter_mobile"></use>
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
                if(Loader::includeModule('sotbit.origami')){
                    $sort = array();
                    $sort = $APPLICATION->IncludeFile( SITE_DIR . "include/sotbit_origami/sort/sort.php", Array(), Array());
                }
                //include \Sotbit\Origami\Helper\Config::getChunkPath('sort');?>
			</div>
			<?
            $intSectionID = $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "origami_default",
                [
                    "IBLOCK_TYPE"               => Config::get('IBLOCK_TYPE'),
                    "IBLOCK_ID"                 => Config::get('IBLOCK_ID'),
                    "ELEMENT_SORT_FIELD"        => $sort['by']['by'],
                    "ELEMENT_SORT_ORDER"        => strtoupper($sort['by']['order']),
                    "ELEMENT_SORT_FIELD2"       => "id",
                    "ELEMENT_SORT_ORDER2"       => "desc",
                    "FIELD_CODE"                => [
                        0 => "NAME",
                        1 => "PREVIEW_PICTURE",
                        2 => "DETAIL_PICTURE",
                        3 => "",
                    ],
                    "PROPERTY_CODE"             => [
                        0   => "",
                        1   => "CML2_BAR_CODE",
                        2   => "CML2_ARTICLE",
                        3   => "MORE_PHOTO",
                        4   => "FILES",
                        5   => "CML2_MANUFACTURER",
                    ],
                    "INCLUDE_SUBSECTIONS"       => "Y",
                    "BASKET_URL"                => Config::get('BASKET_PAGE'),
                    "ACTION_VARIABLE"           => "action",
                    "PRODUCT_ID_VARIABLE"       => "id",
                    "SECTION_ID_VARIABLE"       => "SECTION_ID",
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                    "PRODUCT_PROPS_VARIABLE"    => "prop",
                    "FILTER_NAME"               => "brandFilter",
                    "CACHE_FILTER"              => "Y",
                    "CACHE_GROUPS"              => "Y",
                    "CACHE_TIME"                => "36000000",
                    "CACHE_TYPE"                => "A",
                    "SET_TITLE"                 => 'N',
                    "MESSAGE_404"               => "",
                    "SET_STATUS_404"            => "Y",
                    "SHOW_404"                  => "N",
                    "DISPLAY_COMPARE"           => Config::get('SHOW_COMPARE'),
                    "PAGE_ELEMENT_COUNT"        => $sort['limit']['limit'],
                    "LINE_ELEMENT_COUNT"        => Config::get("CNT_IN_ROW"),
                    "PRICE_CODE"                => $arParams['~PRICE_CODE'],
                    "USE_PRICE_COUNT"           => "N",
                    "SHOW_PRICE_COUNT"          => "1",

                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],

                    "PRICE_VAT_INCLUDE"          => "Y",
                    "USE_PRODUCT_QUANTITY"       => "Y",
                    "ADD_PROPERTIES_TO_BASKET"   => "Y",
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",
                    "PRODUCT_PROPERTIES"         => [],

                    "DISPLAY_TOP_PAGER"               => "N",
                    "DISPLAY_BOTTOM_PAGER"            => "Y",
                    "PAGER_TITLE"                     => $arParams["PAGER_TITLE"],
                    "PAGER_SHOW_ALWAYS"               => $arParams["PAGER_SHOW_ALWAYS"],
                    "PAGER_TEMPLATE"                  => Config::get("PAGINATION"),
                    "PAGER_DESC_NUMBERING"            => $arParams["PAGER_DESC_NUMBERING"],
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                    "PAGER_SHOW_ALL"                  => $arParams["PAGER_SHOW_ALL"],
                    "LAZY_LOAD"                       => "N",
                    "LOAD_ON_SCROLL"                  => "Y",

                    "OFFERS_CART_PROPERTIES" => [
                        0 => "SIZES_SHOES",
                        1 => "SIZES_CLOTHES",
                        2 => "COLOR_REF",
                    ],
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
                    "OFFERS_SORT_FIELD"         => "sort",
                    "OFFERS_SORT_FIELD2"        => "id",
                    "OFFERS_SORT_ORDER"         => "desc",
                    "OFFERS_SORT_ORDER2"        => "desc",
                    "OFFERS_LIMIT"              => "0",
                    "SHOW_ALL_WO_SECTION"       => "Y",
                    "SECTION_CODE"              => "",
                    "USE_MAIN_ELEMENT_SECTION"  => "N",
                    'CONVERT_CURRENCY'          => "N",
                    'HIDE_NOT_AVAILABLE'        => Config::get('HIDE_NOT_AVAILABLE'),
                    'HIDE_NOT_AVAILABLE_OFFERS' => 'N',

                    'LABEL_PROP'           => [0 => "KHIT", 1 => "NOVINKA",],
                    'ADD_PICT_PROP'        => "MORE_PHOTO",
                    'PRODUCT_DISPLAY_MODE' => "Y",
                    'SHOW_SLIDER'          => "Y",

                    'STORES' => $arParams['STORES'],

                    'OFFER_ADD_PICT_PROP' => "MORE_PHOTO",
                    'OFFER_TREE_PROPS'    => [
                        0  => "PROTSESSOR",
                        1  => "OBEM_OPERATICHNOY_PAMYATI",
                        2  => "OBEM_PAMYATI",
                        3  => "RAZMER",
                        4  => "CHASTOTA_PROTSESSORA",
                        5  => "TIP_VIDEOKARTY",
                        6  => "TSVET",
                        7  => "KOLICHESTVO_YADER_PROTSESORA",
                        8  => "OBEM_VIDEOPAMYATI",
                        9  => "TSVET_1",
                        10 => "USTANOVLENNAYA_OS",
                        11 => "CML2_MANUFACTURER",
                    ],

                    'PRODUCT_SUBSCRIPTION'      => "Y",
                    'SHOW_DISCOUNT_PERCENT'     => "Y",
                    'DISCOUNT_PERCENT_POSITION' => "bottom-right",
                    'SHOW_OLD_PRICE'            => "Y",
                    'SHOW_MAX_QUANTITY'         => Config::get('SHOW_STOCK_MODE'),

                    'USE_VOTE_RATING' => "Y",

                    "TEMPLATE_THEME" => "site",
                    "ADD_SECTIONS_CHAIN"   => "N",
                    'ADD_TO_BASKET_ACTION' => "ADD",
                    'COMPARE_PATH'         => Config::get('COMPARE_PAGE'),
                    'COMPARE_NAME'         => "CATALOG_COMPARE_LIST",
                    'USE_COMPARE_LIST'     => 'Y',
                    'COMPATIBLE_MODE'      => "N",
                    "TEMPLATE_LIST_VIEW_DEFAULT" => $sort["view"]
                ],
                false
            );
            ?>
		</div>
	</div>
</div>
<?
$currencyFormat = CCurrencyLang::GetFormatDescription($arParams['CURRENCY_ID']);
?>
<script type="text/javascript">
	BX.Currency.setCurrencyFormat('<?=$arParams['CURRENCY_ID']?>', <? echo
	CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
</script>
