<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arComponentParameters
 */

$arParameters = [];
$arParameters['LOGOTYPE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FOOTER_PARAMETERS_LOGOTYPE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LOGOTYPE_SHOW'] === 'Y') {
    $arParameters['LOGOTYPE_PATH'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_FOOTER_PARAMETERS_LOGOTYPE_PATH'),
        'TYPE' => 'STRING'
    ];
}

$arParameters['PHONE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FOOTER_PARAMETERS_PHONE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PHONE_SHOW'] === 'Y') {
    $arParameters['PHONE_VALUE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_FOOTER_PARAMETERS_PHONE_VALUE'),
        'TYPE' => 'STRING'
    ];
}

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => $arParameters
];