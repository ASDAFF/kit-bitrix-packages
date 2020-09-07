<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 * @var array $arParts
 * @var InnerTemplate $desktopTemplate
 * @var InnerTemplate $fixedTemplate
 * @var InnerTemplate $mobileTemplate
 */

$arParts['AUTHORIZATION'] = function () use (&$arTemplateParameters) {
    unset($arTemplateParameters['LOGIN_URL']);
    unset($arTemplateParameters['PROFILE_URL']);
    unset($arTemplateParameters['PASSWORD_URL']);
    unset($arTemplateParameters['REGISTER_URL']);
};

if (!empty($desktopTemplate)) {
    $arTemplateParameters['AUTHORIZATION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_AUTHORIZATION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($fixedTemplate)) {
    $arTemplateParameters['AUTHORIZATION_SHOW_FIXED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_AUTHORIZATION_SHOW_FIXED'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['AUTHORIZATION_SHOW_MOBILE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_AUTHORIZATION_SHOW_MOBILE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['LOGIN_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_LOGIN_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['PROFILE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_PROFILE_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['PASSWORD_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_PASSWORD_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['REGISTER_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_REGISTER_URL'),
    'TYPE' => 'STRING'
];