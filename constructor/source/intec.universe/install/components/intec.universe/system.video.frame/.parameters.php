<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arParameters = [];
$arParameters['URL'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_URL'),
    'TYPE' => 'STRING'
];

$arParameters['AUTOPLAY'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_AUTOPLAY'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['ANNOTATIONS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_ANNOTATIONS'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['CONTROLS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_CONTROLS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        0 => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_CONTROLS_0'),
        1 => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_CONTROLS_1'),
        2 => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_CONTROLS_2')
    ],
];

$arParameters['KEYBOARD'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_KEYBOARD'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['MUTE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_MUTE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['LOOP'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_LOOP'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['FULLSCREEN'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_FULLSCREEN'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['INFORMATION'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_INFORMATION'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['START'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_START'),
    'TYPE' => 'STRING'
];

$arParameters['END'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_END'),
    'TYPE' => 'STRING'
];

$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => $arParameters
];