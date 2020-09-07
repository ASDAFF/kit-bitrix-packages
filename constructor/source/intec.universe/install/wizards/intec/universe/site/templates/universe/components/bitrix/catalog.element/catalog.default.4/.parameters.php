<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

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

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    /** Типы свойств */
    $hPropertyText = function ($id, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => 'true'];
    };
    $hPropertyCheckbox = function ($id, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => 'true'];
    };
    $hPropertyFile = function ($id, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['MULTIPLE'] === 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => 'true'];
    };
    $hPropertyLink = function ($id, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => 'true'];
    };
    $hPropertyLinkMultiple = function ($id, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => 'true'];
    };

    /** Свойства каталога */
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ]));

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyLink = $arProperties->asArray($hPropertyLink);
    $arPropertyLinkMultiple = $arProperties->asArray($hPropertyLinkMultiple);

    /** Свойства инфоблока предложений */
    $iOffersIBlock = null;
    $arOffersProperties = Arrays::from([]);

    if ($bBase) {
        $iOffersIBlock = CCatalogSku::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
    } else if ($bLite) {
        $iOffersIBlock = CStartShopCatalog::GetByIBlock($arCurrentValues['IBLOCK_ID'])->Fetch();
    }

    if (!empty($iOffersIBlock['IBLOCK_ID']))
        $iOffersIBlock = $iOffersIBlock['IBLOCK_ID'];

    if (!empty($iOffersIBlock)) {
        $arOffersProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iOffersIBlock
        ]))->indexBy('ID');
    }

    $arOffersPropertyText = $arOffersProperties->asArray($hPropertyText);
}

$arTemplateParameters = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    /** Lazy Load */
    $arTemplateParameters['LAZYLOAD_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    /** Метки */
    $arTemplateParameters['PROPERTY_MARKS_NEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_MARKS_NEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_HIT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_MARKS_HIT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARKS_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_MARKS_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_MARKS_NEW']) || !empty($arCurrentValues['PROPERTY_MARKS_HIT']) || !empty($arCurrentValues['PROPERTY_MARKS_RECOMMEND'])) {
        $arTemplateParameters['MARKS_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_MARKS_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    /** Артикул */
    $arTemplateParameters['PROPERTY_ARTICLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_ARTICLE'),
        'TYPE' => 'LIST',
        'VALUES' => $hPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_ARTICLE'])) {
        $arTemplateParameters['OFFERS_PROPERTY_ARTICLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_OFFERS_PROPERTY_ARTICLE'),
            'TYPE' => 'LIST',
            'VALUES' => $arOffersPropertyText,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['ARTICLE_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_ARTICLE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    /** Бренд */
    $arTemplateParameters['PROPERTY_BRAND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_BRAND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_BRAND'])) {
        $arTemplateParameters['BRAND_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_BRAND_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    /** Доп. изображения */
    $arTemplateParameters['PROPERTY_PICTURES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_PICTURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_PICTURES'])) {
        $arTemplateParameters['OFFERS_PROPERTY_PICTURES'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_OFFERS_PROPERTY_PICTURES'),
            'TYPE' => 'LIST',
            'VALUES' => $arOffersPropertyText,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    /** Документы */
    $arTemplateParameters['PROPERTY_DOCUMENTS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_DOCUMENTS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesFile,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_DOCUMENTS'])) {
        $arTemplateParameters['DOCUMENTS_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_DOCUMENTS_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];
    }

    /** Доп. товары */
    $arTemplateParameters['PROPERTY_ADDITIONAL'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTY_ADDITIONAL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLinkMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_ADDITIONAL'])) {
        $arTemplateParameters['ADDITIONAL_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_ADDITIONAL_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

/** Рейтинг */
$arTemplateParameters['VOTE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_VOTE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['VOTE_SHOW'] === 'Y') {
    $arTemplateParameters['VOTE_TYPE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_VOTE_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'rating' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_VOTE_TYPE_RATING'),
            'vote_avg' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_VOTE_TYPE_AVERAGE')
        ],
        'DEFAULT' => 'rating'
    ];
}

/** Количество */
$arTemplateParameters['QUANTITY_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_QUANTITY_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['QUANTITY_SHOW'] === 'Y') {
    $arTemplateParameters['QUANTITY_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_QUANTITY_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'number' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_QUANTITY_MODE_NUMBER'),
            'text' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_QUANTITY_MODE_TEXT'),
            'logic' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_QUANTITY_MODE_LOGIC')
        ],
        'DEFAULT' => 'number',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['QUANTITY_MODE'] === 'text') {
        $arTemplateParameters['QUANTITY_BOUNDS_FEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_QUANTITY_BOUNDS_FEW'),
            'TYPE' => 'STRING',
            'DEFAULT' => 10
        ];
        $arTemplateParameters['QUANTITY_BOUNDS_MANY'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_QUANTITY_BOUNDS_MANY'),
            'TYPE' => 'STRING',
            'DEFAULT' => 50
        ];
    }
}

