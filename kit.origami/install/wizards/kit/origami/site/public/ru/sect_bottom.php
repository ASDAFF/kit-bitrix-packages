<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="row">
	<div class="bx-content col-xs-12">
		<br/>
		<?if (IsModuleInstalled("advertising")):?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:advertising.banner",
			"parallax",
			array(
				"COMPONENT_TEMPLATE" => "parallax",
				"TYPE" => "PARALLAX",
				"NOINDEX" => "Y",
				"QUANTITY" => "1",
				"BS_EFFECT" => "fade",
				"BS_CYCLING" => "N",
				"BS_WRAP" => "Y",
				"BS_PAUSE" => "Y",
				"BS_KEYBOARD" => "Y",
				"BS_ARROW_NAV" => "Y",
				"BS_BULLET_NAV" => "Y",
				"BS_HIDE_FOR_TABLETS" => "N",
				"BS_HIDE_FOR_PHONES" => "Y",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"SCALE" => "N",
				"CYCLING" => "N",
				"EFFECTS" => "",
				"ANIMATION_DURATION" => "500",
				"WRAP" => "1",
				"ARROW_NAV" => "1",
				"BULLET_NAV" => "2",
				"KEYBOARD" => "N",
				"EFFECT" => "random",
				"SPEED" => "500",
				"JQUERY" => "Y",
				"DIRECTION_NAV" => "Y",
				"CONTROL_NAV" => "Y",
				"PARALL_HEIGHT" => "400",
				"HEIGHT" => "400"
			),
			false
		);?>
		<?endif?>
		<br/>
		<h2 style="padding-left: 14px;">Новости</h2>
		<?$APPLICATION->IncludeComponent(
            "bitrix:news.list", 
            "flat", 
            array(
                "IBLOCK_TYPE" => "news",
                "IBLOCK_ID" => "1",
                "NEWS_COUNT" => "3",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "",
                "FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_SHADOW" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "120",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "DISPLAY_PANEL" => "N",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => "",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
                "PAGER_SHOW_ALL" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "COMPONENT_TEMPLATE" => "flat",
                "SET_BROWSER_TITLE" => "Y",
                "SET_META_KEYWORDS" => "Y",
                "SET_META_DESCRIPTION" => "Y",
                "SET_LAST_MODIFIED" => "N",
                "INCLUDE_SUBSECTIONS" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "MEDIA_PROPERTY" => "",
                "SEARCH_PAGE" => "/search/",
                "USE_RATING" => "N",
                "USE_SHARE" => "N",
                "PAGER_TITLE" => "Новости",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "SHOW_404" => "N",
                "MESSAGE_404" => "",
                "TEMPLATE_THEME" => "site",
                "STRICT_SECTION_CHECK" => "N",
                "SLIDER_PROPERTY" => ""
            ),
            false
        );
		?>
	</div>
</div>
