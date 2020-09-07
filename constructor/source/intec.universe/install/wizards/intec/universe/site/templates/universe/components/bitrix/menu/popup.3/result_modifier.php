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

if (!Loader::includeModule('iblock'))
    return;

$arParams = ArrayHelper::merge([
    'THEME' => 'light',
    'MODE' => 'simple',
    'BACKGROUND' => 'none',
    'BACKGROUND_COLOR' => null,
    'BACKGROUND_PICTURE' => null,
    'LOGOTYPE_SHOW' => 'N',
    'LOGOTYPE' => null,
    'LOGOTYPE_LINK' => null,
    'CONTACTS_SHOW' => 'N',
    'CONTACTS_CITY' => null,
    'CONTACTS_ADDRESS' => null,
    'CONTACTS_SCHEDULE' => null,
    'CONTACTS_PHONE' => null,
    'CONTACTS_EMAIL' => null,
    'FORMS_CALL_SHOW' => 'N',
    'FORMS_CALL_ID' => null,
    'FORMS_CALL_TEMPLATE' => null,
    'FORMS_CALL_TITLE' => null,
    'FORMS_FEEDBACK_SHOW' => 'N',
    'FORMS_FEEDBACK_ID' => null,
    'FORMS_FEEDBACK_TEMPLATE' => null,
    'FORMS_FEEDBACK_TITLE' => null,
    'CONSENT_URL' => null,
    'SOCIAL_SHOW' => 'N',
    'SOCIAL_VK_LINK' => null,
    'SOCIAL_INSTAGRAM_LINK' => null,
    'SOCIAL_FACEBOOK_LINK' => null,
    'SOCIAL_TWITTER_LINK' => null,
    'CATALOG_LINKS' => null,
    'LOGIN_URL' => null,
    'PROFILE_URL' => null,
    'PASSWORD_URL' => null,
    'REGISTER_URL' => null,
    'SEARCH_URL' => null,
    'CATALOG_URL' => null,
    'BASKET_URL' => null,
    'COMPARE_URL' => null,
    'ORDER_URL' => null,
    'BASKET_SHOW' => 'N',
    'DELAY_SHOW' => 'N',
    'COMPARE_SHOW' => 'N',
    'COMPARE_IBLOCK_ID' => null,
    'COMPARE_IBLOCK_TYPE' => null,
    'COMPARE_CODE' => null,
    'AUTHORIZATION_SHOW' => 'N'
], $arParams);

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

if (ArrayHelper::fromRange(['simple', 'extended'], $arParams['MODE']) === 'extended') {
    if (!empty($arParams['CATALOG_LINKS']) && Type::isArrayable($arParams['CATALOG_LINKS'])) {
        foreach ($arParams['CATALOG_LINKS'] as $sKey => $sCatalogLink)
            $arParams['CATALOG_LINKS'][$sKey] = StringHelper::replaceMacros($sCatalogLink, $arMacros);

        foreach ($arResult as $sKey => $arItem)
            if (ArrayHelper::isIn(
                $arItem['LINK'],
                $arParams['CATALOG_LINKS']
            )) $arResult[$sKey]['IS_CATALOG'] = 'Y';
    }
}

$arResult = $fBuild($arResult);

$arMenu['MENU']['CATALOGS'] = [];
$arMenu['MENU']['OTHER'] = [];

foreach ($arResult as $arItem) {
    if ($arItem['IS_CATALOG'] === 'Y') {
        $arMenu['MENU']['CATALOGS'][] = $arItem;
    } else {
        $arMenu['MENU']['OTHER'][] = $arItem;
    }
}

$arResult = $arMenu;

if (!empty($arParams['CONTACTS_PHONE']))
    $arParams['CONTACTS_PHONE'] = [
        'DISPLAY' => $arParams['CONTACTS_PHONE'],
        'VALUE' => StringHelper::replace($arParams['CONTACTS_PHONE'], [
            '(' => '',
            ')' => '',
            ' ' => '',
            '-' => ''
        ])
    ];

