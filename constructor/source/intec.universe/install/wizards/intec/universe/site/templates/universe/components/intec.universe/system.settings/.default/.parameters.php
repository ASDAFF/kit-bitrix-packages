<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['VARIABLES_VARIANT'] = [
    'NAME' => Loc::getMessage('C_SYSTEM_SETTINGS_VARIABLES_VARIANT'),
    'PARENT' => 'BASE',
    'TYPE' => 'STRING',
    'DEFAULT' => 'variant'
];