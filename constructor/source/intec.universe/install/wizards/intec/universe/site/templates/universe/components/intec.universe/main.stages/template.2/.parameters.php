<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['LINE_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_STAGES_TEMP2_LINE_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => '2',
        3 => '3',
        4 => '4'
    ],
    'DEFAULT' => 4
];
$arTemplateParameters['ELEMENT_SHOW_DESCRIPTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_STAGES_TEMP2_ELEMENT_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];