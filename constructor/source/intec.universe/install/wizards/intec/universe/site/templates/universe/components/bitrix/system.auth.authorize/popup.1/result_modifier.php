<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arResult['ERRORS'] = [];
$arResult['MESSAGES'] = [];

if (!empty($arParams['~AUTH_RESULT']) && Type::isArray($arParams['~AUTH_RESULT']))
    if (!empty($arParams['~AUTH_RESULT']['MESSAGE'])) {
        if ($arParams['~AUTH_RESULT']['TYPE'] === 'OK') {
            $arResult['MESSAGES'][] = $arParams['~AUTH_RESULT']['MESSAGE'];
        } else {
            $arResult['ERRORS'][] = $arParams['~AUTH_RESULT']['MESSAGE'];
        }
    }

if (!empty($arResult['ERROR_MESSAGE']) && Type::isArray($arResult['ERROR_MESSAGE']))
    $arResult['ERRORS'][] = $arResult['ERROR_MESSAGE']['MESSAGE'];

unset($arResult['ERROR_MESSAGE']);
unset($arError);

$arResult['URL'] = [
    'AUTHORIZE' => $arResult['AUTH_URL'] ? $arResult['AUTH_URL'] : SITE_DIR.'auth/',
    'BACK' => $arResult['BACKURL'],
    'REGISTER' => $arResult['AUTH_REGISTER_URL'],
    'RESTORE' => $arResult['AUTH_FORGOT_PASSWORD_URL']
];

if (!empty($arParams['AUTH_URL']))
    $arResult['URL']['AUTHORIZE'] = $arParams['AUTH_URL'];

if (!empty($arParams['BACKURL']))
    $arResult['URL']['BACK'] = $arParams['BACKURL'];

if (!empty($arParams['AUTH_REGISTER_URL']))
    $arResult['URL']['REGISTER'] = $arParams['AUTH_REGISTER_URL'];

if (!empty($arParams['AUTH_FORGOT_PASSWORD_URL']))
    $arResult['URL']['RESTORE'] = $arParams['AUTH_FORGOT_PASSWORD_URL'];

foreach ($arResult['URL'] as $sKey => $sValue) {
    if (!empty($sValue)) {
        $arResult['URL'][$sKey] = StringHelper::replaceMacros($sValue, $arMacros);
    } else {
        $arResult['URL'][$sKey] = null;
    }
}

unset($sKey);
unset($sValue);