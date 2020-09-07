<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyLink = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyCheckbox = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyLink = $arProperties->asArray($hPropertyLink);
    $arPropertyList = $arProperties->asArray($hPropertyList);
    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
}

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_NAME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_NAME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_IMAGES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_IMAGES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TEXT_ADDITIONAL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_TEXT_ADDITIONAL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_THEME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_THEME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_DETAIL_NARROW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_DETAIL_NARROW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_VIEW'])) {
        $arTemplateParameters['PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyCheckbox,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['PROPERTY_COMPACT_POSITION'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_PROPERTY_COMPACT_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyList,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_IMAGES'])) {
    $arTemplateParameters['IMAGES_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_IMAGES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['BUTTON_TEXT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_BUTTON_TEXT'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_BUTTON_TEXT_DEFAULT')
];