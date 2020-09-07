<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

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

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];

$arTemplateParameters['PICTURE_TYPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_PICTURE_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'square' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_PICTURE_TYPE_SQUARE'),
        'round' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_PICTURE_TYPE_ROUND')
    ],
    'DEFAULT' => 'square',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PICTURE_TYPE'] === 'square') {
    $arTemplateParameters['PICTURE_INDENTS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_PICTURE_INDENTS'),
        'TYPE' => 'CHECKBOX'
    ];
}

$arTemplateParameters['NAME_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_NAME_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_POSITION_LEFT'),
        'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_POSITION_CENTER'),
        'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_POSITION_RIGHT')
    ],
    'DEFAULT' => 'center'
];

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_POSITION_LEFT'),
            'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_POSITION_CENTER'),
            'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

$arTemplateParameters['LAZY_LOAD'] = [
    'PARENT' => 'PAGER_SETTINGS',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_LAZY_LOAD'),
    'TYPE' => 'CHECKBOX'
];