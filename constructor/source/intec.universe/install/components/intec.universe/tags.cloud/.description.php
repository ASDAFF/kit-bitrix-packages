<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    'NAME' => Loc::getMessage('C_TAGS_CLOUD_NAME'),
    'DESCRIPTION' => Loc::getMessage('C_TAGS_CLOUD_DESCRIPTION'),
    'ICON' => '/images/sections_top_count.gif',
    'CACHE_PATH' => 'Y',
    'SORT' => 800,
    'PATH' => array(
        'ID' => 'Universe'
    ),
);