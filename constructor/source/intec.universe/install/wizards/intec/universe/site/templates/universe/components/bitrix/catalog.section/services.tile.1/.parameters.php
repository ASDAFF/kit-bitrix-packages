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
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_1_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];

$arTemplateParameters['LAZY_LOAD'] = [
    'PARENT' => 'PAGER_SETTINGS',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_1_LAZY_LOAD'),
    'TYPE' => 'CHECKBOX'
];