<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

	CModule::IncludeModule('fileman');
	$arMenuTypes = GetMenuTypes(WIZARD_SITE_ID);

SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);
COption::SetOptionInt("fileman", "num_menu_param", 2, false ,WIZARD_SITE_ID);


CModule::IncludeModule('fileman');
$arRes = array();

$menuTypes=array(
    'left'=>GetMessage("WIZ_MENU_left"),
    'top'=>GetMessage("WIZ_MENU_top"),
    'bottom'=>GetMessage("WIZ_MENU_bottom"),
    'sotbit_left' => GetMessage("WIZ_MENU_sotbit_left"),
    'sotbit_top' => GetMessage("WIZ_MENU_sotbit_top"),
    'sotbit_bottom1' => GetMessage("WIZ_MENU_sotbit_bottom_1"),
    'sotbit_bottom2' => GetMessage("WIZ_MENU_sotbit_bottom_2")
);

$armt=array();
$armt = GetMenuTypes();

foreach($menuTypes as $key=>$name)
{
    if(!in_array($key,$armt))
    {
        $tmp=array();
        $tmp[$key]=$name;
        $armt=array_merge($armt,$tmp);
    }

}

SetMenuTypes($armt,WIZARD_SITE_ID);

?>