<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
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

$arParams = ArrayHelper::merge([
    'CONTACTS_USE' => 'N',
    'CONTACTS_IBLOCK_TYPE' => null,
    'CONTACTS_IBLOCK_ID' => null,
    'CONTACTS_ELEMENTS' => null,
    'CONTACTS_ELEMENT' => null,
    'CONTACTS_REGIONALITY_USE' => 'N',
    'CONTACTS_REGIONALITY_STRICT' => 'N',
    'CONTACTS_PROPERTY_CITY' => null,
    'CONTACTS_PROPERTY_ADDRESS' => null,
    'CONTACTS_PROPERTY_PHONE' => null,
    'CONTACTS_PROPERTY_EMAIL' => null,
    'CONTACTS_PROPERTY_REGION' => null,
    'ADDRESS_SHOW' => 'N',
    'ADDRESS_VALUE' => null,
    'EMAIL_SHOW' => 'N',
    'EMAIL_VALUE' => null
], $arParams);

$arResult['CONTACTS'] = [
    'USE' => $arParams['CONTACTS_USE'] === 'Y',
    'REGIONALITY' => [
        'USE' => $arParams['CONTACTS_REGIONALITY_USE'] === 'Y',
        'STRICT' => $arParams['CONTACTS_REGIONALITY_STRICT'] === 'Y',
        'PROPERTY' => $arParams['CONTACTS_PROPERTY_REGION']
    ],
    'ITEMS' => [],
    'ITEM' => null
];

if (empty($arParams['CONTACTS_IBLOCK_ID']))
    $arResult['CONTACTS']['USE'] = false;

if (!$arResult['REGIONALITY']['USE'] || empty($arResult['CONTACTS']['REGIONALITY']['PROPERTY']))
    $arResult['CONTACTS']['REGIONALITY']['USE'] = false;

if ($arResult['CONTACTS']['USE']) {
    $arFilter = [
        'IBLOCK_ID' => $arParams['CONTACTS_IBLOCK_ID'],
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y'
    ];

    if (!empty($arParams['CONTACTS_ELEMENTS']) && Type::isArray($arParams['CONTACTS_ELEMENTS']))
        $arFilter['ID'] = $arParams['CONTACTS_ELEMENTS'];

    if ($arResult['CONTACTS']['REGIONALITY']['USE']) {
        $oRegion = Region::getCurrent();

        if (!empty($oRegion)) {
            $arConditions = [
                'LOGIC' => 'OR',
                ['PROPERTY_'.$arResult['CONTACTS']['REGIONALITY']['PROPERTY'] => $oRegion->id]
            ];

            if (!$arResult['CONTACTS']['REGIONALITY']['STRICT'])
                $arConditions[] = ['PROPERTY_'.$arResult['CONTACTS']['REGIONALITY']['PROPERTY'] => false];

            $arFilter[] = $arConditions;
        }

        unset($oRegion, $arConditions);
    }

    $arContacts = [];
    $rsContacts = CIBlockElement::GetList([
        'SORT' => 'ASC'
    ], $arFilter);

    while ($rsContact = $rsContacts->GetNextElement()) {
        $bEmpty = true;
        $arContact = $rsContact->GetFields();
        $arContact['PROPERTIES'] = $rsContact->GetProperties();
        
        $arItem = [
            'ID' => $arContact['ID'],
            'NAME' => $arContact['NAME'],
            'DATA' => [
                'CITY' => null,
                'ADDRESS' => null,
                'PHONE' => null,
                'EMAIL' => null
            ]
        ];

        foreach ($arItem['DATA'] as $sKey => $mValue) {
            $arProperty = $arParams['CONTACTS_PROPERTY_'.$sKey];

            if (empty($arProperty))
                continue;

            $arProperty = ArrayHelper::getValue($arContact, ['PROPERTIES', $arProperty]);

            if (empty($arProperty))
                continue;

            if (Type::isArray($arProperty['VALUE']))
                $arProperty['VALUE'] = reset($arProperty['VALUE']);

            if (empty($arProperty['VALUE']) && !Type::isNumeric($arProperty['VALUE']))
                continue;

            $arItem['DATA'][$sKey] = $arProperty['VALUE'];
            $bEmpty = false;
        }

        if ($bEmpty)
            continue;

        if (!empty($arItem['DATA']['CITY']))
            $arItem['DATA']['ADDRESS'] = Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_CITY', [
                '#CITY#' => $arItem['DATA']['CITY']
            ]).(!empty($arItem['DATA']['ADDRESS']) ? ', '.$arItem['DATA']['ADDRESS'] : null);

        unset($arItem['DATA']['CITY']);

        if (!empty($arItem['DATA']['PHONE'])) {
            $arItem['DATA']['PHONE'] = [
                'DISPLAY' => $arItem['DATA']['PHONE'],
                'LINK' => StringHelper::replace($arItem['DATA']['PHONE'], [
                    '(' => '',
                    ')' => '',
                    ' ' => '',
                    '-' => ''
                ])
            ];
        }

        $arResult['CONTACTS']['ITEMS'][$arItem['ID']] = $arItem;
    }

    unset($arFilter, $rsContacts, $rsContact, $arContact, $arItemm, $arProperty, $sKey, $mValue);

    if (!empty($arResult['CONTACTS']['ITEMS'])) {
        if (!empty($arParams['CONTACTS_ELEMENT']) && !empty($arResult['CONTACTS']['ITEMS'][$arParams['CONTACTS_ELEMENT']]))
            $arResult['CONTACTS']['ITEM'] = $arResult['CONTACTS']['ITEMS'][$arParams['CONTACTS_ELEMENT']];

        if (empty($arResult['CONTACTS']['ITEM']))
            $arResult['CONTACTS']['ITEM'] = reset($arResult['CONTACTS']['ITEMS']);
    }
}

$arResult['ADDRESS'] = [
    'SHOW' => $arParams['ADDRESS_SHOW'] === 'Y',
    'VALUE' => $arParams['ADDRESS_VALUE']
];

$arResult['EMAIL'] = [
    'SHOW' => $arParams['EMAIL_SHOW'] === 'Y',
    'VALUE' => $arParams['EMAIL_VALUE']
];

if ($arResult['CONTACTS']['USE']) {
    $arResult['PHONE']['VALUE'] = null;
    $arResult['ADDRESS']['VALUE'] = null;
    $arResult['EMAIL']['VALUE'] = null;
    $arItem = $arResult['CONTACTS']['ITEM'];

    if (!empty($arItem)) {
        $arResult['PHONE']['VALUE'] = $arItem['DATA']['PHONE'];
        $arResult['ADDRESS']['VALUE'] = $arItem['DATA']['ADDRESS'];
        $arResult['EMAIL']['VALUE'] = $arItem['DATA']['EMAIL'];
    }

    unset($arItem);
}

if (empty($arResult['PHONE']['VALUE']))
    $arResult['PHONE']['SHOW'] = false;

if (empty($arResult['ADDRESS']['VALUE']))
    $arResult['ADDRESS']['SHOW'] = false;

if (empty($arResult['EMAIL']['VALUE']))
    $arResult['EMAIL']['SHOW'] = false;