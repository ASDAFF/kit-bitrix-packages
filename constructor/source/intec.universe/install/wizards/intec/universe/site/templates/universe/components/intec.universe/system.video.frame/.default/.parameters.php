<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['ADAPTATION_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_ADAPTATION_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ADAPTATION_USE'] === 'Y') {
    $arTemplateParameters['ADAPTATION_RATIO'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_ADAPTATION_RATIO'),
        'TYPE' => 'LIST',
        'VALUES' => [
            '1:1' => '1:1',
            '4:3' => '4:3',
            '16:9' => '16:9',
            '16:10' => '16:10'
        ],
        'ADDITIONAL_VALUES' => 'Y',
        'DEFAULT' => '16:9'
    ];

    $arTemplateParameters['ADAPTATION_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_ADAPTATION_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'cover' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_ADAPTATION_MODE_COVER'),
            'contain' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_ADAPTATION_MODE_CONTAIN')
        ],
        'DEFAULT' => 'cover'
    ];
}

$arTemplateParameters['WIDTH'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_WIDTH'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'parent' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_WIDTH_PARENT')
    ],
    'ADDITIONAL_VALUES' => 'Y',
    'DEFAULT' => 'parent'
];

$arTemplateParameters['HEIGHT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_HEIGHT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'parent' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DEFAULT_HEIGHT_PARENT')
    ],
    'ADDITIONAL_VALUES' => 'Y',
    'DEFAULT' => 'parent'
];