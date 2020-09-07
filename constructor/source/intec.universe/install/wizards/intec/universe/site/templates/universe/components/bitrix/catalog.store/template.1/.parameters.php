<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [
    'SETTINGS_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_STORE_TEMPLATE_1_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ],
    'MAP_ID' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_STORE_TEMPLATE_1_MAP_ID'),
        'TYPE' => 'STRING'
    ]
];