<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

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

/** Список типов инфоблоков */
$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

$arIBlocks = [];
$rsIBlocks = CIBlock::GetList();
while ($arIBlock = $rsIBlocks->GetNext()) {
    $arIBlocks[$arIBlock['IBLOCK_TYPE_ID']][$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];
    $arIBlocks['all'][$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];
}

$arTemplateParameters['VIDEO_IBLOCK_TYPE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIDEO_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['VIDEO_IBLOCK_TYPE'])) {
    $arTemplateParameters['VIDEO_IBLOCK_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIDEO_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks[$arCurrentValues['VIDEO_IBLOCK_TYPE']],
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}
if (!empty($arCurrentValues['VIDEO_IBLOCK_ID'])) {
    $arProperties = null;
    $rsProperties = CIBlockProperty::GetList([], ["ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues['VIDEO_IBLOCK_ID']]);
    while ($arProperty = $rsProperties->GetNext())
    {
        if ($arProperty['PROPERTY_TYPE'] === 'S')
            $arProperties[$arProperty["CODE"]] = '['.$arProperty["CODE"]."] ".$arProperty["NAME"];
    }

    $arTemplateParameters['VIDEO_PROPERTY_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIDEO_PROPERTY_URL'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['SERVICES_IBLOCK_TYPE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SERVICES_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['SERVICES_IBLOCK_TYPE'])) {
    $arTemplateParameters['SERVICES_IBLOCK_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SERVICES_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks[$arCurrentValues['SERVICES_IBLOCK_TYPE']],
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['REVIEWS_IBLOCK_TYPE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_REVIEWS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['REVIEWS_IBLOCK_TYPE'])) {
    $arTemplateParameters['REVIEWS_IBLOCK_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_REVIEWS_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks[$arCurrentValues['REVIEWS_IBLOCK_TYPE']],
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['REVIEWS_IBLOCK_ID'])) {
        $arProperties = null;
        $rsProperties = CIBlockProperty::GetList([], ["ACTIVE" => "Y", "IBLOCK_ID" => $arCurrentValues['REVIEWS_IBLOCK_ID']]);
        while ($arProperty = $rsProperties->GetNext()) {
            if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
                $arProperties[$arProperty["CODE"]] = '['.$arProperty["CODE"]."] ".$arProperty["NAME"];
        }

        $arTemplateParameters['REVIEWS_PROPERTY_ELEMENT_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_REVIEWS_PROPERTY_ELEMENT_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arEvents = Arrays::fromDBResult(CEventType::GetList([], [
        'SORT' => 'ASC'
    ]));

    $arTemplateParameters['REVIEWS_MAIL_EVENT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_REVIEWS_MAIL_EVENT'),
        'TYPE' => 'LIST',
        'VALUES' => $arEvents->asArray(function ($iIndex, $arEvent) {
            return [
                'key' => $arEvent['EVENT_NAME'],
                'value' => '['.$arEvent['EVENT_NAME'].'] '.$arEvent['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['REVIEWS_USE_CAPTCHA'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_REVIEWS_USE_CAPTCHA'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['REVIEWS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_REVIEWS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['VIEW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'tabs' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIEW_TABS'),
        'wide' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIEW_WIDE')
    ],
    'DEFAULT' => 'wide',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['VIEW'] === 'tabs') {
    $arTemplateParameters['VIEW_TABS_POSITION'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIEW_TABS_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'top' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIEW_TABS_POSITION_TOP'),
            'right' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIEW_TABS_POSITION_RIGHT')
        ]
    ];
}

$arTemplateParameters['SKU_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SKU_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'dynamic' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SKU_VIEW_DYNAMIC'),
        'list' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SKU_VIEW_LIST')
    ]
];

$arTemplateParameters['PANEL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PANEL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['PANEL_MOBILE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PANEL_MOBILE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['ACTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_ACTION_NONE'),
        'buy' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_ACTION_BUY'),
        'order' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_ACTION_ORDER')
    ],
    'DEFAULT' => 'none'
];

$arTemplateParameters['VOTE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VOTE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['VOTE_SHOW'] === 'Y') {
    $arTemplateParameters['VOTE_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VOTE_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'rating' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VOTE_MODE_RATING'),
            'vote_avg' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VOTE_MODE_AVERAGE')
        ],
        'DEFAULT' => 'rating'
    ];
}

$arTemplateParameters['QUANTITY_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['QUANTITY_SHOW'] === 'Y') {
    $arTemplateParameters['QUANTITY_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'number' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_MODE_NUMBER'),
            'text' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_MODE_TEXT'),
            'logic' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_MODE_LOGIC')
        ],
        'DEFAULT' => 'number',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['QUANTITY_MODE'] === 'text') {
        $arTemplateParameters['QUANTITY_BOUNDS_FEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_BOUNDS_FEW'),
            'TYPE' => 'STRING',
        ];
        $arTemplateParameters['QUANTITY_BOUNDS_MANY'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_BOUNDS_MANY'),
            'TYPE' => 'STRING',
        ];
    }
}

