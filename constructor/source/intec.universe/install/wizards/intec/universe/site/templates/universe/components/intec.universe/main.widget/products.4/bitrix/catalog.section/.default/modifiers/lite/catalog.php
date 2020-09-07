<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arVisual
 */

$arParams = ArrayHelper::merge([
    '~CONVERT_CURRENCY' => 'N',
    '~CURRENCY_ID' => null,
    'PRICE_CODE' => []
], $arParams);

$arParams['CONVERT_CURRENCY'] = $arParams['~CONVERT_CURRENCY'];
$arParams['CURRENCY_ID'] = $arParams['~CURRENCY_ID'];

if (empty($arParams['CURRENCY_ID']))
    $arParams['CONVERT_CURRENCY'] = 'N';

if (!Type::isArray($arParams['PRICE_CODE']))
    $arParams['PRICE_CODE'] = [];

if (empty($arResult['ITEMS']))
    return;

$arItems = [];
$arPricesTypes = Arrays::fromDBResult(CStartShopPrice::GetList())->indexBy('CODE');

foreach ($arResult['ITEMS'] as &$arItem)
    $arItems[] = $arItem['ID'];

unset($arItem);

$arItems = Arrays::fromDBResult(CStartShopCatalogProduct::GetList(
    [],
    ['ID' => $arItems],
    ['SORT' => 'ASC'],
    [],
    $arParams['CONVERT_CURRENCY'] === 'Y' ? $arParams['CURRENCY_ID'] : false,
    $arParams['PRICE_CODE']
))->indexBy('ID');

