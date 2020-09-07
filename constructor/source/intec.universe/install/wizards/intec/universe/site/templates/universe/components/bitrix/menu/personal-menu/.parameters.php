<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arCurrentValues;
 */

if (!CModule::IncludeModule('iblock'))
    return;

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = array();
$iIBlockId = $arCurrentValues['IBLOCK_ID'];
$arIBlocksFilter = array();
$arIBlocksFilter['ACTIVE'] = 'Y';

if (!empty($sIBlockType))
    $arIBlocksFilter['TYPE'] = $sIBlockType;

$rsIBlocks = CIBlock::GetList(array('SORT' => 'ASC'), $arIBlocksFilter);

while ($arIBlock = $rsIBlocks->Fetch())
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

$arTemplateParameters = array();
$arTemplateParameters['IBLOCK_TYPE'] = array(
    'PARENT' => 'BASE',
    'NAME' => GetMessage('M_VERTICAL_PARAMETERS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'REFRESH' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
);
$arTemplateParameters['IBLOCK_ID'] = array(
    'PARENT' => 'BASE',
    'NAME' => GetMessage('M_VERTICAL_PARAMETERS_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks,
    'REFRESH' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
);

if (!empty($iIBlockId)) {
    $arFields = array();
    $rsFields = CUserTypeEntity::GetList(array('SORT' => 'ASC'), array(
        'ENTITY_ID' => 'IBLOCK_'.$iIBlockId.'_SECTION',
        'USER_TYPE_ID' => 'file'
    ));

    while ($arField = $rsFields->Fetch())
        $arFields[$arField['FIELD_NAME']] = $arField['FIELD_NAME'];

    $arTemplateParameters['PROPERTY_IMAGE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => GetMessage('M_VERTICAL_PARAMETERS_PROPERTY_IMAGE'),
        'TYPE' => 'LIST',
        'VALUES' => $arFields,
        'REFRESH' => 'Y',
        'ADDITIONAL_VALUES' => 'Y'
    );
}