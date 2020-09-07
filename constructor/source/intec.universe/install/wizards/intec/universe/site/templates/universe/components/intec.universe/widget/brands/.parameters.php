<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 */

if (!CModule::IncludeModule('iblock'))
    return;

$arTemplateParameters = array();

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = array();
$arIBlocksFilter = array();
$arIBlocksFilter['ACTIVE'] = 'Y';

if (!empty($sIBlockType))
    $arIBlocksFilter['TYPE'] = $sIBlockType;

$rsIBlocks = CIBlock::GetList(array('SORT' => 'ASC'), $arIBlocksFilter);

while ($arIBlock = $rsIBlocks->Fetch())
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

$iIBlockId = (int)$arCurrentValues['IBLOCK_ID'];


$arTemplateParameters['IBLOCK_TYPE'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('C_W_BRANDS_PARAMETERS_IBLOCK_TYPE'),
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);

$arTemplateParameters['IBLOCK_ID'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('C_W_BRANDS_PARAMETERS_IBLOCK_ID'),
    'VALUES' => $arIBlocks,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);

$arTemplateParameters['ITEMS_LIMIT'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'STRING',
    'NAME' => GetMessage('C_W_BRANDS_PARAMETERS_ITEMS_LIMIT'),
    'DEFAULT' => 20
);

$arTemplateParameters["DISPLAY_TITLE"] = array(
    "PARENT" => "VISUAL",
    "NAME" => GetMessage("C_W_BRANDS_PARAMETERS_SHOW_TITLE"),
    "TYPE" => "CHECKBOX",
    "REFRESH" => "Y"
);
if ($arCurrentValues["DISPLAY_TITLE"] == "Y") {
    $arTemplateParameters["TITLE_ALIGN"] = array(
        "PARENT" => "VISUAL",
        "NAME" => GetMessage("C_W_BRANDS_PARAMETERS_TITLE_ALIGN"),
        "TYPE" => "CHECKBOX",
        'DEFAULT' => 'N'
    );
    $arTemplateParameters["TITLE"] = array(
        "PARENT" => "VISUAL",
        "NAME" => GetMessage("C_W_BRANDS_PARAMETERS_TITLE"),
        "TYPE" => "TEXT"
    );
}

$arTemplateParameters["SHOW_DESCRIPTION"] = array(
    "PARENT" => "VISUAL",
    "NAME" => GetMessage("C_W_BRANDS_PARAMETERS_SHOW_DESCRIPTION"),
    "TYPE" => "CHECKBOX",
    "REFRESH" => "Y"
);
if ($arCurrentValues["SHOW_DESCRIPTION"] == "Y") {
    $arTemplateParameters["DESCRIPTION_ALIGN"] = array(
        "PARENT" => "VISUAL",
        "NAME" => GetMessage("C_W_BRANDS_PARAMETERS_DESCRIPTION_ALIGN"),
        "TYPE" => "CHECKBOX",
        'DEFAULT' => 'N'
    );
    $arTemplateParameters["DESCRIPTION"] = array(
        "PARENT" => "VISUAL",
        "NAME" => GetMessage("C_W_BRANDS_PARAMETERS_DESCRIPTION"),
        "TYPE" => "TEXT"
    );
}

$arTemplateParameters["COUNT_ELEMENT_IN_ROW"] = array(
    "PARENT" => "VISUAL",
    "NAME"=>GetMessage("C_W_BRANDS_COUNT_IN_ROW"),
    "TYPE" => "LIST",
    "VALUES" => array(
        "3" => 3,
        "4" => 4,
        "5" => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8
    )
);

$arTemplateParameters["AUTOPLAY"] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage("C_W_BRANDS_AUTOPLAY"),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => "Y"
);
if ($arCurrentValues["AUTOPLAY"] == "Y") {
    $arTemplateParameters["TIMEOUT_AUTOPLAY"] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage("C_W_BRANDS_TIMEOUT"),
        'TYPE' => 'NUMBER',
        'RELOAD' => "Y"
    );
}
