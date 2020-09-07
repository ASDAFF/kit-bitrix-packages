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

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyList = $arProperties->asArray($hPropertyList);

    $arTemplateParameters['PROPERTY_SIZE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_PROPERTY_SIZE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['POSITION_HORIZONTAL'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_POSITION_HORIZONTAL'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_POSITION_HORIZONTAL_LEFT'),
        'right' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_POSITION_HORIZONTAL_RIGHT')
    ],
    'DEFAULT' => 'left'
];
$arTemplateParameters['POSITION_VERTICAL'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_POSITION_VERTICAL'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'top' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_POSITION_VERTICAL_TOP'),
        'bottom' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_POSITION_VERTICAL_BOTTOM')
    ],
    'DEFAULT' => 'bottom'
];

if (!empty($arCurrentValues['PROPERTY_LINK']) || $arCurrentValues['LINK_MODE'] === 'component') {
    $arTemplateParameters['LINK_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_LINK_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['LINK_USE'] === 'Y') {
        $arTemplateParameters['LINK_BLANK'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_5_LINK_BLANK'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}