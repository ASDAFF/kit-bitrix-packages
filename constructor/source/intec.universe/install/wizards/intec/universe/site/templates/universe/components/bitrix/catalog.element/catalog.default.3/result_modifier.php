<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
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

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arParams = ArrayHelper::merge([
    'PROPERTY_ARTICLE' => null,
    'PROPERTY_BRAND' => null,
    'PROPERTY_MARKS_HIT' => null,
    'PROPERTY_MARKS_NEW' => null,
    'PROPERTY_MARKS_RECOMMEND' => null,
    'PROPERTY_PICTURES' => null,
    'OFFERS_PROPERTY_ARTICLE' => null,
    'OFFERS_PROPERTY_PICTURES' => null,
    'PROPERTY_ADDITIONAL' => null,
    'PROPERTY_ASSOCIATED' => null,
    'PROPERTY_RECOMMENDED' => null,
    'PROPERTY_SERVICES' => null,
    'PROPERTY_ORDER_USE' => null,
    'MARKS_SHOW' => 'N',
    'MARKS_ORIENTATION' => 'horizontal',
    'GALLERY_PANEL' => 'N',
    'GALLERY_POPUP' => 'N',
    'GALLERY_ZOOM' => 'N',
    'GALLERY_PREVIEW' => 'N',
    'PRICE_RANGE' => 'N',
    'PRICE_DIFFERENCE' => 'N',
    'ARTICLE_SHOW' => 'N',
    'ACTION' => 'none',
    'COUNTER_SHOW' => 'N',
    'ADDITIONAL_SHOW' => 'N',
    'DESCRIPTION_SHOW' => 'N',
    'DESCRIPTION_NAME' => null,
    'DESCRIPTION_MODE' => 'detail',
    'DESCRIPTION_EXPANDED' => 'N',
    'OFFERS_NAME' => null,
    'OFFERS_EXPANDED' => 'N',
    'PROPERTIES_SHOW' => 'N',
    'PROPERTIES_NAME' => null,
    'PROPERTIES_EXPANDED' => 'N',
    'ASSOCIATED_SHOW' => 'N',
    'ASSOCIATED_NAME' => null,
    'ASSOCIATED_EXPANDED' => 'N',
    'RECOMMENDED_SHOW' => 'N',
    'RECOMMENDED_NAME' => null,
    'RECOMMENDED_EXPANDED' => 'N',
    'SERVICES_SHOW' => 'N',
    'SERVICES_NAME' => null,
    'SERVICES_EXPANDED' => 'N',
    'INFORMATION_PAYMENT_SHOW' => 'N',
    'INFORMATION_PAYMENT_PATH' => null,
    'INFORMATION_SHIPMENT_SHOW' => 'N',
    'INFORMATION_SHIPMENT_PATH' => null,
    'LAZYLOAD_USE' => 'N',
    'PANEL_SHOW' => 'N',
    'PANEL_MOBILE_SHOW' => 'N',
    'QUANTITY_SHOW' => 'N',
    'QUANTITY_MODE' => 'number',
    'QUANTITY_BOUNDS_FEW' => 10,
    'QUANTITY_BOUNDS_MANY' => 20,
    'BASKET_URL' => null,
    'CONSENT_URL' => null,
    'PRINT_SHOW' => 'N',
    'WIDE' => 'Y',
    'PROPERTY_ADVANTAGES' => null,
    'ADVANTAGES_SHOW' => 'N',
    'FORMS_CHEAPER_SHOW' => 'N',
    'FORMS_CHEAPER_ID' => null,
    'FORMS_CHEAPER_TEMPLATE' => null,
    'FORMS_CHEAPER_PROPERTY_PRODUCT' => null,
], $arParams);

