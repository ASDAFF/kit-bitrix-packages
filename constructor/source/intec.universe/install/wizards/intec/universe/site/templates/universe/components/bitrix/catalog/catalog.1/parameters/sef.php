<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['SEF_TABS_USE'] = [
    'PARENT' => 'SEF_MODE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_TABS_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

$arTemplateParameters['SEF_MODE'] = [];
$arTemplateParameters['SEF_MODE']['sections'] = [
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_PAGES_SECTIONS'),
    'DEFAULT' => '',
    'VARIABLES' => []
];

$arTemplateParameters['SEF_MODE']['section'] = [
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_PAGES_SECTION'),
    'DEFAULT' => '#SECTION_ID#/',
    'VARIABLES' => [
        'SECTION_ID',
        'SECTION_CODE',
        'SECTION_CODE_PATH'
    ]
];

$arTemplateParameters['SEF_MODE']['element'] = [
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_PAGES_DETAIL'),
    'DEFAULT' => '#SECTION_ID#/#ELEMENT_ID#/',
    'VARIABLES' => [
        'ELEMENT_ID',
        'ELEMENT_CODE',
        'SECTION_ID',
        'SECTION_CODE',
        'SECTION_CODE_PATH'
    ]
];

$arTemplateParameters['SEF_MODE']['compare'] = [
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_PAGES_COMPARE'),
    'DEFAULT' => 'compare.php?action=#ACTION_CODE#',
    'VARIABLES' => [
        'action'
    ]
];

if($arCurrentValues['SEF_MODE'] === 'Y') {
    $arTemplateParameters['VARIABLE_ALIASES'] = [];
    $arTemplateParameters['VARIABLE_ALIASES']['ELEMENT_ID'] = [
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_VARIABLES_ELEMENT_ID'),
        'TEMPLATE' => '#ELEMENT_ID#'
    ];
    $arTemplateParameters['VARIABLE_ALIASES']['ELEMENT_CODE'] = [
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_VARIABLES_ELEMENT_CODE'),
        'TEMPLATE' => '#ELEMENT_CODE#'
    ];
    $arTemplateParameters['VARIABLE_ALIASES']['SECTION_ID'] = [
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_VARIABLES_SECTION_ID'),
        'TEMPLATE' => '#SECTION_ID#'
    ];
    $arTemplateParameters['VARIABLE_ALIASES']['SECTION_CODE'] = [
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_VARIABLES_SECTION_CODE'),
        'TEMPLATE' => '#SECTION_CODE#'
    ];
    $arTemplateParameters['VARIABLE_ALIASES']['SECTION_CODE_PATH'] = [
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_VARIABLES_SECTION_CODE_PATH'),
        'TEMPLATE' => '#SECTION_CODE_PATH#'
    ];
    $arTemplateParameters['VARIABLE_ALIASES']['SMART_FILTER_PATH'] = [
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_VARIABLES_SECTION_SMART_FILTER_PATH'),
        'TEMPLATE' => '#SMART_FILTER_PATH#'
    ];

    if ($arCurrentValues['SEF_TABS_USE'] === 'Y') {
        $arTemplateParameters['VARIABLE_ALIASES']['TAB'] = [
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_VARIABLES_TAB'),
            'TEMPLATE' => '#TAB#'
        ];

        $arTemplateParameters['SEF_MODE']['tab'] = [
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_PAGES_TAB'),
            'DEFAULT' => ($arCurrentValues['SEF_URL_TEMPLATES']['element'] ? $arCurrentValues['SEF_URL_TEMPLATES']['element'] : '#SECTION_ID#/#ELEMENT_ID#/').'#TAB#/',
            'VARIABLES' => [
                'ELEMENT_ID',
                'ELEMENT_CODE',
                'SECTION_ID',
                'SECTION_CODE',
                'SECTION_CODE_PATH',
                'TAB'
            ]
        ];
    }

    $arTemplateParameters['SEF_MODE']['smart_filter'] = [
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SEF_PAGES_SMART_FILTER'),
        'DEFAULT' => ($arCurrentValues['SEF_URL_TEMPLATES']['section'] ? $arCurrentValues['SEF_URL_TEMPLATES']['section'] : '#SECTION_ID#/').'filter/#SMART_FILTER_PATH#/apply/',
        'VARIABLES' => [
            'SECTION_ID',
            'SECTION_CODE',
            'SECTION_CODE_PATH',
            'SMART_FILTER_PATH'
        ]
    ];
} else {
    if ($arCurrentValues['SEF_TABS_USE'] === 'Y')
        $arTemplateParameters['VARIABLE_ALIASES_TAB'] = [
            'PARENT' => 'SEF_MODE',
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_VARIABLE_ALIASES_TAB'),
            'TYPE' => 'STRING',
            'DEFAULT' => 'TAB'
        ];
}