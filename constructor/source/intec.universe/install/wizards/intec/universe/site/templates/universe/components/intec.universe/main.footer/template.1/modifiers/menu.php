<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

$arParams = ArrayHelper::merge([
    'MENU_MAIN_SHOW' => 'N',
    'MENU_MAIN_ROOT' => null,
    'MENU_MAIN_CHILD' => null,
    'MENU_MAIN_LEVEL' => 1
], $arParams);

$arResult['MENU'] = [];
$arResult['MENU']['MAIN'] = [
    'SHOW' => $arParams['MENU_MAIN_SHOW'] === 'Y',
    'ROOT' => $arParams['MENU_MAIN_ROOT'],
    'CHILD' => $arParams['MENU_MAIN_CHILD'],
    'LEVEL' => $arParams['MENU_MAIN_LEVEL']
];