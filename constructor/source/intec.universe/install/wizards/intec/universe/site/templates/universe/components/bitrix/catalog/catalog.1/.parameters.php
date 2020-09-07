<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$bBase = false;
$bLite = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

Loc::loadMessages(__FILE__);

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], ['ACTIVE' => 'Y']))->indexBy('ID');
$arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$arParametersCommon = [
    'FORM_ID',
    'FORM_TEMPLATE',
    'FORM_PROPERTY_PRODUCT',
    'PROPERTY_MARKS_RECOMMEND',
    'PROPERTY_MARKS_NEW',
    'PROPERTY_MARKS_HIT',
    'PROPERTY_ORDER_USE',
    'CONSENT_URL',
    'LAZY_LOAD',
    'VOTE_MODE',
    'DELAY_USE',
    'QUANTITY_MODE',
    'QUANTITY_BOUNDS_FEW',
    'QUANTITY_BOUNDS_MANY',

    'VIDEO_IBLOCK_TYPE',
    'VIDEO_IBLOCK_ID',
    'VIDEO_PROPERTY_URL',
    'SERVICES_IBLOCK_TYPE',
    'SERVICES_IBLOCK_ID',
    'REVIEWS_IBLOCK_TYPE',
    'REVIEWS_IBLOCK_ID',
    'REVIEWS_PROPERTY_ELEMENT_ID',
    'REVIEWS_USE_CAPTCHA',
    'PROPERTY_ARTICLE',
    'PROPERTY_BRAND',
    'PROPERTY_PICTURES',
    'PROPERTY_SERVICES',
    'PROPERTY_DOCUMENTS',
    'PROPERTY_ADDITIONAL',
    'PROPERTY_ASSOCIATED',
    'PROPERTY_RECOMMENDED',
    'PROPERTY_VIDEO',
    'OFFERS_PROPERTY_ARTICLE',
    'OFFERS_PROPERTY_PICTURES',

    'CONVERT_CURRENCY',
    'CURRENCY_ID',
    'PRICE_CODE'
];

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arProperties = Arrays::from([]);
$arOffersProperties = Arrays::from([]);

