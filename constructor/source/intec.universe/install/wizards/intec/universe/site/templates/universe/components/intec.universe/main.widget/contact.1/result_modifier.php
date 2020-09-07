<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\regionality\models\Region;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'MAP_VENDOR' => 'yandex',
    'CONSENT_URL' => null,
    'WIDE' => 'N',
    'BLOCK_SHOW' => 'N',
    'BLOCK_VIEW' => 'left',
    'ADDRESS_SHOW' => 'N',
    'EMAIL_SHOW' => 'N',
    'FORM_SHOW' => 'N',
    'PHONE_SHOW' => 'N',
    'PROPERTY_MAP' => null,
    'PROPERTY_CITY' => null,
    'PROPERTY_STREET' => null,
    'PROPERTY_PHONE' => null,
    'PROPERTY_EMAIL' => null,
    'MAIN' => null,
    'REGIONALITY_USE' => 'N',
    'REGIONALITY_STRICT' => 'N',
    'PROPERTY_REGION' => null,
    'CONTACT_TYPE' => 'PARAMS'
], $arParams);

include(__DIR__.'/modifiers/regionality.php');

if ($arParams['CONTACT_TYPE'] === 'IBLOCK')
    if (!Loader::includeModule('iblock'))
        $arParams['CONTACT_TYPE'] = 'PARAMS';

if ($arParams['CONTACT_TYPE'] === 'IBLOCK') {
    $arResult['REGIONALITY']['USE'] = $arParams['REGIONALITY_USE'] === 'Y';
    $arResult['REGIONALITY']['STRICT'] = $arParams['REGIONALITY_STRICT'] === 'Y';
    $arResult['REGIONALITY']['PROPERTY'] = $arParams['PROPERTY_REGION'];

    if (empty($arResult['REGIONALITY']['PROPERTY']))
        $arResult['REGIONALITY']['USE'] = false;

    $arFilter = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y'
    ];

    if (!empty($arParams['MAIN']))
        $arFilter['ID'] = $arParams['MAIN'];

    if ($arResult['REGIONALITY']['USE']) {
        $oRegion = Region::getCurrent();

        if (!empty($oRegion)) {
            $arConditions = [
                'LOGIC' => 'OR',
                ['PROPERTY_'.$arResult['REGIONALITY']['PROPERTY'] => $oRegion->id]
            ];

            if (!$arResult['REGIONALITY']['STRICT'])
                $arConditions[] = ['PROPERTY_'.$arResult['REGIONALITY']['PROPERTY'] => false];

            $arFilter[] = $arConditions;
        }

        unset($oRegion, $arConditions);
    }

    $rsItem = null;

    if (!empty($arFilter['ID']))
        $rsItem = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            $arFilter,
            false,
            ['nPageSize' => 1],
            ['ID', 'NAME', 'IBLOCK_ID']
        )->GetNextElement();

    if (empty($rsItem)) {
        unset($arFilter['ID']);

        $rsItem = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            $arFilter,
            false,
            ['nPageSize' => 1],
            ['ID', 'NAME', 'IBLOCK_ID']
        )->GetNextElement();
    }

    if (!empty($rsItem)) {
        $arItem = $rsItem->GetFields();
        $arItem['PROPERTIES'] = $rsItem->GetProperties();

        $arContact = [
            'ID' => $arItem['ID'],
            'NAME' => $arItem['NAME'],
            'DATA' => [
                'MAP' => null,
                'CITY' => null,
                'STREET' => null,
                'PHONE' => null,
                'EMAIL' => null
            ]
        ];

        foreach ($arContact['DATA'] as $sKey => $mValue) {
            $arProperty = $arParams['PROPERTY_'.$sKey];

            if (empty($arProperty))
                continue;

            $arProperty = ArrayHelper::getValue($arItem, ['PROPERTIES', $arProperty]);

            if (empty($arProperty))
                continue;

            if (Type::isArray($arProperty['VALUE']))
                $arProperty['VALUE'] = reset($arProperty['VALUE']);

            if (empty($arProperty['VALUE']) && !Type::isNumeric($arProperty['VALUE']))
                continue;

            $arContact['DATA'][$sKey] = $arProperty['VALUE'];
        }
    }

    $arPhones = [
        $arContact['DATA']['PHONE']
    ];
    $arEmail = [
        $arContact['DATA']['EMAIL']
    ];
} else {
    $arContact = [
        'NAME' => $arItem['NAME'],
        'DATA' => [
            'MAP' => null,
            'CITY' => ArrayHelper::getValue($arParams, 'ADDRESS_CITY'),
            'STREET' => ArrayHelper::getValue($arParams, 'ADDRESS_STREET'),
            'PHONE' => ArrayHelper::getValue($arParams, 'PHONE_VALUES'),
            'EMAIL' => ArrayHelper::getValue($arParams, 'EMAIL_VALUES')
        ]
    ];

    $arPhones = $arContact['DATA']['PHONE'];
    $arEmail = $arContact['DATA']['EMAIL'];
}

