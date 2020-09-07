<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

use Bitrix\Main\Loader;
Loader::IncludeModule('sotbit.origami');
CModule::IncludeModule("iblock");
use Sotbit\Origami\Helper\Config;

$arIMAGE = CFile::MakeFileArray(WIZARD_SERVICE_RELATIVE_PATH . "/users_files/b288d31525e72e384edd9c8838327fb7.jpg");
$arIMAGE["MODULE_ID"] = "main";

$user = new CUser;
$arFields = Array(
    "NAME"              => GetMessage("MANAGER_FIRST_NAME"),
    "LAST_NAME"         => GetMessage("MANAGER_LAST_NAME"),
    "EMAIL"             => "r_manager_msk@sotbit.ru",
    "LOGIN"             => "Manager_msk",
    "LID"               => "ru",
    "ACTIVE"            => "Y",
    "GROUP_ID"          => array(3,4,7,8),
    "PASSWORD"          => "sotbit123456",
    "CONFIRM_PASSWORD"  => "sotbit123456",
    "PERSONAL_PHOTO"    => $arIMAGE,
    "WORK_POSITION"     => GetMessage("MANAGER_WORKPOSITION"),
    "WORK_PROFILE"      => GetMessage("MANAGER_DESCRIPTION"),
);

$ID = $user->Add($arFields);

$obUserField = new CUserTypeEntity();
$arFields = Array(
    'ENTITY_ID' => 'SOTBIT_REGIONS',
    'FIELD_NAME' => 'UF_REGION_MNGR',
    'USER_TYPE_ID' => 'string',
    "EDIT_FORM_LABEL" => GetMessage("REGIONAL_MANAGER"),
    "LIST_COLUMN_LABEL" => GetMessage("REGIONAL_MANAGER"),
    "LIST_FILTER_LABEL" => GetMessage("REGIONAL_MANAGER"),
);
$arFields["SETTINGS"] = Array(
    "DEFAULT_VALUE" => $ID,
);
$obUserField->Add($arFields);

$arFilter = Array('IBLOCK_ID'=> Config::get('IBLOCK_ID_SHOP') );
$rsSections = CIBlockSection::GetList(array("SORT"=>"ASC"), $arFilter, false);
$arSections = array();
while ($it = $rsSections->Fetch()) {
    $arSections[] = $it['ID'];
}

$obSection = new CIBlockSection;
foreach ($arSections as $sectionId) {
    $obSection->Update($sectionId, array("UF_REGIONAL_MANAGER"=> array($ID)));
}

