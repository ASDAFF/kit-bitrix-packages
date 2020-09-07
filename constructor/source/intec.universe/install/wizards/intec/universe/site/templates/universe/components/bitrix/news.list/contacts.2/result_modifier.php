<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\RegExp;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'MAP_VENDOR' => 'yandex',
    'MAIN' => null,
    'PROPERTY_ADDRESS' => null,
    'PROPERTY_SCHEDULE' => null,
    'PROPERTY_PHONE' => null,
    'PROPERTY_MAP_LOCATION' => null,
    'PROPERTY_LIST_NAME' => null,
    'PROPERTY_MAP_NAME' => null,
    'PROPERTY_CITY' => null,
    'PROPERTY_INDEX' => null,
    'PROPERTY_EMAIL' => null,
    'ADDRESS_SHOW' => 'N',
    'SCHEDULE_SHOW' => 'N',
    'PHONE_SHOW' => 'N',
    'EMAIL_SHOW' => 'N',
    'PROPERTY_STORE_ID' => null,
    'WEB_FORM_TEMPLATE' => '.default',
    'WEB_FORM_ID' => null,
    'WEB_FORM_TITLE' => null,
    'WEB_FORM_CONSENT_URL' => null,
    'FEEDBACK_BUTTON_TEXT' => null,
    'FEEDBACK_BUTTON_SHOW' => 'Y',
    'FEEDBACK_TEXT' => null,
    '~FEEDBACK_TEXT' => null,
    'FEEDBACK_TEXT_SHOW' => 'Y',
    'FEEDBACK_IMAGE' => null,
    'MARKUP_COMPANY' => null,
    'FEEDBACK_IMAGE_SHOW' => 'Y'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

if ($arParams['SETTINGS_USE'] == 'Y')
    include(__DIR__.'/parts/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'ADDRESS' => [
        'SHOW' => $arParams['ADDRESS_SHOW'] === 'Y'
    ],
    'SCHEDULE' => [
        'SHOW' => $arParams['SCHEDULE_SHOW'] === 'Y'
    ],
    'PHONE' => [
        'SHOW' => $arParams['PHONE_SHOW'] === 'Y'
    ],
    'EMAIL' => [
        'SHOW' => $arParams['EMAIL_SHOW'] === 'Y'
    ],
    'FEEDBACK' => [
        'SHOW' => $arParams['SHOW_FORM'] === 'Y',
        'TEMPLATE' => $arParams['WEB_FORM_TEMPLATE'],
        'IMAGE' => [
            'VALUE' => StringHelper::replaceMacros($arParams['FEEDBACK_IMAGE'], $arMacros),
            'SHOW' => $arParams['FEEDBACK_IMAGE_SHOW'] === 'Y'
        ],
        'TEXT' => [
            'VALUE' => $arParams['~FEEDBACK_TEXT'],
            'SHOW' => $arParams['FEEDBACK_TEXT_SHOW'] === 'Y'
        ],
        'BUTTON' => [
            'VALUE' => $arParams['FEEDBACK_BUTTON_TEXT'],
            'SHOW' => $arParams['FEEDBACK_BUTTON_SHOW'] === 'Y'
        ]
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arResult['FORM'] = [
    'ID' => $arParams['WEB_FORM_ID'],
    'CONSENT' => $arParams['WEB_FORM_CONSENT'],
    'NAME' => $arParams['WEB_FORM_NAME']
];

$mapId = $arParams['MAP_ID'];
$mapIdLength = StringHelper::length($mapId);
$mapIdExpression = new RegExp('^[A-Za-z_][A-Za-z01-9_]*$');

if ($mapIdLength <= 0 || $mapIdExpression->isMatch($mapId))
    $arParams['MAP_ID'] = 'MAP_'.RandString();


$hSetProperty = function (&$arItem, $property = null, $default = null) {
    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property
        ]);

        if (!empty($arProperty['VALUE']))
            return $arProperty['VALUE'];
        else if (!empty($default))
            return $default;
    }

    return null;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'ADDRESS' => $hSetProperty($arItem, $arParams['PROPERTY_ADDRESS']),
        'SCHEDULE' => $hSetProperty($arItem, $arParams['PROPERTY_SCHEDULE']),
        'PHONE' => $hSetProperty($arItem, $arParams['PROPERTY_PHONE']),
        'CITY' => $hSetProperty($arItem, $arParams['PROPERTY_CITY']),
        'EMAIL' => $hSetProperty($arItem, $arParams['PROPERTY_EMAIL']),
        'INDEX' => $hSetProperty($arItem, $arParams['PROPERTY_INDEX']),
        'LIST' => [
            'NAME' => $hSetProperty($arItem, $arParams['PROPERTY_LIST_NAME'], $arItem['NAME'])
        ],
        'MAP' => [
            'LOCATION' => $hSetProperty($arItem, $arParams['PROPERTY_MAP_LOCATION']),
            'NAME' => $hSetProperty($arItem, $arParams['PROPERTY_MAP_NAME'], $arItem['NAME'])
        ]
    ];

    $iStoreId = $arItem['ID'];
    $arStoreProperty = $hSetProperty($arItem, $arParams['PROPERTY_STORE_ID']);

    if (!empty($arStoreProperty['VALUE']))
        $iStoreId = $arStoreProperty['VALUE'];

    $arMacros['ELEMENT_ID'] = $iStoreId;
    $arMacros['ID'] = $iStoreId;

    $sStoreLink = StringHelper::replaceMacros($arParams['DETAIL_URL'], $arMacros);

    $arItem['DATA']['STORE'] = [
        'ID' => $iStoreId,
        'LINK' => $sStoreLink
    ];

    unset($iStoreId, $sStoreLink);
}

unset($arItem, $arMacros);

if (!empty($arParams['MAIN']))
    foreach ($arResult['ITEMS'] as $contact)
        if ($contact['ID'] == $arParams['MAIN']) {
            $arResult['MAIN'] = $contact;
            break;
        }
