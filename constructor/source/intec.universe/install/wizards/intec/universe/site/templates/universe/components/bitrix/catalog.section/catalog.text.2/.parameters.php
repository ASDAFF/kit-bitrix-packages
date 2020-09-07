<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
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

$bBase = false;
$bLite = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], ['ACTIVE' => 'Y']))->indexBy('ID');
$arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters = [];
$arTemplateParameters['ACTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ACTION_NONE'),
        'buy' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ACTION_BUY'),
        'detail' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ACTION_DETAIL'),
        'order' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ACTION_ORDER')
    ],
    'DEFAULT' => 'buy',
    'REFRESH' => 'Y'
];

$arTemplateParameters['BUTTON_TOGGLE_ACTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_BUTTON_TOGGLE_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_BUTTON_TOGGLE_ACTION_NONE'),
        'buy' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_BUTTON_TOGGLE_ACTION_BUY')
    ],
    'DEFAULT' => 'buy'
];

$arTemplateParameters['PROPERTIES_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PROPERTIES_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['PROPERTIES_AMOUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PROPERTIES_AMOUNT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6
    ],
    'DEFAULT' => 5
];

$arTemplateParameters['COUNTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_COUNTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['OFFERS_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_OFFERS_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['CONSENT_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_CONSENT_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['LAZY_LOAD'] = [
    'PARENT' => 'PAGER_SETTINGS',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_LAZY_LOAD'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $arTemplateParameters['PROPERTY_ORDER_USE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PROPERTY_ORDER_USE'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

if ($bBase) {
    $arTemplateParameters['DELAY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_DELAY_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['DELAY_SHOW_INACTIVE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_DELAY_SHOW_INACTIVE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['COMPARE_SHOW_INACTIVE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_COMPARE_SHOW_INACTIVE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['VOTE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_VOTE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['VOTE_SHOW'] === 'Y') {
    $arTemplateParameters['VOTE_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_VOTE_MODE_RATING'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'rating' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_VOTE_MODE_RATING'),
            'average' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_VOTE_MODE_AVERAGE')
        ],
        'DEFAULT' => 'rating'
    ];
}

$arTemplateParameters['QUANTITY_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['QUANTITY_SHOW'] === 'Y') {
    $arTemplateParameters['QUANTITY_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'number' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_MODE_NUMBER'),
            'text' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_MODE_TEXT'),
            'logic' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_MODE_LOGIC')
        ],
        'DEFAULT' => 'number',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['QUANTITY_MODE'] === 'text') {
        $arTemplateParameters['QUANTITY_BOUNDS_FEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_BOUNDS_FEW'),
            'TYPE' => 'STRING',
        ];
        $arTemplateParameters['QUANTITY_BOUNDS_MANY'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_BOUNDS_MANY'),
            'TYPE' => 'STRING',
        ];
    }
}

include(__DIR__.'/parameters/quick.view.php');

if (Loader::includeModule('form')) {
    include(__DIR__.'/parameters/base/forms.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/parameters/lite/forms.php');
}