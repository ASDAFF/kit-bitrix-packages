<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\net\Url;
use intec\template\Properties;

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('iblock'))
    return;

/**
 * @global $APPLICATION
 * @global $USER
 * @var array $arParams
 * @var array $arResult
 */

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'BASKET_SHOW' => 'Y',
    'DELAY_SHOW' => 'Y',
    'CATALOG_URL' => null,
    'BASKET_URL' => null,
    'ORDER_URL' => null,
    'COMPARE_URL' => null,
    'PERSONAL_URL' => null,
    'REGISTER_URL' => null,
    'CONSENT_URL' => null,
    'PROFILE_URL' => null,
    'FORGOT_PASSWORD_URL' => null,
    'HIDDEN' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/parts/settings.php');

$arResult['URL'] = [
    'CATALOG' => $arParams['CATALOG_URL'],
    'BASKET' => $arParams['BASKET_URL'],
    'ORDER' => $arParams['ORDER_URL'],
    'COMPARE' => $arParams['COMPARE_URL'],
    'PERSONAL' => $arParams['PERSONAL_URL'],
    'REGISTER' => $arParams['REGISTER_URL'],
    'CONSENT' => $arParams['CONSENT_URL'],
    'PROFILE' => $arParams['PROFILE_URL'],
    'FORGOT_PASSWORD' => $arParams['FORGOT_PASSWORD_URL']
];

$arVisual = [
    'PANEL' => [
        'HIDDEN' => $arParams['HIDDEN'] === 'Y'
    ]
];

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

$arResult['URL']['DELAYED'] = '';

if (!empty($arResult['URL']['BASKET'])) {
    $sUrl = new Url($arResult['URL']['BASKET']);
    $sUrl->getQuery()->set('delay', 'Y');
    $sUrl = $sUrl->build();
    $arResult['URL']['DELAYED'] = $sUrl;
}

unset($sUrl);

$arResult['BASKET']['SHOW'] = $arParams['BASKET_SHOW'] === 'Y';
$arResult['DELAYED']['SHOW'] = $arParams['DELAYED_SHOW'] === 'Y';
$arResult['COMPARE']['SHOW'] = $arParams['COMPARE_SHOW'] === 'Y';
$arResult['FORM'] = [
    'SHOW' => ArrayHelper::getValue($arParams, 'FORM_SHOW') == 'Y',
    'TITLE' => ArrayHelper::getValue($arParams, 'FORM_TITLE')
];

$arResult['PERSONAL'] = [
    'SHOW' => ArrayHelper::getValue($arParams, 'PERSONAL_SHOW') == 'Y',
    'AUTHORIZED' => CUser::IsAuthorized() ? true : false
];

if (empty($arResult['URL']['COMPARE']))
    $arResult['COMPARE']['SHOW'] = false;

if (empty($arResult['URL']['PERSONAL']))
    $arResult['PERSONAL']['SHOW'] = false;

if (defined('EDITOR'))
    return;

if (!Loader::includeModule('catalog') || !Loader::includeModule('sale'))
    $arResult['DELAYED']['SHOW'] = false;

if (Loader::includeModule('form')) {
    include(__DIR__.'/modifiers/base/forms.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/modifiers/lite/forms.php');
}

if (empty($arResult['FORM']['ID']))
    $arResult['FORM']['SHOW'] = false;