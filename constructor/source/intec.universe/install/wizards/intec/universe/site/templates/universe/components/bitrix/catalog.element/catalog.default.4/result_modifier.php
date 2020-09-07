<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$bBase = false;
$bLite = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale'))
    $bBase = true;
else if (Loader::includeModule('intec.startshop'))
    $bLite = true;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    /*'PROPERTY_MARKS_NEW' => null,
    'PROPERTY_MARKS_HIT' => null,
    'PROPERTY_MARKS_RECOMMEND' => null,*/
    'PROPERTY_ARTICLE' => null,
    'PROPERTY_BRAND' => null,
    'PROPERTY_PICTURES' => null,
    'PROPERTY_DOCUMENTS' => null,
    'OFFERS_PROPERTY_ARTICLE' => null,
    'OFFERS_PROPERTY_PICTURES' => null,
    //'MARKS_SHOW' => 'N',
    'ARTICLE_SHOW' => 'N',
    'BRAND_SHOW' => 'N',
    'VOTE_SHOW' => 'N',
    'VOTE_TYPE' => 'rating',
    'GALLERY_SHOW' => 'N',
    'GALLERY_ACTION' => 'none',
    'GALLERY_ZOOM' => 'N',
    'GALLERY_PREVIEW_SHOW' => 'N',
    'ACTION' => 'buy',
    'DESCRIPTION_PREVIEW_SHOW' => 'N',
    'DESCRIPTION_PREVIEW_POSITION' => 'left',
    'PROPERTIES_PREVIEW_SHOW' => 'N',
    'PROPERTIES_PREVIEW_COUNT' => 2,
    'PROPERTIES_PREVIEW_POSITION' => 'left',
    'PROPERTIES_PREVIEW_COLUMNS' => 2,
    'DESCRIPTION_DETAIL_SHOW' => 'N',
    'PROPERTIES_DETAIL_SHOW' => 'N',
    'DOCUMENTS_SHOW' => 'N',
    'INFORMATION_PAYMENT_SHOW' => 'N',
    'INFORMATION_PAYMENT_PATH' => null,
    'INFORMATION_SHIPMENT_SHOW' => 'N',
    'INFORMATION_SHIPMENT_PATH' => null
], $arParams);

include(__DIR__.'/modifiers/base/catalog.php');
include(__DIR__.'/modifiers/catalog.php');

