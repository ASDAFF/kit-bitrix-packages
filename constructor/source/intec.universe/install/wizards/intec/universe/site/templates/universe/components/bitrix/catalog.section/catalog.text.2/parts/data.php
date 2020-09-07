<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Iblock\PropertyTable;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

/**
 * @param array $arItem
 * @return array
 */
$dData = function (&$arItem) use (&$arResult, &$arVisual) {
    $fHandle = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual) {
        $arData = [
            'id' => Type::toInteger($arItem['ID']),
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
                        'measure' => $arItem['CATALOG_MEASURE_NAME'],
                        'display' => $arPrice['PRINT_PRICE'],
                        'difference' => $arPrice['PRINT_DISCOUNT']
                    ]
                ];

                unset($arPrice);
            }

        if (!$bOffer) {
            $arData['name'] = $arItem['~NAME'];
            $arData['properties'] = [];

            if (!empty($arItem['SKU_PROPS']))
                $arData['properties'] = $arItem['SKU_PROPS'];

            $arData['quickView'] = [
                'show' => false,
                'template' => null,
                'parameters' => [],
            ];

            if ($arResult['QUICK_VIEW']['USE'] && !empty($arResult['QUICK_VIEW']['TEMPLATE'])) {
                $arData['quickView']['template'] = $arResult['QUICK_VIEW']['TEMPLATE'];
                $arData['quickView']['show'] = true;
            }

            if ($arData['quickView']['show']) {
                $arParameters = $arResult['QUICK_VIEW']['PARAMETERS'];

                if (!empty($arParameters['PROPERTY_CODE']) && Type::isArray($arParameters['PROPERTY_CODE'])) {
                    $iCount = 0;
                    $arProperties = [];

                    foreach ($arParameters['PROPERTY_CODE'] as $sPropertyCode) {
                        $sPropertyValue = ArrayHelper::getValue($arItem, ['PROPERTIES', $sPropertyCode, 'VALUE']);

                        if (empty($sPropertyValue) && !Type::isNumeric($sPropertyValue))
                            continue;

                        $arProperties[] = $sPropertyCode;
                        $iCount++;

                        if ($iCount >= 10)
                            break;
                    }

                    $arParameters['PROPERTY_CODE'] = $arProperties;
                }

                $arParameters = ArrayHelper::merge($arParameters, [
                    'ELEMENT_ID' => $arItem['ID'],
                    'ELEMENT_CODE' => $arItem['CODE'],
                    'SECTION_ID' => $arItem['IBLOCK_SECTION_ID'],
                    'SECTION_CODE' => null
                ]);

                $arData['quickView']['parameters'] = $arParameters;
            }
        } else {
            $arData['values'] = [];

            if (!empty($arResult['SKU_PROPS'])) {
                foreach ($arResult['SKU_PROPS'] as $arSKUProperty) {
                    foreach ($arItem['PROPERTIES'] as $arProperty) {
                        $sCode = null;
                        $sValue = null;

                        if (!empty($arProperty['CODE'])) {
                            $sCode = $arProperty['CODE'];
                        } else {
                            $sCode = $arProperty['ID'];
                        }

                        $sCode = 'P_'.$sCode;

                        if ($sCode !== $arSKUProperty['code'])
                            $sCode = null;

                        if (empty($sCode))
                            continue;

                        if ($arProperty['PROPERTY_TYPE'] === PropertyTable::TYPE_LIST) {
                            $sValue = $arProperty['VALUE_ENUM_ID'];
                        } else if (
                            $arProperty['PROPERTY_TYPE'] === PropertyTable::TYPE_STRING &&
                            $arProperty['USER_TYPE'] === 'directory'
                        ) {
                            $sValue = $arProperty['VALUE'];
                        }

                        if (empty($sValue))
                            $sValue = '0';

                        $arData['values'][$sCode] = $sValue;
                    }
                }
            } else if (!empty($arItem['SKU_VALUES'])) {
                foreach ($arItem['SKU_VALUES'] as $arValue)
                    $arData['values']['P_'.$arValue['ID']] = $arValue['VALUE']['ID'];
            }
        }

        return $arData;
    };

    $arData = $fHandle($arItem);
    $arData['offers'] = [];

    if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as $arOffer)
            $arData['offers'][] = $fHandle($arOffer, true);

    return $arData;
};