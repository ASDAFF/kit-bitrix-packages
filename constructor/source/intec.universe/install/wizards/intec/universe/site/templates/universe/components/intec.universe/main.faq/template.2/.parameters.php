<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

/** VISUAL */
$arTemplateParameters['THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_2_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'dark' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_2_THEME_DARK'),
        'light' => Loc::getMessage('C_MAIN_FAQ_TEMPLATE_2_THEME_LIGHT')
    ],
    'DEFAULT' => 'dark'
];