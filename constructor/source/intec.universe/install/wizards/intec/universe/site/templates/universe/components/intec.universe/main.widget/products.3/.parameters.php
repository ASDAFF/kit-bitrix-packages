<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Currency;
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

Loc::loadMessages(__FILE__);

$bBase = false;
$bLite = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$arIBlocksFilter = [
    'ACTIVE' => 'Y'
];

$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

if (!empty($sIBlockType))
    $arIBlocksFilter['TYPE'] = $sIBlockType;

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], $arIBlocksFilter))->indexBy('ID');
$arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$bOffersIblockExist = false;

$arOfferProperties = [];

if (!empty($arIBlock)) {
    if ($bBase) {
        $arOfferIBlock = CCatalogSku::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);

        if (!empty($arOfferIBlock['IBLOCK_ID'])) {
            $arOfferProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arOfferIBlock['IBLOCK_ID']
                ]
            ))->indexBy('ID');
            $bOffersIblockExist = true;
        }
    } else if (!$bBase) {
        $arOfferIBlock = CStartShopCatalog::GetByIBlock($arCurrentValues['IBLOCK_ID'])->Fetch();

        if (!empty($arOfferIBlock['OFFERS_IBLOCK'])) {
            $arOfferProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arOfferIBlock['OFFERS_IBLOCK']
                ]
            ))->indexBy('ID');
            $bOffersIblockExist = true;
        }
    }
}

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) {
        return [
            'key' => $arIBlock['ID'],
            'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['MODE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MODE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'all' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MODE_ALL'),
        'categories' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MODE_CATEGORIES'),
        'category' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MODE_CATEGORY')
    ],
    'REFRESH' => 'Y',
    'DEFAULT' => 'all'
];

if ($arCurrentValues['MODE'] === 'categories' || $arCurrentValues['MODE'] === 'category') {
    $arCategories = [];

    if (!empty($arIBlock) && !empty($arCurrentValues['PROPERTY_CATEGORY'])) {
        $arProperty = CIBlockProperty::GetList([], [
            'IBLOCK_ID' => $arIBlock['ID'],
            'CODE' => $arCurrentValues['PROPERTY_CATEGORY']
        ])->GetNext();

        if (!empty($arProperty)) {
            $rsCategories = CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], [
                'PROPERTY_ID' => $arProperty['ID']
            ]);

            while ($arCategory = $rsCategories->GetNext())
                if (!empty($arCategory['XML_ID']))
                    $arCategories[$arCategory['XML_ID']] = '['.$arCategory['XML_ID'].'] '.$arCategory['VALUE'];

            unset($arCategory);
        }
    }

    if ($arCurrentValues['MODE'] === 'categories') {
        $arTemplateParameters['CATEGORIES'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_CATEGORIES'),
            'TYPE' => 'LIST',
            'VALUES' => $arCategories,
            'MULTIPLE' => 'Y',
            'ADDITIONAL_VALUES' => 'Y'
        ];
    } else {
        $arTemplateParameters['CATEGORY'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_CATEGORY'),
            'TYPE' => 'LIST',
            'VALUES' => $arCategories,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }
}

$arTemplateParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
    'SECTION',
    'SECTION_URL',
    Loc::getMessage('C_WIDGET_PRODUCTS_3_SECTION_URL'),
    '',
    'URL_TEMPLATES'
);

$arTemplateParameters['DETAIL_URL'] = CIBlockParameters::GetPathTemplateParam(
    'DETAIL',
    'DETAIL_URL',
    Loc::getMessage('C_WIDGET_PRODUCTS_3_DETAIL_URL'),
    '',
    'URL_TEMPLATES'
);

/** PRICES */
if ($bBase) {
    $arPrices = CCatalogIBlockParameters::getPriceTypesList();
    $arTemplateParameters['PRICE_CODE'] = [
        'PARENT' => 'PRICES',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PRICE_CODE'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'VALUES' => $arPrices
    ];

    $arTemplateParameters['CONVERT_CURRENCY'] = [
        'PARENT' => 'PRICES',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_CONVERT_CURRENCY'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['CONVERT_CURRENCY'] === 'Y') {
        $arTemplateParameters['CURRENCY_ID'] = [
            'PARENT' => 'PRICES',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_CURRENCY_ID'),
            'TYPE' => 'LIST',
            'VALUES' => Currency\CurrencyManager::getCurrencyList(),
            'DEFAULT' => Currency\CurrencyManager::getBaseCurrency(),
            'ADDITIONAL_VALUES' => 'Y'
        ];
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
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PRICE_CODE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPriceCodes->asArray($hPriceCodes),
        'MULTIPLE' => 'Y',
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['CONVERT_CURRENCY'] = [
        'PARENT' => 'PRICES',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_CONVERT_CURRENCY'),
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
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_CURRENCY_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arCurrencies->asArray($hCurrencies),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }
}

$arTemplateParameters['USE_PRICE_COUNT'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_USE_PRICE_COUNT'),
    'TYPE' => 'CHECKBOX'
];
$arTemplateParameters['PRICE_VAT_INCLUDE'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PRICE_VAT_INCLUDE'),
    'TYPE' => 'CHECKBOX'
];
$arTemplateParameters['SHOW_PRICE_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_SHOW_PRICE_COUNT'),
    'TYPE' => 'TEXT'
];

