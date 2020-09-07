<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [
     'DISPLAY_TAB_ALL' => [
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('C_NEWS_LIST_DISPLAY_TAB_ALL')
    ],
    'DESCRIPTION_DISPLAY' => [
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('C_NEWS_LIST_DESCRIPTION_DISPLAY'),
        "DEFAULT" => 'Y'
    ],
    'PICTURE_DISPLAY' => [
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('C_NEWS_LIST_PICTURE_DISPLAY'),
        "DEFAULT" => 'Y'
    ]
];
