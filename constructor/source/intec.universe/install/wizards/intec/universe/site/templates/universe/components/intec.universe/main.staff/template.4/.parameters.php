<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_4_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_4_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_4_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

$arTemplateParameters['SLIDER_NAV'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_4_SLIDER_NAV'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];