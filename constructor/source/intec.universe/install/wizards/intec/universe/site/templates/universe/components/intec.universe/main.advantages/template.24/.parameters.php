<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'));

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyFileMultiple = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['MULTIPLE'] === 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyTextMultiple = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyList = $arProperties->asArray($hPropertyList);
    $arPropertyFileMultiple = $arProperties->asArray($hPropertyFileMultiple);
    $arPropertyTextMultiple = $arProperties->asArray($hPropertyTextMultiple);

    $arTemplateParameters['PROPERTY_CATEGORY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_PROPERTY_CATEGORY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFileMultiple,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PICTURES_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_PROPERTY_PICTURES_TEXT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyTextMultiple,
        'ADDITIONAL_VALUES' => 'Y'
    ];

}

if (!empty($arCurrentValues['PROPERTY_CATEGORY'])) {
    $arTemplateParameters['CATEGORY_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_CATEGORY_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($arCurrentValues['PROPERTY_PICTURES'])) {
    $arTemplateParameters['PICTURES_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_PICTURES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['HIDING_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_HIDING_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HIDING_USE'] === 'Y') {
    $arTemplateParameters['HIDING_VISIBLE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_24_HIDING_VISIBLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 2
    ];
}