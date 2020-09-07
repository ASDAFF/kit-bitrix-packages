<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = array(
    'SETTINGS_USE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ),
    'BASKET_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_BASKET_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'FORM_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_FORM_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'PERSONAL_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_PERSONAL_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'CATALOG_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_CATALOG_URL'),
        'TYPE' => 'STRING'
    ),
    'BASKET_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_BASKET_URL'),
        'TYPE' => 'STRING'
    ),
    'ORDER_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_ORDER_URL'),
        'TYPE' => 'STRING'
    ),
    'COMPARE_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_COMPARE_URL'),
        'TYPE' => 'STRING'
    ),
    'PERSONAL_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_PERSONAL_URL'),
        'TYPE' => 'STRING'
    ),
    'CONSENT_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_CONSENT_URL'),
        'TYPE' => 'STRING'
    ),
    'REGISTER_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_REGISTER_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/personal/profile/'
    ),
    'FORGOT_PASSWORD_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_FORGOT_PASSWORD_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/personal/profile/?forgot_password=yes'
    ),
    'PROFILE_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_PROFILE_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/personal/profile/'
    ),
    'HIDDEN' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_HIDDEN'),
        'TYPE' => 'CHECKBOX'
    ),
);


if (Loader::includeModule('sale') && Loader::includeModule('catalog')) {
    include('parameters/base/catalog.php');
}
