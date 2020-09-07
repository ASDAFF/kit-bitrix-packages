<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Type;

/**
 * @var array $arResult
 */

/**
 * @param array $arItem
 * @return array
 */
$dData = function (&$arItem) use (&$arResult) {
    /**
     * @param $arItem
     * @return array
     */
    $fHandle = function (&$arItem) use (&$arResult) {
        $arData = [
            'id' => Type::toInteger($arItem['ID']),
            'name' => $arItem['~NAME'],
            'prices' => [],
            'available' => Type::toBoolean($arItem['CAN_BUY']),
            'quantity' => [
                'value' => Type::toFloat($arItem['CATALOG_QUANTITY']),
                'ratio' => Type::toFloat($arItem['CATALOG_MEASURE_RATIO']),
                'measure' => $arItem['CATALOG_MEASURE_NAME'],
                'trace' => $arItem['CATALOG_QUANTITY_TRACE'] === 'Y',
                'zero' => $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y'
            ]
        ];

        if (!empty($arItem['ITEM_PRICES']))
            foreach ($arItem['ITEM_PRICES'] as &$arPrice) {
                $arData['prices'][] = [
                    'quantity' => [
                        'from' => $arPrice['QUANTITY_FROM'] !== null ? Type::toFloat($arPrice['QUANTITY_FROM']) : null,
                        'to' => $arPrice['QUANTITY_TO'] !== null ? Type::toFloat($arPrice['QUANTITY_TO']) : null
                    ],
                    'base' => [
                        'value' => $arPrice['BASE_PRICE'],
                        'display' => $arPrice['PRINT_BASE_PRICE']
                    ],
                    'discount' => [
                        'use' => $arPrice['DISCOUNT'] > 0,
                        'percent' => $arPrice['PERCENT'],
                        'value' => $arPrice['PRICE'],
                        'display' => $arPrice['PRINT_PRICE'],
                        'difference' => $arPrice['PRINT_DISCOUNT']
                    ]
                ];

                unset($arPrice);
            }

        return $arData;
    };

    $arData = $fHandle($arItem);
    $arData['offers'] = [];

    return $arData;
};