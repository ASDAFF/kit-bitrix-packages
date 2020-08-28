<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

use Bitrix\Main\Loader;

CModule::IncludeModule('sotbit.origami');

if(!WIZARD_INSTALL_DEMO_DATA){
	return;
}

//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";

// copy files
CopyDirFiles(
    WIZARD_TEMPLATE_ABSOLUTE_PATH."/theme/themes/" . WIZARD_THEME_ID,
    $bitrixTemplateDir."/theme/themes/",
    $rewrite = true, 
    $recursive = true,
    $delete_after_copy = false,
    $exclude = "description.php"
);

/*$dir =  '/local/templates/sotbit_origami/theme/custom';
if(!is_dir($_SERVER['DOCUMENT_ROOT'] .$dir))
{
    mkdir($_SERVER['DOCUMENT_ROOT'] .$dir);
}

switch (WIZARD_THEME_ID) {
    case 1:
        SotbitOrigami::genTheme(['COLOR_BASE' => '#fb0040','FONT_BASE' => 'Open Sans','WIDTH' => '1344px'],$dir);
        break;
    case 2:
        SotbitOrigami::genTheme(['COLOR_BASE' => '#6610f2','FONT_BASE' => 'Open Sans','WIDTH' => '100%'],$dir);
        break;
    case 3:
        SotbitOrigami::genTheme(['COLOR_BASE' => '#343a40','FONT_BASE' => 'Arial','WIDTH' => '1500px'],$dir);
        break;
}*/

//Option::set("main", "wizard_".WIZARD_TEMPLATE_ID."_theme_id", WIZARD_THEME_ID, "", WIZARD_SITE_ID);

// color scheme for main.interface.grid/form
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".strToLower($GLOBALS["DB"]->type)."/favorites.php");
//CUserOptions::SetOption("main.interface", "global", array("theme" => WIZARD_THEME_ID), true);
?>