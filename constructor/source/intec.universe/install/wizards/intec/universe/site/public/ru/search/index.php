<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Поиск");

?>
<?php $APPLICATION->IncludeComponent(
    "bitrix:search.page",
    ".default",
    array(
        "SETTINGS_USE" => "Y",
        "LAZYLOAD_USE" => "N",
        "RESTART" => "Y",
        "NO_WORD_LOGIC" => "N",
        "CHECK_DATES" => "N",
        "USE_TITLE_RANK" => "N",
        "DEFAULT_SORT" => "rank",
        "FILTER_NAME" => "",
        "arrFILTER" => array(
            0 => "no",
        ),
        "SHOW_WHERE" => "N",
        "SHOW_WHEN" => "N",
        "PAGE_RESULT_COUNT" => "20",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "USE_LANGUAGE_GUESS" => "Y",
        "USE_SUGGEST" => "N",
        "SHOW_ITEM_TAGS" => "Y",
        "TAGS_INHERIT" => "N",
        "SHOW_ITEM_DATE_CHANGE" => "Y",
        "SHOW_ORDER_BY" => "Y",
        "SHOW_TAGS_CLOUD" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Результаты поиска",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ""
    ),
    false
); ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>