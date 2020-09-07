<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    'NAME' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_NAME'),
    'DESCRIPTION' => Loc::getMessage('C_SYSTEM_VIDEO_FRAME_DESCRIPTION'),
    'CACHE_PATH' => 'Y',
    'SORT' => 1,
    'PATH' => [
        'ID' => 'Universe'
    ]
];