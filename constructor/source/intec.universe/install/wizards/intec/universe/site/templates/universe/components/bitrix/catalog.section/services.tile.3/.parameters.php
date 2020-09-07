<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

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
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
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
    $hPropertyListMultiple = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyListMultiple = $arProperties->asArray($hPropertyListMultiple);

    $arTemplateParameters['PROPERTY_PRICE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_PROPERTY_PRICE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_CATEGORY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_PROPERTY_CATEGORY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyListMultiple,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

if (!empty($arCurrentValues['PROPERTY_PRICE'])) {
    $arTemplateParameters['PRICE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_PRICE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PRICE_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_PRICE_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_LEFT'),
            'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_CENTER'),
            'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

if (!empty($arCurrentValues['PROPERTY_CATEGORY'])) {
    $arTemplateParameters['CATEGORY_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_CATEGORY_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['CATEGORY_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_CATEGORY_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_LEFT'),
            'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_CENTER'),
            'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];

$arTemplateParameters['NAME_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_NAME_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_LEFT'),
        'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_CENTER'),
        'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_RIGHT')
    ],
    'DEFAULT' => 'center'
];

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_LEFT'),
            'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_CENTER'),
            'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

$arTemplateParameters['LAZY_LOAD'] = [
    'PARENT' => 'PAGER_SETTINGS',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_LAZY_LOAD'),
    'TYPE' => 'CHECKBOX'
];


$arTemplateParameters['ORDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ORDER_USE'] === 'Y') {
    /**
     * @var array $arForms - список форм
     * @var array $arFormFields список полей выбранной формы
     */

    if (Loader::includeModule('form'))
        include(__DIR__.'/parameters/base.php');
    elseif (Loader::includeModule('intec.startshop'))
        include(__DIR__.'/parameters/lite.php');
    else
        return;

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate)
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);

    $arTemplateParameters['ORDER_FORM_ID'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['ORDER_FORM_ID'])) {
        $arTemplateParameters['ORDER_FORM_TEMPLATE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'ADDITIONAL_VALUES' => 'Y',
            'DEFAULT' => '.default'
        ];

        $arTemplateParameters['ORDER_FORM_FIELD'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_FORM_FIELD'),
            'TYPE' => 'LIST',
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['ORDER_FORM_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_FORM_TITLE_DEFAULT')
        ];

        $arTemplateParameters['ORDER_FORM_CONSENT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_FORM_CONSENT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#SITE_DIR#company/consent/'
        ];

        $arTemplateParameters['ORDER_BUTTON'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_BUTTON'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_BUTTON_DEFAULT')
        ];

        $arTemplateParameters['ORDER_BUTTON_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_ORDER_BUTTON_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_LEFT'),
                'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_CENTER'),
                'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_POSITION_RIGHT')
            ],
            'DEFAULT' => 'center'
        ];
    }
}