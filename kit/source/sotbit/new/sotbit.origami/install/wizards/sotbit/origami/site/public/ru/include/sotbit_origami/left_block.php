<?
$APPLICATION->IncludeComponent("bitrix:menu", "origami_filter", array(
    "ROOT_MENU_TYPE" => "left",
    "MENU_CACHE_TYPE" => "A",
    "MENU_CACHE_TIME" => "3600000",
    "MENU_CACHE_USE_GROUPS" => "Y",
    "MENU_CACHE_GET_VARS" => "",
    "MAX_LEVEL" => "2",
    "CHILD_MENU_TYPE" => "left",
    "USE_EXT" => "Y",
    "DELAY" => "N",
    "ALLOW_MULTI_SELECT" => "N" ),
    false, array( "ACTIVE_COMPONENT" => "Y" )
);
?>