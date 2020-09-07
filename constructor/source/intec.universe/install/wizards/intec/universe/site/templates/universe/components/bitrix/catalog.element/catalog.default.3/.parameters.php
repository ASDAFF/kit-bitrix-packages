<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
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

$bOffersIblockExist = false;

$arPropertiesCheckbox = [];
$arPropertiesFile = [];
$arPropertiesLink = [];
$arPropertiesString = [];
$arOffersPropertiesFile = [];
$arOffersPropertiesString = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('CODE');

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
    $hPropertiesLink = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
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
    $arPropertiesFile = $arProperties->asArray($hPropertiesFile);
    $arPropertiesLink = $arProperties->asArray($hPropertiesLink);
    $arPropertiesString = $arProperties->asArray($hPropertiesString);
    $arPropertiesElement = $arProperties->asArray($hPropertiesElement);

    $arOffersProperties = Arrays::from([]);

    if ($bBase) {
        $arOffersIBlock = CCatalogSku::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);

        if (!empty($arOffersIBlock['IBLOCK_ID'])) {
            $arOffersProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arOffersIBlock['IBLOCK_ID']
                ]
            ))->indexBy('ID');

            $bOffersIblockExist = true;
        }
    } else if ($bLite) {
        $arOffersIBlock = CStartShopCatalog::GetByIBlock($arCurrentValues['IBLOCK_ID'])->Fetch();

        if (!empty($arOffersIBlock['OFFERS_IBLOCK'])) {
            $arOffersProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arOffersIBlock['OFFERS_IBLOCK']
                ]
            ))->indexBy('ID');

            $bOffersIblockExist = true;
        }
    }

    $arOffersPropertiesString = $arOffersProperties->asArray($hPropertiesString);
    $arOffersPropertiesFile = $arOffersProperties->asArray($hPropertiesFile);
}

$arTemplateParameters = [];