$arResult['BLOCK'] = [
    'SHOW' => $arParams['BLOCK_SHOW'],
    'TITLE' => ArrayHelper::getValue($arParams, 'BLOCK_TITLE'),
    'VIEW' => ArrayHelper::fromRange([
        'left',
        'over'
    ], $arParams['BLOCK_VIEW'])
];

$arResult['ADDRESS'] = [
    'SHOW' => $arParams['ADDRESS_SHOW'] === 'Y',
    'CITY' => $arContact['DATA']['CITY'],
    'STREET' => $arContact['DATA']['STREET']
];

if (empty($arResult['ADDRESS']['CITY']) && empty($arResult['ADDRESS']['STREET']))
    $arResult['ADDRESS']['SHOW'] = false;

$arResult['EMAIL'] = [
    'SHOW' => $arParams['EMAIL_SHOW'] === 'Y',
    'VALUES' => []
];

if (!empty($arEmail) && Type::isArray($arEmail)) {
    foreach ($arEmail as $sEmail) {
        if (empty($sEmail))
            continue;

        $arResult['EMAIL']['VALUES'][] = $sEmail;
    }

    unset($sEmail);
}

if (empty($arResult['EMAIL']['VALUES']))
    $arResult['EMAIL']['SHOW'] = false;

$arResult['FORM'] = [
    'SHOW' => $arParams['FORM_SHOW'] === 'Y',
    'ID' => ArrayHelper::getValue($arParams, 'FORM_ID'),
    'TEMPLATE' => ArrayHelper::getValue($arParams, 'FORM_TEMPLATE'),
    'TITLE' => ArrayHelper::getValue($arParams, 'FORM_TITLE'),
    'BUTTON' => [
        'TEXT' => ArrayHelper::getValue($arParams, 'FORM_BUTTON_TEXT')
    ]
];

if (empty($arResult['FORM']['TITLE']))
    $arResult['FORM']['TITLE'] = Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_TITLE_DEFAULT');

if (empty($arResult['FORM']['BUTTON']['TEXT']))
    $arResult['FORM']['BUTTON']['TEXT'] = Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_BUTTON_TEXT_DEFAULT');

if (empty($arResult['FORM']['ID']))
    $arResult['FORM']['SHOW'] = false;

$arResult['PHONE'] = [
    'SHOW' => $arParams['PHONE_SHOW'] === 'Y',
    'VALUES' => []
];

if (!empty($arPhones) && Type::isArray($arPhones)) {
    foreach ($arPhones as $sPhone) {
        if (empty($sPhone))
            continue;

        $sValue = StringHelper::replace($sPhone, [
            '(' => '',
            ')' => '',
            ' ' => '',
            '-' => ''
        ]);

        if (empty($sValue))
            continue;

        $arResult['PHONE']['VALUES'][] = [
            'VALUE' => $sValue,
            'DISPLAY' => $sPhone
        ];
    }

    unset($sValue);
    unset($sPhone);
}

if (empty($arResult['PHONE']['VALUES']))
    $arResult['PHONE']['SHOW'] = false;

if ($arParams['CONTACT_TYPE'] === 'IBLOCK') {
    $arMapData = [];

    if (!empty($arContact['DATA']['MAP'])) {
        $arMapCoordinate = explode(',', $arContact['DATA']['MAP']);

        $arMapData['PLACEMARKS'][] = [
            'LON' => $arMapCoordinate[1],
            'LAT' => $arMapCoordinate[0],
            'TEXT' => $arContact['NAME']
        ];

        if ($arParams['MAP_VENDOR'] === 'yandex') {
            $arMapData['yandex_lon'] = $arMapCoordinate[1];
            $arMapData['yandex_lat'] = $arMapCoordinate[0];
            $arMapData['yandex_scale'] = '16';
        } elseif ($arParams['MAP_VENDOR'] === 'google') {
            $arMapData['google_lon'] = $arMapCoordinate[1];
            $arMapData['google_lat'] = $arMapCoordinate[0];
            $arMapData['google_scale'] = '16';
        }
    }
}

$arResult['MAP'] = [];
$sPrefix = 'MAP_';

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sValue = ArrayHelper::getValue($arParams, '~'.$sKey);
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        $arResult['MAP'][$sKey] = $sValue;
    }

$arResult['MAP']['MAP_WIDTH'] = '100%';
$arResult['MAP']['MAP_HEIGHT'] = '100%';

if ($arParams['CONTACT_TYPE'] === 'IBLOCK') {
    $arResult['MAP']['MAP_DATA'] = serialize($arMapData);
}

$arResult['MAP']['INIT_MAP_TYPE'] = ArrayHelper::getValue($arParams, 'INIT_MAP_TYPE');
$arResult['WIDE'] = $arParams['WIDE'] === 'Y';

unset($sValue);
unset($sKey);
unset($sPrefix);
unset($arMapData);

if (!$arResult['BLOCK']['SHOW']) {
    $arResult['ADDRESS']['SHOW'] = false;
    $arResult['EMAIL']['SHOW'] = false;
    $arResult['PHONE']['SHOW'] = false;
} else if (
    !$arResult['ADDRESS']['SHOW'] &&
    !$arResult['EMAIL']['SHOW'] &&
    !$arResult['PHONE']['SHOW']
) {
    $arResult['BLOCK']['SHOW'] = false;
}