/** DATA_SOURCE */
if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $hProperties = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesCheckbox = function ($sKey, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
            return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
        ];
    };
    $hPropertiesFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesCheckbox = $arProperties->asArray($hPropertiesCheckbox);

    $arTemplateParameters['PROPERTY_ORDER_USE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PROPERTY_ORDER_USE'),
        'TYPE' => 'LIST',
        'VALUES' => $hPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_MARKS_HIT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PROPERTY_MARKS_HIT'),
        'TYPE' => 'LIST',
        'VALUES' => $hPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_NEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PROPERTY_MARKS_NEW'),
        'TYPE' => 'LIST',
        'VALUES' => $hPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PROPERTY_MARKS_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $hPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertiesFile),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bOffersIblockExist) {
        $arTemplateParameters['OFFERS_PROPERTY_PICTURES'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_OFFERS_PROPERTY_PICTURES'),
            'TYPE' => 'LIST',
            'VALUES' => $arOfferProperties->asArray($hPropertiesFile),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
        $arTemplateParameters['OFFERS_PROPERTY_CODE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_OFFERS_PROPERTY_CODE'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => $arOfferProperties->asArray($hProperties),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_CATEGORY'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PROPERTY_CATEGORY'),
        'VALUES' => $arProperties->asArray(function ($iIndex, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'L' || $arProperty['MULTIPLE'] === 'Y')
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    );
}

/** VISUAL */
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => '2',
        3 => '3',
        4 => '4'
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['COLUMNS_MOBILE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_COLUMNS_MOBILE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        1 => '1',
        2 => '2'
    ],
    'DEFAULT' => 1
];
$arTemplateParameters['LINES'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_LINES'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['BLOCKS_HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BLOCKS_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BLOCKS_HEADER_SHOW'] === 'Y') {
    $arTemplateParameters['BLOCKS_HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BLOCKS_HEADER_TEXT'),
        'TYPE' => 'TEXT',
        'DEFAULT' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BLOCKS_HEADER_TEXT_DEFAULT')
    ];

    $arTemplateParameters['BLOCKS_HEADER_ALIGN'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BLOCKS_HEADER_ALIGN'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
            'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
            'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
        ],
        'DEFAULT' => 'left'
    ];
}

$arTemplateParameters['BLOCKS_DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BLOCKS_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BLOCKS_DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['BLOCKS_DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BLOCKS_DESCRIPTION_TEXT'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['BLOCKS_DESCRIPTION_ALIGN'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BLOCKS_DESCRIPTION_ALIGN'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
            'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
            'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
        ],
        'DEFAULT' => 'left'
    ];
}

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['DESCRIPTION_ALIGN'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_DESCRIPTION_ALIGN'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
            'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
            'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
        ],
        'DEFAULT' => 'left'
    ];
}

$arTemplateParameters['TABS_ALIGN'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_TABS_ALIGN'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_DESCRIPTION_ALIGN_LEFT'),
        'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_DESCRIPTION_ALIGN_CENTER'),
        'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_DESCRIPTION_ALIGN_RIGHT')
    ],
    'DEFAULT' => 'left'
];

if ($bBase) {
    $arTemplateParameters['DELAY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_DELAY_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['MARKS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MARKS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['MARKS_SHOW'] === 'Y') {
    $arTemplateParameters['MARKS_ORIENTATION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MARKS_ORIENTATION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'horizontal' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MARKS_ORIENTATION_HORIZONTAL'),
            'vertical' => Loc::getMessage('C_WIDGET_PRODUCTS_3_MARKS_ORIENTATION_VERTICAL')
        ],
        'DEFAULT' => 'horizontal'
    ];
}

$arTemplateParameters['NAME_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_NAME_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'top' => Loc::getMessage('C_WIDGET_PRODUCTS_3_POSITION_TOP'),
        'middle' => Loc::getMessage('C_WIDGET_PRODUCTS_3_POSITION_MIDDLE')
    ],
    'DEFAULT' => 'middle'
];
$arTemplateParameters['NAME_ALIGN'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_NAME_ALIGN'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
        'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
        'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
    ],
    'DEFAULT' => 'left'
];
$arTemplateParameters['SECTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_SECTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SECTION_SHOW'] === 'Y') {
    $arTemplateParameters['SECTION_ALIGN'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_SECTION_ALIGN'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
            'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
            'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
        ],
        'DEFAULT' => 'left'
    ];
}

