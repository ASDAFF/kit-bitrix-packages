<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

/** Типы инфоблоков */
$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

/** Список инфоблоков по выбранному типу */
$arIBlocks = array();

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $rsIBlocks = CIBlock::GetList(
        array(),
        array(
            'TYPE' => $arCurrentValues['IBLOCK_TYPE']
        )
    );
} else {
    $rsIBlocks = CIBlock::GetList();
}

while ($arIBlock = $rsIBlocks->GetNext()) {
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];
}

/** Список свойств инфоблока */
$arPropertiesList = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $rsProperties = CIBlockProperty::GetList(
        array(),
        array(
            'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
        )
    );

    while ($arProperty = $rsProperties->Fetch()) {
        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L') {
            $arPropertiesList[$arProperty['CODE']] = '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME'];
        }
    }
}

/** Параметры компонента */
$arComponentParameters = array(
    'GROUPS' => array(),
    'PARAMETERS' => array(
        'IBLOCK_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_TAGS_CLOUD_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_TAGS_CLOUD_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ),
        'CACHE_TIME' => array()
    )
);

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arComponentParameters['PARAMETERS']['PROPERTY_TAG'] = array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_TAGS_CLOUD_PROPERTY_TAG'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesList,
        'ADDITIONAL_VALUES' => 'Y'
    );

    if (!empty($arCurrentValues['PROPERTY_TAG'])) {
        $arComponentParameters['PARAMETERS']['TAG_VARIABLE_NAME'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_TAGS_CLOUD_TAG_VARIABLE_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => 'tag'
        );
    }
}