/** DATA_SOURCE */
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_ARTICLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_ARTICLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bOffersIblockExist) {
        $arTemplateParameters['OFFERS_PROPERTY_ARTICLE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_OFFERS_PROPERTY_ARTICLE'),
            'TYPE' => 'LIST',
            'VALUES' => $arOffersPropertiesString,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if (!empty($arCurrentValues['PROPERTY_ARTICLE']) || !empty($arCurrentValues['OFFERS_PROPERTY_ARTICLE'])) {
        $arTemplateParameters['ARTICLE_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ARTICLE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_BRAND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_BRAND'),
        'TYPE' => 'LIST',
        'VALUES' => $hPropertiesFile,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_BRAND'])) {
        $arTemplateParameters['BRAND_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_BRAND_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_ORDER_USE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_ORDER_USE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_MARKS_HIT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_MARKS_HIT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_NEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_MARKS_NEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_MARKS_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bOffersIblockExist) {
        $arTemplateParameters['OFFERS_PROPERTY_PICTURES'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_OFFERS_PROPERTY_PICTURES'),
            'TYPE' => 'LIST',
            'VALUES' => $arOffersPropertiesFile,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_ADDITIONAL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_ADDITIONAL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesLink,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_ADDITIONAL'])) {
        $arTemplateParameters['ADDITIONAL_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ADDITIONAL_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_ASSOCIATED'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_ASSOCIATED'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_RECOMMENDED'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_RECOMMENDED'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['SERVICES_IBLOCK_ID'])) {
        $arTemplateParameters['PROPERTY_SERVICES'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_SERVICES'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesLink,
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_ADVANTAGES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTY_ADVANTAGES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_ADVANTAGES'])) {
        $arTemplateParameters['ADVANTAGES_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ADVANTAGES_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['ADVANTAGES_SHOW'] === 'Y') {
            include(__DIR__.'/parameters/advantages.php');
        }
    }
}

/** VISUAL */
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['SKU_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SKU_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'dynamic' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SKU_VIEW_DYNAMIC'),
        'list' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SKU_VIEW_LIST')
    ]
];
$arTemplateParameters['PANEL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PANEL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

$arTemplateParameters['PANEL_MOBILE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PANEL_MOBILE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['PANEL_SHOW'] === 'Y') {
    $arTemplateParameters['QUANTITY_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_QUANTITY_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['QUANTITY_SHOW'] === 'Y') {
        $arTemplateParameters['QUANTITY_MODE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_QUANTITY_MODE'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'number' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_QUANTITY_MODE_NUMBER'),
                'text' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_QUANTITY_MODE_TEXT'),
                'logic' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_QUANTITY_MODE_LOGIC')
            ],
            'DEFAULT' => 'number',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['QUANTITY_MODE'] === 'text') {
            $arTemplateParameters['QUANTITY_BOUNDS_FEW'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_QUANTITY_BOUNDS_FEW'),
                'TYPE' => 'STRING'
            ];
            $arTemplateParameters['QUANTITY_BOUNDS_MANY'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_QUANTITY_BOUNDS_MANY'),
                'TYPE' => 'STRING'
            ];
        }
    }
}

if (!empty($arCurrentValues['PROPERTY_MARKS_HIT']) || !empty($arCurrentValues['PROPERTY_MARKS_NEW']) || !empty($arCurrentValues['PROPERTY_MARKS_RECOMMEND'])) {
    $arTemplateParameters['MARKS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_MARKS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['GALLERY_PANEL'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_GALLERY_PANEL'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['GALLERY_POPUP'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_GALLERY_POPUP'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['GALLERY_ZOOM'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_GALLERY_ZOOM'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['GALLERY_PREVIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_GALLERY_PREVIEW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PRICE_RANGE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PRICE_RANGE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PRICE_DIFFERENCE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PRICE_DIFFERENCE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['ACTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ACTION_NONE'),
        'buy' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ACTION_BUY'),
        'order' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ACTION_ORDER')
    ],
    'DEFAULT' => 'buy',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ACTION'] === 'buy') {
    $arTemplateParameters['COUNTER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_COUNTER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['DESCRIPTION_NAME'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_DESCRIPTION_NAME'),
        'TYPE' => 'STRING'
    ];
    $arTemplateParameters['DESCRIPTION_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_DESCRIPTION_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'preview' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_DESCRIPTION_MODE_PREVIEW'),
            'detail' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_DESCRIPTION_MODE_DETAIL')
        ],
        'DEFAULT' => 'DETAIL'
    ];
    $arTemplateParameters['DESCRIPTION_EXPANDED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_DESCRIPTION_EXPANDED'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if ($bOffersIblockExist) {
    $arTemplateParameters['OFFERS_NAME'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_OFFERS_NAME'),
        'TYPE' => 'STRING'
    ];
    $arTemplateParameters['OFFERS_EXPANDED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_OFFERS_EXPANDED'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['PROPERTIES_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTIES_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PROPERTIES_SHOW'] === 'Y') {
    $arTemplateParameters['PROPERTIES_NAME'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTIES_NAME'),
        'TYPE' => 'STRING'
    ];
    $arTemplateParameters['PROPERTIES_EXPANDED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTIES_EXPANDED'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['INFORMATION_PAYMENT_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_PAYMENT_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['INFORMATION_PAYMENT_SHOW'] === 'Y') {
    $arTemplateParameters['INFORMATION_PAYMENT_PATH'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_PAYMENT_PATH'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['INFORMATION_SHIPMENT_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_SHIPMENT_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['INFORMATION_SHIPMENT_SHOW'] === 'Y') {
    $arTemplateParameters['INFORMATION_SHIPMENT_PATH'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_SHIPMENT_PATH'),
        'TYPE' => 'STRING'
    ];
}

if (!empty($arCurrentValues['PROPERTY_ASSOCIATED'])) {
    $arTemplateParameters['ASSOCIATED_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ASSOCIATED_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['ASSOCIATED_SHOW'] === 'Y') {
        $arTemplateParameters['ASSOCIATED_NAME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ASSOCIATED_NAME'),
            'TYPE' => 'STRING'
        ];
        $arTemplateParameters['ASSOCIATED_EXPANDED'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ASSOCIATED_EXPANDED'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_RECOMMENDED'])) {
    $arTemplateParameters['RECOMMENDED_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_RECOMMENDED_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['RECOMMENDED_SHOW'] === 'Y') {
        $arTemplateParameters['RECOMMENDED_NAME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_RECOMMENDED_NAME'),
            'TYPE' => 'STRING'
        ];
        $arTemplateParameters['RECOMMENDED_EXPANDED'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_RECOMMENDED_EXPANDED'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

if (!empty($arCurrentValues['SERVICES_IBLOCK_ID']) && !empty($arCurrentValues['PROPERTY_SERVICES'])) {
    $arTemplateParameters['SERVICES_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SERVICES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['SERVICES_SHOW'] === 'Y') {
        $arTemplateParameters['SERVICES_NAME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SERVICES_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SERVICES_NAME_DEFAULT')
        ];
        $arTemplateParameters['SERVICES_EXPANDED'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SERVICES_EXPANDED'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];

        include(__DIR__ . '/parameters/services.php');
    }
}

include(__DIR__.'/parameters/shares.php');

$arTemplateParameters['PRINT_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PRINT_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];


if (Loader::includeModule('form')) {
    include('parameters/base/forms.php');
} else if (Loader::includeModule('intec.startshop')) {
    include('parameters/lite/forms.php');
} else {
    return;
}
/*
$arTemplateParameters['FORM_CHEAPER_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORM_CHEAPER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);
$arTemplateParameters['FORM_CHEAPER_ID'] = array(
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORM_CHEAPER_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y'
);
*/