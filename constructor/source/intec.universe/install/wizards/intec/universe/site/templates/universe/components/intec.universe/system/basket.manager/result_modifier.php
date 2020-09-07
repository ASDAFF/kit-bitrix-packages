<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var CBitrixComponentTemplate $this
 */

$arParams = ArrayHelper::merge([
    'BASKET' => 'Y',
    'COMPARE' => 'Y',
    'COMPARE_CODE' => 'compare'
], $arParams);

$arResult = [];
$arResult['ACTION'] = $this->GetFolder().'/ajax.php';
$arResult['BASKET'] = [];
$arResult['COMPARE'] = [];

if (Loader::includeModule('sale')) {
    include(__DIR__.'/modifiers/base/products.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/modifiers/lite/products.php');
}

if ($arParams['COMPARE'] === 'Y')
    include(__DIR__.'/modifiers/compare.php');