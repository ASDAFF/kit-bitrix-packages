<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arFiles = [];

/**
 * @param $arItem
 * @param $sProperty
 */
$hCollect = function (&$arItem, $sProperty = null) use (&$arFiles) {
    if (!empty($arItem['PREVIEW_PICTURE']) && !Type::isArray($arItem['PREVIEW_PICTURE']))
        $arFiles[] = $arItem['PREVIEW_PICTURE'];

    if (!empty($arItem['DETAIL_PICTURE']) && !Type::isArray($arItem['DETAIL_PICTURE']))
        $arFiles[] = $arItem['DETAIL_PICTURE'];

    if (!empty($sProperty)) {
        $arPictures = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $sProperty,
            'VALUE'
        ]);

        if (!Type::isArray($arPictures))
            $arPictures = [];

        foreach ($arPictures as $iPicture)
            $arFiles[] = $iPicture;
    }
};

/**
 * @param $arItem
 * @param $sProperty
 */
$hSet = function (&$arItem, $sProperty = null) use (&$arFiles) {
    $arItem['PICTURES'] = [];

    if (!empty($arItem['PREVIEW_PICTURE']) && !Type::isArray($arItem['PREVIEW_PICTURE']))
        $arItem['PREVIEW_PICTURE'] = $arFiles->get($arItem['PREVIEW_PICTURE']);

    if (!empty($arItem['DETAIL_PICTURE']) && !Type::isArray($arItem['DETAIL_PICTURE']))
        $arItem['DETAIL_PICTURE'] = $arFiles->get($arItem['DETAIL_PICTURE']);

    if (!empty($arItem['DETAIL_PICTURE'])) {
        $arItem['PICTURES'][] = $arItem['DETAIL_PICTURE'];
    } else if (!empty($arItem['PREVIEW_PICTURE'])) {
        $arItem['PICTURES'][] = $arItem['PREVIEW_PICTURE'];
    }

    if (!empty($sProperty)) {
        $arPictures = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $sProperty,
            'VALUE'
        ]);

        if (!Type::isArray($arPictures))
            $arPictures = [];

        foreach ($arPictures as $iPicture) {
            $arPicture = $arFiles->get($iPicture);

            if (empty($arPicture))
                continue;

            $arItem['PICTURES'][] = $arPicture;
        }
    }
};

$hCollect($arResult, $arCodes['PICTURES']);

if (!empty($arResult['OFFERS'])) {
    foreach ($arResult['OFFERS'] as &$arOffer)
        $hCollect($arOffer, $arCodes['OFFERS']['PICTURES']);

    unset($arOffer);
}

if (!empty($arFiles)) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arFiles)
    ]))->each(function ($iIndex, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arFiles = new Arrays();
}

$hSet($arResult, $arCodes['PICTURES']);

if (!empty($arResult['OFFERS'])) {
    foreach ($arResult['OFFERS'] as &$arOffer)
        $hSet($arOffer, $arCodes['OFFERS']['PICTURES']);

    unset($arOffer);
}

unset($hCollect);
unset($hSet);
unset($arFiles);