$fHandle = function (&$arItem, $arData, &$arParent = null) use (&$fHandle, &$arPricesTypes) {
    $bOffer = !empty($arParent);

    if (!Type::isArray($arData))
        $arData = [];

    $arData = ArrayHelper::merge([
        'AVAILABLE' => false,
        'QUANTITY' => [
            'USE' => true,
            'RATIO' => 1,
            'VALUE' => 0
        ],
        'PRICES' => [
            'LIST' => [],
            'MINIMAL' => null
        ],
        'OFFERS' => [],
        'OFFER' => [
            'PROPERTIES' => []
        ]
    ], $arData);

    $arItem['CAN_BUY'] = $arData['AVAILABLE'];
    $arItem['CATALOG_CAN_BUY_ZERO'] = 'N';
    $arItem['CATALOG_QUANTITY_TRACE'] = $arData['QUANTITY']['USE'] ? 'Y' : 'N';
    $arItem['CATALOG_MEASURE_RATIO'] = $arData['QUANTITY']['RATIO'];
    $arItem['CATALOG_MEASURE_NAME'] = null;
    $arItem['CATALOG_QUANTITY'] = $arData['QUANTITY']['VALUE'];
    $arItem['PRICES'] = [];
    $arItem['ITEM_PRICES'] = [];
    $arItem['MIN_PRICE'] = null;
    $arItem['OFFERS'] = [];
    $arItem['SKU_PROPS'] = null;

    if (!empty($arData['PRICES']['LIST'])) {
        $arPriceMinimal = $arData['PRICES']['MINIMAL'];

        foreach ($arData['PRICES']['LIST'] as $arPrice) {
            $arPriceType = $arPricesTypes->get($arPrice['TYPE']);
            $arPriceType['NAME'] = ArrayHelper::getValue($arPriceType, ['LANG', LANGUAGE_ID, 'NAME']);

            if (empty($arPriceType))
                continue;

            $arPriceResult = [
                'PRICE_ID' => $arPriceType['ID'],
                'ID' => $arPriceType['ID'],
                'CAN_ACCESS' => 'Y',
                'CAN_BUY' => $arItem['CAN_BUY'],
                'MIN_PRICE' => $arPrice['TYPE'] === $arPriceMinimal['TYPE'] ? 'Y' : 'N',
                'CURRENCY' => $arPrice['CURRENCY'],
                'VALUE_VAT' => $arPrice['VALUE'],
                'DISCOUNT_VALUE_VAT' => $arPrice['VALUE'],
                'DISCOUNT_VALUE_NOVAT' => $arPrice['VALUE'],
                'ROUND_VALUE_VAT' => $arPrice['VALUE'],
                'ROUND_VALUE_NOVAT' => $arPrice['VALUE'],
                'VALUE' => $arPrice['VALUE'],
                'UNROUND_DISCOUNT_VALUE' => $arPrice['VALUE'],
                'DISCOUNT_VALUE' => $arPrice['VALUE'],
                'DISCOUNT_DIFF' => 0,
                'DISCOUNT_DIFF_PERCENT' => 0,
                'VATRATE_VALUE' => 0,
                'DISCOUNT_VATRATE_VALUE' => 0,
                'ROUND_VATRATE_VALUE' => 0,
                'PRINT_VALUE_NOVAT' => $arPrice['PRINT_VALUE'],
                'PRINT_VALUE_VAT' => $arPrice['PRINT_VALUE'],
                'PRINT_VATRATE_VALUE' => '0',
                'PRINT_DISCOUNT_VALUE_NOVAT' => $arPrice['PRINT_VALUE'],
                'PRINT_DISCOUNT_VALUE_VAT' => $arPrice['PRINT_VALUE'],
                'PRINT_DISCOUNT_VATRATE_VALUE' => '0',
                'PRINT_VALUE' => $arPrice['PRINT_VALUE'],
                'PRINT_DISCOUNT_VALUE' => $arPrice['PRINT_VALUE'],
                'PRINT_DISCOUNT_DIFF' => '0',
                'CODE' => $arPriceType['CODE'],
                'TITLE' => $arPriceType['NAME']
            ];

            $arItem['PRICES'][$arPrice['TYPE']] = $arPriceResult;

            if ($arPriceResult['MIN_PRICE'] === 'Y') {
                $arItem['MIN_PRICE'] = $arPriceResult;
                $arItem['ITEM_PRICES'][] = [
                    'UNROUND_BASE_PRICE' => $arPrice['VALUE'],
                    'UNROUND_PRICE' => $arPrice['VALUE'],
                    'BASE_PRICE' => $arPrice['VALUE'],
                    'PRICE' => $arPrice['VALUE'],
                    'ID' => $arPriceType['ID'],
                    'PRICE_TYPE_ID' => $arPriceType['ID'],
                    'CURRENCY' => $arPrice['CURRENCY'],
                    'DISCOUNT' => 0,
                    'PERCENT' => 0,
                    'VAT' => 0,
                    'QUANTITY_FROM' => null,
                    'QUANTITY_TO' => null,
                    'QUANTITY_HASH' => 'ZERO-INF',
                    'MEASURE_RATIO_ID' => null,
                    'PRINT_BASE_PRICE' => $arPrice['PRINT_VALUE'],
                    'PRINT_PRICE' => $arPrice['PRINT_VALUE'],
                    'RATIO_PRICE' => $arPrice['VALUE'],
                    'PRINT_RATIO_PRICE' => $arPrice['PRINT_VALUE'],
                    'PRINT_DISCOUNT' => '0',
                    'RATIO_DISCOUNT' => 0,
                    'PRINT_RATIO_DISCOUNT' => '0',
                    'PRINT_VAT' => '0',
                    'RATIO_VAT' => 0,
                    'PRINT_RATIO_VAT' => '0',
                    'MIN_QUANTITY' => $arData['QUANTITY']['RATIO'],
                    'CODE' => $arPriceType['CODE'],
                    'TITLE' => $arPriceType['NAME']
                ];
            }
        }

        unset($arPrice);
        unset($arPriceType);
        unset($arPriceResult);
        unset($arPriceMinimal);
    }

    if (!$bOffer) {
        $arItem['SKU_PROPS'] = $arData['OFFER']['PROPERTIES'];

        if (!empty($arData['OFFERS']))
            foreach ($arData['OFFERS'] as $arOffer) {
                $fHandle($arOffer, $arOffer['STARTSHOP'], $arItem);
                $arItem['OFFERS'][$arOffer['ID']] = $arOffer;
            }

        $arItem['SKU_PROPS'] = [];

        if (!empty($arData['OFFER']['PROPERTIES'])) {
            foreach ($arData['OFFER']['PROPERTIES'] as $arProperty) {
                $arProperties = [
                    'id' => $arProperty['ID'],
                    'code' => 'P_'.$arProperty['ID'],
                    'name' => $arProperty['NAME'],
                    'type' => $arProperty['TYPE'] === 'TEXT' ? 'text' : 'picture',
                    'values' => []
                ];

                if (!empty($arProperty['VALUES'])) {
                    foreach ($arProperty['VALUES'] as $arValue) {
                        $arProperties['values'][] = [
                            'id' => $arValue['ID'],
                            'name' => $arValue['TEXT'],
                            'stub' => false,
                            'picture' => !empty($arValue['PICTURE']) ? $arValue['PICTURE'] : null
                        ];
                    }
                }

                $arProperties['values'][] = [
                    'id' => '0',
                    'name' => '-',
                    'stub' => true,
                    'picture' => ''
                ];

                $arItem['SKU_PROPS'][] = $arProperties;
            }

            unset($arValue);
            unset($arProperties);
            unset($arProperty);
        }
    } else {
        $arItem['SKU_VALUES'] = [];

        foreach ($arParent['SKU_PROPS'] as $arProperty) {
            $arValue = ArrayHelper::getValue($arData, [
                'OFFER',
                'PROPERTIES',
                $arProperty['CODE']
            ]);

            if (empty($arValue)) {
                $arValue = $arProperty;
                $arValue['VALUE'] = [
                    'ID' => 0,
                    'CODE' => 0,
                    'TEXT' => '-'
                ];

                if ($arProperty['TYPE'] === 'PICTURE')
                    $arValue['VALUE']['PICTURE'] = null;

                unset($arValue['VALUES']);
            }

            $arItem['SKU_VALUES'][$arProperty['CODE']] = $arValue;
        }
    }
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arData = null;

    if ($arItems->exists($arItem['ID']))
        $arData = $arItems[$arItem['ID']]['STARTSHOP'];

    $fHandle($arItem, $arData);
}

unset($arItem);
unset($arData);
unset($fHandle);