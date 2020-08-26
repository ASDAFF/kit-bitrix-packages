<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    "NAME" => GetMessage("CROSSSELL_COLLECTION_COMPONENT"),
    "DESCRIPTION" => GetMessage("CROSSSELL_COLLECTION_COMPONENT_DESCRIPTION"),
    "ICON" => "/images/icon.gif",
    "COMPLEX" => "Y",
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "sotbit",
        "CHILD" => array(
            "ID" => "crosssell.collection",
            "NAME" => GetMEssage("CROSSSELL_COLLECTION")
        )
    ),
);
?>