<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'PROPERTY_EXPANDED' => null,
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null,
    'LIST_PAGE_URL' => null,
    'HIDE' => 'N'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arVisual = [
    'HIDE' => $arParams['HIDE'] === 'Y' && count($arResult['ITEMS']) > 4
];

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arFooter = [
    'SHOW' => $arParams['FOOTER_SHOW'] === 'Y' || $arResult['VISUAL']['HIDE'],
    'POSITION' => ArrayHelper::fromRange([
        'center',
        'left',
        'right'
    ], $arParams['FOOTER_POSITION']),
    'BUTTON' => [
        'SHOW' => $arParams['FOOTER_BUTTON_SHOW'] === 'Y',
        'TEXT' => $arParams['FOOTER_BUTTON_TEXT'],
        'LINK' => null
    ]
];

if (!empty($arParams['LIST_PAGE_URL']))
    $arFooter['BUTTON']['LINK'] = StringHelper::replaceMacros(
        $arParams['LIST_PAGE_URL'],
        $arMacros
    );

if (empty($arFooter['BUTTON']['TEXT']) || empty($arFooter['BUTTON']['LINK']))
    $arFooter['BUTTON']['SHOW'] = false;

if (!$arFooter['BUTTON']['SHOW'] && !$arResult['VISUAL']['HIDE'])
    $arFooter['SHOW'] = false;

$arResult['BLOCKS']['FOOTER'] = $arFooter;

unset($arFooter, $arMacros);

$hSetCheckboxProperty = function (&$arItem,$property) use (&$arParams) {
    $arReturn = false;

    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property,
            'VALUE'
        ]);

        if (!empty($arProperty))
            $arReturn = $arProperty === 'Y';
    }

    return $arReturn;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'EXPANDED' => $hSetCheckboxProperty($arItem, $arParams['PROPERTY_EXPANDED'])
    ];
}

unset($arItem);