<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\Type;

foreach ($arResult['ITEMS'] as &$arItem) {
    $arProperties = [];

    if (!empty($arItem['DISPLAY_PROPERTIES']))
        foreach ($arItem['DISPLAY_PROPERTIES'] as $arProperty) {
            if (empty($arProperty['NAME']))
                if (!Type::isNumeric($arProperty['NAME']))
                    continue;

            if (empty($arProperty['VALUE']))
                if (!Type::isNumeric($arProperty['VALUE']))
                    continue;

            if (empty($arProperty['DISPLAY_VALUE']))
                if (!Type::isNumeric($arProperty['DISPLAY_VALUE']))
                    continue;

            $arProperties[] = $arProperty;
        }

    $arItem['DISPLAY_PROPERTIES'] = $arProperties;

    unset($arProperty);
    unset($arProperties);
}

unset($arItem);