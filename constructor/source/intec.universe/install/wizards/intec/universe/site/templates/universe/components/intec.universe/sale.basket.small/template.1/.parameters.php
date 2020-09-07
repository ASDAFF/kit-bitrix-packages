<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = [
    'SETTINGS_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ],
    'LAZYLOAD_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],
    'BASKET_SHOW' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BASKET_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ],
    'FORM_SHOW' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_FORM_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ],
    'PERSONAL_SHOW' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_PERSONAL_SHOW'),
        'TYPE' => 'CHECKBOX'
    ],
    'AUTO' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_AUTO'),
        'TYPE' => 'CHECKBOX'
    ],
    'CATALOG_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_CATALOG_URL'),
        'TYPE' => 'STRING'
    ],
    'BASKET_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BASKET_URL'),
        'TYPE' => 'STRING'
    ],
    'ORDER_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_ORDER_URL'),
        'TYPE' => 'STRING'
    ],
    'COMPARE_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_COMPARE_URL'),
        'TYPE' => 'STRING'
    ],
    'PERSONAL_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_PERSONAL_URL'),
        'TYPE' => 'STRING'
    ],
    'CONSENT_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_CONSENT_URL'),
        'TYPE' => 'STRING'
    ],
    'PANEL_SHOW' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_PANEL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],
];

if ($arCurrentValues['FORM_SHOW'] === 'Y') {
    $arTemplateParameters['FORM_TITLE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_FORM_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_FORM_TITLE_DEFAULT')
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