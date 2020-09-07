<?php if (defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
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
        if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'F' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
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
    $hPropertyListMultiple = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyList = $arProperties->asArray($hPropertyList);
    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
    $arPropertyListMultiple = $arProperties->asArray($hPropertyListMultiple);
}

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_BACKGROUND_IMAGE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_BACKGROUND_IMAGE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_BACKGROUND_COLOR'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_BACKGROUND_COLOR'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_IMAGE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_IMAGE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyListMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_LOGOTYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_LOGOTYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PRICE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_PRICE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_PRICE'])) {
        $arTemplateParameters['PROPERTY_PRICE_OLD'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_PRICE_OLD'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyText,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_BUTTON_SHOW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_BUTTON_SHOW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_BUTTON_SHOW'])) {
        $arTemplateParameters['PROPERTY_BUTTON_URL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_BUTTON_URL'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyText,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['PROPERTY_BUTTON_URL'])) {
            $arTemplateParameters['PROPERTY_BUTTON_TEXT'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_BUTTON_TEXT'),
                'TYPE' => 'LIST',
                'VALUES' => $arPropertyText,
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }

    $arTemplateParameters['PROPERTY_MARKS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_MARKS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyListMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TABS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_TABS'),
        'TYPE' => 'LIST',
        'VALUES' => ArrayHelper::merge($arPropertyListMultiple, $arPropertyList),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_THEME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PROPERTY_THEME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

if (!empty($arCurrentValues['PROPERTY_IMAGE'])) {
    $arTemplateParameters['IMAGE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_IMAGE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_TYPE'])) {
    $arTemplateParameters['TYPE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_TYPE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_LOGOTYPE'])) {
    $arTemplateParameters['LOGOTYPE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_LOGOTYPE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_PRICE'])) {
    $arTemplateParameters['PRICE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PRICE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['PRICE_SHOW'] === 'Y' && !empty($arCurrentValues['PROPERTY_PRICE_OLD'])) {
        $arTemplateParameters['PRICE_OLD_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_PRICE_OLD_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_MARKS'])) {
    $arTemplateParameters['MARKS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_MARKS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_TABS'])) {
    $arTemplateParameters['TABS_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_TABS_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TABS_USE'] === 'Y') {
        $arTemplateParameters['TABS_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_TABS_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_POSITION_LEFT'),
                'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_POSITION_CENTER'),
                'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_POSITION_RIGHT')
            ],
            'DEFAULT' => 'center'
        ];
    }
}

$arTemplateParameters['LAZY_LOAD'] = [
    'PARENT' => 'PAGER_SETTINGS',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_LAZY_LOAD'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LAZY_LOAD'] === 'Y') {
    $arTemplateParameters['LOAD_ON_SCROLL'] = [
        'PARENT' => 'PAGER_SETTINGS',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_LOAD_ON_SCROLL'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}