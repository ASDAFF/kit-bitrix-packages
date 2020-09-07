<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 * @var array $arParts
 * @var InnerTemplate $desktopTemplate
 * @var InnerTemplate $fixedTemplate
 * @var InnerTemplate $mobileTemplate
 */

$arParts['BASKET'] = null;
$arParts['DELAY'] = null;
$arParts['COMPARE'] = null;

if (!empty($desktopTemplate)) {
    $arTemplateParameters['BASKET_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_BASKET_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($fixedTemplate)) {
    $arTemplateParameters['BASKET_SHOW_FIXED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_BASKET_SHOW_FIXED'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['BASKET_SHOW_MOBILE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_BASKET_SHOW_MOBILE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['BASKET_POPUP'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_BASKET_POPUP'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['BASKET_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_BASKET_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['ORDER_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_ORDER_URL'),
    'TYPE' => 'STRING'
];

if (
    ModuleManager::isModuleInstalled('catalog') &&
    ModuleManager::isModuleInstalled('sale')
) {
    if (!empty($desktopTemplate)) {
        $arTemplateParameters['DELAY_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_HEADER_TEMP1_DELAY_SHOW'),
            'TYPE' => 'CHECKBOX'
        ];
    }

    if (!empty($fixedTemplate)) {
        $arTemplateParameters['DELAY_SHOW_FIXED'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_HEADER_TEMP1_DELAY_SHOW_FIXED'),
            'TYPE' => 'CHECKBOX'
        ];
    }

    if (!empty($mobileTemplate)) {
        $arTemplateParameters['DELAY_SHOW_MOBILE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_HEADER_TEMP1_DELAY_SHOW_MOBILE'),
            'TYPE' => 'CHECKBOX'
        ];
    }
}

if (!empty($desktopTemplate)) {
    $arTemplateParameters['COMPARE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_COMPARE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($fixedTemplate)) {
    $arTemplateParameters['COMPARE_SHOW_FIXED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_COMPARE_SHOW_FIXED'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['COMPARE_SHOW_MOBILE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_COMPARE_SHOW_MOBILE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (Loader::includeModule('iblock')) {
    $arIBlocksTypes = CIBlockParameters::GetIBlockTypes();

    $arTemplateParameters['COMPARE_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_COMPARE_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocksTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arIBlocks = [];
    $rsIBlocks = CIBlock::GetList([], [
        'ACTIVE' => 'Y',
        'TYPE' => $arCurrentValues['COMPARE_IBLOCK_TYPE']
    ]);

    while ($arIBlock = $rsIBlocks->GetNext())
        $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

    $arTemplateParameters['COMPARE_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_COMPARE_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['COMPARE_CODE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_COMPARE_CODE'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['COMPARE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_COMPARE_URL'),
    'TYPE' => 'STRING'
];