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
    'MAP_VENDOR' => 'yandex',
    'MAIN' => null,
    'PROPERTY_ADDRESS' => null,
    'PROPERTY_PHONE' => null,
    'PROPERTY_MAP' => null,
    'PROPERTY_NAME_MAP' => null,
    'PROPERTY_CITY' => null,
    'ADDRESS_SHOW' => 'N',
    'PHONE_SHOW' => 'N',
    'WEB_FORM_TEMPLATE' => '.default',
    'WEB_FORM_ID' => null,
    'WEB_FORM_TITLE' => null,
    'WEB_FORM_CONSENT_URL' => null,
    'FEEDBACK_BUTTON_TEXT' => null,
    'FEEDBACK_BUTTON_SHOW' => 'N',
    'FEEDBACK_TEXT' => null,
    '~FEEDBACK_TEXT' => null,
    'FEEDBACK_TEXT_SHOW' => 'N',
    'FEEDBACK_IMAGE' => null,
    'FEEDBACK_IMAGE_SHOW' => 'N',
    'LAZYLOAD_USE' => 'N',
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'ADDRESS' => [
        'SHOW' => $arParams['ADDRESS_SHOW'] === 'Y'
    ],
    'PHONE' => [
        'SHOW' => $arParams['PHONE_SHOW'] === 'Y'
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

if (!empty($arParams['MAIN']))
    foreach ($arResult['ITEMS'] as $contact)
        if ($contact['ID'] == $arParams['MAIN']) {
            $arResult['MAIN'] = $contact;
            break;
        }


$mapId = $arParams['MAP_ID'];
$mapIdLength = StringHelper::length($mapId);
$mapIdExpression = new RegExp('^[A-Za-z_][A-Za-z01-9_]*$');

if ($mapIdLength <= 0 || $mapIdExpression->isMatch($mapId))
    $arParams['MAP_ID'] = 'MAP_'.RandString();


$hSetProperty = function (&$arItem, $property = null, $setName = false) {
    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property
        ]);

        if (!empty($arProperty['VALUE']))
            return [
                'SHOW' => true,
                'VALUE' => $arProperty['VALUE']
            ];
        else if ($setName)
            return [
                'SHOW' => true,
                'VALUE' => $arItem['NAME']
            ];
    }

    return ['SHOW' => false];
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'ADDRESS' => $hSetProperty($arItem, $arParams['PROPERTY_ADDRESS']),
        'PHONE' => $hSetProperty($arItem, $arParams['PROPERTY_PHONE']),
        'MAP' => $hSetProperty($arItem, $arParams['PROPERTY_MAP']),
        'CITY' => $hSetProperty($arItem, $arParams['PROPERTY_CITY']),
        'EMAIL' => $hSetProperty($arItem, $arParams['PROPERTY_EMAIL']),
        'NAME' => [
            'MAP' => $hSetProperty($arItem, $arParams['PROPERTY_NAME_MAP'], true)
        ]
    ];
}

unset($arItem, $arMacros);