/*$sAddressCity = ArrayHelper::getValue($arParams, 'ADDRESS_CITY');
$sAddressCity = trim($sAddressCity);
$sAddressCity = !empty($sAddressCity) ? $sAddressCity : null;
$sAddressStreet = ArrayHelper::getValue($arParams, 'ADDRESS_STREET');
$sAddressStreet = trim($sAddressStreet);
$bAddressShow = ArrayHelper::getValue($arParams , 'ADDRESS_SHOW');
$bAddressShow = $bAddressShow == 'Y' && (!empty($sAddressCity) || !empty($sAddressStreet));

$arPhoneNumber = ArrayHelper::getValue($arParams, 'PHONE_NUMBER');
$arPhoneNumber = array_filter($arPhoneNumber);
$bPhoneShow = ArrayHelper::getValue($arParams, 'PHONE_SHOW');
$bPhoneShow = $bPhoneShow == 'Y' && !empty($arPhoneNumber);

foreach ($arPhoneNumber as $iKey => $sPhone) {
    $arPhone = [
        'PRINT' => $sPhone,
        'HREF' => StringHelper::replace($sPhone, [
            '(' => '',
            ')' => '',
            ' ' => '',
            '-' => ''
        ])
    ];

    $arPhoneNumber[$iKey] = $arPhone;
}

$arEmailAddress = ArrayHelper::getValue($arParams, 'EMAIL_ADDRESS');
$arEmailAddress = array_filter($arEmailAddress);
$bEmailAddressShow = ArrayHelper::getValue($arParams, 'EMAIL_SHOW');
$bEmailAddressShow = $bEmailAddressShow == 'Y' && !empty($arEmailAddress);

$bInfoShow = ArrayHelper::getValue($arParams, 'INFO_SHOW');
$bInfoShow = $bInfoShow == 'Y' && ($bAddressShow || $bPhoneShow || $bEmailAddressShow);
$sInfoTitle = ArrayHelper::getValue($arParams, 'INFO_TITLE');
$sInfoTitle = trim($sInfoTitle);

$sOrderCallConsent = ArrayHelper::getValue($arParams, 'ORDER_CALL_CONSENT');
$sOrderCallConsent = trim($sOrderCallConsent);
$sOrderCallConsent = StringHelper::replaceMacros($sOrderCallConsent, ['SITE_SIR' => SITE_DIR]);
$sOrderCallForm = ArrayHelper::getValue($arParams, 'ORDER_CALL_FORM');
$sOrderCallFormTemplate = ArrayHelper::getValue($arParams, 'ORDER_CALL_FORM_TEMPLATE');
$sOrderCallTitle = ArrayHelper::getValue($arParams, 'ORDER_CALL_TITLE');
$sOrderCallTitle = trim($sOrderCallTitle);
$sOrderCallText = ArrayHelper::getValue($arParams, 'ORDER_CALL_TEXT');
$sOrderCallText = trim($sOrderCallText);
$sOrderCallText = !empty($sOrderCallText) ? $sOrderCallText : Loc::getMessage('T_MGV_TEMP1_FORM_CALL_TEXT_DEFAULT');
$bOrderCallShow = ArrayHelper::getValue($arParams, 'ORDER_CALL_SHOW');
$bOrderCallShow = $bOrderCallShow == 'Y' && !empty($sOrderCallForm);

$sMapComponent = ($arParams['MAP_VENDOR'] == 'google') ? "bitrix:map.google.view" : "bitrix:map.yandex.view";

$arResult['VIEW_PARAMETERS'] = [
    'WIDTH' => ArrayHelper::getValue($arParams, 'WIDTH') == 'Y',
    'INFO_SHOW' => $bInfoShow,
    'BLOCK_INFO_POSITION' => ArrayHelper::getValue($arParams, 'BLOCK_INFO_VIEW'),
    'INFO_TITLE' => Html::encode($sInfoTitle),
    'ADDRESS_SHOW' => $bAddressShow,
    'ADDRESS_CITY' => $sAddressCity,
    'ADDRESS_STREET' => $sAddressStreet,
    'PHONE_SHOW' => $bPhoneShow,
    'PHONE_NUMBER' => $arPhoneNumber,
    'ORDER_CALL_SHOW' => $bOrderCallShow,
    'ORDER_CALL_FORM' => $sOrderCallForm,
    'ORDER_CALL_FORM_TEMPLATE' => $sOrderCallFormTemplate,
    'ORDER_CALL_CONSENT' => $sOrderCallConsent,
    'ORDER_CALL_TITLE' => Html::encode($sOrderCallTitle),
    'ORDER_CALL_TEXT' => Html::encode($sOrderCallText),
    'EMAIL_SHOW' => $bEmailAddressShow,
    'EMAIL_ADDRESS' => $arEmailAddress,
    'MAP_VENDOR' => $sMapComponent
];*/