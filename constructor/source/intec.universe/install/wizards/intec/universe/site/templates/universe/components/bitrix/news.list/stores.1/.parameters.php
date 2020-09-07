<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::IncludeModule('iblock'))
    return;

$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];
$iIBlockId = $arCurrentValues['IBLOCK_ID'];

$arTemplateParameters = array(
    'LAZYLOAD_USE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ),
    'SHOW_MAP' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_SHOW_MAP'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'MAP_VENDOR' => array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_MAP_VENDOR'),
        'TYPE' => 'LIST',
        'VALUES' => array(
            'google' => Loc::getMessage('C_NEWS_LIST_STORES_1_MAP_VENDOR_GOOGLE'),
            'yandex' => Loc::getMessage('C_NEWS_LIST_STORES_1_MAP_VENDOR_YANDEX'),
        ),
        'ADDITIONAL_VALUES' => 'N',
        'DEFAULT' => 'google'
    ),
    'SETTINGS_USE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    )
);

if (!empty($iIBlockId)) {
    $arPropertiesString = array();
    $arPropertiesMap = array();

    $arIBlockProperties = array();
    $rsIBlockProperties = CIBlockProperty::GetList(array(
        'SORT' => 'ASC'
    ), array(
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $iIBlockId,
        'IBLOCK_TYPE' => $sIBlockType
    ));

    while ($arIBlockProperty = $rsIBlockProperties->Fetch()) {
        if (empty($arIBlockProperty['CODE']))
            continue;

        $arIBlockProperties[$arIBlockProperty['CODE']] = $arIBlockProperty;

        if ($arIBlockProperty['PROPERTY_TYPE'] == 'S')
            $arPropertiesString[$arIBlockProperty['CODE']] = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];

        if ($arIBlockProperty['PROPERTY_TYPE'] == 'S' && ($arIBlockProperty['USER_TYPE'] == 'map_google' || $arIBlockProperty['USER_TYPE'] == 'map_yandex'))
            $arPropertiesMap[$arIBlockProperty['CODE']] = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];
    }

    if ($arCurrentValues['SHOW_MAP'] == 'Y') {
        $arTemplateParameters['PROPERTY_MAP'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_PROPERTY_MAP'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesMap,
            'ADDITIONAL_VALUES' => 'Y'
        );

        $arTemplateParameters['API_KEY_MAP'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_API_KEY_MAP'),
            'TYPE' => 'STRING'
        );
    }

    $arTemplateParameters['PROPERTY_ADDRESS'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_PROPERTY_ADDRESS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_PHONE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_PROPERTY_PHONE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_EMAIL'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_PROPERTY_EMAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_SCHEDULE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_PROPERTY_SCHEDULE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );
}

$arTemplateParameters['DESCRIPTION_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arTemplateParameters['DESCRIPTION'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_STORES_1_DESCRIPTION'),
        'TYPE' => 'TEXT',
        'VALUES' => ''
    );
}