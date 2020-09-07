<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = array(
	'SETTINGS_USE' => array(
		'PARENT' => 'BASE',
		'NAME' => Loc::getMessage('TP_BSP_SETTINGS_USE'),
		'TYPE' => 'CHECKBOX'
	),
	'LAZYLOAD_USE' =>  array(
		'PARENT' => 'BASE',
		'NAME' => Loc::getMessage('TP_BSP_LAZYLOAD_USE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N'
	),
	"USE_SUGGEST" => Array(
		"NAME" => Loc::getMessage("TP_BSP_USE_SUGGEST"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"SHOW_ITEM_TAGS" => array(
		"NAME" => Loc::getMessage("TP_BSP_SHOW_ITEM_TAGS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	"TAGS_INHERIT" => array(
		"NAME" => Loc::getMessage("TP_BSP_TAGS_INHERIT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_ITEM_DATE_CHANGE" => array(
		"NAME" => Loc::getMessage("TP_BSP_SHOW_ITEM_DATE_CHANGE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_ORDER_BY" => array(
		"NAME" => Loc::getMessage("TP_BSP_SHOW_ORDER_BY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_TAGS_CLOUD" => array(
		"NAME" => Loc::getMessage("TP_BSP_SHOW_TAGS_CLOUD"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
);

if($arCurrentValues["SHOW_ITEM_TAGS"] == "N")
	unset($arTemplateParameters["TAGS_INHERIT"]);

if($arCurrentValues["SHOW_TAGS_CLOUD"] == "Y")
{
	$arTemplateParameters = array_merge($arTemplateParameters, array(
		"SHOW_TAGS_CLOUD" => array(
			"NAME" => Loc::getMessage("TP_BSP_SHOW_TAGS_CLOUD"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		"TAGS_SORT" => array(
			"NAME" => Loc::getMessage("TP_BSP_SORT"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array("NAME"=>Loc::getMessage("TP_BSP_NAME"), "CNT"=>Loc::getMessage("TP_BSP_CNT")),
			"DEFAULT" => "NAME",
		),
		"TAGS_PAGE_ELEMENTS" => array(
			"NAME" => Loc::getMessage("TP_BSP_PAGE_ELEMENTS"),
			"TYPE" => "STRING",
			"DEFAULT" => "150",
		),
		"TAGS_PERIOD" => array(
			"NAME" => Loc::getMessage("TP_BSP_PERIOD"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"TAGS_URL_SEARCH" => array(
			"NAME" => Loc::getMessage("TP_BSP_URL_SEARCH"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"TAGS_INHERIT" => array(
			"NAME" => Loc::getMessage("TP_BSP_TAGS_INHERIT"),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "Y",
		),
		"FONT_MAX" => array(
			"NAME" => Loc::getMessage("TP_BSP_FONT_MAX"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "50",
		),
		"FONT_MIN" => array(
			"NAME" => Loc::getMessage("TP_BSP_FONT_MIN"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "10",
		),
		"COLOR_NEW" => array(
			"NAME" => Loc::getMessage("TP_BSP_COLOR_NEW"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "000000",
		),
		"COLOR_OLD" => array(
			"NAME" => Loc::getMessage("TP_BSP_COLOR_OLD"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "C8C8C8",
		),
		"PERIOD_NEW_TAGS" => array(
			"NAME" => Loc::getMessage("TP_BSP_PERIOD_NEW_TAGS"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
		),
		"SHOW_CHAIN" => array(
			"NAME" => Loc::getMessage("TP_BSP_SHOW_CHAIN"),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "Y",
		),
		"COLOR_TYPE" => array(
			"NAME" => Loc::getMessage("TP_BSP_COLOR_TYPE"),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "Y",
		),
		"WIDTH" => array(
			"NAME" => Loc::getMessage("TP_BSP_WIDTH"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "100%",
		),
	));
}

if(COption::GetOptionString("search", "use_social_rating") == "Y")
{
	$arTemplateParameters["SHOW_RATING"] = Array(
		"NAME" => Loc::getMessage("TP_BSP_SHOW_RATING"),
		"TYPE" => "LIST",
		"VALUES" => Array(
			"" => Loc::getMessage("TP_BSP_SHOW_RATING_CONFIG"),
			"Y" => Loc::getMessage("MAIN_YES"),
			"N" => Loc::getMessage("MAIN_NO"),
		),
		"MULTIPLE" => "N",
		"DEFAULT" => "",
	);
	$arTemplateParameters["RATING_TYPE"] = Array(
		"NAME" => Loc::getMessage("TP_BSP_RATING_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => Array(
			"" => Loc::getMessage("TP_BSP_RATING_TYPE_CONFIG"),
			"like" => Loc::getMessage("TP_BSP_RATING_TYPE_LIKE_TEXT"),
			"like_graphic" => Loc::getMessage("TP_BSP_RATING_TYPE_LIKE_GRAPHIC"),
			"standart_text" => Loc::getMessage("TP_BSP_RATING_TYPE_STANDART_TEXT"),
			"standart" => Loc::getMessage("TP_BSP_RATING_TYPE_STANDART_GRAPHIC"),
		),
		"MULTIPLE" => "N",
		"DEFAULT" => "",
	);
	$arTemplateParameters["PATH_TO_USER_PROFILE"] = Array(
		"NAME" => Loc::getMessage("TP_BSP_PATH_TO_USER_PROFILE"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
}
?>
