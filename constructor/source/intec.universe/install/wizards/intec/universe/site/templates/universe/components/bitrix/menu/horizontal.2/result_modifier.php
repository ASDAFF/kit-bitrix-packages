<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\base\Collection;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * CBitrixComponentTemplate $this
 */

if (!CModule::IncludeModule('intec.core'))
    return;

/**
 * @param array $arResult
 * @return array
 */
$fBuild = function ($arResult) {
    $bFirst = true;

    if (empty($arResult))
        return [];

    $fBuild = function () use (&$fBuild, &$bFirst, &$arResult) {
        $iLevel = null;
        $arItems = array();
        $arItem = null;

        if ($bFirst) {
            $arItem = reset($arResult);
            $bFirst = false;
        }

        while (true) {
            if ($arItem === null) {
                $arItem = next($arResult);

                if (empty($arItem))
                    break;
            }

            if ($iLevel === null)
                $iLevel = $arItem['DEPTH_LEVEL'];

            if ($arItem['DEPTH_LEVEL'] < $iLevel) {
                prev($arResult);
                break;
            }

            if ($arItem['IS_PARENT'] === true)
                $arItem['ITEMS'] = $fBuild();

            $arItems[] = $arItem;
            $arItem = null;
        }

        return $arItems;
    };

    return $fBuild();
};

$arFiles = Collection::from([]);

foreach ($arResult as $sKey => $arItem) {
    $arResult[$sKey]['IMAGE'] = null;

    if (!empty($arItem['PARAMS']['ELEMENT'])) {
        $arElement = &$arItem['PARAMS']['ELEMENT'];

        if (!empty($arElement['PREVIEW_PICTURE'])) {
            $arResult[$sKey]['IMAGE'] = $arElement['PREVIEW_PICTURE'];
        } else if (!empty($arElement['DETAIL_PICTURE'])) {
            $arResult[$sKey]['IMAGE'] = $arElement['DETAIL_PICTURE'];
        }
    } else if (!empty($arItem['PARAMS']['SECTION'])) {
        $arSection = &$arItem['PARAMS']['SECTION'];

        if (!empty($arParams['PROPERTY_IMAGE']) && !empty($arSection[$arParams['PROPERTY_IMAGE']])) {
            $arResult[$sKey]['IMAGE'] = $arSection[$arParams['PROPERTY_IMAGE']];
        } elseif (!empty($arSection['PICTURE'])) {
            $arResult[$sKey]['IMAGE'] = $arSection['PICTURE'];
        }
    }

    if (!empty($arResult[$sKey]['IMAGE']))
        if (!$arFiles->has($arResult[$sKey]['IMAGE']))
            $arFiles->add($arResult[$sKey]['IMAGE']);
}

unset($arElement);
unset($arSection);

if (!$arFiles->isEmpty()) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arFiles->asArray())
    ]))->each(function ($iIndex, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arFiles = new Arrays();
}

foreach ($arResult as $sKey => $arItem) {
    if (!empty($arResult[$sKey]['IMAGE']))
        $arResult[$sKey]['IMAGE'] = $arFiles->get($arResult[$sKey]['IMAGE']);
}

if (!empty($arParams['CATALOG_LINKS']) && Type::isArrayable($arParams['CATALOG_LINKS'])) {
    foreach ($arParams['CATALOG_LINKS'] as $sKey => $sCatalogLink)
        $arParams['CATALOG_LINKS'][$sKey] = StringHelper::replaceMacros($sCatalogLink, $arMacros);

    foreach ($arResult as $sKey => $arItem)
        if (ArrayHelper::isIn(
            $arItem['LINK'],
            $arParams['CATALOG_LINKS']
        )) $arResult[$sKey]['IS_CATALOG'] = 'Y';
}


$arResult = $fBuild($arResult);