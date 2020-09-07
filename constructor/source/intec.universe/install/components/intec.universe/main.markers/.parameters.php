<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arParameters['HIT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MARKERS_HIT'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arParameters['NEW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MARKERS_NEW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arParameters['RECOMMEND'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MARKERS_RECOMMEND'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => $arParameters
];