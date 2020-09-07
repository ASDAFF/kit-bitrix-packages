<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\Type;

$arProperties = [];

if (!empty($arResult['DISPLAY_PROPERTIES']))
    foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) {
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

$arResult['DISPLAY_PROPERTIES'] = $arProperties;

unset($arProperty);
unset($arProperties);