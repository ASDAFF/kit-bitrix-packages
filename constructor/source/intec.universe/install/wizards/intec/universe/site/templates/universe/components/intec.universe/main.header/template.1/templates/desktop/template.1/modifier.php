<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$arResult['CONTACTS']['POSITION'] = ArrayHelper::fromRange([
    'bottom',
    'top'
], ArrayHelper::getValue($arParams, 'PHONES_POSITION'));

$arResult['MENU']['MAIN']['POSITION'] = ArrayHelper::fromRange([
    'bottom',
    'top'
], ArrayHelper::getValue($arParams, 'MENU_MAIN_POSITION'));

$arResult['MENU']['MAIN']['TRANSPARENT'] =
    ArrayHelper::getValue($arParams, 'MENU_MAIN_TRANSPARENT') == 'Y' &&
    $arResult['MENU']['MAIN']['POSITION'] == 'bottom';

$arResult['MENU']['INFO'] = [
    'SHOW' => ArrayHelper::getValue($arParams, 'MENU_INFO_SHOW') == 'Y',
    'ROOT' => ArrayHelper::getValue($arParams, 'MENU_INFO_ROOT'),
    'CHILD' => ArrayHelper::getValue($arParams, 'MENU_INFO_CHILD'),
    'LEVEL' => ArrayHelper::getValue($arParams, 'MENU_INFO_LEVEL')
];

$arResult['SOCIAL']['POSITION'] = ArrayHelper::fromRange([
    'left',
    'center'
], $arParams['SOCIAL_POSITION']);