$arCodes = [
    'ARTICLE' => $arParams['PROPERTY_ARTICLE'],
    'MARKS' => [
        'HIT' => $arParams['PROPERTY_MARKS_HIT'],
        'NEW' => $arParams['PROPERTY_MARKS_NEW'],
        'RECOMMEND' => $arParams['PROPERTY_MARKS_RECOMMEND']
    ],
    'PICTURES' => $arParams['PROPERTY_PICTURES'],
    'OFFERS' => [
        'ARTICLE' => $arParams['OFFERS_PROPERTY_ARTICLE'],
        'PICTURES' => $arParams['OFFERS_PROPERTY_PICTURES']
    ],
    'ADDITIONAL' => $arParams['PROPERTY_ADDITIONAL'],
    'ASSOCIATED' => $arParams['PROPERTY_ASSOCIATED'],
    'RECOMMENDED' => $arParams['PROPERTY_RECOMMENDED']
];

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'PANEL' => [
        'DESKTOP' => [
            'SHOW' => $arParams['PANEL_SHOW'] === 'Y'
        ],
        'MOBILE' => [
            'SHOW' => $arParams['PANEL_MOBILE_SHOW'] === 'Y'
        ]
    ],
    'BRAND' => [
        'SHOW' => $arParams['BRAND_SHOW'] === 'Y'
    ],
    'MARKS' => [
        'SHOW' => $arParams['MARKS_SHOW'] === 'Y',
        'ORIENTATION' => ArrayHelper::fromRange(['horizontal', 'vertical'], $arParams['MARKS_ORIENTATION'])
    ],
    'GALLERY' => [
        'PANEL' => $arParams['GALLERY_PANEL'] === 'Y',
        'POPUP' => $arParams['GALLERY_POPUP'] === 'Y',
        'ZOOM' => $arParams['GALLERY_ZOOM'] === 'Y',
        'PREVIEW' => $arParams['GALLERY_PREVIEW'] === 'Y'
    ],
    'PRICE' => [
        'RANGE' => $arParams['PRICE_RANGE'] === 'Y',
        'DIFFERENCE' =>$arParams['PRICE_DIFFERENCE'] === 'Y'
    ],
    'ARTICLE' => [
        'SHOW' => $arParams['ARTICLE_SHOW'] === 'Y'
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y'
    ],
    'ADDITIONAL' => [
        'SHOW' => $arParams['ADDITIONAL_SHOW'] === 'Y'
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
        'NAME' => !empty($arParams['DESCRIPTION_NAME']) ? Html::encode(trim($arParams['DESCRIPTION_NAME'])) : null,
        'MODE' => ArrayHelper::fromRange(['detail', 'preview'], $arParams['DESCRIPTION_MODE']),
        'EXPANDED' => $arParams['DESCRIPTION_EXPANDED'] === 'Y'
    ],
    'OFFERS' => [
        'NAME' => !empty($arParams['OFFERS_NAME']) ? Html::encode(trim($arParams['OFFERS_NAME'])) : null,
        'EXPANDED' => $arParams['OFFERS_EXPANDED'] === 'Y'
    ],
    'PROPERTIES' => [
        'SHOW' => $arParams['PROPERTIES_SHOW'] === 'Y',
        'NAME' => !empty($arParams['PROPERTIES_NAME']) ? Html::encode(trim($arParams['PROPERTIES_NAME'])) : null,
        'EXPANDED' => $arParams['PROPERTIES_EXPANDED'] === 'Y'
    ],
    'INFORMATION' => [
        'PAYMENT' => [
            'SHOW' => $arParams['INFORMATION_PAYMENT_SHOW'] === 'Y',
            'PATH' => $arParams['INFORMATION_PAYMENT_PATH']
        ],
        'SHIPMENT' => [
            'SHOW' => $arParams['INFORMATION_SHIPMENT_SHOW'] === 'Y',
            'PATH' => $arParams['INFORMATION_SHIPMENT_PATH']
        ]
    ],
    'ASSOCIATED' => [
        'SHOW' => $arParams['ASSOCIATED_SHOW'] === 'Y',
        'NAME' => !empty($arParams['ASSOCIATED_NAME']) ? Html::encode(trim($arParams['ASSOCIATED_NAME'])) : null,
        'EXPANDED' => $arParams['ASSOCIATED_EXPANDED'] === 'Y'
    ],
    'RECOMMENDED' => [
        'SHOW' => $arParams['RECOMMENDED_SHOW'] === 'Y',
        'NAME' => !empty($arParams['RECOMMENDED_NAME']) ? Html::encode(trim($arParams['RECOMMENDED_NAME'])) : null,
        'EXPANDED' => $arParams['RECOMMENDED_EXPANDED'] === 'Y'
    ],
    'SERVICES' => [
        'SHOW' => $arParams['SERVICES_SHOW'] === 'Y',
        'NAME' => $arParams['SERVICES_NAME'],
        'EXPANDED' => $arParams['SERVICES_EXPANDED'] === 'Y'
    ],
    'QUANTITY' => [
        'SHOW' => $arParams['QUANTITY_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['number', 'text', 'logic'], $arParams['QUANTITY_MODE']),
        'BOUNDS' => [
            'FEW' => Type::toFloat($arParams['QUANTITY_BOUNDS_FEW']),
            'MANY' => Type::toFloat($arParams['QUANTITY_BOUNDS_MANY'])
        ]
    ],
    'PRINT' => [
        'SHOW' => $arParams['PRINT_SHOW'] === 'Y'
    ],
    'ADVANTAGES' => [
        'SHOW' => $arParams['ADVANTAGES_SHOW'] === 'Y'
    ],
    'WIDE' => $arParams['WIDE'] === 'Y'
];

