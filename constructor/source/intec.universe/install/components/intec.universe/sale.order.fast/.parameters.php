<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Currency\CurrencyLangTable;
use Bitrix\Sale\Internals\PersonTypeTable;
use Bitrix\Sale\Delivery\Services\Manager as Delivery;
use Bitrix\Sale\PaySystem\Manager as PaySystem;
use Bitrix\Sale\Internals\OrderPropsTable;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

if (!Loader::includeModule('intec.core'))
    return;

$bIsBase = Loader::includeModule('catalog') && Loader::includeModule('sale');
$bIsLite = !$bIsBase && Loader::includeModule('intec.startshop');

$arParameters = [];
$arParameters['AJAX_MODE'] = [];

$arParameters['VARIABLES_ACTION'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_VARIABLES_ACTION'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'action'
];

$arParameters['VARIABLES_VALUES'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_VARIABLES_VALUES'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'values'
];

if ($bIsBase) {
    $arCurrenciesLanguage = Arrays::from(CurrencyLangTable::getList([
        'filter' => [
            'LID' => LANGUAGE_ID
        ]
    ])->fetchAll())->indexBy('CURRENCY');

    $arCurrencies = Arrays::from(CurrencyTable::getList()->fetchAll());
    $arCurrencies = $arCurrencies->asArray(function ($iIndex, $arCurrency) use (&$arCurrenciesLanguage) {
        $arLanguage = $arCurrenciesLanguage->get($arCurrency['CURRENCY']);
        $sName = $arCurrency['CURRENCY'];

        if (!empty($arLanguage))
            $sName = $arLanguage['FULL_NAME'];

        return [
            'key' => $arCurrency['CURRENCY'],
            'value' => '['.$arCurrency['CURRENCY'].'] '.$sName
        ];
    });

    unset($arCurrenciesLanguage);

    $arCurrencies = ArrayHelper::merge([
        '' => Loc::getMessage('C_SALE_ORDER_FAST_CURRENCY_DEFAULT')
    ], $arCurrencies);

    $arDeliveries = Arrays::from(Delivery::getActiveList());
    $arDeliveries = $arDeliveries->asArray(function ($iIndex, $arDelivery) {
        return [
            'key' => $arDelivery['ID'],
            'value' => '['.$arDelivery['ID'].'] '.$arDelivery['NAME']
        ];
    });

    $arPayments = Arrays::from(PaySystem::getList()->fetchAll());
    $arPayments = $arPayments->asArray(function ($iIndex, $arPayment) {
        return [
            'key' => $arPayment['ID'],
            'value' => '['.$arPayment['ID'].'] '.$arPayment['NAME']
        ];
    });

    $arPersons = Arrays::from(PersonTypeTable::getList()->fetchAll());
    $arPersons = $arPersons->asArray(function ($iIndex, $arPerson) {
        return [
            'key' => $arPerson['ID'],
            'value' => '['.$arPerson['ID'].'] '.$arPerson['NAME']
        ];
    });

    $arProperties = [];

    if (!empty($arCurrentValues['PERSON'])) {
        $arProperties = Arrays::from(OrderPropsTable::getList([
            'filter' => [
                'PERSON_TYPE_ID' => $arCurrentValues['PERSON']
            ]
        ])->fetchAll());

        $arProperties = $arProperties->asArray(function ($iIndex, $arProperty) {
            if (!ArrayHelper::isIn($arProperty['TYPE'], [
                'STRING',
                'NUMBER',
                'Y/N'
            ])) return ['skip' => true];

            if ($arProperty['REQUIRED'] === 'Y')
                return ['skip' => true];

            if ($arProperty['MULTIPLE'] === 'Y')
                return ['skip' => true];

            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];
        });
    }

    $arParameters['CURRENCY'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_CURRENCY'),
        'TYPE' => 'LIST',
        'VALUES' => $arCurrencies
    ];

    $arParameters['DELIVERY'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DELIVERY'),
        'TYPE' => 'LIST',
        'VALUES' => $arDeliveries
    ];

    $arParameters['PAYMENT'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_PAYMENT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPayments
    ];

    $arParameters['PERSON'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_PERSON'),
        'TYPE' => 'LIST',
        'VALUES' => $arPersons,
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PERSON'])) {
        $arParameters['PROPERTIES'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_PROPERTIES'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => $arProperties
        ];
    }

    $arParameters['FIELDS_COMMENT_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_FIELDS_COMMENT_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ];
} else if ($bIsLite) {
    $arCurrencies = Arrays::fromDBResult(CStartShopCurrency::GetList([], ['ACTIVE' => 'Y']));
    $arCurrencies = $arCurrencies->asArray(function ($iIndex, $arCurrency) {
        return [
            'key' => $arCurrency['CODE'],
            'value' => '['.$arCurrency['CODE'].'] '.$arCurrency['LANG'][LANGUAGE_ID]['NAME']
        ];
    });

    $arCurrencies = ArrayHelper::merge([
        '' => Loc::getMessage('C_SALE_ORDER_FAST_CURRENCY_DEFAULT')
    ], $arCurrencies);

    $arDeliveries = Arrays::fromDBResult(CStartShopDelivery::GetList([], ['ACTIVE' => 'Y']));
    $arDeliveries = $arDeliveries->asArray(function ($iIndex, $arDelivery) {
        return [
            'key' => $arDelivery['ID'],
            'value' => '['.$arDelivery['ID'].']['.$arDelivery['SID'].'] '.$arDelivery['LANG'][LANGUAGE_ID]['NAME']
        ];
    });

    $arDeliveries = ArrayHelper::merge([
        '' => Loc::getMessage('C_SALE_ORDER_FAST_EMPTY')
    ], $arDeliveries);

    $arPayments = Arrays::fromDBResult(CStartShopPayment::GetList([], ['ACTIVE' => 'Y']));
    $arPayments = $arPayments->asArray(function ($iIndex, $arPayment) {
        return [
            'key' => $arPayment['ID'],
            'value' => '['.$arPayment['ID'].'] '.$arPayment['LANG'][LANGUAGE_ID]['NAME']
        ];
    });

    $arPayments = ArrayHelper::merge([
        '' => Loc::getMessage('C_SALE_ORDER_FAST_EMPTY')
    ], $arPayments);

    $arStatuses = Arrays::fromDBResult(CStartShopOrderStatus::GetList());
    $arStatuses = $arStatuses->asArray(function ($iIndex, $arStatus) {
        return [
            'key' => $arStatus['ID'],
            'value' => '['.$arStatus['ID'].'] '.$arStatus['LANG'][LANGUAGE_ID]['NAME']
        ];
    });

    $arStatuses = ArrayHelper::merge([
        '' => Loc::getMessage('C_SALE_ORDER_FAST_EMPTY')
    ], $arStatuses);

    $arProperties = Arrays::fromDBResult(CStartShopOrderProperty::GetList([], ['ACTIVE' => 'Y']));
    $arProperties = $arProperties->asArray(function ($iIndex, $arProperty) {
        if (!ArrayHelper::isIn($arProperty['TYPE'], [
            'S',
            'B'
        ])) return ['skip' => true];

        if ($arProperty['ACTIVE'] !== 'Y')
            return ['skip' => true];

        if ($arProperty['REQUIRED'] === 'Y')
            return ['skip' => true];

        return [
            'key' => $arProperty['ID'],
            'value' => '['.$arProperty['ID'].']['.$arProperty['SID'].'] '.$arProperty['LANG'][LANGUAGE_ID]['NAME']
        ];
    });

    $arParameters['CURRENCY'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_CURRENCY'),
        'TYPE' => 'LIST',
        'VALUES' => $arCurrencies
    ];

    $arParameters['DELIVERY'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DELIVERY'),
        'TYPE' => 'LIST',
        'VALUES' => $arDeliveries
    ];

    $arParameters['PAYMENT'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_PAYMENT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPayments
    ];

    $arParameters['STATUS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_STATUS'),
        'TYPE' => 'LIST',
        'VALUES' => $arStatuses
    ];

    $arParameters['PROPERTIES'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_PROPERTIES'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'VALUES' => $arProperties
    ];
}

$arComponentParameters = [
    'PARAMETERS' => $arParameters
];