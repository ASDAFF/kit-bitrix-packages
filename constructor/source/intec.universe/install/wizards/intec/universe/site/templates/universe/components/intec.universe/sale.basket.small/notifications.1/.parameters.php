<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;


$arTemplateParameters = array(
    'BASKET_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_NOTIFICATIONS_1_BASKET_URL'),
        'TYPE' => 'STRING'
    ),
    'COMPARE_SHOW' => array(
        'HIDDEN' => 'Y'
    )
);