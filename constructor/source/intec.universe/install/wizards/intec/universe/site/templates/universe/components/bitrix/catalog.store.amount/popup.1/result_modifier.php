<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arVisual = [
    'MIN_AMOUNT' => [
        'USE' => $arParams['USE_MIN_AMOUNT'] === 'Y',
        'VALUE' => Type::toInteger($arParams['MIN_AMOUNT'])
    ],
    'GENERAL' => $arParams['SHOW_GENERAL_STORE_INFORMATION'] === 'Y'
];

if (!$arVisual['MIN_AMOUNT']['VALUE'])
    $arVisual['MIN_AMOUNT']['VALUE'] = 10;

$arIds = [];

if ($arResult['IS_SKU']) {
    foreach ($arResult['JS']['SKU'] as $key => $arSku) {
        $arIds[] = $key;
    }

    unset($key, $arSku);
} else {
    $arIds[] = Type::toInteger($arParams['ELEMENT_ID']);
}

if (!empty($arIds)) {
    $arMeasures = [];
    $arIds = ProductTable::getCurrentRatioWithMeasure($arIds);

    foreach ($arIds as $key => $arMeasure)
        $arMeasures[$key] = $arMeasure['MEASURE']['SYMBOL'];

    unset($key, $arMeasure);

    if (!empty($arMeasures))
        $arResult['MEASURES'] = $arMeasures;

    unset($arMeasures);
}

unset($arIds);

if (!empty($arResult['STORES'])) {
    if ($arVisual['GENERAL'] && $arVisual['MIN_AMOUNT']['USE']) {
        $arFilter = [
            'PRODUCT_ID' => $arParams['ELEMENT_ID'],
            'STORE_ID' => array_filter($arParams['STORES'])
        ];

        if (!empty($arFilter['PRODUCT_ID']) && !empty($arFilter['STORE_ID'])) {
            $iCalculateAmount = 0;

            $arStores = Arrays::fromDBResult(CCatalogStoreProduct::GetList(
                ['SORT' => 'ASC'],
                $arFilter,
                false,
                false,
                ['ID', 'PRODUCT_ID', 'STORE_ID', 'AMOUNT']
            ))->asArray();

            if (!empty($arStores)) {
                foreach ($arStores as $arStore) {
                    $iCalculateAmount += $arStore['AMOUNT'];
                }
            }
            $arResult['STORES'][0]['TITLE'] = Loc::getMessage('C_CATALOG_STORE_AMOUNT_POPUP_1_STORES_ALL');
            $arResult['STORES'][0]['AMOUNT'] = $iCalculateAmount;
        }
    }

    foreach ($arResult['STORES'] as &$arStore) {
        if ($arVisual['MIN_AMOUNT']['USE']) {
            if ($arStore['AMOUNT'] > $arVisual['MIN_AMOUNT']['VALUE']) {
                $arStore['AMOUNT_STATUS'] = 'many';
                $arStore['AMOUNT_PRINT'] = Loc::getMessage('C_CATALOG_STORE_AMOUNT_POPUP_1_MANY');
            } else if ($arStore['AMOUNT'] <= $arVisual['MIN_AMOUNT']['VALUE'] && $arStore['AMOUNT'] > 0) {
                $arStore['AMOUNT_STATUS'] = 'few';
                $arStore['AMOUNT_PRINT'] = Loc::getMessage('C_CATALOG_STORE_AMOUNT_POPUP_1_FEW');
            } else {
                $arStore['AMOUNT_STATUS'] = 'empty';
                $arStore['AMOUNT_PRINT'] = Loc::getMessage('C_CATALOG_STORE_AMOUNT_POPUP_1_EMPTY');
            }
        } else {
            if ($arStore['AMOUNT'] > 0) {
                $arStore['AMOUNT_STATUS'] = 'many';
            } else {
                $arStore['AMOUNT_STATUS'] = 'empty';
            }

            $arStore['AMOUNT_PRINT'] = $arStore['AMOUNT'];
        }
    }

    unset($arStore);
}

if ($arResult['IS_SKU']) {
    $arJsParameters = [
        'stores' => $arResult['JS']['STORES'],
        'offers' => $arResult['JS']['SKU'],
        'measures' => $arResult['MEASURES'],
        'messages' => [
            Loc::getMessage('C_CATALOG_STORE_AMOUNT_POPUP_1_MANY'),
            Loc::getMessage('C_CATALOG_STORE_AMOUNT_POPUP_1_FEW'),
            Loc::getMessage('C_CATALOG_STORE_AMOUNT_POPUP_1_EMPTY')
        ],
        'states' => [
            0 => 'many',
            1 => 'few',
            2 => 'empty'
        ],
        'parameters' => [
            'showEmptyStore' => $arParams['SHOW_EMPTY_STORE'] === 'Y',
            'useMinAmount' => $arVisual['MIN_AMOUNT']['USE'],
            'minAmount' => $arVisual['MIN_AMOUNT']['VALUE']
        ]
    ];

    $arResult['JS'] = $arJsParameters;
}

$arResult['VISUAL'] = $arVisual;