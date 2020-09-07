<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['ADAPTATION_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_ADAPTATION_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ADAPTATION_USE'] === 'Y') {
    $arTemplateParameters['ADAPTATION_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_ADAPTATION_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'cover' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_ADAPTATION_MODE_COVER'),
            'contain' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_ADAPTATION_MODE_CONTAIN')
        ],
        'DEFAULT' => 'cover'
    ];
}

$arTemplateParameters['WIDTH'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_WIDTH'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'parent' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_WIDTH_PARENT')
    ],
    'ADDITIONAL_VALUES' => 'Y',
    'DEFAULT' => 'parent'
];

$arTemplateParameters['HEIGHT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_HEIGHT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'parent' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_DEFAULT_HEIGHT_PARENT')
    ],
    'ADDITIONAL_VALUES' => 'Y',
    'DEFAULT' => 'parent'
];