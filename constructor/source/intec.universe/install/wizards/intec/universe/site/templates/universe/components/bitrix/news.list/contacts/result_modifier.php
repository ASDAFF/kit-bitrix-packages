<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;
use intec\regionality\models\Region;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'REGIONALITY_USE' => null,
    'REGIONALITY_PROPERTY' => null,
    'DETAIL_URL' => null,
    'IBLOCK_TYPE' => null,
    'IBLOCK_ID' => null,
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'SHOW_LIST' => null,
    'SHOW_MAP' => null,
    'MAP_ID' => null,
    'MAP_VENDOR' => 'google',
    'TITLE_SHOW' => 'N',
    'TITLE_TEXT' => null,
    'DESCRIPTION_SHOW' => 'N',
    'DESCRIPTION_TEXT' => null
], $arParams);

if ($arParams['SETTINGS_USE'] == 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'VIEW' => ArrayHelper::fromRange([
        'NONE',
        'SHOPS',
        'OFFICES'
    ], $arParams['SHOW_LIST'])
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['REGIONALITY'] = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y',
    'PROPERTY' => $arParams['REGIONALITY_PROPERTY']
];

if (!Loader::includeModule('intec.regionality') || empty($arResult['REGIONALITY']['PROPERTY']))
    $arResult['REGIONALITY']['USE'] = false;

$arResult['MAP'] = [
    'SHOW' => $arParams['SHOW_MAP'] === 'Y',
    'ID' => $arParams['MAP_ID'],
    'VENDOR' => ArrayHelper::fromRange([
        'google',
        'yandex'
    ], $arParams['MAP_VENDOR'])
];

if (
    StringHelper::length($arResult['MAP']['ID']) <= 0 ||
    !RegExp::isMatchBy('/^[A-Za-z_][A-Za-z0-9_]*$/', $arResult['MAP']['ID'])
) $arResult['MAP']['ID'] = 'MAP_'.RandString();

$arResult['TITLE'] = [
    'SHOW' => $arParams['TITLE_SHOW'] === 'Y',
    'TEXT' => !empty($arParams['TITLE_TEXT']) ?
        $arParams['TITLE'] :
        Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_OFFICES')
];

$arResult['DESCRIPTION'] = [
    'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
    'TEXT' => !empty($arParams['DESCRIPTION_TEXT']) ?
        $arParams['DESCRIPTION_TEXT'] :
        Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_OFFICES_DESCRIPTION')
];

$arResult['SECTIONS'] = [];

