<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use intec\core\helpers\Type;
use intec\core\helpers\ArrayHelper;

$arDisplayedProperties = [];

foreach ($arResult['ITEMS'] as &$arItem) {
    $arProperties = [];

    if (!empty($arItem['DISPLAY_PROPERTIES']))
        foreach ($arItem['DISPLAY_PROPERTIES'] as $sKey => $arProperty) {
            if (empty($arProperty['NAME']))
                if (!Type::isNumeric($arProperty['NAME']))
                    continue;

            if (empty($arProperty['VALUE']))
                if (!Type::isNumeric($arProperty['VALUE']))
                    continue;

            if (empty($arProperty['DISPLAY_VALUE']))
                if (!Type::isNumeric($arProperty['DISPLAY_VALUE']))
                    continue;

            $arProperties[$sKey] = $arProperty;
            $arDisplayedProperties[$sKey] = [
                'NAME' => $arProperty['NAME'],
                'CODE' => $arProperty['CODE']
            ];
        }

    $arItem['DISPLAY_PROPERTIES'] = $arProperties;

}

$arResult['DISPLAY_PROPERTIES'] = array_values($arDisplayedProperties);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arProperties = [];

    if (!empty($arResult['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']))
        foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) {
            if (!empty($arItem['DISPLAY_PROPERTIES'][$arProperty['CODE']])) {
                $arProperties[] = $arItem['DISPLAY_PROPERTIES'][$arProperty['CODE']];
            } else {
                $arProperties[] = null;
            }
        }

    $arItem['DISPLAY_PROPERTIES'] = $arProperties;

}

unset($arItem, $arProperties, $arDisplayedProperties);