/** Галерея */
$arTemplateParameters['GALLERY_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_GALLERY_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['GALLERY_SHOW'] === 'Y') {
    $arTemplateParameters['GALLERY_ACTION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_GALLERY_ACTION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'none' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_GALLERY_ACTION_NONE'),
            'source' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_GALLERY_ACTION_SOURCE'),
            'popup' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_GALLERY_ACTION_POPUP')
        ],
        'DEFAULT' => 'none'
    ];
    $arTemplateParameters['GALLERY_ZOOM'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_GALLERY_ZOOM'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['GALLERY_PREVIEW_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_GALLERY_PREVIEW_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

/** Действия с товаром */
$arTemplateParameters['ACTION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_ACTION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_ACTION_NONE'),
        'buy' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_ACTION_BUY'),
        'order' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_ACTION_ORDER')
    ],
    'DEFAULT' => 'buy',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ACTION'] === 'buy') {
    $arTemplateParameters['COUNTER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_COUNTER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['COUNTER_SHOW'] === 'Y') {
        $arTemplateParameters['PRICE_RANGE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PRICE_RANGE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['PRICE_EXTENDED'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PRICE_EXTENDED'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** Быстрый заказ */
$arTemplateParameters['ORDER_FAST_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_ORDER_FAST_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ORDER_FAST_USE']) {
    include(__DIR__.'/parameters/order.fast.php');
}

/** Превью текст */
$arTemplateParameters['DESCRIPTION_PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_DESCRIPTION_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

/** Детальный текст */
$arTemplateParameters['DESCRIPTION_DETAIL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_DESCRIPTION_DETAIL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** Краткие характеристики */
$arTemplateParameters['PROPERTIES_PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTIES_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PROPERTIES_PREVIEW_SHOW'] === 'Y') {
    $arTemplateParameters['PROPERTIES_PREVIEW_COUNT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTIES_PREVIEW_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '4'
    ];
    $arTemplateParameters['PROPERTIES_PREVIEW_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTIES_PREVIEW_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTIES_PREVIEW_POSITION_LEFT'),
            'right' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTIES_PREVIEW_POSITION_RIGHT'),
        ],
        'DEFAULT' => 'left',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTIES_PREVIEW_COLUMNS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTIES_PREVIEW_COLUMNS'),
        'TYPE' => 'LIST',
        'VALUES' => ArrayHelper::merge([
            2 => '2',
            3 => '3'
        ], $arCurrentValues['PROPERTIES_PREVIEW_POSITION'] === 'right' ? [4 => '4'] : []),
        'DEFAULT' => 2
    ];
}

/** Полные характеристики */
$arTemplateParameters['PROPERTIES_DETAIL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_PROPERTIES_DETAIL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** Ссылки на доп. информацию */
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