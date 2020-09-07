<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::IncludeModule('intec.core'))
    return;

$sPropertyImage = ArrayHelper::getValue($arParams, 'PROPERTY_IMAGE');

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

foreach ($arResult as &$arItem) {
    $arItem['ACTIVE'] = false;
    $arItem['PICTURE'] = null;

    if ($arItem['LINK'] == $APPLICATION->GetCurPage())
        $arItem['ACTIVE'] = true;

    if (!empty($arItem['PARAMS']['SECTION'])) {
        $arSection = &$arItem['PARAMS']['SECTION'];

        if (!empty($arSection[$sPropertyImage])) {
            $arItem['PICTURE'] = CFile::GetFileArray($arSection[$sPropertyImage]);
        } elseif (!empty($arSection['PICTURE'])) {
            $arItem['PICTURE'] = CFile::GetFileArray($arSection['PICTURE']);
        }
    }
}

unset($arItem);

$arResult = $fBuild($arResult);
