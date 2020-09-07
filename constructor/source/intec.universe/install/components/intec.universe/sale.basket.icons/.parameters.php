<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

/**
 * @var array $arCurrentValues
 */

$arParameters = [];
$arParameters['BASKET_SHOW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_BASKET_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BASKET_SHOW'] == 'Y') {
    $arParameters['BASKET_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_BASKET_URL'),
        'TYPE' => 'STRING'
    ];
}

if (ModuleManager::isModuleInstalled('sale')) {
    $arParameters['DELAY_SHOW'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_DELAY_SHOW'),
        'TYPE' => 'CHECKBOX'
    ];
}

$arParameters['COMPARE_SHOW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_COMPARE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['COMPARE_SHOW'] == 'Y') {
    if (Loader::includeModule('iblock')) {
        $arIBlocksTypes = CIBlockParameters::GetIBlockTypes();

        $arParameters['COMPARE_IBLOCK_TYPE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_COMPARE_IBLOCK_TYPE'),
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

        $arParameters['COMPARE_IBLOCK_ID'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_COMPARE_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arParameters['COMPARE_CODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_COMPARE_CODE'),
        'TYPE' => 'STRING'
    ];

    $arParameters['COMPARE_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_ICONS_PARAMETERS_COMPARE_URL'),
        'TYPE' => 'STRING'
    ];
}

$arComponentParameters = [
    'PARAMETERS' => $arParameters
];