if (!empty($arParams['IBLOCK_ID'])) {
    $arResult['SECTIONS'] = Arrays::fromDBResult(CIBlockSection::GetList([
        'SORT' => 'ASC'
    ], [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'SECTION_ID' => false
    ]))->indexBy('ID')->asArray();

    foreach ($arResult['SECTIONS'] as &$arSection)
        $arSection['ITEMS'] = [];

    unset($arSection);
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'MAP' => null,
        'CITY' => null,
        'ADDRESS' => null,
        'PHONE' => null,
        'EMAIL' => null,
        'WORK_TIME' => null,
        'OPENING_HOURS' => null,
        'STORE_ID' => null
    ];

    foreach ($arItem['DATA'] as $sKey => $mValue) {
        $sProperty = ArrayHelper::getValue($arParams, 'PROPERTY_'.$sKey);

        if (!empty($sProperty)) {
            $arProperty = ArrayHelper::getValue($arItem, [
                'PROPERTIES',
                $sProperty
            ]);

            if (!empty($arProperty)) {
                if ($sKey === 'WORK_TIME') {
                    $mValue = [];

                    if (Type::isArray($arProperty['VALUE'])) {
                        foreach ($arProperty['VALUE'] as $iIndex => $sTime) {
                            if (empty($sTime) && !Type::isNumeric($sTime))
                                continue;

                            $sRange = ArrayHelper::getValue($arProperty, [
                                'DESCRIPTION',
                                $iIndex
                            ]);

                            if (empty($sRange) && !Type::isNumeric($sRange))
                                $sRange = null;

                            $mValue[] = [
                                'TIME' => $sTime,
                                'RANGE' => $sRange
                            ];
                        }

                        unset($iIndex, $sTime, $sRange);
                    }
                } else {
                    $mValue = $arProperty['VALUE'];

                    if (Type::isArray($mValue))
                        $mValue = reset($mValue);

                    if (empty($mValue)) {
                        $mValue = null;
                    } else if ($sKey === 'MAP') {
                        $mValue = explode(',', $mValue);

                        if (count($mValue) === 2) {
                            $mValue = [
                                'LATITUDE' => $mValue[0],
                                'LONGITUDE' => $mValue[1]
                            ];
                        } else {
                            $mValue = null;
                        }
                    }
                }
            }
        }

        $arItem['DATA'][$sKey] = $mValue;
    }

    if (!empty($arItem['DATA']['PHONE']) || Type::isNumeric($arItem['DATA']['PHONE'])) {
        $arItem['DATA']['PHONE'] = [
            'DISPLAY' => $arItem['DATA']['PHONE'],
            'VALUE' => StringHelper::replace($arItem['DATA']['PHONE'], [
                '-' => '',
                ' ' => '',
                '(' => '',
                ')' => ''
            ])
        ];
    } else {
        $arItem['DATA']['PHONE'] = null;
    }

    if (!empty($arItem['IBLOCK_SECTION_ID']) && ArrayHelper::keyExists($arItem['IBLOCK_SECTION_ID'], $arResult['SECTIONS'])) {
        $arSection = &$arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']];
        $arSection['ITEMS'][] = &$arItem;
    }
}

unset($arItem, $arSection);

$arResult['CONTACT'] = $arParams['CONTACT_ID'];

if (!empty($arResult['CONTACT'])) {
    $bReplaced = false;

    foreach ($arResult['ITEMS'] as &$arItem)
        if ($arItem['ID'] == $arResult['CONTACT']) {
            $arResult['CONTACT'] = &$arItem;
            $bReplaced = true;
            break;
        }

    if (!$bReplaced)
        $arResult['CONTACT'] = null;

    unset($arItem, $bReplaced);
}

if (empty($arResult['CONTACT']))
    if ($arResult['REGIONALITY']['USE']) {
        $oRegion = Region::getCurrent();

        if (!empty($oRegion)) {
            $bReplace = false;

            foreach ($arResult['ITEMS'] as $arItem) {
                $mRegions = ArrayHelper::getValue($arItem, [
                    'PROPERTIES',
                    $arResult['REGIONALITY']['PROPERTY'],
                    'VALUE'
                ]);

                if (empty($mRegions))
                    continue;

                if (!Type::isArray($mRegions)) {
                    $bReplace = $mRegions == $oRegion->id;
                } else {
                    $bReplace = ArrayHelper::isIn($oRegion->id, $mRegions);
                }

                if ($bReplace) {
                    $arResult['CONTACT'] = $arItem;
                    break;
                }
            }

            unset($bReplace);
        }
    } else {
        $arResult['CONTACT'] = reset($arResult['ITEMS']);
    }

if (empty($arResult['CONTACT']))
    $arResult['CONTACT'] = null;

$arResult['URL'] = [
    'CONSENT' => $arParams['WEB_FORM_CONSENT_URL']
];

foreach ($arResult['URL'] as $sKey => $sUrl) {
    if (!empty($sUrl))
        $sUrl = StringHelper::replaceMacros($sUrl, [
            'SITE_DIR' => SITE_DIR
        ]);

    $arResult['URL'][$sKey] = $sUrl;
}

unset($sKey, $sUrl);

$arResult['VISUAL'] = $arVisual;

unset($arVisual);