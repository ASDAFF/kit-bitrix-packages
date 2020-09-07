<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die;

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

if (!empty($arCurrentValues['IBLOCK_ID'])) {

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
    } else if ($bLite) {
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

/** DATA_SOURCE */
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
        ['SORT' => 'ASC'],
        [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
        ]
    ))->indexBy('ID');

    $hPropertyCheckbox = function ($sKey, $arProperty) {
        if (!empty($arProperty['CODE']))
            if ($arProperty['PROPERTY_TYPE'] === 'L' || $arProperty['LIST_TYPE'] === 'C')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                ];

        return ['skip' => true];
    };
    $hPropertyFile = function ($sKey, $arProperty) {
        if (!empty($arProperty['CODE']))
            if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                ];

        return ['skip' => true];
    };
    $hPropertyText = function ($sKey, $arProperty) {
        if (!empty($arProperty['CODE']))
            if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L')
                return[
                    'key' => $arProperty['CODE'],
                    'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                ];

        return ['skip' => true];
    };

    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyText = $arProperties->asArray($hPropertyText);


    $arTemplateParameters['PROPERTY_MARKS_HIT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_PROPERTY_MARKS_HIT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_NEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_PROPERTY_MARKS_NEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_PROPERTY_MARKS_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bOffersIblockExist) {
        $arTemplateParameters['OFFERS_PROPERTY_PICTURES'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_OFFERS_PROPERTY_PICTURES'),
            'TYPE' => 'LIST',
            'VALUES' => $arOfferProperties->asArray($hPropertyFile),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_PROPERTY_TEXT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

/** VISUAL */
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_MARKS_HIT']) || !empty($arCurrentValues['PROPERTY_MARKS_NEW']) || !empty($arCurrentValues['PROPERTY_MARKS_RECOMMEND'])) {
    $arTemplateParameters['MARKS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_MARKS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if ($bBase) {
    $arTemplateParameters['WEIGHT_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_WEIGHT_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['QUANTITY_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['QUANTITY_SHOW'] === 'Y') {
    $arTemplateParameters['QUANTITY_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'number' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_MODE_NUMBER'),
            'text' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_MODE_TEXT'),
            'logic' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_MODE_LOGIC')
        ],
        'DEFAULT' => 'number',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['QUANTITY_MODE'] === 'text') {
        $arTemplateParameters['QUANTITY_BOUNDS_FEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_BOUNDS_FEW'),
            'TYPE' => 'STRING'
        ];
        $arTemplateParameters['QUANTITY_BOUNDS_MANY'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_BOUNDS_MANY'),
            'TYPE' => 'STRING'
        ];
    }
}

$arTemplateParameters['ACTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_ACTION_NONE'),
        'buy' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_ACTION_BUY'),
        'detail' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_ACTION_DETAIL')
    ],
    'DEFAULT' => 'none',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ACTION'] === 'buy') {
    $arTemplateParameters['COUNTER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_COUNTER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['DESCRIPTION_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_DESCRIPTION_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'preview' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_DESCRIPTION_MODE_PREVIEW'),
            'detail' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_DESCRIPTION_MODE_DETAIL')
        ],
        'DEFAULT' => 'preview'
    ];
}

if (!empty($arCurrentValues['PROPERTY_TEXT'])) {
    $arTemplateParameters['TEXT_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_TEXT_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['GALLERY_PANEL'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_GALLERY_PANEL'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['GALLERY_PREVIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_GALLERY_PREVIEW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['INFORMATION_PAYMENT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_INFORMATION_PAYMENT'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['INFORMATION_PAYMENT'] === 'Y') {
    $arTemplateParameters['PAYMENT_URL'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_PAYMENT_URL'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['INFORMATION_SHIPMENT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_INFORMATION_SHIPMENT'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['INFORMATION_SHIPMENT'] === 'Y') {
    $arTemplateParameters['SHIPMENT_URL'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_SHIPMENT_URL'),
        'TYPE' => 'STRING'
    ];
}