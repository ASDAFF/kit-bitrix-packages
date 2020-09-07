<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

Loc::loadMessages(__FILE__);

$arParams = ArrayHelper::merge([
    'ACTION' => null,
    'NAME' => null,
    'LAZYLOAD_USE' => 'N',
    'BASKET_URL' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$bBase = Loader::includeModule('catalog') && Loader::includeModule('sale');
$bLite = !$bBase && Loader::includeModule('intec.startshop');

$oRequest = Core::$app->request;
$arVisual = [
    'ACTION' => ArrayHelper::fromRange([
        'none',
        'buy',
        'detail'
    ], $arParams['ACTION']),
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'LABELS' => [
        'SHOW' => false
    ],
    'PROPERTIES' => [
        'SHOW' => false
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

$arResult['AJAX'] = false;

if ($oRequest->getIsPost()) {
    $arResult['AJAX'] = $oRequest->post('ajax_action');

    if ($arResult['AJAX'] !== 'Y')
        $arResult['AJAX'] = $oRequest->post('compare_result_reload');

    $arResult['AJAX'] = $arResult['AJAX'] === 'Y';
}

if (!empty($arResult['ITEMS'])) {
    include(__DIR__ . '/modifiers/items.php');

    if ($bBase) {
        include(__DIR__ . '/modifiers/base/catalog.php');
    } else if ($bLite) {
        include(__DIR__ . '/modifiers/lite/catalog.php');
    }

    include(__DIR__ . '/modifiers/pictures.php');
    include(__DIR__ . '/modifiers/properties.php');

    foreach ($arResult['PROPERTIES'] as $arProperty) {
        if (!$arResult['DIFFERENT'] || $arProperty['DIFFERENT']) {
            if ($arProperty['HIDDEN']) {
                $arVisual['LABELS']['SHOW'] = true;
            } else {
                $arVisual['PROPERTIES']['SHOW'] = true;
            }
        }

        if ($arVisual['LABELS']['SHOW'] && $arVisual['PROPERTIES']['SHOW'])
            break;
    }

    unset($arProperty);
}

$arResult['URL'] = [
    'BASKET' => $arParams['BASKET_URL']
];

foreach ($arResult['URL'] as $sCode => $sUrl)
    $arResult['URL'][$sCode] = StringHelper::replaceMacros($sUrl, $arMacros);

unset($sCode, $sUrl);

$arResult['VISUAL'] = $arVisual;