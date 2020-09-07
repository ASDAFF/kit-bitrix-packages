<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\collections\Arrays;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $arVisual
 */

if (empty($arResult['ITEMS']))
    return;

$arItems = [];

foreach ($arResult['ITEMS'] as &$arItem)
    $arItems[] = $arItem['ID'];

$arItems = Arrays::fromDBResult(CIBlockElement::GetList(['SORT' => 'ASC'], [
    'ID' => $arItems
]))->indexBy('ID');

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['NAME'] = !empty($arItem['OFFER_FIELDS']['NAME']) ? $arItem['OFFER_FIELDS']['NAME'] : $arItem['FIELDS']['NAME'];
    $arItem['ACTION'] = $arVisual['ACTION'];
    $arItem['PREVIEW_PICTURE'] = null;
    $arItem['DETAIL_PICTURE'] = null;

    $arData = $arItems->get($arItem['ID']);

    if (empty($arData))
        continue;

    $arItem['PREVIEW_PICTURE'] = $arData['PREVIEW_PICTURE'];
    $arItem['DETAIL_PICTURE'] = $arData['DETAIL_PICTURE'];
}

unset($arItem, $arItems);