<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    'NAME' => Loc::getMessage('LANDING_WIDGET_NAME'),
    'DESCRIPTION' => Loc::getMessage('LANDING_WIDGET_DESCRIPTION'),
    'ICON' => '/images/sections_top_count.gif',
    'CACHE_PATH' => 'Y',
    'SORT' => 1,
    'PATH' => array(
        'ID' => 'Universe'
    ),
);