<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arServices = Array(
    "main" => Array(
        "NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
        "STAGES" => Array(
		"locations.php",
            "modules.php",
            "files.php", // Copy bitrix files
            "search.php", // Indexing files
            "template.php", // Install template
            "theme.php", // Install theme
            "menu.php", // Install menu
            "forms.php",
            "settings.php",
        ),
    ),
    "catalog" => Array(
        "NAME" => GetMessage("SERVICE_CATALOG_SETTINGS"),
        "STAGES" => Array(
            "index.php"
        ),
    ),
    "iblock" => Array(
        "NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
        "STAGES" => Array(
            "types.php", //IBlock types
            "blog.php",
            "news.php",
            "services.php",
            "banners.php",
            "brands.php",
            "shops.php",
            "advantages.php",
            "references.php",//reference of Highload-blocks
            "catalog.php",//catalog iblock import
            "catalog2.php",//offers iblock import
            "catalog3.php",
            "catalog4.php",
			"promotions.php",
            "faq.php",
            "users.php"
        ),
    ),
    "sale" => Array(
        "NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
        "STAGES" => Array(
            "step1.php",
            //"payments.php",
            "step2.php",
            "step3.php"
        ),
    ),
    "sotbit.opengraph" => Array(
        "NAME" => GetMessage("SERVICE_OPENGRAPH"),
        "STAGES" => Array(
            "settings.php"
        )
    ),
    "sotbit.crosssell" => Array(
        "NAME" => GetMessage("SERVICE_CROSSSELL"),
        "STAGES" => Array(
            "settings.php"
        )
    ),
    "sotbit.regions" => Array(
        "NAME" => GetMessage("SERVICE_REGIONS"),
        "STAGES" => Array(
            "settings.php"
        )
    ),
    "sotbit.origami" => Array(
        "NAME" => GetMessage("SERVICE_ORIGAMI"),
        "STAGES" => Array(
            "settings.php",
            "iblock_props.php",
//            "macros.php",
            "blocks.php",
			"userfields.php"
        )
    ),
);
?>
