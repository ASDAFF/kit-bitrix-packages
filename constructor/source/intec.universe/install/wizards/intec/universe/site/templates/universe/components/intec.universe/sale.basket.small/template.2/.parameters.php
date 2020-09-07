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
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ),
    'LAZYLOAD_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],
    'BASKET_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_BASKET_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'FORM_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_FORM_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'PERSONAL_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_PERSONAL_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'AUTO' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_AUTO'),
        'TYPE' => 'CHECKBOX'
    ),
    'CATALOG_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_CATALOG_URL'),
        'TYPE' => 'STRING'
    ),
    'BASKET_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_BASKET_URL'),
        'TYPE' => 'STRING'
    ),
    'ORDER_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_ORDER_URL'),
        'TYPE' => 'STRING'
    ),
    'COMPARE_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_COMPARE_URL'),
        'TYPE' => 'STRING'
    ),
    'PERSONAL_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_PERSONAL_URL'),
        'TYPE' => 'STRING'
    ),
    'CONSENT_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_CONSENT_URL'),
        'TYPE' => 'STRING'
    ),
    'SBERBANK_ICON_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_SBERBANK_ICON_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'QIWI_ICON_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_QIWI_ICON_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'YANDEX_MONEY_ICON_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_YANDEX_MONEY_ICON_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'VISA_ICON_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_VISA_ICON_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'MASTERCARD_ICON_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_MASTERCARD_ICON_SHOW'),
        'TYPE' => 'CHECKBOX'
    ),
    'REGISTER_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_REGISTER_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/personal/profile/'
    ),
    'FORGOT_PASSWORD_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_FORGOT_PASSWORD_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/personal/profile/?forgot_password=yes'
    ),
    'PROFILE_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_PROFILE_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/personal/profile/'
    ),
    'PANEL_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_PANEL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    )
);

if ($arCurrentValues['FORM_SHOW'] === 'Y') {
    $arTemplateParameters['FORM_TITLE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_FORM_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_FORM_TITLE_DEFAULT')
    ];
}

if (Loader::includeModule('sale') && Loader::includeModule('catalog')) {
    include('parameters/base/catalog.php');
}

if (Loader::includeModule('form')) {
    include('parameters/base/forms.php');
} else if (Loader::includeModule('intec.startshop')) {
    include('parameters/lite/forms.php');
}