<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$bBase = false;
$bLite = false;

if (!Loader::includeModule('intec.core'))
    return;

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

$arParams = ArrayHelper::merge([
    'TRIGGER' => null
], $arParams);

$arResult['TRIGGER'] = $arParams['TRIGGER'];

if (empty($arResult['TRIGGER']))
    $arResult['TRIGGER'] = 'additional';

if ($bBase) {
    include(__DIR__.'/modifiers/base/catalog.php');
} else if ($bLite) {
    include(__DIR__.'/modifiers/lite/catalog.php');
}

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');