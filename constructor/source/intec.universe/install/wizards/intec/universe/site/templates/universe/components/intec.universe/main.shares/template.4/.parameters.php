<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

/** Получение свойств инфоблока для выбора свойства цены услуги */
$arProperties = [];

/** Параметры шаблона */
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['LINE_COUNT'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_LINE_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => array(
        2 => '2',
        3 => '3',
        4 => '4'
    ),
    'DEFAULT' => 3
);
$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['SEE_ALL_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_SEE_ALL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['SEE_ALL_SHOW'] == 'Y') {
    $arTemplateParameters['SEE_ALL_POSITION'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_SEE_ALL_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => array(
            'left' => Loc::getMessage('C_SHARES_TEMPLATE_4_POSITION_LEFT'),
            'center' => Loc::getMessage('C_SHARES_TEMPLATE_4_POSITION_CENTER'),
            'right' => Loc::getMessage('C_SHARES_TEMPLATE_4_POSITION_RIGHT')
        ),
        'DEFAULT' => 'center'
    );
    $arTemplateParameters['SEE_ALL_TEXT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_SEE_ALL_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_SHARES_TEMPLATE_4_SEE_ALL_TEXT_DEFAULT')
    );
}

if ($arCurrentValues['IBLOCK_ID']) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertiesString = function ($sKey, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] !== 'S')
            return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
        ];
    };
    $hPropertiesCheckbox = function ($sKey, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] !== 'L' && $arProperty['MULTIPLY'] !== 'Y')
            return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
        ];
    };
    $hPropertiesFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertiesCheckbox = $arProperties->asArray($hPropertiesCheckbox);
    $arPropertiesString = $arProperties->asArray($hPropertiesString);
    $arPropertiesFile = $arProperties->asArray($hPropertiesFile);

    $arTemplateParameters['PROPERTY_STICK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_PROPERTY_STICK'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['PROPERTY_STICK']) {
        $arTemplateParameters['STICK_SHOW'] = array(
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_STICK_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        );
        $arTemplateParameters['PROPERTY_STICK_BACKGROUND'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_PROPERTY_STICK_BACKGROUND'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesString,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['PROPERTY_TITLE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_PROPERTY_TITLE'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesString,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_BACKGROUND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_PROPERTY_BACKGROUND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_PICTURE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SHARES_TEMPLATE_4_PROPERTY_PICTURE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesFile,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}