if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $arTemplateParameters['PROPERTY_MARKS_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_MARKS_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_MARKS_NEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_MARKS_NEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_MARKS_HIT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_MARKS_HIT'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_ORDER_USE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_ORDER_USE'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_ARTICLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_ARTICLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'S')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_BRAND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_BRAND'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_SERVICES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_SERVICES'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_DOCUMENTS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_DOCUMENTS'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_ADDITIONAL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_ADDITIONAL'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_ASSOCIATED'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_ASSOCIATED'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_RECOMMENDED'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_RECOMMENDED'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_VIDEO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PROPERTY_VIDEO'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bBase) {
        $arTemplateParameters['DELAY_USE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_DELAY_USE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];

        $arOffersIBlock = CCatalogSku::GetInfoByProductIBlock($arIBlock['ID']);

        if (!empty($arOffersIBlock['IBLOCK_ID'])) {
            $arOffersProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arOffersIBlock['IBLOCK_ID']
                ]
            ))->indexBy('ID');
        }
    } else if ($bLite) {
        $arPriceCodes = Arrays::fromDBResult(CStartShopPrice::GetList())->indexBy('CODE');

        $hPriceCodes = function ($sKey, $arProperty) {
            if (!empty($arProperty['CODE']))
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '['.$arProperty['CODE'].'] '.$arProperty['LANG'][LANGUAGE_ID]['NAME']
                ];

            return ['skip' => true];
        };

        $arTemplateParameters['PRICE_CODE'] = [
            'PARENT' => 'PRICES',
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_PRICE_CODE'),
            'TYPE' => 'LIST',
            'VALUES' => $arPriceCodes->asArray($hPriceCodes),
            'MULTIPLE' => 'Y',
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['CONVERT_CURRENCY'] = [
            'PARENT' => 'PRICES',
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_CONVERT_CURRENCY'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['CONVERT_CURRENCY'] === 'Y') {
            $arCurrencies = Arrays::fromDBResult(CStartShopCurrency::GetList())->indexBy('CODE');

            $hCurrencies = function ($sKey, $arProperty) {
                if (!empty($arProperty['CODE']))
                    return [
                        'key' => $arProperty['CODE'],
                        'value' => '['.$arProperty['CODE'].'] '.$arProperty['LANG'][LANGUAGE_ID]['NAME']
                    ];

                return ['skip' => true];
            };

            $arTemplateParameters['CURRENCY_ID'] = [
                'PARENT' => 'PRICES',
                'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_CURRENCY_ID'),
                'TYPE' => 'LIST',
                'VALUES' => $arCurrencies->asArray($hCurrencies),
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }

        $arOffersIBlock = CStartShopCatalog::GetByIBlock($arIBlock['ID'])->Fetch();

        if (!empty($arOffersIBlock['OFFERS_IBLOCK'])) {
            $arOffersProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arOffersIBlock['OFFERS_IBLOCK']
                ]
            ))->indexBy('ID');
        }
    }

    $arTemplateParameters['OFFERS_PROPERTY_ARTICLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_OFFERS_PROPERTY_ARTICLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arOffersProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'S')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['OFFERS_PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_OFFERS_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arOffersProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

$arTemplateParameters['VIDEO_IBLOCK_TYPE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_VIDEO_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], ['ACTIVE' => 'Y']))->indexBy('ID');
$arIBlockType = $arIBlocks->asArray();

$hGetIblockByType = function ($sType) use ($arIBlockType) {
    $arIblockList = [];

    foreach ($arIBlockType as $sKey => $arIblock) {
        if ($arIblock['IBLOCK_TYPE_ID'] !== $sType) continue;

        $arIblockList[$arIblock['ID']] = $arIblock['NAME'];
    }

    return $arIblockList;
};

if (!empty($arCurrentValues['VIDEO_IBLOCK_TYPE'])) {
    $arTemplateParameters['VIDEO_IBLOCK_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_VIDEO_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $hGetIblockByType($arCurrentValues['VIDEO_IBLOCK_TYPE'] ),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

if (!empty($arCurrentValues['VIDEO_IBLOCK_ID'])) {
    $arProperties = null;
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['VIDEO_IBLOCK_ID']
    ]))->indexBy('ID');

    $arTemplateParameters['VIDEO_PROPERTY_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_VIDEO_PROPERTY_URL'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'S')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['SERVICES_IBLOCK_TYPE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SERVICES_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['SERVICES_IBLOCK_TYPE'])) {
    $arTemplateParameters['SERVICES_IBLOCK_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SERVICES_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $hGetIblockByType($arCurrentValues['SERVICES_IBLOCK_TYPE']),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['REVIEWS_IBLOCK_TYPE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_REVIEWS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['REVIEWS_IBLOCK_TYPE'])) {
    $arTemplateParameters['REVIEWS_IBLOCK_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_REVIEWS_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $hGetIblockByType($arCurrentValues['REVIEWS_IBLOCK_TYPE']),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

if (!empty($arCurrentValues['REVIEWS_IBLOCK_ID'])) {
    $arProperties = null;
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['REVIEWS_IBLOCK_ID']
    ]))->indexBy('ID');

    $arTemplateParameters['REVIEWS_PROPERTY_ELEMENT_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_REVIEWS_PROPERTY_ELEMENT_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];

            return ['skip' => true];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['REVIEWS_USE_CAPTCHA'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_REVIEWS_USE_CAPTCHA'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['COMPARE_ACTION'] = [
    'PARENT' => 'COMPARE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_ACTION_NONE'),
        'buy' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_ACTION_BUY'),
        'detail' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_ACTION_DETAIL')
    ]
];

$arTemplateParameters['COMPARE_LAZYLOAD_USE'] = [
    'PARENT' => 'COMPARE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['ROOT_LAYOUT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_ROOT_LAYOUT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        '1' => 1,
        '2' => 2,
    ],
    'DEFAULT' => 1
];

$arTemplateParameters['SECTIONS_LAYOUT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SECTIONS_LAYOUT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        '1' => 1,
        '2' => 2,
    ],
    'DEFAULT' => 1
];

include(__DIR__.'/parameters/menu.php');
include(__DIR__.'/parameters/sections.php');
include(__DIR__.'/parameters/filter.php');
include(__DIR__.'/parameters/elements.php');
include(__DIR__.'/parameters/navigation.php');
include(__DIR__.'/parameters/element.php');
include(__DIR__.'/parameters/vote.php');
include(__DIR__.'/parameters/quantity.php');
include(__DIR__.'/parameters/order.fast.php');
include(__DIR__.'/parameters/quick.view.php');
include(__DIR__.'/parameters/tags.php');
include(__DIR__.'/parameters/regionality.php');
include(__DIR__.'/parameters/sef.php');

$arTemplateParameters['CONSENT_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_CONSENT_URL'),
    'TYPE' => 'STRING'
];

if (Loader::includeModule('form')) {
    include(__DIR__.'/parameters/base/forms.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/parameters/lite/forms.php');
}