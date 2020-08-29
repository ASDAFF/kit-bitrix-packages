<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Kit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

global $kitSeoMetaBottomDesc;
global $kitSeoMetaTopDesc;
global $kitSeoMetaAddDesc;
global $kitSeoMetaFile;
global $issetCondition;
global $origamiSectionDescription;
global $origamiSectionDescriptionBottom;

$moduleRegions = CModule::IncludeModule("kit.regions");
$moduleSeo = CModule::IncludeModule("kit.seometa");

Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/catalog/kit_origami_catalog/style.css");

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
}
else
{
	$basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}
?>
<?if($request->get('ajaxFilter') != 'Y'):?>
<div id="comp_catalog_content"  class="puzzle_block block_main_left_menu catalog-wrapper <?= Config::get('MENU_SIDE') ?>">
<?endif;?>
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
    </div>

    <div class="block_main_left_menu__content active">
        <?

		$APPLICATION->IncludeComponent(
		        'bitrix:breadcrumb',
                'origami_default',
			array(
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => "-"
			), false, Array(
				'HIDE_ICONS' => 'N'
			) );
		?>
		<div class="catalog_content">
			<h1 class="catalog_content__title fonts__middle_title">
                <?$APPLICATION->ShowTitle(false);?>
            </h1>
		<?
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			"origami_catalog",
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
				"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
				"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
				"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
				"ADD_SECTIONS_CHAIN" => 'Y',
                'SECTION_FIELDS' => array(
                    'PICTURE',
                    'DETAIL_PICTURE',
                    'DESCRIPTION'
                ),
                "SECTION_USER_FIELDS" => array(
                    0 => "UF_DESCR_BOTTOM",
                ),
                "SECTION_ROOT_TEMPLATE" => $arParams["SECTION_ROOT_TEMPLATE"]
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);

		if ($arParams["USE_COMPARE"]=="Y")
		{
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.compare.list",
				"",
				array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"NAME" => $arParams["COMPARE_NAME"],
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
					"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action"),
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					'POSITION_FIXED' => isset($arParams['COMPARE_POSITION_FIXED']) ? $arParams['COMPARE_POSITION_FIXED'] : '',
					'POSITION' => isset($arParams['COMPARE_POSITION']) ? $arParams['COMPARE_POSITION'] : ''
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
		}

        //if($moduleSeo)
        {
            ?>
            <?$APPLICATION->ShowViewContent('kit_origami_sections_horizontal__descr');?>
            <?
            if ($arParams['TAGS_POSITION'] == 'TOP') {
                $APPLICATION->ShowViewContent('kit_origami_meta_tags');
            }

//            if ($arParams['TAGS_POSITION'] == 'TOP')
//            {
//                $APPLICATION->IncludeComponent(
//                    "kit:seo.meta.tags",
//                    'origami_default',
//                    Array(
//                        "CACHE_GROUPS" => "Y",
//                        "CACHE_TIME" => "36000000",
//                        "CACHE_TYPE" => "A",
//                        "CNT_TAGS" => "",
//                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
//                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
//                        "INCLUDE_SUBSECTIONS" => "A",
//                        "SECTION_ID" => $arCurSection['ID'],
//                        "SORT" => "NAME",
//                        "SORT_ORDER" => "asc",
//                        "COMPONENT_TEMPLATE" => "origami_default"
//                    )
//                );
//            }
        }

		if($arParams['FILTER_VIEW_MODE'] == 'HORIZONTAL')
		{
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.smart.filter",
				"origami_horizontal",
				array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SHOW_ALL_WO_SECTION" => $arParams["SHOW_ALL_WO_SECTION"],
					"SECTION_ID" => $arCurSection['ID'],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"PRICE_CODE" => $arParams["~PRICE_CODE"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SAVE_IN_SESSION" => "N",
					"FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
					"XML_EXPORT" => "N",
					"SECTION_TITLE" => "NAME",
					"SECTION_DESCRIPTION" => "DESCRIPTION",
					'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
					"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
					'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
					'CURRENCY_ID' => $arParams['CURRENCY_ID'],
					"SEF_MODE" => $arParams["SEF_MODE"],
					"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
					"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
					"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
					"INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                    'FILTER_MODE' => Config::get('FILTER_MODE')
				),
				$component,
				array('HIDE_ICONS' => 'Y')
			);

            if($moduleSeo)
            {
                $APPLICATION->IncludeComponent(
                    'kit:seo.meta',
                    'origami_default',
                    [
                        'FILTER_NAME' => $arParams['FILTER_NAME'],
                        'SECTION_ID' => $arCurSection['ID'],
                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                        'CACHE_TIME' => $arParams['CACHE_TIME']
                    ]);

                $this->SetViewTarget('kit_origami_meta_tags');
                    $APPLICATION->IncludeComponent(
                        "kit:seo.meta.tags",
                        'origami_default',
                        Array(
                            "CACHE_GROUPS" => "Y",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "CNT_TAGS" => "",
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "INCLUDE_SUBSECTIONS" => "A",
                            "SECTION_ID" => $arCurSection['ID'],
                            "SORT" => "NAME",
                            "SORT_ORDER" => "asc",
                            "COMPONENT_TEMPLATE" => "origami_default"
                        )
                    );
                $this->EndViewTarget();
            }

            $arTmpfilter = ${$arParams["FILTER_NAME"]};
            if(isset($arTmpfilter["PROPERTY_REGIONS"]))
                unset($arTmpfilter["PROPERTY_REGIONS"]);

            $this->SetViewTarget('kit_origami_sections_horizontal__descr');
            if($arParams['SECTION_DESCRIPTION'] == "ABOVE" || $arParams['SECTION_DESCRIPTION'] == "BOTH")
            {
                if($arParams['SEO_DESCRIPTION'] == "NOT_HIDE" || ($arParams['SEO_DESCRIPTION'] == "HIDE_IF_RULE_EXIST" && !$issetCondition) || ($arParams['SEO_DESCRIPTION'] == "ANY_FILTERED_PAGE" && empty($arTmpfilter)))
                {
                    if($arParams['SECTION_DESCRIPTION_TOP'] == 'SECTION_DESC')
                        echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $origamiSectionDescription . '</div>';
                    elseif ($arParams['SECTION_DESCRIPTION_TOP'] == 'UF_DESCR_BOTTOM')
                        echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $origamiSectionDescriptionBottom . '</div>';
                }
            }
            if(!empty($kitSeoMetaTopDesc))
            {
                echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $kitSeoMetaTopDesc . '</div>';
            }
            $this->EndViewTarget();
        }
        ?>
        </div>
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
        if(isset($_GET['AJAX']) && $_GET['AJAX'] == 'Y')
            $GLOBALS['APPLICATION']->RestartBuffer();
        $intSectionID = $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "origami_default",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SHOW_ALL_WO_SECTION" => $arParams["SHOW_ALL_WO_SECTION"],
                "ELEMENT_SORT_FIELD" => $sort['by']['by'],
                "ELEMENT_SORT_ORDER" => strtoupper($sort['by']['order']),
                "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
                "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                "BASKET_URL" => $arParams["BASKET_URL"],
                "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SET_TITLE" => $arParams["SET_TITLE"],
                "MESSAGE_404" => $arParams["~MESSAGE_404"],
                "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                "SHOW_404" => $arParams["SHOW_404"],
                "FILE_404" => $arParams["FILE_404"],
                "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                "PAGE_ELEMENT_COUNT" => $sort['limit']['limit'],
                "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                "PRICE_CODE" => $arParams["~PRICE_CODE"],
                "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                'USE_MIN_AMOUNT' => $arParams['USE_MIN_AMOUNT'],
                "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                "FILL_ITEM_ALL_PRICES" => $arParams["FILL_ITEM_ALL_PRICES"],
                "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

                "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

                "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

                "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

                'LABEL_PROP' => $arParams['LABEL_PROP'],
                'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
                'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
                'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
                'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
                'MESS_RELATIVE_QUANTITY_NO' => (isset($arParams['~MESS_RELATIVE_QUANTITY_NO']) ? $arParams['~MESS_RELATIVE_QUANTITY_NO'] : ''),
                'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
                'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
                'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

                'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
                'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

                'MOBILE_VIEW_MINIMAL' => ($arParams['MOBILE_VIEW_MINIMAL'] == 'ADMIN') ? \Kit\Origami\Helper\Config::get('MOBILE_VIEW_MINIMAL') : $arParams['MOBILE_VIEW_MINIMAL'],

                'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                "ADD_SECTIONS_CHAIN" => "N",
                'ADD_TO_BASKET_ACTION' => $basketAction,
                'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                'USE_COMPARE_LIST' => 'Y',
                'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
                'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
                'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
                'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
                'ACTION_PRODUCTS' => $arParams['ACTION_PRODUCTS'],
                'VARIANT_LIST_VIEW' => $arParams["VARIANT_LIST_VIEW"],
                "TEMPLATE_LIST_VIEW_DEFAULT" => $sort["view"]
            ),
            $component,array("HIDE_ICONS" => 'N')
        );
        if(isset($_GET['AJAX']) && $_GET['AJAX'] == 'Y')
            die();
        ?>

        <?
        if ($arParams['TAGS_POSITION'] == 'BOTTOM') {
            $APPLICATION->ShowViewContent('kit_origami_meta_tags');
        }

        if($arParams['SECTION_DESCRIPTION'] == "BELOW" || $arParams['SECTION_DESCRIPTION'] == "BOTH")
        {
            if($arParams['SEO_DESCRIPTION'] == "NOT_HIDE" || ($arParams['SEO_DESCRIPTION'] == "HIDE_IF_RULE_EXIST" && !$issetCondition) || ($arParams['SEO_DESCRIPTION'] == "ANY_FILTERED_PAGE" && empty($arTmpfilter)))
            {
                if($arParams['SECTION_DESCRIPTION_BOTTOM'] == 'SECTION_DESC')
                    echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $origamiSectionDescription . '</div>';
                elseif ($arParams['SECTION_DESCRIPTION_BOTTOM'] == 'UF_DESCR_BOTTOM')
                    echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $origamiSectionDescriptionBottom . '</div>';
            }
        }
        if(!empty($kitSeoMetaBottomDesc))
        {
            echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $kitSeoMetaBottomDesc . '</div>';
        }

		$GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
		?>
	</div>
<?if($request->get('ajaxFilter') != 'Y'):?>
</div>
<?endif;?>
