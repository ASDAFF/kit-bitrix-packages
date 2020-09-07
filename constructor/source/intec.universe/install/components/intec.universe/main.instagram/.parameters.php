<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arComponentParameters['PARAMETERS'] = array(
    'ACCESS_TOKEN' => array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('MAIN_INSTAGRAM_ACCESS_TOKEN'),
        'TYPE' => 'STRING',
        'VALUES' => '',
        'REFRESH' => 'Y'
    ),
    'COUNT_ITEMS' => array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('MAIN_INSTAGRAM_COUNT_ITEMS'),
        'TYPE' => 'STRING',
        'VALUES' => '',
        'DEFAULT' => '10'
    ),
    'CACHE_PATH' => array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('MAIN_INSTAGRAM_CACHE_PATH'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'upload/intec.universe/instagram/cache/#SITE_DIR#'
    ),
    'CACHE_TIME' => array()
);