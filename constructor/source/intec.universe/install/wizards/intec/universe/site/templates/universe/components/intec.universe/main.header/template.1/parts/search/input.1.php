<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

$arSearchParams = !empty($arSearchParams) ? $arSearchParams : [];

$sPrefix = 'SEARCH_';
$arParameters = [];

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        $arParameters[$sKey] = $sValue;
    }

$arParameters = ArrayHelper::merge($arParameters, $arSearchParams);
$arParameters['PAGE'] = $arResult['SEARCH']['MODE'] === 'site' ? $arResult['URL']['SEARCH'] : $arResult['URL']['CATALOG'];
$arParameters['INPUT_ID'] = $arParameters['INPUT_ID'].'-input-1';

?>
<!--noindex-->
<?php $APPLICATION->IncludeComponent(
    "bitrix:search.title",
    "input.1",
    $arParameters,
    $this->getComponent()
) ?>
<!--/noindex-->
<?php unset($arParameters) ?>
<?php unset($arSearchParams) ?>