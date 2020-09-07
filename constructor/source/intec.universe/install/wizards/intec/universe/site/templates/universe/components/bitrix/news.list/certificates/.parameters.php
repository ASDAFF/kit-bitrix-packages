<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [
    'SETTINGS_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('N_L_GALLERY_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ],

    'LAZYLOAD_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('N_L_GALLERY_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],

    'DESKTOP_TEMPLATE' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('N_L_GALLERY_DESKTOP_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'settings' => Loc::getMessage('N_L_GALLERY_DESKTOP_TEMPLATE_SETTINGS'),
            'list' => Loc::getMessage('N_L_GALLERY_DESKTOP_TEMPLATE_LIST'),
            'tiles' => Loc::getMessage('N_L_GALLERY_DESKTOP_TEMPLATE_TILES')
        ]
    ]
];
