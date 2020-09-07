<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['DATE_SHOW'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DATE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['DATE_SHOW'] === 'Y') {
    $arTemplateParameters['DATE_TYPE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DATE_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'DATE_CREATE' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DATE_TYPE_CREATE'),
            'DATE_ACTIVE_FROM' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DATE_TYPE_ACTIVE_FROM'),
            'DATE_ACTIVE_TO' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DATE_TYPE_ACTIVE_TO'),
            'TIMESTAMP_X' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DATE_TYPE_TIMESTAMP_X')
        ],
        'DEFAULT' => 'DATE_ACTIVE_FROM'
    ];

    $arTemplateParameters['DATE_FORMAT'] = CIBlockParameters::GetDateFormat(
        Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DATE_FORMAT'),
        'VISUAL'
    );
}

$arTemplateParameters['IBLOCK_DESCRIPTION_SHOW'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_IBLOCK_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_LIST_1_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];