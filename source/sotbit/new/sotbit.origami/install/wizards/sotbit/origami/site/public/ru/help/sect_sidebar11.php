<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="bx-sidebar-block">
    <?$APPLICATION->IncludeComponent("bitrix:menu", "personal_menu", array(
        "ROOT_MENU_TYPE" => "sotbit_bottom1",
        "MAX_LEVEL" => "1",
        "MENU_CACHE_TYPE" => "A",
        "CACHE_SELECTED_ITEMS" => "N",
        "MENU_CACHE_TIME" => "36000000",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => array(),
    ),
        false
    );?>
</div>



