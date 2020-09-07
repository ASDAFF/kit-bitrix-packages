<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arParameters = [];
$arParameters['FILES_MP4'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_FILES_MP4'),
    'TYPE' => 'STRING'
];

$arParameters['FILES_WEBM'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_FILES_WEBM'),
    'TYPE' => 'STRING'
];

$arParameters['FILES_OGV'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_FILES_OGV'),
    'TYPE' => 'STRING'
];

$arParameters['PICTURE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_PICTURE'),
    'TYPE' => 'STRING'
];

$arParameters['AUTOPLAY'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_AUTOPLAY'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['CONTROLS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_CONTROLS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arParameters['MUTE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_MUTE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['LOOP'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_LOOP'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['PRELOAD'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_PRELOAD'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_PRELOAD_NONE'),
        'metadata' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_PRELOAD_METADATA'),
        'auto' => Loc::getMessage('C_SYSTEM_VIDEO_TAG_PRELOAD_AUTO')
    ],
    'DEFAULT' => 'metadata'
];

$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => $arParameters
];