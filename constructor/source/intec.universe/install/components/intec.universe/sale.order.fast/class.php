<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Order;
use Bitrix\Sale\Internals\OrderPropsTable;
use Bitrix\Sale\Internals\PersonTypeTable;
use Bitrix\Sale\Internals\StatusTable;
use Bitrix\Sale\Delivery\Services\Manager as Delivery;
use Bitrix\Sale\PaySystem\Manager as PaySystem;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

class IntecSaleOrderFastComponent extends CBitrixComponent
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

    protected function getCurrency($sCode = null)
    {
        $arCurrency = null;

        if ($this->getIsBase()) {
            if (!empty($sCode))
                $arCurrency = CCurrency::GetByID($sCode);

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
            $arCurrency = CStartShopCurrency::GetList([], [
                'CODE' => $sCode,
                'ACTIVE' => 'Y'
            ])->Fetch();

            if (empty($arCurrency))
                $arCurrency = CStartShopCurrency::GetBase()->Fetch();

            if (empty($arCurrency))
                $arCurrency = null;
        }

        return $arCurrency;
    }

    protected function getDelivery($iId = null)
    {
        $arDelivery = null;

        if ($this->getIsBase()) {
            $arDeliveries = Delivery::getActiveList();

            if (!empty($iId) && !empty($arDeliveries[$iId]))
                $arDelivery = $arDeliveries[$iId];

            if (!empty($arDelivery)) {
                $arDelivery = [
                    'ID' => Type::toInteger($arDelivery['ID']),
                    'NAME' => $arDelivery['NAME'],
                    'SORT' => Type::toInteger($arDelivery['SORT'])
                ];
            } else {
                $arDelivery = null;
            }
        } else if ($this->getIsLite()) {
            $arDelivery = CStartShopDelivery::GetList([], [
                'ID' => $iId,
                'ACTIVE' => 'Y',
                'SID' => SITE_ID
            ])->Fetch();

            if (!empty($arDelivery) && $arDelivery['ACTIVE'] === 'Y') {
                $arDelivery = [
                    'ID' => Type::toInteger($arDelivery['ID']),
                    'NAME' => ArrayHelper::getValue($arDelivery, ['LANG', LANGUAGE_ID, 'NAME']),
                    'SORT' => Type::toInteger($arDelivery['SORT'])
                ];
            } else {
                $arDelivery = null;
            }
        }

        return $arDelivery;
    }

    protected function getPayment($iId = null)
    {
        $arPayment = null;

        if ($this->getIsBase()) {
            if (!empty($iId))
                $arPayment = PaySystem::getById($iId);

            if (!empty($arPayment)) {
                $arPayment = [
                    'ID' => Type::toInteger($arPayment['ID']),
                    'NAME' => $arPayment['NAME'],
                    'SORT' => Type::toInteger($arPayment['SORT'])
                ];
            } else {
                $arPayment = null;
            }
        } else if ($this->getIsLite()) {
            $arPayment = CStartShopPayment::GetList([], [
                'ID' => $iId,
                'ACTIVE' => 'Y'
            ])->Fetch();

            if (!empty($arPayment) && $arPayment['ACTIVE'] === 'Y') {
                $arPayment = [
                    'ID' => Type::toInteger($arPayment['ID']),
                    'NAME' => ArrayHelper::getValue($arPayment, ['LANG', LANGUAGE_ID, 'NAME']),
                    'SORT' => Type::toInteger($arPayment['SORT'])
                ];
            } else {
                $arPayment = null;
            }
        }

        return $arPayment;
    }

    protected function getPerson($iId = null)
    {
        $arPerson = null;

        if ($this->getIsBase()) {
            if (!empty($iId))
                $arPerson = PersonTypeTable::getById($iId)->fetch();

            if (empty($arPerson))
                $arPerson = null;
        }

        return $arPerson;
    }

    protected function getProduct($iId = null, $iQuantity = 1, $arCurrency = null)
    {
        global $USER;

        if (empty($iId))
            return null;

        if (!Loader::includeModule('iblock'))
            return null;

        $rsProduct = CIBlockElement::GetByID($iId)->GetNextElement();

        if (empty($rsProduct))
            return null;

        $arProduct = $rsProduct->GetFields();
        $arProduct['PROPERTIES'] = $rsProduct->GetProperties();

        unset($rsProduct);

        if (!empty($arProduct['PREVIEW_PICTURE']))
            $arProduct['PREVIEW_PICTURE'] = CFile::GetFileArray($arProduct['PREVIEW_PICTURE']);

        if (!empty($arProduct['DETAIL_PICTURE']))
            $arProduct['DETAIL_PICTURE'] = CFile::GetFileArray($arProduct['DETAIL_PICTURE']);

        $arProduct['AVAILABLE'] = 'N';
        $arProduct['PRICE'] = null;
        $arProduct['MEASURE'] = [
            'ID' => null,
            'CODE' => null,
            'NAME' => null,
            'VALUE' => null,
            'RATIO' => 1
        ];

        $arProduct['QUANTITY'] = [
            'TRACE' => 'Y',
            'UNLIMITED' => 'N',
            'VALUE' => 0
        ];

        if ($this->getIsBase()) {
            $arCatalogProduct = ProductTable::getById($arProduct['ID'])->fetch();

            if (!empty($arCatalogProduct)) {
                $arProduct['AVAILABLE'] = $arCatalogProduct['AVAILABLE'];
                $arProduct['QUANTITY'] = [
                    'TRACE' => $arCatalogProduct['QUANTITY_TRACE'],
                    'UNLIMITED' => $arCatalogProduct['CAN_BUY_ZERO'],
                    'VALUE' => Type::toFloat($arCatalogProduct['QUANTITY'])
                ];

                if ($arProduct['AVAILABLE'] === 'Y')
                    $arProduct['AVAILABLE'] = $arProduct['ACTIVE'];

                $arRatio = CCatalogMeasureRatio::getList([], [
                    'PRODUCT_ID' => $arProduct['ID']
                ])->Fetch();

                if (!empty($arRatio))
                    $arProduct['MEASURE']['RATIO'] = Type::toFloat($arRatio['RATIO']);

                if ($iQuantity < $arProduct['MEASURE']['RATIO'])
                    $iQuantity = $arProduct['MEASURE']['RATIO'];

                if (
                    $arProduct['QUANTITY']['TRACE'] === 'Y' &&
                    $arProduct['QUANTITY']['UNLIMITED'] !== 'Y' &&
                    $iQuantity > $arProduct['QUANTITY']
                ) $iQuantity = $arProduct['QUANTITY'];

                $arProduct['PRICE'] = CCatalogProduct::GetOptimalPrice($arProduct['ID'], $iQuantity, $USER->GetUserGroupArray());

                if (!empty($arCatalogProduct['MEASURE'])) {
                    $arMeasure = CCatalogMeasure::getList([], [
                        'ID' => $arCatalogProduct['MEASURE']
                    ])->Fetch();

                    if (!empty($arMeasure)) {
                        $arProduct['MEASURE'] = [
                            'ID' => Type::toInteger($arMeasure['ID']),
                            'CODE' => $arMeasure['CODE'],
                            'NAME' => $arMeasure['MEASURE_TITLE'],
                            'VALUE' => $arMeasure['SYMBOL']
                        ];
                    }
                }

                if (!empty($arProduct['PRICE'])) {
                    $arProduct['PRICE'] = [
                        'BASE' => [
                            'VALUE' => $arProduct['PRICE']['RESULT_PRICE']['BASE_PRICE'],
                            'DISPLAY' => null
                        ],
                        'CURRENCY' => $arProduct['PRICE']['RESULT_PRICE']['CURRENCY'],
                        'DISCOUNT' => [
                            'VALUE' => $arProduct['PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'],
                            'DISPLAY' => null,
                            'SIZE' => $arProduct['PRICE']['RESULT_PRICE']['DISCOUNT'],
                            'PERCENT' => $arProduct['PRICE']['RESULT_PRICE']['PERCENT']
                        ]
                    ];

                    $arProduct['PRICE']['BASE']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                        $arProduct['PRICE']['BASE']['VALUE'],
                        $arProduct['PRICE']['CURRENCY']
                    );

                    $arProduct['PRICE']['DISCOUNT']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                        $arProduct['PRICE']['DISCOUNT']['VALUE'],
                        $arProduct['PRICE']['CURRENCY']
                    );
                } else {
                    $arProduct['PRICE'] = null;
                }

                $arParentProduct = CCatalogSku::GetProductInfo($arProduct['ID'], $arProduct['IBLOCK_ID']);

                if (!empty($arParentProduct)) {
                    $arParentProduct = CIBlockElement::GetList([], [
                        'ID' => $arParentProduct['ID']
                    ])->GetNext();

                    if (!empty($arParentProduct)) {
                        if (!empty($arParentProduct['DETAIL_PICTURE']))
                            $arParentProduct['DETAIL_PICTURE'] = CFile::GetFileArray($arParentProduct['DETAIL_PICTURE']);

                        if (empty($arProduct['PREVIEW_PICTURE']) && !empty($arParentProduct['PREVIEW_PICTURE']))
                            $arProduct['PREVIEW_PICTURE'] = CFile::GetFileArray($arParentProduct['PREVIEW_PICTURE']);

                        if (empty($arProduct['DETAIL_PICTURE']) && !empty($arParentProduct['DETAIL_PICTURE'])) {
                            $arProduct['DETAIL_PICTURE'] = $arParentProduct['DETAIL_PICTURE'];
                        }
                    }
                }
            }
        } else {
            $arCatalogProduct = CStartShopCatalogProduct::GetList([], ['ID' => $arProduct['ID']], [], [], !empty($arCurrency) ? $arCurrency['CODE'] : null)->Fetch();

            if (!empty($arCatalogProduct)) {
                $arProduct['DETAIL_PAGE_URL'] = $arCatalogProduct['DETAIL_PAGE_URL'];
                $arProduct['AVAILABLE'] = $arCatalogProduct['STARTSHOP']['AVAILABLE'] ? 'Y' : 'N';
                $arProduct['QUANTITY'] = [
                    'TRACE' => $arCatalogProduct['STARTSHOP']['QUANTITY']['USE'] ? 'Y' : 'N',
                    'UNLIMITED' => 'N',
                    'VALUE' => $arCatalogProduct['STARTSHOP']['QUANTITY']['VALUE']
                ];

                $arProduct['MEASURE']['RATIO'] = $arCatalogProduct['STARTSHOP']['QUANTITY']['RATIO'];
                $arProduct['PRICE'] = $arCatalogProduct['STARTSHOP']['PRICES']['MINIMAL'];

                if (!empty($arProduct['PRICE'])) {
                    $arProduct['PRICE'] = [
                        'BASE' => [
                            'VALUE' => $arProduct['PRICE']['VALUE'],
                            'DISPLAY' => null
                        ],
                        'CURRENCY' => $arProduct['PRICE']['CURRENCY'],
                        'DISCOUNT' => [
                            'VALUE' => $arProduct['PRICE']['VALUE'],
                            'DISPLAY' => null,
                            'SIZE' => 0,
                            'PERCENT' => 0
                        ]
                    ];

                    $arProduct['PRICE']['BASE']['DISPLAY'] = CStartShopCurrency::FormatAsString(
                        $arProduct['PRICE']['BASE']['VALUE'],
                        $arProduct['PRICE']['CURRENCY']
                    );

                    $arProduct['PRICE']['DISCOUNT']['DISPLAY'] = $arProduct['PRICE']['BASE']['DISPLAY'];
                } else {
                    $arProduct['PRICE'] = null;
                }
            }
        }

        return $arProduct;
    }

    protected function getProperties($arPerson = null, $arRequired = [])
    {
        $arProperties = [];

        if (!Type::isArray($arRequired))
            $arRequired = [];

        if ($this->getIsBase()) {
            if (!empty($arPerson)) {
                $rsProperties = OrderPropsTable::getList([
                    'filter' => [
                        'PERSON_TYPE_ID' => $arPerson['ID']
                    ]
                ])->fetchAll();

                foreach ($rsProperties as $arProperty) {
                    if (!ArrayHelper::isIn($arProperty['TYPE'], [
                        'STRING',
                        'NUMBER',
                        'Y/N'
                    ])) continue;

                    if ($arProperty['MULTIPLE'] === 'Y')
                        continue;

                    if ($arProperty['REQUIRED'] !== 'Y' && !ArrayHelper::isIn(
                    $arProperty['ID'],
                    $arRequired
                    )) continue;

                    $arProperties[] = [
                        'ID' => Type::toInteger($arProperty['ID']),
                        'CODE' => $arProperty['CODE'],
                        'NAME' => $arProperty['NAME'],
                        'SORT' => Type::toInteger($arProperty['SORT']),
                        'REQUIRED' => $arProperty['REQUIRED'],
                        'MULTIPLE' => $arProperty['MULTIPLE'],
                        'TYPE' => $arProperty['TYPE'],
                        'SUBTYPE' => null,
                        'DATA' => [],
                        'USER_FIELD' => null,
                        'IS_EMAIL' => $arProperty['IS_EMAIL'],
                        'IS_PROFILE_NAME' => $arProperty['IS_PROFILE_NAME'],
                        'IS_PAYER' => $arProperty['IS_PAYER'],
                        'IS_ZIP' => $arProperty['IS_ZIP'],
                        'IS_PHONE' => $arProperty['IS_PHONE'],
                        'IS_ADDRESS' => $arProperty['IS_ADDRESS'],
                        'VALUE' => null,
                        'ERROR' => null
                    ];
                }
            }
        } else if ($this->getIsLite()) {
            $rsProperties = CStartShopOrderProperty::GetList(['SORT' => 'ASC'], [
                'SID' => SITE_ID,
                'ACTIVE' => 'Y'
            ]);

            while ($arProperty = $rsProperties->GetNext()) {
                if ($arProperty['TYPE'] === 'B') {
                    $arProperty['TYPE'] = 'Y/N';
                } else {
                    $arProperty['TYPE'] = 'STRING';
                }

                if ($arProperty['ACTIVE'] !== 'Y')
                    continue;

                if ($arProperty['REQUIRED'] !== 'Y' && !ArrayHelper::isIn(
                    $arProperty['ID'],
                    $arRequired
                )) continue;

                if (empty($arProperty['SUBTYPE']))
                    $arProperty['SUBTYPE'] = null;

                if (empty($arProperty['DATA']))
                    $arProperty['DATA'] = [];

                $arProperties[] = [
                    'ID' => Type::toInteger($arProperty['ID']),
                    'CODE' => $arProperty['CODE'],
                    'NAME' => ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']),
                    'SORT' => Type::toInteger($arProperty['SORT']),
                    'REQUIRED' => $arProperty['REQUIRED'],
                    'TYPE' => $arProperty['TYPE'],
                    'SUBTYPE' => $arProperty['SUBTYPE'],
                    'DATA' => $arProperty['DATA'],
                    'USER_FIELD' => $arProperty['USER_FIELD'],
                    'IS_EMAIL' => $arProperty['USER_FIELD'] === 'EMAIL' ? 'Y' : 'N',
                    'IS_PROFILE_NAME' => $arProperty['USER_FIELD'] === 'LAST_MAME' || $arProperty['USER_FIELD'] === 'MAME' ? 'Y' : 'N',
                    'IS_PAYER' => $arProperty['USER_FIELD'] === 'LAST_MAME' || $arProperty['USER_FIELD'] === 'MAME' ? 'Y' : 'N',
                    'IS_ZIP' => $arProperty['USER_FIELD'] === 'PERSONAL_ZIP' ? 'Y' : 'N',
                    'IS_PHONE' => $arProperty['USER_FIELD'] === 'PERSONAL_PHONE' ? 'Y' : 'N',
                    'IS_ADDRESS' => $arProperty['USER_FIELD'] === 'PERSONAL_STREET' ? 'Y' : 'N',
                    'VALUE' => null,
                    'ERROR' => null
                ];
            }
        }

        return $arProperties;
    }

    protected function getStatus($mStatus)
    {
        $arStatus = null;

        if ($this->getIsLite()) {
            $arStatus = CStartShopOrderStatus::GetByID($mStatus)->Fetch();

            if (empty($arStatus))
                $arStatus = CStartShopOrderStatus::GetDefault(SITE_ID)->Fetch();

            if (!empty($arStatus)) {
                $arStatus = [
                    'ID' => $arStatus['ID'],
                    'SORT' => Type::toInteger($arStatus['SORT'])
                ];
            } else {
                $arStatus = null;
            }
        }

        return $arStatus;
    }

    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule('intec.core'))
            return;

        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'VARIABLES_ACTION' => 'action',
            'VARIABLES_VALUES' => 'values',
            'FIELDS_COMMENT_USE' => 'Y',
            'PRODUCT' => null,
            'CURRENCY' => null,
            'DELIVERY' => null,
            'PAYMENT' => null,
            'PERSON' => null,
            'STATUS' => null,
            'PROPERTIES' => [],
            'QUANTITY' => 1
        ], $arParams);

        if (empty($arParams['VARIABLES_ACTION']))
            $arParams['VARIABLES_ACTION'] = 'action';

        if (empty($arParams['VARIABLES_VALUES']))
            $arParams['VARIABLES_VALUES'] = 'values';

        if (!Type::isArray($arParams['PROPERTIES']))
            $arParams['PROPERTIES'] = [];

        return parent::onPrepareComponentParams($arParams);
    }

    public function executeComponent()
    {
        global $USER;

        if (!Loader::includeModule('intec.core'))
            return null;

        if (!$this->getIsBase() && !$this->getIsLite())
            return null;

        $oRequest = Core::$app->request;
        $sSiteId = Context::getCurrent()->getSite();
        $arUser = null;

        if ($USER->IsAuthorized()) {
            $arUser = CUser::GetByID($USER->GetID());
            $arUser = $arUser->GetNext();

            if (empty($arUser))
                $arUser = null;
        }

        $arParams = $this->arParams;
        $arResult = [];

        $arResult['AVAILABLE'] = true;
        $arResult['ACTION'] = null;
        $arResult['STATE'] = null;
        $arResult['ERROR'] = null;
        $arResult['VARIABLES'] = [
            'ACTION' => $arParams['VARIABLES_ACTION'],
            'VALUES' => $arParams['VARIABLES_VALUES'],
            'SESSION' => 'session'
        ];

        $arResult['CURRENCY'] = $this->getCurrency($arParams['CURRENCY']);
        $arResult['DELIVERY'] = $this->getDelivery($arParams['DELIVERY']);
        $arResult['PAYMENT'] = $this->getPayment($arParams['PAYMENT']);
        $arResult['PERSON'] = $this->getPerson($arParams['PERSON']);
        $arResult['PRODUCT'] = null;
        $arResult['PROPERTIES'] = [];
        $arResult['STATUS'] = $this->getStatus($arParams['STATUS']);
        $arResult['QUANTITY'] = Type::toFloat($arParams['QUANTITY']);
        $arResult['ORDER'] = null;
        $arResult['PRICE'] = null;

        $arResult['FIELDS'] = [];
        $arResult['FIELDS']['COMMENT'] = [
            'USE' => $arParams['FIELDS_COMMENT_USE'] === 'Y',
            'VALUE' => null
        ];

        if ($arResult['QUANTITY'] <= 0)
            $arResult['QUANTITY'] = 1;

        $arResult['PRODUCT'] = $this->getProduct($arParams['PRODUCT'], $arResult['QUANTITY'], $arResult['CURRENCY']);

        if ($this->getIsBase()) {
            if (empty($arResult['PERSON'])) {
                $arResult['ERROR'] = [
                    'CODE' => 'SETUP',
                    'ENTITY' => 'PERSON',
                    'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_SETUP_PERSON')
                ];
            } else if (empty($arResult['CURRENCY'])) {
                $arResult['ERROR'] = [
                    'CODE' => 'SETUP',
                    'ENTITY' => 'CURRENCY',
                    'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_SETUP_CURRENCY')
                ];
            } else if (empty($arResult['DELIVERY'])) {
                $arResult['ERROR'] = [
                    'CODE' => 'SETUP',
                    'ENTITY' => 'DELIVERY',
                    'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_SETUP_DELIVERY')
                ];
            } else if (empty($arResult['PAYMENT'])) {
                $arResult['ERROR'] = [
                    'CODE' => 'SETUP',
                    'ENTITY' => 'PAYMENT',
                    'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_SETUP_PAYMENT')
                ];
            }
        } else {
            $arResult['FIELDS']['COMMENT']['USE'] = false;
        }

        if (!empty($arResult['PRODUCT'])) {
            $arProduct = $arResult['PRODUCT'];
            $arResult['AVAILABLE'] = $arProduct['AVAILABLE'] === 'Y';
            $arResult['PRICE'] = $arProduct['PRICE'];

            if ($arResult['QUANTITY'] < $arProduct['MEASURE']['RATIO'])
                $arResult['QUANTITY'] = $arProduct['MEASURE']['RATIO'];

            if (
                $arProduct['QUANTITY']['TRACE'] === 'Y' &&
                $arProduct['QUANTITY']['UNLIMITED'] !== 'Y' &&
                $arResult['QUANTITY'] > $arProduct['QUANTITY']
            ) $arResult['QUANTITY'] = $arProduct['QUANTITY'];

            if (!empty($arResult['PRICE'])) {
                $arResult['PRICE']['BASE']['VALUE'] = $arResult['PRICE']['BASE']['VALUE'] * $arResult['QUANTITY'];
                $arResult['PRICE']['DISCOUNT']['VALUE'] = $arResult['PRICE']['DISCOUNT']['VALUE'] * $arResult['QUANTITY'];

                if ($this->getIsBase()) {
                    $arResult['PRICE']['BASE']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                        $arResult['PRICE']['BASE']['VALUE'],
                        $arResult['PRICE']['CURRENCY']
                    );

                    $arResult['PRICE']['DISCOUNT']['DISPLAY'] = CCurrencyLang::CurrencyFormat(
                        $arResult['PRICE']['DISCOUNT']['VALUE'],
                        $arResult['PRICE']['CURRENCY']
                    );
                } else {
                    $arResult['PRICE']['BASE']['DISPLAY'] = CStartShopCurrency::FormatAsString(
                        $arResult['PRICE']['BASE']['VALUE'],
                        $arResult['PRICE']['CURRENCY']
                    );

                    $arResult['PRICE']['DISCOUNT']['DISPLAY'] = $arResult['PRICE']['BASE']['DISPLAY'];
                }
            }
        }

        $arProperties = $this->getProperties(
            $arResult['PERSON'],
            $arParams['PROPERTIES']
        );

        if ($this->getIsLite() && !empty($arUser)) {
            foreach ($arProperties as &$arProperty)
                if (!empty($arProperty['USER_FIELD'])) {
                    $arProperty['VALUE'] = ArrayHelper::getValue(
                        $arUser,
                        $arProperty['USER_FIELD']
                    );
                }

            unset($arProperty);
        }

        if ($oRequest->getIsPost()) {
            if (check_bitrix_sessid($arResult['VARIABLES']['SESSION'])) {
                $arResult['ACTION'] = $oRequest->post($arResult['VARIABLES']['ACTION']);
            }

            $arValues = $oRequest->post($arResult['VARIABLES']['VALUES']);

            if (Type::isArray($arValues))
                foreach ($arProperties as &$arProperty)
                    $arProperty['VALUE'] = ArrayHelper::getValue($arValues, $arProperty['ID']);

            if ($arResult['FIELDS']['COMMENT']['USE'])
                $arResult['FIELDS']['COMMENT']['VALUE'] = ArrayHelper::getValue($arValues, 'COMMENT');

            unset($arProperty);
            unset($arValues);
        }

        foreach ($arProperties as $arProperty) {
            if (!empty($arResult['ACTION'])) {
                if ($arProperty['REQUIRED'] === 'Y')
                    if (empty($arProperty['VALUE']) && !Type::isNumeric($arProperty['VALUE']))
                        $arProperty['ERROR'] = [
                            'CODE' => 'REQUIRED',
                            'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROPERTIES_REQUIRED')
                        ];

                if ($this->getIsLite() && empty($arProperty['ERROR'])) {
                    if ($arProperty['TYPE'] === 'STRING') {
                        if (empty($arProperty['SUBTYPE']) && !empty($arProperty['DATA']['LENGTH']))
                            if (StringHelper::length($arProperty['VALUE']) > $arProperty['DATA']['LENGTH'])
                                $arProperty['ERROR'] = [
                                    'CODE' => 'LENGTH',
                                    'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROPERTIES_LENGTH', [
                                        '#LENGTH#' => $arProperty['DATA']['LENGTH']
                                    ])
                                ];

                        if (!empty($arProperty['DATA']['EXPRESSION']))
                            if (!RegExp::isMatchBy($arProperty['DATA']['EXPRESSION'], $arProperty['VALUE']))
                                $arProperty['ERROR'] = [
                                    'CODE' => 'MATCH',
                                    'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROPERTIES_MATCH')
                                ];
                    }
                }

                if (!empty($arProperty['ERROR']))
                    $arResult['ERROR'] = [
                        'CODE' => 'PROCESS',
                        'STEP' => 'PROPERTIES',
                        'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROCESS_PROPERTIES')
                    ];
            }

            $arResult['PROPERTIES'][] = $arProperty;
        }

        if ($arResult['ACTION'] === 'apply' && $arResult['AVAILABLE'] && empty($arResult['ERROR'])) {
            if (empty($arUser)) {
                $sCaptcha = COption::GetOptionString('main', 'captcha_registration', 'N');
                $arUser = [
                    'login' => 'user_'.(microtime(true) * 10000),
                    'email' => null,
                    'name' => null,
                    'lastName' => null,
                    'password' => $this->randString(10),
                    'phone' => null
                ];

                foreach ($arResult['PROPERTIES'] as $arProperty) {
                    if ($arProperty['IS_EMAIL'] === 'Y')
                        $arUser['email'] = $arProperty['VALUE'];

                    if ($arProperty['IS_PROFILE_NAME'] === 'Y') {
                        $arUser['name'] = $arProperty['VALUE'];

                        if (!empty($arUser['name'])) {
                            $arUser['name'] = explode(' ', $arUser['name']);
                            $arUser['lastName'] = ArrayHelper::getValue($arUser['name'], 0);
                            $arUser['name'] = ArrayHelper::getValue($arUser['name'], 1);
                        }
                    }

                    if ($arProperty['PHONE'] === 'Y') {
                        $arUser['phone'] = $arProperty['VALUE'];
                    }
                }

                if (empty($arUser['email']))
                    $arUser['email'] = $arUser['login'].'@'.$_SERVER['SERVER_NAME'];

                if ($sCaptcha === 'Y')
                    COption::SetOptionString('main', 'captcha_registration', 'N');

                $bUserExists = CUser::GetList($by = 'sort', $order = 'asc', [
                    'EMAIL' => $arUser['email']
                ])->Fetch();

                $bUserExists = !empty($bUserExists);

                if ($bUserExists) {
                    $arResult['ERROR'] = [
                        'CODE' => 'PROCESS',
                        'STEP' => 'USER',
                        'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROCESS_USER').'. '.Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROCESS_USER_EXISTS')
                    ];

                    $arUser = null;
                } else {
                    $arUser = $USER->Register(
                        $arUser['login'],
                        $arUser['name'],
                        $arUser['lastName'],
                        $arUser['password'],
                        $arUser['password'],
                        $arUser['email']
                    );

                    if ($sCaptcha === 'Y')
                        COption::SetOptionString('main', 'captcha_registration', 'Y');

                    if ($arUser['TYPE'] === 'ERROR') {
                        $arResult['ERROR'] = [
                            'CODE' => 'PROCESS',
                            'STEP' => 'USER',
                            'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROCESS_USER')
                        ];

                        if (!empty($arUser['MESSAGE']))
                            $arResult['ERROR']['MESSAGE'] .= '. '.$arUser['MESSAGE'];

                        $arUser = null;
                    } else {
                        $arUser = CUser::GetByID($arUser['ID'])->Fetch();

                        (new CUser)->Update($arUser['ID'], [
                            'PERSONAL_PHONE' => $arUser['phone']
                        ]);
                    }
                }
            }

            if ($this->getIsBase()) {
                if (!empty($arUser)) {
                    $oOrder = Order::create($sSiteId, $arUser['ID'], $arResult['CURRENCY']['CODE']);
                    $oOrder->setPersonTypeId($arResult['PERSON']['ID']);
                    $oOrder->setField('CURRENCY', $arResult['CURRENCY']['CODE']);

                    if ($arResult['FIELDS']['COMMENT']['USE'])
                        $oOrder->setField('USER_DESCRIPTION', $arResult['FIELDS']['COMMENT']['VALUE']);

                    $oBasket = null;

                    if (!empty($arProduct)) {
                        $oBasket = Basket::create($sSiteId);

                        /** @var BasketItem $oItem */
                        $oItem = $oBasket->createItem('catalog', $arProduct['ID']);
                        $oItem->setFields([
                            'QUANTITY' => $arResult['QUANTITY'],
                            'CURRENCY' => $arResult['CURRENCY']['CODE'],
                            'LID' => $sSiteId,
                            'PRODUCT_PROVIDER_CLASS' => class_exists('\Bitrix\Catalog\Product\CatalogProvider') ?
                                '\Bitrix\Catalog\Product\CatalogProvider' :
                                'CCatalogProductProvider',
                            'CATALOG_XML_ID' => $arProduct['IBLOCK_EXTERNAL_ID'],
                            'PRODUCT_XML_ID' => $arProduct['EXTERNAL_ID']
                        ]);

                        $arItemProperties = [];

                        if (!empty($arProduct['IBLOCK_EXTERNAL_ID']))
                            $arItemProperties[] = [
                                'NAME' => 'Catalog XML_ID',
                                'CODE' => 'CATALOG.XML_ID',
                                'VALUE' => $arProduct['IBLOCK_EXTERNAL_ID'],
                                'SORT' => 100
                            ];

                        if (!empty($arProduct['EXTERNAL_ID']))
                            $arItemProperties[] = [
                                'NAME' => 'Product XML_ID',
                                'CODE' => 'PRODUCT.XML_ID',
                                'VALUE' => $arProduct['EXTERNAL_ID'],
                                'SORT' => 100
                            ];

                        $oItemProperties = $oItem->getPropertyCollection();
                        $oItemProperties->setProperty($arItemProperties);
                    } else {
                        $oBasket = Basket::loadItemsForFUser(Fuser::getId(), $sSiteId);
                    }

                    $oOrder->setBasket($oBasket);
                    $oShipments = $oOrder->getShipmentCollection();
                    $oShipment = $oShipments->createItem();
                    $oShipment->setFields([
                        'DELIVERY_ID' => $arResult['DELIVERY']['ID'],
                        'DELIVERY_NAME' => $arResult['DELIVERY']['NAME']
                    ]);

                    $oShipmentItems = $oShipment->getShipmentItemCollection();

                    foreach ($oBasket as $oItem) {
                        $oShipmentItem = $oShipmentItems->createItem($oItem);
                        $oShipmentItem->setQuantity($oItem->getQuantity());
                    }

                    $oPayments = $oOrder->getPaymentCollection();
                    $oPayment = $oPayments->createItem();
                    $oPayment->setFields([
                        'PAY_SYSTEM_ID' => $arResult['PAYMENT']['ID'],
                        'PAY_SYSTEM_NAME' => $arResult['PAYMENT']['NAME']
                    ]);

                    $oProperties = $oOrder->getPropertyCollection();

                    foreach ($arResult['PROPERTIES'] as $arProperty) {
                        $oProperty = null;

                        foreach ($oProperties as $oProperty) {
                            if ($oProperty->getField('ORDER_PROPS_ID') == $arProperty['ID'])
                                break;

                            $oProperty = null;
                        }

                        if (!empty($oProperty))
                            $oProperty->setValue($arProperty['VALUE']);
                    }

                    $oOrder->doFinalAction(true);
                    $oResult = $oOrder->save();

                    if ($oOrder->getId() > 0) {
                        $arResult['ORDER'] = $oOrder;
                    } else {
                        $arMessages = $oResult->getErrorMessages();
                        $arResult['ERROR'] = [
                            'CODE' => 'PROCESS',
                            'STEP' => 'ORDER',
                            'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROCESS_ORDER')
                        ];

                        foreach ($arMessages as $sMessage)
                            $arResult['ERROR']['MESSAGE'] .= '. ' . $sMessage;
                    }
                }
            } else {
                $fOrderSum = 0;
                $arOrder = [
                    'SID' => $sSiteId,
                    'ITEMS' => [],
                    'PROPERTIES' => []
                ];

                if (!empty($arProduct)) {
                    $fOrderSum = $arResult['PRICE']['DISCOUNT']['VALUE'];
                    $arOrder['ITEMS'][$arProduct['ID']] = [
                        'NAME' => $arProduct['NAME'],
                        'QUANTITY' => $arResult['QUANTITY'],
                        'PRICE' => $fOrderSum
                    ];
                } else {
                    $arProducts = CStartShopBasket::GetList([], [], [], [], !empty($arResult['CURRENCY']) ? $arResult['CURRENCY']['CODE'] : null, $sSiteId);
                    $arProducts = CStartShopUtil::DBResultToArray($arProducts);

                    foreach ($arProducts as $arProduct) {
                        $fPrice = ArrayHelper::getValue($arProduct, ['STARTSHOP', 'BASKET', 'PRICE', 'VALUE'], 0);
                        $fQuantity = ArrayHelper::getValue($arProduct, ['STARTSHOP', 'BASKET', 'QUANTITY']);
                        $fQuantity = $fQuantity < 0 ? 1 : Type::toFloat($fQuantity);
                        $fOrderSum += $fPrice * $fQuantity;

                        $arOrder['ITEMS'][$arProduct['ID']] = [
                            'NAME' => $arProduct['NAME'],
                            'QUANTITY' => $fQuantity,
                            'PRICE' => $fPrice
                        ];
                    }

                    unset($arProduct);
                }

                foreach ($arProperties as $arProperty)
                    $arOrder['PROPERTIES'][$arProperty['ID']] = $arProperty['VALUE'];

                if (!empty($arUser))
                    $arOrder['USER'] = $arUser['ID'];

                if (!empty($arResult['CURRENCY']))
                    $arOrder['CURRENCY'] = $arResult['CURRENCY']['CODE'];

                if (!empty($arResult['DELIVERY']))
                    $arOrder['DELIVERY'] = $arResult['DELIVERY']['ID'];

                if (!empty($arResult['PAYMENT']))
                    $arOrder['PAYMENT'] = $arResult['PAYMENT']['ID'];

                if (!empty($arResult['STATUS']))
                    $arOrder['STATUS'] = $arResult['STATUS']['ID'];

                $arOrder = CStartShopOrder::Add($arOrder);

                if (!empty($arOrder)) {
                    if (empty($arProduct))
                        CStartShopBasket::Clear($sSiteId);

                    $arOrder = CStartShopOrder::GetByID($arOrder);
                    $arOrder = $arOrder->Fetch();

                    $arResult['ORDER'] = $arOrder;
                }

                if (!empty($arResult['ORDER'])) {
                    if (CStartShopVariables::Get('MAIL_USE', 'N', $sSiteId) == 'Y') {
                        if (CStartShopVariables::Get('MAIL_ADMIN_ORDER_CREATE', 'N', $sSiteId) == 'Y') {
                            $arMail = [
                                'EVENT' => CStartShopVariables::Get('MAIL_ADMIN_ORDER_CREATE_EVENT', '', $sSiteId),
                                'MAIL' => CStartShopVariables::Get('MAIL_MAIL', '', $sSiteId),
                                'ITEMS' => [],
                                'PROPERTIES' => []
                            ];

                            if (!empty($arMail['EVENT']) && !empty($arMail['MAIL'])) {
                                foreach ($arOrder['ITEMS'] as $arItem)
                                    $arMail['ITEMS'][] = $arItem['NAME'].' '.
                                        $arItem['QUANTITY'].'x'.
                                        CStartShopCurrency::FormatAsString(CStartShopCurrency::Convert(
                                            $arItem['PRICE'],
                                            $arOrder['CURRENCY']
                                        ));

                                foreach ($arProperties as $arProperty) {
                                    if (empty($arProperty['VALUE']))
                                        continue;

                                    $arMail['PROPERTIES'][] = $arProperty['NAME'].': '.$arProperty['VALUE'];
                                }

                                $arMail['ITEMS'] = implode("\r\n", $arMail['ITEMS']);
                                $arMail['PROPERTIES'] = implode("\r\n", $arMail['PROPERTIES']);

                                $oEvent = new CEvent();
                                $oEvent->SendImmediate($arMail['EVENT'], $sSiteId, [
                                    'ORDER_ID' => $arResult['ORDER']['ID'],
                                    'ORDER_AMOUNT' => CStartShopCurrency::FormatAsString(CStartShopCurrency::Convert(
                                        $fOrderSum,
                                        $arResult['ORDER']['CURRENCY']
                                    )),
                                    'STARTSHOP_SHOP_EMAIL' => $arMail['MAIL'],
                                    'STARTSHOP_ORDER_LIST' => $arMail['ITEMS'],
                                    'STARTSHOP_ORDER_PROPERTY' => $arMail['PROPERTIES']
                                ], 'N', '');
                            }
                        }
                    }
                } else {
                    $arResult['ORDER'] = null;
                    $arResult['ERROR'] = [
                        'CODE' => 'PROCESS',
                        'STEP' => 'ORDER',
                        'MESSAGE' => Loc::getMessage('C_SALE_ORDER_FAST_ERRORS_PROCESS_ORDER')
                    ];
                }
            }
        }

        $this->arResult = $arResult;
        $this->includeComponentTemplate();

        return $this->arResult;
    }
}