<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arParametersCommon
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$arTemplateParameters['QUANTITY_MODE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_QUANTITY_MODE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'number' => Loc::getMessage('C_CATALOG_CATALOG_1_QUANTITY_MODE_NUMBER'),
        'text' => Loc::getMessage('C_CATALOG_CATALOG_1_QUANTITY_MODE_TEXT'),
        'logic' => Loc::getMessage('C_CATALOG_CATALOG_1_QUANTITY_MODE_LOGIC')
    ],
    'DEFAULT' => 'number',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['QUANTITY_MODE'] === 'text') {
    $arTemplateParameters['QUANTITY_BOUNDS_FEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_QUANTITY_BOUNDS_FEW'),
        'TYPE' => 'STRING',
    ];
    $arTemplateParameters['QUANTITY_BOUNDS_MANY'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_QUANTITY_BOUNDS_MANY'),
        'TYPE' => 'STRING',
    ];
}