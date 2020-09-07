<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\net\Url;

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
    'AUTO' => 'N',
    'TAB' => null
], $arParams);

include(__DIR__.'/parts/settings.php');

$arResult['URL'] = [
    'CATALOG' => ArrayHelper::getValue($arParams, 'CATALOG_URL'),
    'BASKET' => ArrayHelper::getValue($arParams, 'BASKET_URL'),
    'ORDER' => ArrayHelper::getValue($arParams, 'ORDER_URL'),
    'COMPARE' => ArrayHelper::getValue($arParams, 'COMPARE_URL'),
    'PERSONAL' => ArrayHelper::getValue($arParams, 'PERSONAL_URL'),
    'CONSENT' => ArrayHelper::getValue($arParams, 'CONSENT_URL')
];

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

$arResult['AUTO'] = $arParams['AUTO'] === 'Y';
$arResult['TAB'] = $arParams['TAB'];
$arResult['BASKET']['SHOW'] = ArrayHelper::getValue($arParams, 'BASKET_SHOW') == 'Y';
$arResult['DELAYED']['SHOW'] = ArrayHelper::getValue($arParams, 'DELAYED_SHOW') == 'Y';
$arResult['COMPARE']['SHOW'] = ArrayHelper::getValue($arParams, 'COMPARE_SHOW') == 'Y';

$arResult['PERSONAL'] = [
    'SHOW' => ArrayHelper::getValue($arParams, 'PERSONAL_SHOW') == 'Y'
];

if (!Loader::includeModule('catalog') || !Loader::includeModule('sale'))
    $arResult['DELAYED']['SHOW'] = false;

if (empty($arResult['URL']['COMPARE']))
    $arResult['COMPARE']['SHOW'] = false;