<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    'NAME' => Loc::getMessage('C_SCHEDULE_NAME'),
    'DESCRIPTION' => Loc::getMessage('C_SCHEDULE_DESCRIPTION'),
    'ICON' => '/images/shares_top_count.gif',
    'CACHE_PATH' => 'Y',
    'SORT' => 1,
    'PATH' => array(
        'ID' => 'Universe'
    ),
);