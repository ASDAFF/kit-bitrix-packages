<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('iblock'))
    return;

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_VIDEO_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_VIDEO_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters["DISPLAY_FIRST_VIDEO"] = [
    'PARENT' => 'DATA_SOURCE',
    "NAME" => Loc::getMessage("C_NEWS_LIST_VIDEO_IBLOCK_FIRST_VIDEO"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "N",
    "REFRESH" => "Y",
];

$iBlockProperty = [];
$properties = CIBlockProperty::GetList(["sort" => "asc"], ["ACTIVE" => "Y", "IBLOCK_ID" => $arCurrentValues["IBLOCK_ID"]]);

while ($propFields = $properties->GetNext()) {
    $iBlockProperty[$propFields["CODE"]] = '[' . $propFields["CODE"] . ']' . $propFields["NAME"];
}

$arTemplateParameters["IBLOCK_PROPERTY"] = [
    'PARENT' => 'DATA_SOURCE',
    "NAME" => Loc::getMessage("C_NEWS_LIST_VIDEO_IBLOCK_PROPERTY"),
    "TYPE" => "LIST",
    'VALUES' => $iBlockProperty,
];

$arTemplateParameters["PROPERTY_DURATION"] = [
    'PARENT' => 'DATA_SOURCE',
    "NAME" => Loc::getMessage("C_NEWS_LIST_VIDEO_PROPERTY_DURATION"),
    "TYPE" => "LIST",
    'VALUES' => $iBlockProperty,
];

$arElement = [];
$arFilter = [
    "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"],
    "ACTIVE_DATE"=>"Y",
    "ACTIVE"=>"Y"
];
$res = CIBlockElement::GetList([], $arFilter, false, ["nPageSize"=>50], []);

while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arElement[$arFields['ID']] = $arFields['NAME'];
}

if ($arCurrentValues['DISPLAY_FIRST_VIDEO'] == "Y")
    $arTemplateParameters["IBLOCK_FIRST_VIDEO"] = [
        'PARENT' => 'DATA_SOURCE',
        "NAME" => Loc::getMessage("C_NEWS_LIST_VIDEO_IBLOCK_FIRST_VIDEO"),
        "TYPE" => "LIST",
        'VALUES' => $arElement,
    ];

$arTemplateParameters["IBLOCK_DISPLAY_VIDEO"] = [
    'PARENT' => 'DATA_SOURCE',
    "NAME" => Loc::getMessage("C_NEWS_LIST_VIDEO_IBLOCK_DISPLAY_VIDEO"),
    "TYPE" => "LIST",
    'MULTIPLE' => 'Y',
    'VALUES' => $arElement,
];