$arTemplateParameters['PRICE_ALIGN'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_PRICE_ALIGN'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'start' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
        'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
        'end' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
    ],
    'DEFAULT' => 'start'
];

if (!empty($arCurrentValues['PROPERTY_PICTURES']) || !empty($arCurrentValues['OFFERS_PROPERTY_PICTURES'])) {
    $arTemplateParameters['IMAGE_SLIDER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_IMAGE_SLIDER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['IMAGE_SLIDER_SHOW'] === 'Y') {
        $arTemplateParameters['IMAGE_SLIDER_ANIMATION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_IMAGE_SLIDER_ANIMATION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'standard' => Loc::getMessage('C_WIDGET_PRODUCTS_3_IMAGE_SLIDER_ANIMATION_STANDARD'),
                'fade' => Loc::getMessage('C_WIDGET_PRODUCTS_3_IMAGE_SLIDER_ANIMATION_FADE')
            ],
            'DEFAULT' => 'standard'
        ];

        $arTemplateParameters['IMAGE_SLIDER_NAV'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_IMAGE_SLIDER_NAV'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['ACTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ACTION_NONE'),
        'buy' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ACTION_BUY'),
        'detail' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ACTION_DETAIL'),
        'order' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ACTION_ORDER')
    ],
    'DEFAULT' => 'none',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ACTION'] === 'buy') {
    $arTemplateParameters['COUNTER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_COUNTER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if ($arCurrentValues['ACTION'] === 'order') {
    if (Loader::includeModule('form')) {
        include(__DIR__.'/parameters/base/forms.php');
    } else if (Loader::includeModule('intec.startshop')) {
        include(__DIR__.'/parameters/lite/forms.php');
    }

    $arTemplateParameters['CONSENT_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_CONSENT_URL'),
        'TYPE' => 'STRING'
    ];
}

if ($bOffersIblockExist) {
    $arTemplateParameters['OFFERS_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_OFFERS_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['OFFERS_USE'] === 'Y') {
        $arTemplateParameters['OFFERS_ALIGN'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_OFFERS_ALIGN'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
                'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
                'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
            ],
            'DEFAULT' => 'left'
        ];
    }
}

$arTemplateParameters['VOTE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_VOTE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['VOTE_SHOW'] === 'Y') {
    $arTemplateParameters['VOTE_ALIGN'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_VOTE_ALIGN'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
            'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
            'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
        ],
        'DEFAULT' => 'left'
    ];
    $arTemplateParameters['VOTE_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_VOTE_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'rating' => Loc::getMessage('C_WIDGET_PRODUCTS_3_VOTE_MODE_RATING'),
            'average' => Loc::getMessage('C_WIDGET_PRODUCTS_3_VOTE_MODE_AVERAGE')
        ],
        'DEFAULT' => 'rating'
    ];
}

$arTemplateParameters['QUANTITY_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['QUANTITY_SHOW'] === 'Y') {
    $arTemplateParameters['QUANTITY_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'number' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_MODE_NUMBER'),
            'text' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_MODE_TEXT'),
            'logic' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_MODE_LOGIC')
        ],
        'DEFAULT' => 'number',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['QUANTITY_MODE'] === 'text') {
        $arTemplateParameters['QUANTITY_BOUNDS_FEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_BOUNDS_FEW'),
            'TYPE' => 'STRING',
        ];
        $arTemplateParameters['QUANTITY_BOUNDS_MANY'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_BOUNDS_MANY'),
            'TYPE' => 'STRING',
        ];
    }

    $arTemplateParameters['QUANTITY_ALIGN'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_QUANTITY_ALIGN'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
            'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
            'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
        ],
        'DEFAULT' => 'left'
    ];
}

if ($bBase) {
    $arTemplateParameters['WEIGHT_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_WEIGHT_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['WEIGHT_SHOW'] === 'Y') {
        $arTemplateParameters['WEIGHT_ALIGN'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_WEIGHT_ALIGN'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_LEFT'),
                'center' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_CENTER'),
                'right' => Loc::getMessage('C_WIDGET_PRODUCTS_3_ALIGN_RIGHT')
            ],
            'DEFAULT' => 'left'
        ];
    }
}

$arTemplateParameters['OFFERS_LIMIT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_OFFERS_LIMIT'),
    'TYPE' => 'STRING'
];
$arTemplateParameters['BASKET_URL'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_BASKET_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['USE_COMPARE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_USE_COMPARE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['USE_COMPARE'] === 'Y') {
    $arTemplateParameters['COMPARE_PATH'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_COMPARE_PATH'),
        'TYPE' => 'STRING'
    ];
    $arTemplateParameters['COMPARE_NAME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_COMPARE_NAME'),
        'TYPE' => 'STRING'
    ];
}

include(__DIR__.'/parameters/quick.view.php');
include(__DIR__.'/parameters/regionality.php');