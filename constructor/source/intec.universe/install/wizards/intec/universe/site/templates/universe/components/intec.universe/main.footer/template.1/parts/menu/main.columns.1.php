<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var InnerTemplate $this
 */

$arMenu = $arResult['MENU']['MAIN'];
$arMenuParams = !empty($arMenuParams) ? $arMenuParams : [];

$sPrefix = 'MENU_MAIN_';
$arParameters = [];

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        $arParameters[$sKey] = $sValue;
    }

$arParameters = ArrayHelper::merge($arParameters, $arMenuParams, [
    'ROOT_MENU_TYPE' => $arMenu['ROOT'],
    'CHILD_MENU_TYPE' => $arMenu['CHILD'],
    'MAX_LEVEL' => $arMenu['LEVEL'],
    'MENU_CACHE_TYPE' => 'N',
    'USE_EXT' => 'Y',
    'DELAY' => 'N',
    'ALLOW_MULTI_SELECT' => 'N'
]);

$APPLICATION->IncludeComponent(
    'bitrix:menu',
    'columns.1',
    $arParameters,
    $this->getComponent()
);

unset($arParameters);
unset($sPrefix);
unset($arMenu);