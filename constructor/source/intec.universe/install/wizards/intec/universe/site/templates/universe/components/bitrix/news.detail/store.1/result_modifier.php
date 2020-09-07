<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$arParams = ArrayHelper::merge([
    'MAP_VENDOR' => 'yandex',
    'DESCRIPTION_SHOW' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] == 'Y')
    include(__DIR__.'/parts/settings.php');

$arResult['TITLE'] = null;
$arParams['MAP_VENDOR'] = ArrayHelper::fromRange([
    'google',
    'yandex'
], $arParams['MAP_VENDOR']);

$mapId = $arParams['MAP_ID'];
$mapIdLength = StringHelper::length($mapId);
$mapIdExpression = new RegExp('^[A-Za-z_][A-Za-z01-9_]*$');

if ($mapIdLength <= 0 || $mapIdExpression->isMatch($mapId))
    $arParams['MAP_ID'] = 'MAP_'.RandString();


$arResult['PHONES'] = [];
$arPhones = ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['PROPERTY_PHONE'], 'VALUE']);
if (!empty($arPhones)) {
    if (Type::isArray($arPhones)){
        foreach ($arPhones as $sPhone) {
            $arResult['PHONES'][] = [
                'DISPLAY' => $sPhone,
                'VALUE' => StringHelper::replace(
                    $sPhone, [
                        '-' => '',
                        ' ' => '',
                        '(' => '',
                        ')' => ''
                    ]
                )
            ];
        }
    } else {
        $arResult['PHONES'][] = [
            'DISPLAY' => $arPhones,
            'VALUE' => StringHelper::replace(
                $arPhones, [
                    '-' => '',
                    ' ' => '',
                    '(' => '',
                    ')' => ''
                ]
            )
        ];
    }
}

$sAddress = ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['PROPERTY_ADDRESS'], 'VALUE']);
$arResult['ADDRESS'] = !empty($sAddress) ? $sAddress : null;

$sEmail = ArrayHelper::getValue($arResult,['PROPERTIES', $arParams['PROPERTY_EMAIL'], 'VALUE']);
$arResult['EMAIL'] = !empty($sEmail) ? $sEmail : null;

$sSchedule = ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['PROPERTY_SCHEDULE'], 'VALUE']);
$arResult['SCHEDULE'] = !empty($sSchedule) ? $sSchedule : null;

$arResult['DESCRIPTION'] = null;
if (!empty($arResult['DETAIL_TEXT'])) {
    $arResult['DESCRIPTION'] = $arResult['DETAIL_TEXT'];
} else if (!empty($arResult['PREVIEW_TEXT'])) {
    $arResult['DESCRIPTION'] = $arResult['PREVIEW_TEXT'];
}
?>