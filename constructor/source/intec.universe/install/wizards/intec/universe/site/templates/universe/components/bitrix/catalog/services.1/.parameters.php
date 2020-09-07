<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

Loc::loadMessages(__FILE__);

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], ['ACTIVE' => 'Y']))->indexBy('ID');
$arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters = [];

if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');
}

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SETTINGS_USE'] === 'Y') {
    $arTemplateParameters['SETTINGS_PROFILE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_SETTINGS_PROFILE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'services' => Loc::getMessage('C_CATALOG_SERVICES_1_SETTINGS_PROFILE_SERVICES')
        ]
    ];
}

include(__DIR__.'/parameters/hidden.php');
include(__DIR__.'/parameters/menu.php');
include(__DIR__.'/parameters/sections.php');
include(__DIR__.'/parameters/elements.php');
include(__DIR__.'/parameters/element.php');
include(__DIR__.'/parameters/regionality.php');