<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arViews
 * @var array $arColumns
 * @var CBitrixComponent
 * @global CMain $APPLICATION
 */

$arParams = ArrayHelper::merge([
    'MENU_TEMPLATE' => null,
    'MENU_ROOT' => null,
    'MENU_CHILD' => null,
    'MENU_LEVEL' => null
], $arParams);

$arMenu = [
    'SHOW' => true,
    'TEMPLATE' => $arParams['MENU_TEMPLATE'],
    'PARAMETERS' => []
];

if (empty($arMenu['TEMPLATE']))
    $arMenu['SHOW'] = false;

if ($arMenu['SHOW']) {
    $sPrefix = 'MENU_';
    $arMenu['TEMPLATE'] = 'vertical.'.$arMenu['TEMPLATE'];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        if ($sKey === 'TEMPLATE')
            continue;

        $arMenu['PARAMETERS'][$sKey] = $mValue;
    }

    foreach ($arResult['PARAMETERS']['COMMON'] as $sProperty)
        $arSections['PARAMETERS'][$sProperty] = ArrayHelper::getValue($arParams, $sProperty);

    $arMenu['PARAMETERS'] = ArrayHelper::merge($arMenu['PARAMETERS'], [
        'ROOT_MENU_TYPE' => $arParams['MENU_ROOT'],
        'CHILD_MENU_TYPE' => $arParams['MENU_CHILD'],
        'MAX_LEVEL' => $arParams['MENU_LEVEL'],
        'MENU_CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'MENU_CACHE_TIME' => $arParams['CACHE_TIME'],
        'MENU_CACHE_USE_GROUPS' => $arParams['CACHE_GROUPS'],
        'MENU_CACHE_GET_VARS' => [],
        'USE_EXT' => 'Y',
        'DELAY' => 'N',
        'ALLOW_MULTI_SELECT' => 'N'
    ]);

    if (!empty($arResult['ALIASES']))
        foreach ($arResult['ALIASES'] as $sAlias)
            if (!Type::isArray($sAlias))
                $arMenu['PARAMETERS']['MENU_CACHE_GET_VARS'][] = $sAlias;
}

if (empty($arMenu['PARAMETERS']['ROOT_MENU_TYPE']))
    $arMenu['SHOW'] = false;

if ($arMenu['SHOW']) {
    $oMenu = new CMenu($arMenu['PARAMETERS']['ROOT_MENU_TYPE']);

    if (!$oMenu->Init($APPLICATION->GetCurDir(), $arMenu['PARAMETERS']['USE_EXT'] === 'Y') && !empty($oMenu->arMenu))
        $arMenu['SHOW'] = false;

    unset($oMenu);
}