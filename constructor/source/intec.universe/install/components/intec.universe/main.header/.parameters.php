<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arComponentParameters
 */

if (!Loader::includeModule('iblock'))
    return;

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        'LOGOTYPE_SHOW' => [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('HEADER_LOGOTYPE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'Y'
        ],
        'PHONES_SHOW' => [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('HEADER_PHONE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'Y'
        ]
    ]
];

if ($arCurrentValues['LOGOTYPE_SHOW'] == 'Y') {
    $arComponentParameters['PARAMETERS']['LOGOTYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('HEADER_LOGOTYPE'),
        'TYPE' => 'STRING'
    ];
}

if ($arCurrentValues['PHONES_SHOW'] == 'Y') {
    $arComponentParameters['PARAMETERS']['PHONES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('HEADER_PHONE'),
        'TYPE' => 'STRING',
        'MULTIPLE' => 'Y'
    ];
}