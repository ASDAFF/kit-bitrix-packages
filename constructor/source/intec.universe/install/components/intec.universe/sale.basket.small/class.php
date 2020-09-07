<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use intec\core\bitrix\components\IBlockElements;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class IntecSaleBasketSmallComponent extends IBlockElements
{
    protected $_isBase = null;
    protected $_isLite = null;

    public function getIsBase()
    {
        if ($this->_isBase === null)
            $this->_isBase =
                Loader::includeModule('catalog') &&
                Loader::includeModule('sale');

        return $this->_isBase;
    }

    public function getIsLite()
    {
        if ($this->_isLite === null)
            $this->_isLite = !$this->getIsBase() && Loader::includeModule('intec.startshop');

        return $this->_isLite;
    }

    public function getCurrency($sCurrency = null)
    {
        $arCurrency = null;

        if ($this->getIsBase()) {
            if (!empty($sCurrency))
                $arCurrency = CCurrency::GetByID($sCurrency);

            if (empty($arCurrency)) {
                $arCurrency = Option::Get('sale', 'default_currency', 'RUB');
                $arCurrency = CCurrency::GetByID($arCurrency);
            }

            if (!empty($arCurrency)) {
                $arCurrency = [
                    'ID' => Type::toInteger($arCurrency['NUMCODE']),
                    'CODE' => $arCurrency['CURRENCY'],
                    'ACTIVE' => 'Y',
                    'BASE' => $arCurrency['BASE'],
                    'RATE' => Type::toFloat($arCurrency['AMOUNT']),
                    'RATING' => Type::toInteger($arCurrency['AMOUNT_CNT']),
                    'LANG' => [],
                    'FORMAT' => []
                ];
            } else {
                $arCurrency = null;
            }
        } else if ($this->getIsLite()) {
            $arCurrency = CStartShopCurrency::GetByCode($sCurrency)->Fetch();

            if (empty($arCurrency))
                $arCurrency = CStartShopCurrency::GetBase()->Fetch();

            if (empty($arCurrency))
                $arCurrency = null;
        }

        return $arCurrency;
    }

    public function getBasketItems($arCurrency)
    {
        global $USER;

        $arResult = [];
        $this->setIsGlobal(true);

        if (empty($arCurrency))
            return $arResult;

        if ($this->getIsBase()) {
            $arParentItems = [];
            $arOrderErrors = [];
            $arOrder = [
                'SITE_ID' => SITE_ID,
                'USER_ID' => $USER->GetID(),
                'ORDER_PRICE' => "0",
                'ORDER_WEIGHT' => "0",
                'BASKET_ITEMS' => []
            ];

            $arBasket = Arrays::fromDBResult(CSaleBasket::GetList([], [
                'FUSER_ID' => CSaleBasket::GetBasketUserID(),
                'LID' => SITE_ID,
                'ORDER_ID' => 'NULL',
                'SET_PARENT_ID' => 'NULL'
            ]), true)->indexBy('PRODUCT_ID');

            if ($arBasket->isEmpty())
                return $arResult;

            $arItems = Arrays::from($this->getElements(['SORT' => 'ASC'], [
                'ID' => $arBasket->getKeys(),
                'ACTIVE' => 'Y'
            ]))->indexBy('ID');

            $arProducts = Arrays::fromDBResult(CCatalogProduct::GetList([], [
                'ID' => $arBasket->getKeys()
            ]))->indexBy('ID');

            $arMeasures = Arrays::fromDBResult(CCatalogMeasureRatio::getList([], [
                'PRODUCT_ID' => $arBasket->getKeys()
            ]))->indexBy('PRODUCT_ID');

            foreach ($arBasket as $arBasketItem) {
                if ($arBasketItem['CAN_BUY'] !== 'Y')
                    continue;

                $iId = $arBasketItem['PRODUCT_ID'];
                $arItem = $arItems->get($iId);
                $arProduct = $arProducts->get($iId);
                $arMeasure = $arMeasures->get($iId);

                if (empty($arItem) || empty($arProduct))
                    continue;

                $arResultItem = [
                    'ID' => $iId,
                    'IBLOCK' => [
                        'ID' => $arItem['IBLOCK_ID']
                    ],
                    'SECTION' => [
                        'ID' => $arItem['IBLOCK_SECTION_ID']
                    ],
                    'NAME' => $arItem['NAME'],
                    'ACTIVE' => $arItem['ACTIVE'] === 'Y',
                    'LINK' => $arItem['DETAIL_PAGE_URL'],
                    'DELAY' => $arBasketItem['DELAY'] === 'Y',
                    'PICTURE' => null,
                    'PROPERTIES' => $arItem['PROPERTIES'],
                    'MEASURE' => [
                        'RATIO' => !empty($arMeasure) ? Type::toFloat($arMeasure['RATIO']) : 1
                    ],
                    'QUANTITY' => [
                        'TRACE' => $arProduct['QUANTITY_TRACE'] === 'Y',
                        'UNLIMITED' => $arProduct['CAN_BUY_ZERO'] === 'Y',
                        'AVAILABLE' => $arProduct['QUANTITY'],
                        'VALUE' => $arBasketItem['QUANTITY']
                    ]
                ];

                if (!empty($arItem['PREVIEW_PICTURE'])) {
                    $arResultItem['PICTURE'] = $arItem['PREVIEW_PICTURE'];
                } else if (!empty($arItem['DETAIL_PICTURE'])) {
                    $arResultItem['PICTURE'] = $arItem['DETAIL_PICTURE'];
                }

                $arParentItem = CCatalogSku::GetProductInfo($arResultItem['ID'], $arItem['IBLOCK_ID']);

                if (!empty($arParentItem)) {
                    if (ArrayHelper::keyExists($arParentItem['ID'], $arParentItems)) {
                        $arParentItem = $arParentItems[$arParentItem['ID']];
                    } else {
                        $arParentItem = CIBlockElement::GetList([], [
                            'ID' => $arParentItem['ID']
                        ])->GetNext();

                        if (!empty($arParentItem)) {
                            if (!empty($arParentItem['PREVIEW_PICTURE']))
                                $arParentItem['PREVIEW_PICTURE'] = CFile::GetFileArray($arParentItem['PREVIEW_PICTURE']);

                            if (!empty($arParentItem['DETAIL_PICTURE']))
                                $arParentItem['DETAIL_PICTURE'] = CFile::GetFileArray($arParentItem['DETAIL_PICTURE']);

                            $arParentItems[$arParentItem['ID']] = $arParentItem;
                        }
                    }

                    if (!empty($arParentItem)) {
                        if (empty($arResultItem['PICTURE'])) {
                            if (!empty($arParentItem['PREVIEW_PICTURE'])) {
                                $arResultItem['PICTURE'] = $arParentItem['PREVIEW_PICTURE'];
                            } else if (!empty($arParentItem['DETAIL_PICTURE'])) {
                                $arResultItem['PICTURE'] = $arParentItem['DETAIL_PICTURE'];
                            }
                        }
                    }
                }

                $arPrice = [
                    'CURRENCY' => $arBasketItem['CURRENCY'],
                    'TYPE' => $arBasketItem['PRICE_TYPE_ID'],
                    'BASE' => [
                        'VALUE' => Type::toFloat($arBasketItem['PRICE'])
                    ],
                    'DISCOUNT' => [
                        'VALUE' => Type::toFloat($arBasketItem['PRICE'])
                    ]
                ];

                if (!$arResultItem['DELAY'])
                    $arOrder['BASKET_ITEMS'][] = [
                        'PRODUCT_ID' => $arResultItem['ID'],
                        'PRODUCT_PRICE_ID' => $arPrice['TYPE'],
                        'PRICE' => $arPrice['BASE']['VALUE'],
                        'CURRENCY' => $arCurrency['CODE'],
                        'BASE_PRICE' => $arPrice['BASE']['VALUE'],
                        'QUANTITY' => $arResultItem['QUANTITY']['VALUE'],
                        'LID' => SITE_ID,
                        'MODULE' => 'catalog',
                    ];

                $arResultItem['PRICE'] = $arPrice;
                $arResult[$arResultItem['ID']] = $arResultItem;
            }

            unset($iId);
            unset($arMeasure);
            unset($arProduct);
            unset($arParentItem);
            unset($arResultItem);

            /*CSaleDiscount::DoProcessOrder($arOrder, [
                'COUNT_DISCOUNT_4_ALL_QUANTITY' => 'Y'
            ], $arOrderErrors);*/

            $arOrderItems = Arrays::from($arOrder['BASKET_ITEMS']);
            $arOrderItems->indexBy('PRODUCT_ID');

            foreach ($arResult as &$arResultItem) {
                $arOrderItem = $arOrderItems->get($arResultItem['ID']);
                $arPrice = $arResultItem['PRICE'];

                if (!empty($arOrderItem))
                    $arPrice['DISCOUNT']['VALUE'] = $arOrderItem['PRICE'];

                $arPrice['BASE']['VALUE'] = CCurrencyRates::ConvertCurrency($arPrice['BASE']['VALUE'], $arPrice['CURRENCY'], $arCurrency['CODE']);
                $arPrice['BASE']['DISPLAY'] = CCurrencyLang::CurrencyFormat($arPrice['BASE']['VALUE'], $arCurrency['CODE'], true);
                $arPrice['DISCOUNT']['VALUE'] = CCurrencyRates::ConvertCurrency($arPrice['DISCOUNT']['VALUE'], $arPrice['CURRENCY'], $arCurrency['CODE']);
                $arPrice['DISCOUNT']['DISPLAY'] = CCurrencyLang::CurrencyFormat($arPrice['DISCOUNT']['VALUE'], $arCurrency['CODE'], true);
                $arPrice['CURRENCY'] = $arCurrency['CODE'];
                $arResultItem['PRICE'] = $arPrice;

                unset($arResultItem);
            }
        } else if ($this->getIsLite()) {
            $arBasket = Arrays::fromDBResult(CStartShopBasket::GetList([], [], [], [], !empty($arCurrency) ? $arCurrency['CODE'] : null))->indexBy('ID');

            if ($arBasket->isEmpty())
                return $arResult;

            $arItems = Arrays::from($this->getElements(['SORT' => 'ASC'], [
                'ID' => $arBasket->getKeys(),
                'ACTIVE' => 'Y'
            ]))->indexBy('ID');

            foreach ($arItems as $arItem) {
                $iId = $arItem['ID'];
                $arProduct = $arBasket->get($arItem['ID']);

                $arResultItem = [
                    'ID' => $iId,
                    'IBLOCK' => [
                        'ID' => $arItem['IBLOCK_ID']
                    ],
                    'SECTION' => [
                        'ID' => $arItem['IBLOCK_SECTION_ID']
                    ],
                    'NAME' => $arItem['NAME'],
                    'ACTIVE' => $arItem['ACTIVE'] === 'Y',
                    'LINK' => $arProduct['DETAIL_PAGE_URL'],
                    'DELAY' => false,
                    'PICTURE' => null,
                    'PROPERTIES' => $arItem['PROPERTIES'],
                    'MEASURE' => [
                        'RATIO' => $arProduct['STARTSHOP']['QUANTITY']['RATIO']
                    ],
                    'QUANTITY' => [
                        'TRACE' => $arProduct['STARTSHOP']['QUANTITY']['USE'],
                        'UNLIMITED' => false,
                        'AVAILABLE' => $arProduct['STARTSHOP']['QUANTITY']['VALUE'],
                        'VALUE' => CStartShopBasket::GetQuantity($iId)
                    ],
                    'PRICE' => null
                ];

                if (!empty($arItem['PREVIEW_PICTURE'])) {
                    $arResultItem['PICTURE'] = $arItem['PREVIEW_PICTURE'];
                } else if (!empty($arItem['DETAIL_PICTURE'])) {
                    $arResultItem['PICTURE'] = $arItem['DETAIL_PICTURE'];
                }

                $arPrice = CStartShopBasket::GetItemPriceType($iId);
                $arPrice = ArrayHelper::getValue($arProduct, ['STARTSHOP', 'PRICES', 'LIST', $arPrice]);

                if (empty($arPrice))
                    continue;

                $arPrice = [
                    'CURRENCY' => $arPrice['CURRENCY'],
                    'TYPE' => $arPrice['TYPE'],
                    'BASE' => [
                        'VALUE' => $arPrice['VALUE'],
                        'DISPLAY' => $arPrice['PRINT_VALUE']
                    ],
                    'DISCOUNT' => [
                        'VALUE' => $arPrice['VALUE'],
                        'DISPLAY' => $arPrice['PRINT_VALUE']
                    ]
                ];

                unset($iId);

                $arResultItem['PRICE'] = $arPrice;
                $arResult[$arResultItem['ID']] = $arResultItem;
            }
        }

        return $arResult;
    }

    public function getCompareItems($sCode, $iIBlockId)
    {
        $arResult = ArrayHelper::getValue($_SESSION, [
            $sCode,
            $iIBlockId,
            'ITEMS'
        ]);

        if (!Type::isArray($arResult))
            $arResult = [];

        return $arResult;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('intec.core'))
            return null;

        if (!$this->getIsBase() && !$this->getIsLite())
            return null;

        $arParams = $this->arParams;
        $arResult = [];

        $arParams = ArrayHelper::merge([
            'CURRENCY' => null,
            'COMPARE_SHOW' => 'N'
        ], $arParams);

        $arCurrency = $this->getCurrency($arParams['CURRENCY']);
        $arItems = $this->getBasketItems($arCurrency);

        $arResult['CURRENCY'] = $arCurrency;
        $arResult['BASKET'] = [
            'ITEMS' => [],
            'SUM' => [
                'BASE' => [
                    'VALUE' => 0,
                    'DISPLAY' => '0'
                ],
                'DISCOUNT' => [
                    'VALUE' => 0,
                    'DISPLAY' => '0'
                ]
            ]
        ];

        $arResult['DELAYED'] = [
            'ITEMS' => [],
            'SUM' => [
                'BASE' => [
                    'VALUE' => 0,
                    'DISPLAY' => '0'
                ],
                'DISCOUNT' => [
                    'VALUE' => 0,
                    'DISPLAY' => '0'
                ]
            ]
        ];

        foreach ($arItems as $arItem) {
            if (!$arItem['DELAY']) {
                $arResult['BASKET']['SUM']['BASE']['VALUE'] += $arItem['PRICE']['BASE']['VALUE'] * $arItem['QUANTITY']['VALUE'];
                $arResult['BASKET']['SUM']['DISCOUNT']['VALUE'] += $arItem['PRICE']['DISCOUNT']['VALUE'] * $arItem['QUANTITY']['VALUE'];
                $arResult['BASKET']['ITEMS'][$arItem['ID']] = $arItem;
            } else {
                $arResult['DELAYED']['SUM']['BASE']['VALUE'] += $arItem['PRICE']['BASE']['VALUE'] * $arItem['QUANTITY']['VALUE'];
                $arResult['DELAYED']['SUM']['DISCOUNT']['VALUE'] += $arItem['PRICE']['DISCOUNT']['VALUE'] * $arItem['QUANTITY']['VALUE'];
                $arResult['DELAYED']['ITEMS'][$arItem['ID']] = $arItem;
            }
        }

        $arResult['BASKET']['COUNT'] = count($arResult['BASKET']['ITEMS']);
        $arResult['DELAYED']['COUNT'] = count($arResult['DELAYED']['ITEMS']);

        if (!empty($arCurrency)) {
            if ($this->getIsBase()) {
                $arResult['BASKET']['SUM']['BASE']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                    $arResult['BASKET']['SUM']['BASE']['VALUE'],
                    $arCurrency['CODE'],
                    true
                );

                $arResult['BASKET']['SUM']['DISCOUNT']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                    $arResult['BASKET']['SUM']['DISCOUNT']['VALUE'],
                    $arCurrency['CODE'],
                    true
                );

                $arResult['DELAYED']['SUM']['BASE']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                    $arResult['BASKET']['SUM']['BASE']['VALUE'],
                    $arCurrency['CODE'],
                    true
                );

                $arResult['DELAYED']['SUM']['DISCOUNT']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                    $arResult['BASKET']['SUM']['DISCOUNT']['VALUE'],
                    $arCurrency['CODE'],
                    true
                );
            } else if ($this->getIsLite()) {
                $arResult['BASKET']['SUM']['BASE']['DISPLAY'] = CStartShopCurrency::FormatAsString(
                    $arResult['BASKET']['SUM']['DISCOUNT']['VALUE'],
                    !empty($arCurrency) ? $arCurrency['CODE'] : null
                );

                $arResult['BASKET']['SUM']['DISCOUNT']['DISPLAY'] = $arResult['BASKET']['SUM']['BASE']['DISPLAY'];
                $arResult['DELAYED']['SUM']['BASE']['DISPLAY'] = 0;
                $arResult['DELAYED']['SUM']['DISCOUNT']['DISPLAY'] = 0;
            }
        }

        $arResult['COMPARE'] = [
            'SHOW' => $arParams['COMPARE_SHOW'] === 'Y'
        ];

        $arResult['COMPARE']['ITEMS'] = $this->getCompareItems(
            $arParams['COMPARE_CODE'],
            $arParams['COMPARE_IBLOCK_ID']
        );

        $arResult['COMPARE']['COUNT'] = count($arResult['COMPARE']['ITEMS']);
        $this->arResult = $arResult;
        $this->includeComponentTemplate();

        return $this->arResult;
    }
}