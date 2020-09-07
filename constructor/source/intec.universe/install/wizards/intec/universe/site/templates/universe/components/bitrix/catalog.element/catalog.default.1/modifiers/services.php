<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arServicesParameters = [
    'SHOW' => !empty($arParams['SERVICES_IBLOCK_ID']) && !empty($arParams['PROPERTY_SERVICES']),
    'PREFIX' => 'SERVICES_',
    'PARAMETERS' => [
        'LAZYLOAD_USE' => $arParams['LAZYLOAD_USE']
    ]
];

if ($arServicesParameters['SHOW']) {
    $arServicesId = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_SERVICES'],
        'VALUE'
    ]);

    if (!empty($arServicesId) && Type::isArray($arServicesId)) {
        $arServicesParameters['PARAMETERS']['FILTER'] = [
                'ID' => $arServicesId
        ];
    } else {
        $arServicesParameters['SHOW'] = false;
    }

    unset($arServicesId);
}

$arExcluded = [
    'SHOW'
];

$sLength = StringHelper::length($arServicesParameters['PREFIX']);

foreach ($arParams as $key => $arValue) {
    if (!StringHelper::startsWith($key, $arServicesParameters['PREFIX']))
        continue;

    $key = StringHelper::cut($key, $sLength);

    if (ArrayHelper::isIn($key, $arExcluded))
        continue;

    $arServicesParameters['PARAMETERS'][$key] = $arValue;
}

$arResult['SERVICES'] = $arServicesParameters;

unset($arServicesParameters, $arExcluded, $sLength, $key, $arValue);