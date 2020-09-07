<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arTemplateParameters['ORIENTATION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MARKERS_TEMP1_ORIENTATION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'horizontal' => Loc::getMessage('C_MARKERS_TEMP1_ORIENTATION_HORIZONTAL'),
        'vertical' => Loc::getMessage('C_MARKERS_TEMP1_ORIENTATION_VERTICAL')
    ],
    'DEFAULT' => 'vertical'
];