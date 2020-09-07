<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;


$arTemplateParameters = array(
    'BASKET_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BASKET_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'CATALOG_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_CATALOG_URL'),
        'TYPE' => 'STRING'
    ),
    'BASKET_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BASKET_URL'),
        'TYPE' => 'STRING'
    ),
    'ORDER_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_ORDER_URL'),
        'TYPE' => 'STRING'
    ),
    'COMPARE_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_COMPARE_URL'),
        'TYPE' => 'STRING'
    )
);

if (Loader::includeModule('catalog')) {
    require_once('parameters/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    require_once('parameters/lite.php');
}