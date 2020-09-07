<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['BY_SECTION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_BY_SECTION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BY_SECTION'] === 'Y') {
        $arTemplateParameters['TABS_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_TABS_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_LEFT'),
                'center' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_CENTER'),
                'right' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_RIGHT')
            ],
            'DEFAULT' => 'center'
        ];
    }
}

$arTemplateParameters['ELEMENT_TEXT_ALIGN'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_ELEMENT_TEXT_ALIGN'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_LEFT'),
        'center' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_CENTER'),
        'right' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_RIGHT')
    ],
    'DEFAULT' => 'center'
];

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] == 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
    }
}

$arTemplateParameters['LIST_PAGE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_1_LIST_PAGE_URL'),
    'TYPE' => 'STRING'
];