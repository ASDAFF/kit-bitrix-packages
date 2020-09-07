<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_NAME'),
    'DESCRIPTION' => Loc::getMessage('C_SALE_ORDER_FAST_DESCRIPTION'),
    'CACHE_PATH' => 'Y',
    'SORT' => 1,
    'PATH' => array(
        'ID' => 'Universe'
    ),
);