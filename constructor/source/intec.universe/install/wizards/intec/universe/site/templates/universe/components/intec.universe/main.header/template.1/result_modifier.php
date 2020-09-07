<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplates;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\regionality\models\Region;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

if (!Loader::includeModule('iblock'))
    return;

Loc::loadMessages(__FILE__);

$arParams = ArrayHelper::merge([
    'DESKTOP' => null,
    'FIXED' => null,
    'MOBILE' => null,
    'BANNER' => null,
    'BANNER_DISPLAY' => 'main',
    'ADDRESS' => null,
    'EMAIL' => null,
    'TAGLINE' => null,
    'REGIONALITY_USE' => 'N',
    'PHONES_ADVANCED_MODE' => 'N',
    'FORMS_CALL_SHOW' => 'N',
    'FORMS_CALL_ID' => null,
    'FORMS_CALL_TEMPLATE' => null,
    'FORMS_CALL_TITLE' => null,
    'COMPARE_IBLOCK_ID' => null,
    'COMPARE_IBLOCK_TYPE' => null,
    'COMPARE_CODE' => null,
    'MENU_MAIN_ROOT' => null,
    'MENU_MAIN_CHILD' => null,
    'MENU_MAIN_LEVEL' => null,
    'MENU_POPUP_TEMPLATE' => 'main.popup.1',
    'BASKET_POPUP' => 'N',
    'SEARCH_MODE' => 'site',
    'CONTACTS_REGIONALITY_USE' => 'N',
    'CONTACTS_REGIONALITY_STRICT' => 'Y',
    'CONTACTS_IBLOCK_ID' => null,
    'CONTACTS_ELEMENT' => null,
    'CONTACTS_ELEMENTS' => null,
    'CONTACTS_ADDRESS_SHOW' => 'Y',
    'CONTACTS_SCHEDULE_SHOW' => 'Y',
    'CONTACTS_EMAIL_SHOW' => 'Y',
    'CONTACTS_PROPERTY_PHONE' => null,
    'CONTACTS_PROPERTY_ADDRESS' => null,
    'CONTACTS_PROPERTY_SCHEDULE' => null,
    'CONTACTS_PROPERTY_EMAIL' => null,
    'MOBILE_FIXED' => 'N',
    'TRANSPARENCY' => 'N',
    'SETTINGS_USE' => 'N',
    'MOBILE_HIDDEN' => 'N'
], $arParams);

$arCodes = [
    'CONTACTS' => [
        'CITY' => $arParams['CONTACTS_PROPERTY_CITY'],
        'ADDRESS' => $arParams['CONTACTS_PROPERTY_ADDRESS'],
        'EMAIL' => $arParams['CONTACTS_PROPERTY_EMAIL'],
        'PHONE' => $arParams['CONTACTS_PROPERTY_PHONE'],
        'SCHEDULE' => $arParams['CONTACTS_PROPERTY_SCHEDULE']
    ]
];

$hDisplay = function ($sKey, $fCondition = null, $sPrefix = '_SHOW') use (&$arParams) {
    $arResult = [
        'DESKTOP' => ArrayHelper::getValue($arParams, $sKey . $sPrefix) == 'Y',
        'FIXED' => ArrayHelper::getValue($arParams, $sKey . $sPrefix.'_FIXED') == 'Y',
        'MOBILE' => ArrayHelper::getValue($arParams, $sKey . $sPrefix.'_MOBILE') == 'Y'
    ];

    if ($fCondition instanceof Closure)
        foreach ($arResult as $sKey => $bValue)
            $arResult[$sKey] = $bValue && $fCondition($sKey);

    return $arResult;
};

if ($arParams['SETTINGS_USE'] == 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arResult['REGIONALITY'] = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y' && Loader::includeModule('intec.regionality')
];

$arResult['LOGOTYPE']['SHOW'] = $hDisplay(
    'LOGOTYPE',
    function () use (&$arResult) {
        return !empty($arResult['LOGOTYPE']['PATH']);
    }
);

$arResult['LOGOTYPE']['LINK'] = [
    'USE' => true,
    'VALUE' => $APPLICATION->GetCurPage(false) !== SITE_DIR ? SITE_DIR : null
];

if (empty($arResult['LOGOTYPE']['LINK']['VALUE']))
    $arResult['LOGOTYPE']['LINK']['USE'] = false;

$arResult['CONTACTS'] = [
    'SHOW' => null,
    'ADVANCED' => $arParams['PHONES_ADVANCED_MODE'] === 'Y',
    'VALUES' => []
];

