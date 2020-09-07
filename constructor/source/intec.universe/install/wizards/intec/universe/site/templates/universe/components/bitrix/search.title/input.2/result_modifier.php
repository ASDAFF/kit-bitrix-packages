<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('intec.core'))
    return;

$arInput = [
    'ID' => ArrayHelper::getValue($arParams, 'INPUT_ID')
];

$arTips = [
    'USE' => ArrayHelper::getValue($arParams, 'TIPS_USE') == 'Y'
];

if (empty($arInput['ID']))
    $arInput['ID'] = 'title-search-input';

$arResult['INPUT'] = $arInput;
$arResult['TIPS'] = $arTips;