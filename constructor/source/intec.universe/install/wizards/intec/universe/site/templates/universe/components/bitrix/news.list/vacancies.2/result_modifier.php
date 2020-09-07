<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'PROPERTY_SALARY' => null,
    'SALARY_SHOW' => 'N',
    'FORM_SUMMARY_SHOW' => 'N',
    'FORM_SUMMARY_ID' => null,
    'PROPERTY_FORM_SUMMARY_VACANCY' => null,
    'CONSENT_URL' => null
], $arParams);

$arVisual = [
    'SALARY' => [
        'SHOW' => $arParams['SALARY_SHOW'] === 'Y'
    ],
    'FORM' => [
        'SHOW' => $arParams['FORM_SUMMARY_SHOW'] === 'Y'
    ]
];

$arResult['VISUAL'] = ArrayHelper::merge($arResult['VISUAL'], $arVisual);

unset($arVisual);

$arResult['FORM'] = [
    'ID' => $arParams['FORM_SUMMARY_ID'],
    'PROPERTY_SUMMARY_VACANCY' => $arParams['PROPERTY_FORM_SUMMARY_VACANCY'],
    'CONSENT_URL' => $arParams['CONSENT_URL']
];

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'SALARY' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_SALARY']])
    ];
}