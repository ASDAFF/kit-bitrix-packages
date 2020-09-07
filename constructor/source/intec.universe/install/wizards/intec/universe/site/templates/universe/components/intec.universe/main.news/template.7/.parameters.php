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
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $hPropertyListMultiple = function ($id, $value) {
        if ($value['PROPERTY_TYPE'] === 'L' && $value['LIST_TYPE'] === 'L' && $value['MULTIPLE'] === 'Y')
            return [
                'key' => $value['CODE'],
                'value' => '['.$value['CODE'].'] '.$value['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyListMultiple = $arProperties->asArray($hPropertyListMultiple);
}

$arTemplateParameters = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_TAGS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_PROPERTY_TAGS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyListMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['DATE_TYPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_DATE_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'DATE_CREATE' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_DATE_TYPE_CREATE'),
        'DATE_ACTIVE_FROM' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_DATE_TYPE_ACTIVE_FROM'),
        'DATE_ACTIVE_TO' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_DATE_TYPE_ACTIVE_TO'),
        'TIMESTAMP_X' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_DATE_TYPE_TIMESTAMP')
    ],
    'DEFAULT' => 'DATE_ACTIVE_FROM'
];
$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LINK_USE'] === 'Y') {
    $arTemplateParameters['LINK_BLANK'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_LINK_BLANK'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_TAGS'])) {
    $arTemplateParameters['TAGS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_TAGS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TAGS_SHOW'] === 'Y') {
        $arTemplateParameters['TAGS_VARIABLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_TAGS_VARIABLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => 'tag'
        ];
        $arTemplateParameters['TAGS_MODE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_TAGS_MODE'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'default' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_TAGS_MODE_DEFAULT'),
                'active' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_7_TAGS_MODE_ACTIVE')
            ],
            'DEFAULT' => 'default'
        ];
    }
}