$arResult['VISUAL'] = [
    'THEME' => $arParams['THEME'],
    'BACKGROUND' => [
        'TYPE' => $arParams['BACKGROUND'],
        'COLOR' => $arParams['BACKGROUND_COLOR'],
        'URL' => $arParams['BACKGROUND_PICTURE']
    ],
    'AUTHORIZATION' => [
        'SHOW' => $arParams['AUTHORIZATION_SHOW'] === 'Y'
    ],
    'BASKET' => [
        'SHOW' => $arParams['BASKET_SHOW'] === 'Y'
    ],
    'DELAY' => [
        'SHOW' => $arParams['DELAY_SHOW'] === 'Y'
    ],
    'COMPARE' => [
        'SHOW' => $arParams['COMPARE_SHOW'] === 'Y'
    ],
    'SEARCH' => [
        'SHOW' => $arParams['SEARCH_SHOW'] === 'Y'
    ]
];

$arResult['CONTACTS'] = [
    'SHOW' => $arParams['CONTACTS_SHOW'] == 'Y',
    'CITY' => $arParams['CONTACTS_CITY'],
    'ADDRESS' => $arParams['CONTACTS_ADDRESS'],
    'SCHEDULE' => $arParams['CONTACTS_SCHEDULE'],
    'PHONE' => $arParams['CONTACTS_PHONE'],
    'EMAIL' => $arParams['CONTACTS_EMAIL']
];

$arResult['FORMS'] = [
    'CALL' => [
        'SHOW' => $arParams['FORMS_CALL_SHOW'] === 'Y',
        'ID' => $arParams['FORMS_CALL_ID'],
        'TEMPLATE' => $arParams['FORMS_CALL_TEMPLATE'],
        'TITLE' => $arParams['FORMS_CALL_TITLE']
    ],
    'FEEDBACK' => [
        'SHOW' => $arParams['FORMS_FEEDBACK_SHOW'] === 'Y',
        'ID' => $arParams['FORMS_FEEDBACK_ID'],
        'TEMPLATE' => $arParams['FORMS_FEEDBACK_TEMPLATE'],
        'TITLE' => $arParams['FORMS_FEEDBACK_TITLE']
    ],
    'CONSENT_URL' => $arParams['CONSENT_URL']
];

$arResult['SOCIAL'] = [
    'SHOW' => $arParams['SOCIAL_SHOW'] == 'Y',
    'ITEMS' => [
        'VK' => [
            'LINK' => $arParams['SOCIAL_VK_LINK'],
            'ICON' => 'fab fa-vk',
        ],
        'INSTAGRAM' => [
            'LINK' => $arParams['SOCIAL_INSTAGRAM_LINK'],
            'ICON' => 'fab fa-instagram',
        ],
        'FACEBOOK' => [
            'LINK' => $arParams['SOCIAL_FACEBOOK_LINK'],
            'ICON' => 'fab fa-facebook-f',
        ],
        'TWITTER' => [
            'LINK' => $arParams['SOCIAL_TWITTER_LINK'],
            'ICON' => 'fab fa-twitter',
        ],
    ]
];

$arResult['URL'] = [
    'LOGIN' => ArrayHelper::getValue($arParams, 'LOGIN_URL'),
    'PROFILE' => ArrayHelper::getValue($arParams, 'PROFILE_URL'),
    'PASSWORD' => ArrayHelper::getValue($arParams, 'PASSWORD_URL'),
    'REGISTER' => ArrayHelper::getValue($arParams, 'REGISTER_URL'),
    'SEARCH' => ArrayHelper::getValue($arParams, 'SEARCH_URL'),
    'CATALOG' => ArrayHelper::getValue($arParams, 'CATALOG_URL'),
    'BASKET' => ArrayHelper::getValue($arParams, 'BASKET_URL'),
    'COMPARE' => ArrayHelper::getValue($arParams, 'COMPARE_URL'),
    'CONSENT' => ArrayHelper::getValue($arParams, 'CONSENT_URL'),
    'ORDER' => ArrayHelper::getValue($arParams, 'ORDER_URL')
];