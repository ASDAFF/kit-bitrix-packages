<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * CBitrixComponentTemplate $this
 */

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

$arResult = $fBuild($arResult);