include(__DIR__.'/modifiers/properties.php');
include(__DIR__.'/modifiers/properties.offers.php');
include(__DIR__.'/modifiers/gallery.php');
include(__DIR__.'/modifiers/product.php');
include(__DIR__.'/modifiers/order.fast.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'ARTICLE' => [
        'SHOW' => $arParams['ARTICLE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_ARTICLE'])
    ],
    'BRAND' => [
        'SHOW' => $arParams['BRAND_SHOW'] === 'Y' && !empty($arResult['DATA']['BRAND'])
    ],
    'VOTE' => [
        'SHOW' => $arParams['VOTE_SHOW'] === 'Y',
        'TYPE' => ArrayHelper::fromRange(['rating', 'vote_avg'], $arParams['VOTE_TYPE'])
    ],
    'QUANTITY' => [
        'SHOW' => $arParams['QUANTITY_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['number', 'text', 'logic'], $arParams['QUANTITY_MODE']),
        'BOUNDS' => [
            'FEW' => Type::toFloat($arParams['QUANTITY_BOUNDS_FEW']),
            'MANY' => Type::toFloat($arParams['QUANTITY_BOUNDS_MANY'])
        ]
    ],
    'GALLERY' => [
        'SHOW' => $arParams['GALLERY_SHOW'] === 'Y',
        'ACTION' => ArrayHelper::fromRange(['none', 'source', 'popup'], $arParams['GALLERY_ACTION']),
        'ZOOM' => $arParams['GALLERY_ZOOM'] === 'Y',
        'PREVIEW' => [
            'SHOW' => $arParams['GALLERY_PREVIEW_SHOW'] === 'Y'
        ]
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y' && $arParams['ACTION'] === 'buy'
    ],
    'PRICE' => [
        'RANGE' => $arParams['PRICE_RANGE'] === 'Y',
        'EXTENDED' => $arParams['PRICE_EXTENDED'] === 'Y'
    ],
    'ADDITIONAL' => [
        'SHOW' => $arParams['ADDITIONAL_SHOW'] === 'Y' && !empty($arResult['DATA']['ADDITIONAL'])
    ],
    'DESCRIPTION' => [
        'PREVIEW' => [
            'SHOW' => $arParams['DESCRIPTION_PREVIEW_SHOW'] === 'Y' && !empty($arResult['PREVIEW_TEXT'])
        ],
        'DETAIL' => [
            'SHOW' => $arParams['DESCRIPTION_DETAIL_SHOW'] === 'Y' && !empty($arResult['DETAIL_TEXT'])
        ]
    ],
    'PROPERTIES' => [
        'PREVIEW' => [
            'SHOW' => $arParams['PROPERTIES_PREVIEW_SHOW'] === 'Y' && !empty($arResult['DATA']['PROPERTIES']),
            'POSITION' => ArrayHelper::fromRange(['left', 'right'], $arParams['PROPERTIES_PREVIEW_POSITION']),
            'COLUMNS' => ArrayHelper::fromRange([2, 3, 4], $arParams['PROPERTIES_PREVIEW_COLUMNS'])
        ],
        'DETAIL' => [
            'SHOW' => $arParams['PROPERTIES_DETAIL_SHOW'] === 'Y' && !empty($arResult['DISPLAY_PROPERTIES'])
        ]
    ],
    'INFORMATION' => [
        'PAYMENT' => [
            'SHOW' => $arParams['INFORMATION_PAYMENT_SHOW'] === 'Y' && !empty($arParams['INFORMATION_PAYMENT_PATH']),
            'PATH' => $arParams['INFORMATION_PAYMENT_PATH']
        ],
        'SHIPMENT' => [
            'SHOW' => $arParams['INFORMATION_SHIPMENT_SHOW'] === 'Y' && !empty($arParams['INFORMATION_SHIPMENT_PATH']),
            'PATH' => $arParams['INFORMATION_SHIPMENT_PATH']
        ],
        'STORES' => [
            'SHOW' => $arParams['USE_STORE'] === 'Y' && !empty(array_filter($arParams['STORES']))
        ]
    ],
    'DOCUMENTS' => [
        'SHOW' => $arParams['DOCUMENTS_SHOW'] === 'Y' && !empty($arParams['PROPERTY_DOCUMENTS'])
    ]
];

/*if ($arVisual['MARKS']['SHOW'] && empty($arData['MARKS']))
    $arVisual['MARKS']['SHOW'] = false;*/

if ($arVisual['QUANTITY']['SHOW'] && $arVisual['QUANTITY']['MODE'] === 'text') {
    if ($arVisual['QUANTITY']['BOUNDS']['FEW'] > 0 && $arVisual['QUANTITY']['BOUNDS']['MANY'] > 0) {
        if ($arVisual['QUANTITY']['BOUNDS']['FEW'] >= $arVisual['QUANTITY']['BOUNDS']['MANY']) {
            if ($arVisual['QUANTITY']['BOUNDS']['FEW'] == $arVisual['QUANTITY']['BOUNDS']['MANY'])
                $arVisual['QUANTITY']['BOUNDS']['MANY'] = ++$arVisual['QUANTITY']['BOUNDS']['MANY'];

            $arVisual['QUANTITY']['BOUNDS'] = [
                'FEW' => $arVisual['QUANTITY']['BOUNDS']['MANY'],
                'MANY' => $arVisual['QUANTITY']['BOUNDS']['FEW']
            ];
        }
    } else {
        $arVisual['QUANTITY']['MODE'] = 'number';
    }
}

if ($arVisual['PRICE']['RANGE'] && !$arVisual['COUNTER']['SHOW'])
    $arVisual['PRICE']['RANGE'] = false;

if ($arVisual['PROPERTIES']['PREVIEW']['POSITION'] === 'left' && $arVisual['PROPERTIES']['PREVIEW']['COLUMNS'] > 3)
    $arVisual['PROPERTIES']['PREVIEW']['COLUMNS'] = 3;

if ($arVisual['DOCUMENTS']['SHOW'] && empty($arResult['DATA']['DOCUMENTS']))
    $arVisual['DOCUMENTS']['SHOW']= false;

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$this->getComponent()->setResultCacheKeys(['PREVIEW_PICTURE', 'DETAIL_PICTURE']);