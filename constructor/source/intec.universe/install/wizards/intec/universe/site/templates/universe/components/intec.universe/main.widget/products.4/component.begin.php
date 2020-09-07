<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\regionality\Module as Regionality;
use intec\regionality\models\Region;

/**
 * @var array $arParams
 * @var CBitrixComponent $this
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] == 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arIBlock = null;

if (Loader::includeModule('iblock') && !empty($arParams['IBLOCK_ID']))
    $arIBlock = CIBlock::GetByID($arParams['IBLOCK_ID'])->Fetch();

if (empty($arParams['FILTER_NAME']))
    $arParams['FILTER_NAME'] = 'arrProducts4Filter';

$arResult['REGIONALITY'] = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y',
    'FILTER' => [
        'USE' => $arParams['REGIONALITY_FILTER_USE'] === 'Y',
        'PROPERTY' => $arParams['REGIONALITY_FILTER_PROPERTY'],
        'STRICT' => $arParams['REGIONALITY_FILTER_STRICT'] === 'Y'
    ],
    'PRICES' => [
        'USE' => $arParams['REGIONALITY_PRICES_TYPES_USE'] === 'Y'
    ],
    'STORES' => [
        'USE' => $arParams['REGIONALITY_STORES_USE'] === 'Y'
    ]
];

if (empty($arIBlock) || !Loader::includeModule('intec.regionality'))
    $arResult['REGIONALITY']['USE'] = false;

if (empty($arResult['REGIONALITY']['FILTER']['PROPERTY']))
    $arResult['REGIONALITY']['FILTER']['USE'] = false;

if ($arResult['REGIONALITY']['USE']) {
    $oRegion = Region::getCurrent();

    if (!empty($oRegion)) {
        if ($arResult['REGIONALITY']['FILTER']['USE']) {
            if (!isset($GLOBALS[$arParams['FILTER_NAME']]) || !Type::isArray($GLOBALS[$arParams['FILTER_NAME']]))
                $GLOBALS[$arParams['FILTER_NAME']] = [];

            $arConditions = [
                'LOGIC' => 'OR',
                ['PROPERTY_'.$arResult['REGIONALITY']['FILTER']['PROPERTY'] => $oRegion->id]
            ];

            if (!$arResult['REGIONALITY']['FILTER']['STRICT'])
                $arConditions[] = ['PROPERTY_'.$arResult['REGIONALITY']['FILTER']['PROPERTY'] => false];

            $GLOBALS[$arParams['FILTER_NAME']][] = $arConditions;
        }

        if ((Loader::includeModule('catalog') && Loader::includeModule('sale')) || Loader::includeModule('intec.startshop')) {
            if ($arResult['REGIONALITY']['PRICES']['USE']) {
                $arParams['PRICE_CODE'] = $_SESSION[Regionality::VARIABLE][Region::VARIABLE]['PRICES']['CODE'];
                $arParams['~PRICE_CODE'] = $_SESSION[Regionality::VARIABLE][Region::VARIABLE]['PRICES']['CODE'];
            }
        }
    }
}

$cache = true;