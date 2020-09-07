<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\ArrayHelper;

foreach ($arCategories as &$arCategory) {
    $arCategory['properties'] = [];

    unset($arCategory);
}

foreach ($arResult['PROPERTIES'] as $sKey => &$arProperty) {
    if (isset($arProperty['visible']) && !$arProperty['visible'])
        continue;

    $arCategory = ArrayHelper::getValue($arProperty, 'category');

    if (
        empty($arCategory) ||
        empty($arCategories[$arCategory])
    ) continue;

    $arCategory = &$arCategories[$arCategory];
    $arCategory['properties'][$sKey] = &$arProperty;

    unset($arCategory);
    unset($arProperty);
}