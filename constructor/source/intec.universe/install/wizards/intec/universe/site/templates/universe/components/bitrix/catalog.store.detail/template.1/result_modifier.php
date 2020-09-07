<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arResult['IMAGE'] = null;
$arResult['EMAIL'] = null;

if (!empty($arResult['IMAGE_ID']))
    $arResult['IMAGE'] = CFile::GetFileArray($arResult['IMAGE_ID']);

unset($arResult['IMAGE_ID']);

$arStore = CCatalogStore::GetList(
    ['ID' => 'ASC'],
    ['ID' => $arParams['STORE'], 'ACTIVE' => 'Y'],
    false,
    false,
    ['EMAIL']
);

$arStore = $arStore->GetNext();

if (!empty($arStore))
    $arResult = ArrayHelper::merge($arResult, $arStore);

if (!empty($arResult['PHONE'])) {
    $arResult['PHONE'] = [
        'VALUE' => StringHelper::replace($arResult['PHONE'], [
            '(' => '',
            ')' => '',
            ' ' => '',
            '-' => ''
        ]),
        'DISPLAY' => $arResult['PHONE']
    ];
} else {
    $arResult['PHONE'] = null;
}

$arResult['MAP'] = [
    'SHOW' =>
        (!empty($arResult['GPS_N']) || Type::isNumeric($arResult['GPS_N'])) &&
        (!empty($arResult['GPS_S']) || Type::isNumeric($arResult['GPS_S'])),
    'VENDOR' => $arResult['MAP'],
    'GPS' => [
        'N' => 0,
        'S' => 0
    ]
];

if ($arResult['MAP']['SHOW']) {
    $arResult['MAP']['GPS']['N'] = Type::toFloat(substr($arResult['GPS_N'],0,15));
    $arResult['MAP']['GPS']['S'] = Type::toFloat(substr($arResult['GPS_S'],0,15));
}

unset($arResult['GPS_N']);
unset($arResult['GPS_S']);