$arTemplateParameters['COUNTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_COUNTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($bBase) {
    $arTemplateParameters['STORES_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_STORES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['SETS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SETS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], ['ACTIVE' => 'Y']))->indexBy('ID');
$arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $hPropertiesString = function ($sKey, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] !== 'S')
            return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
        ];
    };
    $hPropertiesCheckbox = function ($sKey, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
            return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
        ];
    };
    $hPropertiesFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertiesElement = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertiesCheckbox = $arProperties->asArray($hPropertiesCheckbox);
    $arPropertiesElement = $arProperties->asArray($hPropertiesElement);
    $arPropertiesFile = $arProperties->asArray($hPropertiesFile);
    $arPropertiesString = $arProperties->asArray($hPropertiesString);

    $arTemplateParameters['PROPERTY_ORDER_USE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_ORDER_USE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_MARKS_HIT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_MARKS_HIT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_MARKS_NEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_MARKS_NEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_MARKS_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_MARKS_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_MARKS_HIT']) || !empty($arCurrentValues['PROPERTY_MARKS_NEW']) || !empty($arCurrentValues['PROPERTY_MARKS_RECOMMEND'])) {
        $arTemplateParameters['MARKS_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_MARKS_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_ARTICLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_ARTICLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_ARTICLE']) || !empty($arCurrentValues['OFFERS_PROPERTY_ARTICLE'])) {
        $arTemplateParameters['ARTICLE_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_ARTICLE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_BRAND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_BRAND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_BRAND'])) {
        $arTemplateParameters['BRAND_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_BRAND_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['GALLERY_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_GALLERY_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['GALLERY_SHOW'] === 'Y') {
        $arTemplateParameters['GALLERY_ZOOM'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_GALLERY_ZOOM'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
        $arTemplateParameters['GALLERY_POPUP'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_GALLERY_POPUP'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
        $arTemplateParameters['GALLERY_SLIDER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_GALLERY_SLIDER'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesFile,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_TAB_META_TITLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_TAB_META_TITLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_TAB_META_CHAIN'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_TAB_META_CHAIN'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_TAB_META_KEYWORDS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_TAB_META_KEYWORDS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_TAB_META_DESCRIPTION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_TAB_META_DESCRIPTION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_TAB_META_BROWSER_TITLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_TAB_META_BROWSER_TITLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_SERVICES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_SERVICES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_SERVICES'])) {
        include(__DIR__.'/parameters/services.php');
    }

    $arTemplateParameters['PROPERTY_DOCUMENTS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_DOCUMENTS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_DOCUMENTS'])) {
        $arTemplateParameters['DOCUMENTS_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_DOCUMENTS_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_ASSOCIATED'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_PRODUCTS_ASSOCIATED'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_ASSOCIATED'])) {
        $arTemplateParameters['ASSOCIATED_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRODUCTS_ASSOCIATED_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_RECOMMENDED'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_PRODUCTS_RECOMMENDED'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_RECOMMENDED'])) {
        $arTemplateParameters['RECOMMENDED_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRODUCTS_RECOMMENDED_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_VIDEO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_VIDEO'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_VIDEO'])) {
        $arTemplateParameters['VIDEO_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_VIDEO_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arOffersProperties = Arrays::from([]);

    if ($bBase) {
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

    $arOffersPropertiesString = $arOffersProperties->asArray($hPropertiesString);
    $arOffersPropertiesFile = $arOffersProperties->asArray($hPropertiesFile);

    $arTemplateParameters['OFFERS_PROPERTY_ARTICLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_OFFERS_PROPERTY_ARTICLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arOffersPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['OFFERS_PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_OFFERS_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arOffersPropertiesFile,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['DESCRIPTION_PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_DESCRIPTION_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['DESCRIPTION_DETAIL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_DESCRIPTION_DETAIL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['PROPERTIES_PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTIES_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PROPERTIES_PREVIEW_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTIES_PREVIEW_COUNT'),
    'TYPE' => 'STRING',
    'DEFAULT' => 2
];
$arTemplateParameters['PROPERTIES_DETAIL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTIES_DETAIL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['ACTION'] === 'buy') {
    $arTemplateParameters['DELAY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_DELAY_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['PRICE_EXTENDED'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRICE_EXTENDED'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['PRICE_RANGE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRICE_RANGE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['PRICE_DIFFERENCE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRICE_DIFFERENCE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['CONSENT_URL'] = array(
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_CONSENT_URL'),
    'TYPE' => 'STRING'
);

include(__DIR__.'/parameters/products.associated.php');
include(__DIR__.'/parameters/products.recommended.php');
include(__DIR__.'/parameters/order.fast.php');
include(__DIR__.'/parameters/shares.php');

$arTemplateParameters['PRINT_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRINT_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['PROPERTY_ADVANTAGES'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PROPERTY_ADVANTAGES'),
    'TYPE' => 'LIST',
    'VALUES' => $arPropertiesElement,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['PROPERTY_ADVANTAGES'])) {
    $arTemplateParameters['ADVANTAGES_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_ADVANTAGES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['ADVANTAGES_SHOW'] === 'Y') {
        include(__DIR__.'/parameters/advantages.php');
    }
}

if (Loader::includeModule('form')) {
    include('parameters/base/forms.php');
} else if (Loader::includeModule('intec.startshop')) {
    include('parameters/lite/forms.php');
} else {
    return;
}

$arTemplateParameters['FORM_SHOW'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_FORM_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FORM_SHOW'] == 'Y') {

    $arTemplateParameters['WEB_FORM_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_WEB_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate) {
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'] . (!empty($arTemplate['TEMPLATE']) ? ' (' . $arTemplate['TEMPLATE'] . ')' : null);
    }

    $arTemplateParameters['WEB_FORM_TEMPLATE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_WEB_FORM_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

/*$arTemplateParameters['FORM_CHEAPER_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_FORM_CHEAPER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);

if ($arCurrentValues['FORM_CHEAPER_SHOW']) {
    $arTemplateParameters['FORM_CHEAPER_ID'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_FORM_CHEAPER_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate) {
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'] . (!empty($arTemplate['TEMPLATE']) ? ' (' . $arTemplate['TEMPLATE'] . ')' : null);
    }

    $arTemplateParameters['FORM_CHEAPER_TEMPLATE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_FORM_CHEAPER_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arForm = ArrayHelper::getValue($arCurrentValues, 'FORM_CHEAPER_ID');
    $arForm = $arForms->get($arForm);

    if (!empty($arForm)) {
        $arFormFields = [];
        $rsFormFields = CFormField::GetList(
            $arForm['ID'],
            'N',
            $by = null,
            $asc = null,
            [
                'ACTIVE' => 'Y'
            ],
            $filtered = false
        );

        while ($arFormField = $rsFormFields->GetNext()) {
            $rsFormAnswers = CFormAnswer::GetList(
                $arFormField['ID'],
                $sort = '',
                $order = '',
                [],
                $filtered = false
            );

            while ($arFormAnswer = $rsFormAnswers->GetNext()) {
                $sType = $arFormAnswer['FIELD_TYPE'];

                if (empty($sType))
                    continue;

                $sId = 'form_'.$sType.'_'.$arFormAnswer['ID'];
                $arFormFields[$sId] = '['.$arFormAnswer['ID'].'] '.$arFormField['TITLE'];
            }
        }

        unset($arFormField);

        $arTemplateParameters['FORM_PROPERTY_PRODUCT'] = array(
            'PARENT' => 'BASE',
            'TYPE' => 'LIST',
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_FORM_PROPERTY_PRODUCT'),
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        );

        unset($arFormFields);
    }
}
*/