if ($arResult['CONTACTS']['ADVANCED']) {
    $bShow = false;
    $arShow = [
        'CITY' => !empty($arCodes['CONTACTS']['CITY']),
        'ADDRESS' => !empty($arCodes['CONTACTS']['ADDRESS']),
        'EMAIL' => !empty($arCodes['CONTACTS']['EMAIL']),
        'PHONE' => !empty($arCodes['CONTACTS']['PHONE']),
        'SCHEDULE' => !empty($arCodes['CONTACTS']['SCHEDULE'])
    ];

    foreach ($arShow as $bShow)
        if ($bShow) break;

    if (!empty($arParams['CONTACTS_IBLOCK_ID']) && $bShow) {
        $arFilter = [
            'IBLOCK_ID' => $arParams['CONTACTS_IBLOCK_ID'],
            'ACTIVE' => 'Y',
            'ACTIVE_DATE' => 'Y'
        ];

        if (!empty($arParams['CONTACTS_ELEMENTS']) && Type::isArray($arParams['CONTACTS_ELEMENTS']))
            $arFilter['ID'] = $arParams['CONTACTS_ELEMENTS'];

        if ($arResult['REGIONALITY']['USE'] && $arParams['CONTACTS_REGIONALITY_USE'] === 'Y' && !empty($arParams['CONTACTS_PROPERTY_REGION'])) {
            $oRegion = Region::getCurrent();

            if (!empty($oRegion)) {
                $arConditions = [
                    'LOGIC' => 'OR',
                    ['PROPERTY_'.$arParams['CONTACTS_PROPERTY_REGION'] => $oRegion->id]
                ];

                if ($arParams['CONTACTS_REGIONALITY_STRICT'] !== 'Y')
                    $arConditions[] = ['PROPERTY_'.$arParams['CONTACTS_PROPERTY_REGION'] => false];

                $arFilter[] = $arConditions;
            }
        }

        $arContacts = new Arrays();
        $rsContacts = CIBlockElement::GetList([
            'SORT' => 'ASC'
        ], $arFilter);

        while ($rsContact = $rsContacts->GetNextElement()) {
            $arContact = $rsContact->GetFields();
            $arContact['PROPERTIES'] = $rsContact->GetProperties();
            $arContacts->set($arContact['ID'], $arContact);
        }

        unset($arFilter);
        unset($arContact);
        unset($rsContact);
        unset($rsContacts);

        $arContacts = $arContacts->asArray(function ($iId, $arContact) use (&$arShow, &$arCodes) {
            $bEmpty = true;
            $arItem = [
                'ID' => $arContact['ID'],
                'NAME' => $arContact['NAME']
            ];

            foreach ($arShow as $sKey => $bShow) {
                $sValue = null;

                if ($bShow) {
                    $arProperty = ArrayHelper::getValue($arContact, [
                        'PROPERTIES',
                        $arCodes['CONTACTS'][$sKey]
                    ]);

                    if (!empty($arProperty)) {
                        $sValue = $arProperty['VALUE'];

                        if (Type::isArray($sValue) && $sKey !== 'SCHEDULE')
                            $sValue = reset($sValue);

                        if (empty($sValue) && !Type::isNumeric($sValue)) {
                            $sValue = null;
                        } else {
                            if ($sKey === 'SCHEDULE' && Type::isArray($sValue) && !empty($arProperty['DESCRIPTION'])) {
                                foreach ($sValue as $iValueId => $mValue) {
                                    $sDescription = ArrayHelper::getValue($arProperty, ['DESCRIPTION', $iValueId]);

                                    if (!empty($sDescription))
                                        $sValue[$iValueId] = $sDescription.(!empty($mValue) ? ' '.$mValue : null);
                                }
                            }

                            $bEmpty = false;
                        }
                    }
                }

                $arItem[$sKey] = $sValue;
            }

            if ($bEmpty)
                return ['skip' => true];

            if (!empty($arItem['PHONE']))
                $arItem['PHONE'] = [
                    'DISPLAY' => $arItem['PHONE'],
                    'VALUE' => StringHelper::replace($arItem['PHONE'], [
                        '(' => '',
                        ')' => '',
                        ' ' => '',
                        '-' => ''
                    ])
                ];

            if (!empty($arItem['CITY']))
                $arItem['ADDRESS'] = Loc::getMessage('C_HEADER_TEMP1_CONTACTS_CITY', [
                    '#CITY#' => $arItem['CITY']
                ]).(!empty($arItem['ADDRESS']) ? ', '.$arItem['ADDRESS'] : null);

            unset($arItem['CITY']);

            return [
                'key' => $iId,
                'value' => $arItem
            ];
        });

        $arResult['CONTACTS']['VALUES'] = $arContacts;

        unset($arContacts);
    }

    unset($arShow);
    unset($bShow);
} else {
    $arResult['CONTACTS']['VALUES'] = $arResult['PHONES']['VALUES'];
}