$sDescriptionSource = strtoupper($arVisual['DESCRIPTION']['MODE']).'_TEXT';

if (empty($arResult[$sDescriptionSource]))
    $arVisual['DESCRIPTION']['SHOW'] = false;

if (empty($arVisual['INFORMATION']['PAYMENT']['PATH']))
    $arVisual['INFORMATION']['PAYMENT']['SHOW'] = false;

if (empty($arVisual['INFORMATION']['SHIPMENT']['PATH']))
    $arVisual['INFORMATION']['SHIPMENT']['SHOW'] = false;

if (!$arVisual['WIDE'])
    $arVisual['GALLERY']['PREVIEW'] = false;

$arResult['ACTION'] = ArrayHelper::fromRange([
    'none',
    'buy',
    'order'
], $arParams['ACTION']);

$arOrderUse = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_ORDER_USE'],
    'VALUE'
]);

if (!empty($arOrderUse) && $arResult['ACTION'] === 'buy') {
    $arResult['ACTION'] = 'order';
}

$arResult['URL'] = [
    'BASKET' => $arParams['BASKET_URL'],
    'CONSENT' => $arParams['CONSENT_URL']
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

$arResult['FORM']['ORDER'] = [
    'SHOW' => $arResult['ACTION'] === 'order',
    'ID' => $arParams['FORM_ID'],
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'PROPERTIES' => [
        'PRODUCT' => $arParams['FORM_PROPERTY_PRODUCT']
    ]
];

if ($bLite)
    include(__DIR__.'/modifiers/lite/catalog.php');

include(__DIR__.'/modifiers/fields.php');
include(__DIR__.'/modifiers/pictures.php');
include(__DIR__.'/modifiers/properties.php');
include(__DIR__.'/modifiers/marks.php');
include(__DIR__.'/modifiers/tab.php');
include(__DIR__.'/modifiers/shares.php');
include(__DIR__.'/modifiers/services.php');

if ($arResult['ACTION'] !== 'buy') {
    $arVisual['COUNTER']['SHOW'] = false;
    $arVisual['ADDITIONAL']['SHOW'] = false;
}

if (empty($arResult['DISPLAY_PROPERTIES']))
    $arVisual['PROPERTIES']['SHOW'] = false;

if (empty($arResult['ADDITIONAL']))
    $arVisual['ADDITIONAL']['SHOW'] = false;

if (empty($arResult['ASSOCIATED']))
    $arVisual['ASSOCIATED']['SHOW'] = false;

if (empty($arResult['RECOMMENDED']))
    $arVisual['RECOMMENDED']['SHOW'] = false;

if (empty($arResult['ADVANTAGES']))
    $arVisual['ADVANTAGES']['SHOW'] = false;

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

$arResult['SKU_VIEW'] = ArrayHelper::fromRange([
    'dynamic',
    'list'
], $arParams['SKU_VIEW']);

if (!empty($arResult['OFFERS']) && $arResult['SKU_VIEW'] == 'list') {
    $arVisual['PANEL']['DESKTOP']['SHOW'] = false;
    $arVisual['PANEL']['MOBILE']['SHOW'] = false;
}

$arResult['FORM']['CHEAPER'] = [
    'SHOW' => $arParams['FORMS_CHEAPER_SHOW'] === 'Y',
    'ID' => $arParams['FORMS_CHEAPER_ID'],
    'TEMPLATE' => $arParams['FORMS_CHEAPER_TEMPLATE'],
    'PROPERTIES' => [
        'PRODUCT' => $arParams['FORMS_CHEAPER_PROPERTY_PRODUCT']
    ]
];

if (empty($arResult['FORM']['CHEAPER']['ID']))
    $arResult['FORM']['CHEAPER']['SHOW'] = false;

$arResult['VISUAL'] = $arVisual;
$this->getComponent()->setResultCacheKeys(['PREVIEW_PICTURE', 'DETAIL_PICTURE']);