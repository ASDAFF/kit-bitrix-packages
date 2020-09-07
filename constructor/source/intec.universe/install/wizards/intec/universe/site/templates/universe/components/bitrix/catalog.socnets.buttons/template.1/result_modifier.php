<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arParams = ArrayHelper::merge([
    'URL_TO_LIKE' => null,
    'TITLE' => null,
    'DESCRIPTION' => null,
    'IMAGE' => null,
    'FB_USE' => 'N',
    'TW_USE' => 'N',
    'TW_VIA' => null,
    'TW_HASHTAGS' => null,
    'TW_RELATED' => null,
    'GP_USE' => 'N',
    'VK_USE' => 'N',
    'PINTEREST_USE' => 'N',
    'OK_USE' => 'N'
], $arParams);

$protocol = (CMain::IsHTTPS()) ? "https://" : "http://";
$arResult["URL_TO_LIKE"] = $protocol.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

$arVisual = [
    'VK' => [
        'SHOW' => $arParams['VK_USE'] === 'Y'
    ],
    'TW' => [
        'SHOW' => $arParams['TW_USE'] === 'Y'
    ],
    'FB' => [
        'SHOW' => $arParams['FB_USE'] === 'Y'
    ],
    'OK' => [
        'SHOW' => $arParams['OK_USE'] === 'Y'
    ],
    'PINTEREST' => [
        'SHOW' => $arParams['PINTEREST_USE'] === 'Y'
    ]
];

$arResult['VISUAL'] = $arVisual;

if (!empty($arResult['IMAGE'])) {
    $protocol = (CMain::IsHTTPS()) ? "https://" : "http://";
    $arResult["IMAGE"] = $protocol.$_SERVER["HTTP_HOST"].$arResult["IMAGE"];
}