$arResult['CONTACTS']['SELECTED'] = null;

if ($arResult['CONTACTS']['ADVANCED'] && !empty($arParams['CONTACTS_ELEMENT']))
    $arResult['CONTACTS']['SELECTED'] = ArrayHelper::getValue(
        $arResult['CONTACTS']['VALUES'],
        $arParams['CONTACTS_ELEMENT']
    );

if (empty($arResult['CONTACTS']['SELECTED'])) {
    if ($arResult['CONTACTS']['ADVANCED']) {
        $arResult['CONTACTS']['SELECTED'] = ArrayHelper::getFirstValue($arResult['CONTACTS']['VALUES']);
    } else {
        $arResult['CONTACTS']['SELECTED'] = ArrayHelper::shift($arResult['CONTACTS']['VALUES']);
    }
}

if (!empty($arResult['CONTACTS']['SELECTED'])) {
    if ($arResult['CONTACTS']['ADVANCED']) {
        if (empty($arResult['CONTACTS']['SELECTED']['PHONE']))
            $arResult['CONTACTS']['SELECTED'] = null;

        if (!empty($arResult['CONTACTS']['SELECTED']))
            unset($arResult['CONTACTS']['VALUES'][$arResult['CONTACTS']['SELECTED']['ID']]);
    }
}

$arResult['CONTACTS']['SHOW'] = $hDisplay(
    'PHONES',
    function () use (&$arResult) {
        return !empty($arResult['CONTACTS']['SELECTED']);
    }
);

unset($arResult['PHONES']);

$arResult['AUTHORIZATION'] = [
    'SHOW' => $hDisplay('AUTHORIZATION')
];

$arResult['EMAIL'] = [
    'SHOW' => null,
    'VALUE' => $arParams['EMAIL']
];

$arResult['ADDRESS'] = [
    'SHOW' => null,
    'VALUE' => $arParams['ADDRESS']
];

if ($arResult['CONTACTS']['ADVANCED']) {
    $arResult['ADDRESS']['VALUE'] = null;
    $arResult['EMAIL']['VALUE'] = null;
    $arItem = $arResult['CONTACTS']['SELECTED'];

    if (!empty($arItem)) {
        $arResult['ADDRESS']['VALUE'] = $arItem['ADDRESS'];
        $arResult['EMAIL']['VALUE'] = $arItem['EMAIL'];
    }

    unset($arItem);
}

$arResult['EMAIL']['SHOW'] = $hDisplay(
    'EMAIL',
    function () use (&$arResult) {
        return !empty($arResult['EMAIL']['VALUE']);
    }
);

$arResult['ADDRESS']['SHOW'] = $hDisplay(
    'ADDRESS',
    function () use (&$arResult) {
        return !empty($arResult['ADDRESS']['VALUE']);
    }
);

$arResult['SOCIAL'] = [
    'SHOW' => null,
    'ITEMS' => []
];

$bSocialShow = false;

foreach ([
 'VK',
 'INSTAGRAM',
 'FACEBOOK',
 'TWITTER'
] as $sSocial) {
    $sValue = ArrayHelper::getValue($arParams, 'SOCIAL_'.$sSocial);
    $arSocial = [
        'SHOW' => !empty($sValue),
        'VALUE' => $sValue
    ];

    $bSocialShow = $bSocialShow || $arSocial['SHOW'];
    $arResult['SOCIAL']['ITEMS'][$sSocial] = $arSocial;
}

$arResult['SOCIAL']['SHOW'] = $hDisplay(
    'SOCIAL',
    function () use (&$bSocialShow) {
        return $bSocialShow;
    }
);

$arResult['TAGLINE'] = [
    'SHOW' => $hDisplay('TAGLINE'),
    'VALUE' => $arParams['TAGLINE']
];

$arResult['TAGLINE']['SHOW'] = $hDisplay(
    'TAGLINE',
    function () use (&$arResult) {
        return !empty($arResult['TAGLINE']['VALUE']);
    }
);

