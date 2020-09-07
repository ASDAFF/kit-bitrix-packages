<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\core\net\Url;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'AJAX' => 'N',
    'COLLAPSED' => 'N',
    'PRICES_EXPANDED' => [],
    'TYPE_A_PRECISION' => 2,
    'TYPE_B_PRECISION' => 2,
    'MOBILE' => 'N',
    'POPUP_USE' => 'Y'
], $arParams);

$arParams['FILTER_VIEW_MODE'] = 'VERTICAL';

if (!Type::isArray($arParams['PRICES_EXPANDED']))
    $arParams['PRICES_EXPANDED'] = [];

$arResult['AJAX'] = $arParams['AJAX'] === 'Y';
$arResult['VISUAL'] = [
    'DISPLAY' => false,
    'VIEW' => $arParams['FILTER_VIEW_MODE'],
    'COLLAPSED' => $arParams['COLLAPSED'] == 'Y',
    'TYPE' => [
        'A' => [
            'PRECISION' => Type::toInteger($arParams['TYPE_A_PRECISION'])
        ],
        'B' => [
            'PRECISION' => Type::toInteger($arParams['TYPE_B_PRECISION'])
        ]
    ],
    'MOBILE' => $arParams['MOBILE'] === 'Y',
    'POPUP' => [
        'USE' => $arParams['POPUP_USE'] === 'Y'
    ]
];

if (Loader::includeModule('intec.seo')) {
    $APPLICATION->IncludeComponent('intec.seo:filter.loader', '', [
        'FILTER_RESULT' => $arResult
    ], $component);
}

if ($arResult['AJAX'])
    $arResult['VISUAL']['POPUP']['USE'] = false;

if ($arResult['VISUAL']['MOBILE'])
    $arResult['VISUAL']['COLLAPSED'] = false;

if (Loader::includeModule('intec.startshop'))
    include(__DIR__.'/modifier/lite.php');

foreach ($arResult['ITEMS'] as $sKey => &$arItem) {
    if (!isset($arItem['DISPLAY_EXPANDED']))
        $arItem['DISPLAY_EXPANDED'] = 'N';

    if (isset($arItem['PRICE']) && $arItem['PRICE']) {
        $arItem['DISPLAY_TYPE'] = 'A';

        if (ArrayHelper::isIn($sKey, $arParams['PRICES_EXPANDED']))
            $arItem['DISPLAY_EXPANDED'] = 'Y';

        if ($arItem['VALUES']['MIN']['VALUE'] !== $arItem['VALUES']['MAX']['VALUE'])
            $arResult['VISUAL']['DISPLAY'] = true;
    } else {
        if ($arItem['DISPLAY_TYPE'] === 'A' || $arItem['DISPLAY_TYPE'] === 'B') {
            if (isset($arItem['VALUES']['MIN']['VALUE']) && isset($arItem['VALUES']['MAX']['VALUE']))
                if ($arItem['VALUES']['MIN']['VALUE'] !== $arItem['VALUES']['MAX']['VALUE'])
                    $arResult['VISUAL']['DISPLAY'] = true;
        } else if (!empty($arItem['VALUES'])) {
            $arResult['VISUAL']['DISPLAY'] = true;
        }
    }
}

unset($arItem);

if ($arResult['VISUAL']['COLLAPSED'])
    foreach ($arResult['ITEMS'] as $arItem)
        if ($arItem['DISPLAY_EXPANDED'] === 'Y') {
            $arResult['VISUAL']['COLLAPSED'] = false;
            break;
        }

$oRequest = Core::$app->request;

if (($oRequest->getIsAjax() || isset($_SERVER['HTTP_BX_AJAX'])) && $oRequest->get('ajax') === 'y') {
    $oUrl = new Url(Html::decode($arResult['FILTER_URL']));
    $sQuery = $oUrl->getQuery()->get('q');
    $sQuery = Encoding::convert($sQuery, null, Encoding::UTF8);

    $oUrl->getQuery()->set('q', $sQuery);
    $arResult['FILTER_URL'] = Html::encode($oUrl->build());
}