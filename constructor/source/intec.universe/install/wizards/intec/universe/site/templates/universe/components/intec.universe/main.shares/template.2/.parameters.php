<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

/** Получение свойств инфоблока для выбора свойства цены услуги */
$arProperties = [];

/** Параметры шаблона */
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SHARES_TEMP2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SHARES_TEMP2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['LINE_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMP2_LINE_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => array(
        1 => '1',
        2 => '2'
    ),
    'DEFAULT' => 2
];
$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMP2_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['DESCRIPTION_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMP2_DESCRIPTION_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['DATE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMP2_DATE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['DATE_FORMAT'] = CIBlockParameters::GetDateFormat(GetMessage("C_SHARES_TEMP2_DATE_FORMAT"), "VISUAL");

$arTemplateParameters['SEE_ALL_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_TEMP2_SEE_ALL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['SEE_ALL_SHOW'] == 'Y') {
    $arTemplateParameters['SEE_ALL_POSITION'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SHARES_TEMP2_SEE_ALL_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => array(
            'left' => Loc::getMessage('C_SHARES_TEMP2_POSITION_LEFT'),
            'center' => Loc::getMessage('C_SHARES_TEMP2_POSITION_CENTER'),
            'right' => Loc::getMessage('C_SHARES_TEMP2_POSITION_RIGHT')
        ),
        'DEFAULT' => 'center'
    );
    $arTemplateParameters['SEE_ALL_TEXT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SHARES_TEMP2_SEE_ALL_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_SHARES_TEMP2_SEE_ALL_TEXT_DEFAULT')
    );
}