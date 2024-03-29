<?
use Bitrix\Main\Page\Asset;
use Kit\Origami\Helper\Config;

Asset::getInstance()->addcss("/local/templates/kit_origami/theme/contacts/3/style.css");
?>
<div class="contact__techno_block">
    <?
    if(\KitOrigami::isUseRegions() && $_SESSION['KIT_REGIONS']['MAP_YANDEX']){
        ?>
	    #KIT_REGIONS_MAP_YANDEX#
        <?
    }
    else{
        $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            [
                "AREA_FILE_SHOW" => "file",
                "PATH"           => SITE_DIR."include/kit_origami/contacts_map.php",
            ]
        );
    }
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/contacts_info.php"));
    ?>
    <?php

    global $arFilterNews;
    $useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
    if ($useRegion && $_SESSION['KIT_REGIONS']['ID']) {
        $arFilterNews['PROPERTY_REGIONS'] = [
            false,
            $_SESSION['KIT_REGIONS']['ID']
        ];
    }

    $APPLICATION->IncludeComponent(
        "bitrix:news",
        "kit_origami_shops",
        array(
            "IBLOCK_TYPE" => Config::get("IBLOCK_TYPE_SHOP"),
            "IBLOCK_ID" => Config::get("IBLOCK_ID_SHOP"),
            "NEWS_COUNT" => "1",
            "USE_SEARCH" => "N",
            "USE_RSS" => "Y",
            "USE_RATING" => "N",
            "USE_CATEGORIES" => "N",
            "USE_FILTER" => "Y",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "CHECK_DATES" => "Y",
            "SEF_MODE" => "Y",
            "SEF_FOLDER" => "/about/contacts/",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "100000",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "N",
            "SET_TITLE" => "Y",
            "SET_STATUS_404" => "N",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "USE_PERMISSIONS" => "N",
            "PREVIEW_TRUNCATE_LEN" => "",
            "LIST_ACTIVE_DATE_FORMAT" => "j F Y",
            "LIST_FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_TEXT",
                2 => "PREVIEW_PICTURE",
                3 => "DATE_ACTIVE_FROM",
                4 => "",
            ),
            "LIST_PROPERTY_CODE" => array(
                0 => "",
                1 => "METRO",
                2 => "SCHEDULE",
                3 => "PHONE",
                4 => "ADDRESS",
                5 => "PAY_TYPE",
            ),
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "DISPLAY_NAME" => "N",
            "META_KEYWORDS" => "-",
            "META_DESCRIPTION" => "-",
            "BROWSER_TITLE" => "-",
            "DETAIL_ACTIVE_DATE_FORMAT" => "j F Y",
            "DETAIL_FIELD_CODE" => array(
                0 => "PREVIEW_TEXT",
                1 => "DETAIL_TEXT",
                2 => "DETAIL_PICTURE",
                3 => "DATE_ACTIVE_FROM",
                4 => "",
            ),
            "DETAIL_PROPERTY_CODE" => array(
                0 => "",
                1 => "METRO",
                2 => "SCHEDULE",
                3 => "PHONE",
                4 => "ADDRESS",
                5 => "PAY_TYPE",
            ),
            "DETAIL_DISPLAY_TOP_PAGER" => "N",
            "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
            "DETAIL_PAGER_TITLE" => "��������",
            "DETAIL_PAGER_TEMPLATE" => "",
            "DETAIL_PAGER_SHOW_ALL" => "Y",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "��������",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "IMAGE_POSITION" => "left",
            "USE_SHARE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "",
            "USE_REVIEW" => "N",
            "ADD_ELEMENT_CHAIN" => "Y",
            "SHOW_DETAIL_LINK" => "Y",
            "S_ASK_QUESTION" => "",
            "S_ORDER_SERVISE" => "",
            "T_GALLERY" => "",
            "T_DOCS" => "",
            "T_GOODS" => "",
            "T_SERVICES" => "",
            "T_STUDY" => "",
            "COMPONENT_TEMPLATE" => "contacts",
            "SET_LAST_MODIFIED" => "N",
            "T_VIDEO" => "",
            "DETAIL_SET_CANONICAL_URL" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => "",
            "NUM_NEWS" => "20",
            "NUM_DAYS" => "30",
            "YANDEX" => "N",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "SECTIONS_TYPE_VIEW" => "sections_1",
            "SECTION_TYPE_VIEW" => "section_1",
            "SECTION_ELEMENTS_TYPE_VIEW" => "list_elements_2",
            "ELEMENT_TYPE_VIEW" => "element_1",
            "S_ORDER_SERVICE" => "",
            "T_PROJECTS" => "",
            "T_REVIEWS" => "",
            "T_STAFF" => "",
            "IMAGE_CATALOG_POSITION" => "left",
            "SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
            "SHOW_SECTION_DESCRIPTION" => "Y",
            "LINE_ELEMENT_COUNT" => "3",
            "LINE_ELEMENT_COUNT_LIST" => "3",
            "SHOW_CHILD_SECTIONS" => "N",
            "GALLERY_TYPE" => "small",
            "INCLUDE_SUBSECTIONS" => "Y",
            "FORM_ID_ORDER_SERVISE" => "",
            "T_NEXT_LINK" => "",
            "T_PREV_LINK" => "",
            "SHOW_NEXT_ELEMENT" => "N",
            "IMAGE_WIDE" => "N",
            "SHOW_FILTER_DATE" => "Y",
            "FILTER_NAME" => "arFilterNews",
            "FILTER_FIELD_CODE" => array(
                0 => "",
                1 => "",
            ),
            "FILTER_PROPERTY_CODE" => array(
                0 => "",
                1 => "",
            ),
            'ONE_OFFICE' => 'Y',
            "DETAIL_STRICT_SECTION_CHECK" => "N",
            "VIEW_TYPE" => "list",
            "SHOW_TABS" => "Y",
            "SHOW_ASK_QUESTION_BLOCK" => "Y",
            "SHOW_TOP_MAP" => "Y",
            "STRICT_SECTION_CHECK" => "N",
            "SEF_URL_TEMPLATES" => array(
                "news" => "",
                "section" => "",
                "detail" => "#ELEMENT_CODE#/ ",
                "rss" => "rss/",
                "rss_section" => "#SECTION_ID#/rss/",
            )
        ),
        false
    );
    $APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "origami_webform_2",
        Array(
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "EDIT_URL" => "",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "LIST_URL" => "",
            "AJAX_MODE" => "Y",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array(
                "RESULT_ID" => "RESULT_ID",
                "WEB_FORM_ID" => "WEB_FORM_ID"
            ),
            "WEB_FORM_ID" => 3
        )
    );?>
</div>
