<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
], $arParams);

$arResult['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['LAZYLOAD']['USE'] = false;

if ($arResult['LAZYLOAD']['USE'])
    $arResult['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$iIBlockId = ArrayHelper::getValue($arParams, 'IBLOCK_ID');
$sDescription = CIBlock::GetArrayByID($iIBlockId, 'DESCRIPTION');
$arResult['DESCRIPTION'] = $sDescription;