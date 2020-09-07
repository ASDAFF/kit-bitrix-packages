<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('intec.core'))
    return;

$bBase = null;

if (Loader::includeModule('catalog')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bBase = false;
}

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

if (!Loader::includeModule('iblock'))
    return;

if ($bBase === true) {
    include(__DIR__.'/modifiers/prices.base.php');
} else if ($bBase === false) {
    include(__DIR__.'/modifiers/prices.lite.php');
}

if (!empty($arResult['CATEGORIES'])) {
    $arItems = [];
    $arItemsId = [];

    foreach ($arResult['CATEGORIES'] as $arCategory)
        foreach ($arCategory['ITEMS'] as $arItem) {
            if ($arItem['MODULE_ID'] != 'iblock')
                continue;

            $iItemId = ArrayHelper::getValue($arItem, 'ITEM_ID');

            if (empty($iItemId))
                continue;

            if (StringHelper::cut($iItemId, 0, 1) == 'S')
                continue;

            if (!ArrayHelper::isIn($iItemId, $arItemsId))
                $arItemsId[] = $iItemId;
        }

    if (!empty($arItemsId)) {
        if ($bBase === true) {
            include(__DIR__.'/modifiers/items.base.php');
        } else if ($bBase === false) {
            include(__DIR__.'/modifiers/items.lite.php');
        } else {
            include(__DIR__.'/modifiers/items.default.php');
        }

        $arFiles = [];
        $arFilesId = [];

        $arSections = [];
        $arSectionsId = [];

        foreach ($arItems as $arItem) {
            if (!empty($arItem['PREVIEW_PICTURE']))
                $arFilesId[] = $arItem['PREVIEW_PICTURE'];

            if (!empty($arItem['DETAIL_PICTURE']))
                $arFilesId[] = $arItem['DETAIL_PICTURE'];

            if (!ArrayHelper::isIn($arItem['IBLOCK_SECTION_ID'], $arSectionsId))
                $arSectionsId[] = $arItem['IBLOCK_SECTION_ID'];
        }

        if (!empty($arFilesId)) {
            $rsFiles = CFile::GetList([], [
                '@ID' => implode(',', $arFilesId)
            ]);

            while ($arFile = $rsFiles->GetNext()) {
                $arFile['SRC'] = CFile::GetFileSRC($arFile);
                $arFiles[$arFile['ID']] = $arFile;
            }
        }

        foreach ($arItems as &$arItem) {
            if (!empty($arItem['PREVIEW_PICTURE']))
                $arItem['PREVIEW_PICTURE'] = ArrayHelper::getValue($arFiles, $arItem['PREVIEW_PICTURE']);

            if (!empty($arItem['DETAIL_PICTURE']))
                $arItem['DETAIL_PICTURE'] = ArrayHelper::getValue($arFiles, $arItem['DETAIL_PICTURE']);

            unset($arItem);
        }

        if (!empty($arSectionsId)) {
            $rsSections = CIBlockSection::GetList([], [
                'ID' => $arSectionsId
            ]);

            while ($arSection = $rsSections->GetNext())
                $arSections[$arSection['ID']] = $arSection;
        }

        foreach ($arResult['CATEGORIES'] as &$arCategory) {
            foreach ($arCategory['ITEMS'] as &$arItem) {
                if ($arItem['MODULE_ID'] != 'iblock')
                    continue;

                $iItemId = ArrayHelper::getValue($arItem, 'ITEM_ID');

                if (empty($iItemId))
                    continue;

                $arItem['SECTION'] = null;
                $arItem['ITEM'] = ArrayHelper::getValue($arItems, $iItemId);

                if (!empty($arItem['ITEM']))
                    $arItem['SECTION'] = ArrayHelper::getValue($arSections, $arItem['ITEM']['IBLOCK_SECTION_ID']);

                unset($arItem);
            }

            unset($arCategory);
        }
    }
}