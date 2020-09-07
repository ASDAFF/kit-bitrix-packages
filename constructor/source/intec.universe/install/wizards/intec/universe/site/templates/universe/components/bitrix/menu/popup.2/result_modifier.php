<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * CBitrixComponentTemplate $this
 */

/**
 * @param array $arResult
 * @return array
 */

$arParams = ArrayHelper::merge([
    'LOGOTYPE_SHOW' => 'Y',
    'LOGOTYPE' => null,
    'CONTACTS_ADVANCED' => 'N',
    'CONTACTS_PHONE_SHOW' => 'N',
    'CONTACTS_EMAIL_SHOW' => 'N',
    'CONTACTS_ADDRESS_SHOW' => 'N',
    'CONTACTS_SCHEDULE_SHOW' => 'N',
    'CONTACTS_ADDRESS' => null,
    'CONTACTS_SCHEDULE' => null,
    'CONTACTS_PHONE' => null,
    'CONTACTS_EMAIL' => null,
    'FORMS_CALL_SHOW' => 'N',
    'FORMS_CALL_ID' => null,
    'FORMS_CALL_TEMPLATE' => null,
    'FORMS_CALL_TITLE' => null,
    'CONSENT_URL' => null,
    'SEARCH_SHOW' => 'N',
    'SEARCH_MODE' => 'site'
],
$arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arVisual = [
    'LOGOTYPE' => [
        'SHOW' => $arParams['LOGOTYPE_SHOW'] === 'Y'
    ],
    'PHONE' => [
        'SHOW' => $arParams['CONTACTS_PHONE_SHOW'] === 'Y'
    ],
    'EMAIL' => [
        'SHOW' => $arParams['CONTACTS_EMAIL_SHOW'] === 'Y'
    ],
    'ADDRESS' => [
        'SHOW' => $arParams['CONTACTS_ADDRESS_SHOW'] === 'Y'
    ],
    'SCHEDULE' => [
        'SHOW' => $arParams['CONTACTS_SCHEDULE_SHOW'] === 'Y'
    ],
];

$sPageUrl = $APPLICATION->GetCurPage();

foreach ($arResult as &$arItem) {
    $arItem['ACTIVE'] = false;

    if ($arItem['LINK'] == $sPageUrl)
        $arItem['ACTIVE'] = true;

    unset($arItem);
}

$fBuild = function ($arResult) {
    $bFirst = true;

    if (empty($arResult))
        return [];

    $fBuild = function () use (&$fBuild, &$bFirst, &$arResult) {
        $iLevel = null;
        $arItems = array();
        $arItem = null;

        if ($bFirst) {
            $arItem = reset($arResult);
            $bFirst = false;
        }

        while (true) {
            if ($arItem === null) {
                $arItem = next($arResult);

                if (empty($arItem))
                    break;
            }

            if ($iLevel === null)
                $iLevel = $arItem['DEPTH_LEVEL'];

            if ($arItem['DEPTH_LEVEL'] < $iLevel) {
                prev($arResult);
                break;
            }

            if ($arItem['IS_PARENT'] === true)
                $arItem['ITEMS'] = $fBuild();

            $arItems[] = $arItem;
            $arItem = null;
        }

        return $arItems;
    };

    return $fBuild();
};

$arResult['MENU']['ITEMS'] = $fBuild($arResult);

$arContacts = [];
$arPhones = [];
$arAddress = [];
$arEmail = [];
$arSchedule = [];

if (!Type::isArray($arParams['CONTACTS_PHONE'])) {
    $arPhones[] = $arParams['CONTACTS_PHONE'];
} else {
    $arPhones = $arParams['CONTACTS_PHONE'];
}

if (!Type::isArray($arParams['CONTACTS_ADDRESS'])) {
    $arAddress[] = $arParams['CONTACTS_ADDRESS'];
} else {
    $arAddress = $arParams['CONTACTS_ADDRESS'];
}

if (!Type::isArray($arParams['CONTACTS_EMAIL'])) {
    $arEmail[] = $arParams['CONTACTS_EMAIL'];
} else {
    $arEmail = $arParams['CONTACTS_EMAIL'];
}

if (!Type::isArray($arParams['CONTACTS_SCHEDULE'])) {
    $arSchedule[] = $arParams['CONTACTS_SCHEDULE'];
} else {
    $arSchedule = $arParams['CONTACTS_SCHEDULE'];
}

if ($arParams['CONTACTS_ADVANCED'] === 'Y') {
    foreach($arPhones as $iKey => $arPhone) {
        $arContacts[$iKey]['ID'] = $iKey;
        $arContacts[$iKey]['PHONE'] = [
            'DISPLAY' => $arPhone,
            'VALUE' => StringHelper::replace($arPhone, [
                '(' => '',
                ')' => '',
                ' ' => '',
                '-' => ''
            ])
        ];
        $arContacts[$iKey]['ADDRESS'] = $arAddress[$iKey];
        $arContacts[$iKey]['EMAIL'] = $arEmail[$iKey];
        $arContacts[$iKey]['SCHEDULE'] = $arSchedule[$iKey];
    }
} else {
    foreach($arPhones as $iKey => $arPhone) {
        $arContacts[$iKey]['PHONE'] = [
            'DISPLAY' => $arPhone,
            'VALUE' => StringHelper::replace($arPhone, [
                '(' => '',
                ')' => '',
                ' ' => '',
                '-' => ''
            ])
        ];
        $arContacts[$iKey]['EMAIL'] = $arEmail[$iKey];
    }
}

$arContact = array_shift($arContacts);

$arFormsCall = [
    'SHOW' => $arParams['FORM_SHOW'] === 'Y',
    'ID' => $arParams['WEB_FORM_ID'],
    'TEMPLATE' => $arParams['WEB_FORM_TEMPLATE'],
    'TITLE' => $arParams['WEB_FORM_TITLE']
];

if ($arFormsCall['SHOW'] && empty($arFormsCall['ID']))
    $arFormsCall['SHOW'] = false;

$arSearch = [
    'SHOW' => $arParams['SEARCH_SHOW'] === 'Y',
    'MODE' => ArrayHelper::fromRange([
        'site',
        'catalog'
    ], $arSearch['MODE'])
];

$arUrl = [
    'SEARCH' => ArrayHelper::getValue($arParams, 'SEARCH_URL'),
    'CATALOG' => ArrayHelper::getValue($arParams, 'CATALOG_URL')
];

foreach ($arUrl as $sKey => $sUrl)
    $arUrl[$sKey] = StringHelper::replaceMacros(
        $sUrl,
        $arMacros
    );


$arResult['URL'] = $arUrl;
$arResult['PHONES'] = $arPhones;
$arResult['EMAIL'] = $arEmail;
$arResult['ADDRESS'] = $arAddress;
$arResult['FORMS'] = [];
$arResult['FORMS']['CALL'] = $arFormsCall;
$arResult['SEARCH'] = $arSearch;
$arResult['CONTACT'] = $arContact;
$arResult['CONTACTS'] = $arContacts;
$arResult['VISUAL'] = $arVisual;