$arResult['SEARCH'] = [
    'SHOW' => $hDisplay('SEARCH'),
    'MODE' => ArrayHelper::fromRange([
        'site',
        'catalog'
    ], $arParams['SEARCH_MODE'])
];

$arResult['BASKET'] = [
    'SHOW' => $hDisplay('BASKET'),
    'POPUP' => $arParams['BASKET_POPUP'] === 'Y'
];

$arResult['DELAY'] = array(
    'SHOW' => $hDisplay('DELAY')
);

$arResult['COMPARE'] = [
    'SHOW' => $hDisplay('COMPARE'),
    'IBLOCK' => [
        'ID' => $arParams['COMPARE_IBLOCK_ID'],
        'TYPE' => $arParams['COMPARE_IBLOCK_TYPE']
    ],
    'CODE' => $arParams['COMPARE_CODE']
];

$arResult['FORMS'] = [];
$arResult['FORMS']['CALL'] = [
    'SHOW' => $arParams['FORMS_CALL_SHOW'] === 'Y',
    'ID' => $arParams['FORMS_CALL_ID'],
    'TEMPLATE' => $arParams['FORMS_CALL_TEMPLATE'],
    'TITLE' => $arParams['FORMS_CALL_TITLE']
];

if ($arResult['FORMS']['CALL']['SHOW'] && empty($arResult['FORMS']['CALL']['ID']))
    $arResult['FORMS']['CALL']['SHOW'] = false;

$arResult['FORMS']['FEEDBACK'] = [
    'SHOW' => $arParams['FORMS_FEEDBACK_SHOW'] === 'Y',
    'ID' => $arParams['FORMS_FEEDBACK_ID'],
    'TEMPLATE' => $arParams['FORMS_FEEDBACK_TEMPLATE'],
    'TITLE' => $arParams['FORMS_FEEDBACK_TITLE']
];

if ($arResult['FORMS']['FEEDBACK']['SHOW'] && empty($arResult['FORMS']['FEEDBACK']['ID']))
    $arResult['FORMS']['FEEDBACK']['SHOW'] = false;

if ($arResult['REGIONALITY']['USE'])
    $arResult['ADDRESS']['SHOW']['DESKTOP'] = false;

$arResult['MENU'] = [];
$arResult['MENU']['MAIN'] = [
    'SHOW' => $hDisplay('MENU_MAIN'),
    'ROOT' => $arParams['MENU_MAIN_ROOT'],
    'CHILD' => $arParams['MENU_MAIN_CHILD'],
    'LEVEL' => $arParams['MENU_MAIN_LEVEL']
];

$arResult['MENU']['POPUP'] = [
    'TEMPLATE' => ArrayHelper::fromRange([
        'main.popup.1',
        'main.popup.2',
        'main.popup.3',
    ], $arParams['MENU_POPUP_TEMPLATE']).'.php'
];

if ($arParams['BANNER_DISPLAY'] === 'main')
    if ($APPLICATION->GetCurPage(false) !== SITE_DIR)
        $arParams['BANNER'] = null;

$arTemplates = [];
$arTemplates['DESKTOP'] = InnerTemplates::findOne($this, 'templates/desktop', $arParams['DESKTOP']);
$arTemplates['FIXED'] = InnerTemplates::findOne($this, 'templates/fixed', $arParams['FIXED']);
$arTemplates['MOBILE'] = InnerTemplates::findOne($this, 'templates/mobile', $arParams['MOBILE']);
$arTemplates['BANNER'] = InnerTemplates::findOne($this, 'templates/banners', $arParams['BANNER']);

$arResult['MOBILE'] = [
    'FIXED' => $arParams['MOBILE_FIXED'] === 'Y',
    'HIDDEN' => $arParams['MOBILE_HIDDEN'] === 'Y'
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

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros(
        $sUrl,
        $arMacros
    );

$arVisual = [
    'TRANSPARENCY' => $arParams['TRANSPARENCY'] === 'Y'
];

if (empty($arTemplates['BANNER']))
    $arVisual['TRANSPARENCY'] = false;

$arResult['TEMPLATES'] = $arTemplates;
$arResult['VISUAL'] = $arVisual;

/** @var InnerTemplate $oTemplate */
foreach ($arTemplates as $oTemplate) {
    if (empty($oTemplate))
        continue;

    $oTemplate->modify($arParams, $arResult);
}