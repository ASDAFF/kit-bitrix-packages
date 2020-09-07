<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\collections\Arrays;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (empty($arResult['ITEMS']))
    return;

$arFiles = [];

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PREVIEW_PICTURE']))
        $arFiles[] = $arItem['PREVIEW_PICTURE'];

    if (!empty($arItem['DETAIL_PICTURE']))
        $arFiles[] = $arItem['DETAIL_PICTURE'];
}

$arFiles = array_unique($arFiles);

if (!empty($arFiles)) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arFiles)
    ]))->each(function ($iId, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arFiles = Arrays::from([]);
}

if (!$arFiles->isEmpty())
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!empty($arItem['PREVIEW_PICTURE']))
            $arItem['PREVIEW_PICTURE'] = $arFiles->get($arItem['PREVIEW_PICTURE']);

        if (!empty($arItem['DETAIL_PICTURE']))
            $arItem['DETAIL_PICTURE'] = $arFiles->get($arItem['DETAIL_PICTURE']);
    }

unset($arItem, $arFiles);