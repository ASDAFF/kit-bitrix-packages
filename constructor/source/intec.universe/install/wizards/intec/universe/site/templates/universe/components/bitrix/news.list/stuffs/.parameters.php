<?php use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php
/**
 * @var array $arCurrentValues
 */

if (!CModule::IncludeModule('iblock'))
    return;

$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];
$iIBlockId = $arCurrentValues['IBLOCK_ID'];

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('N_L_STUFFS_PARAMETERS_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('N_L_STUFFS_PARAMETERS_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($iIBlockId)) {
    $arPropertiesString = [];

    $arProperties = [];
    $rsIBlockProperties = CIBlockProperty::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $iIBlockId
    ]);

    while ($arIBlockProperty = $rsIBlockProperties->Fetch()) {
        if (empty($arIBlockProperty['CODE']))
            continue;

        $sName = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];

        $arProperties[$arIBlockProperty['CODE']] = $arIBlockProperty;

        if ($arIBlockProperty['PROPERTY_TYPE'] == 'S')
            $arPropertiesString[$arIBlockProperty['CODE']] = $sName;
    }

    $arTemplateParameters['PROPERTY_POSITION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('N_L_STUFFS_PARAMETERS_PROPERTY_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('N_L_STUFFS_PARAMETERS_PROPERTY_PHONE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_SKYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('N_L_STUFFS_PARAMETERS_PROPERTY_SKYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_EMAIL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('N_L_STUFFS_PARAMETERS_PROPERTY_EMAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters["SHOW_ALL_STUFFS"] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage("N_L_STUFFS_SHOW_ALL"),
        "TYPE" => "CHECKBOX",
        'DEFAULT' => 'Y'
    ];
}