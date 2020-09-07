<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

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

if (!empty($arParams['PROPERTY_SERVICES'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_SERVICES'],
        'VALUE'
    ]);

    if (!empty($arProperty) && Type::isArray($arProperty))
        $arServicesParameters['PARAMETERS']['FILTER'] = [
            'ID' => $arProperty
        ];
    else
        $arServicesParameters['SHOW'] = false;

    if ($arServicesParameters['SHOW']) {
        $sLength = StringHelper::length($arServicesParameters['PREFIX']);

        $arExcluded = [
            'SHOW',
            'NAME',
            'EXPANDED'
        ];

        foreach ($arParams as $key => $arValue) {
            if (!StringHelper::startsWith($key, $arServicesParameters['PREFIX']))
                continue;

            $key = StringHelper::cut($key, $sLength);

            if (ArrayHelper::isIn($key, $arExcluded))
                continue;

            $arServicesParameters['PARAMETERS'][$key] = $arValue;
        }

        unset($sLength, $arExcluded, $key, $arValue);
    }
}

$arResult['SERVICES'] = $arServicesParameters;

unset($arServicesParameters);

if (!$arResult['SERVICES']['SHOW'])
    $arVisual['SERVICES']['SHOW'] = false;