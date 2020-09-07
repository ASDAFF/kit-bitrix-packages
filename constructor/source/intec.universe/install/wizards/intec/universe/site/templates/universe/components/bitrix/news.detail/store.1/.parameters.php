<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::IncludeModule('iblock'))
    return;

$arTemplateParameters = array(
    'SHOW_MAP' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_SHOW_MAP'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'DESCRIPTION_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'MAP_VENDOR' => array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_MAP_VENDOR'),
        'TYPE' => 'LIST',
        'VALUES' => array(
            'google' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_MAP_VENDOR_GOOGLE'),
            'yandex' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_MAP_VENDOR_YANDEX'),
        ),
        'ADDITIONAL_VALUES' => 'N',
        'DEFAULT' => 'yandex'
    )
);

$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];
$iIBlockId = $arCurrentValues['IBLOCK_ID'];
if (!empty($iIBlockId)) {

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
            $arPropertiesString[$arIBlockProperty['CODE']] = '[' . $arIBlockProperty['CODE'] . '] ' . $arIBlockProperty['NAME'];

        if ($arIBlockProperty['PROPERTY_TYPE'] == 'S' && ($arIBlockProperty['USER_TYPE'] == 'map_google' || $arIBlockProperty['USER_TYPE'] == 'map_yandex'))
            $arPropertiesMap[$arIBlockProperty['CODE']] = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];
    }

    if ($arCurrentValues['MAP_SHOW'] == 'Y') {
        $arTemplateParameters['PROPERTY_MAP'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_PROPERTY_MAP'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesMap,
            'ADDITIONAL_VALUES' => 'Y'
        );

        $arTemplateParameters['API_KEY_MAP'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_API_KEY_MAP'),
            'TYPE' => 'STRING'
        );
    }

    $arTemplateParameters['PROPERTY_ADDRESS'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_PROPERTY_ADDRESS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_PHONE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_PROPERTY_PHONE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_EMAIL'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_PROPERTY_EMAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_SCHEDULE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_STORE_1_PARAMETERS_PROPERTY_SCHEDULE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

}
?>
