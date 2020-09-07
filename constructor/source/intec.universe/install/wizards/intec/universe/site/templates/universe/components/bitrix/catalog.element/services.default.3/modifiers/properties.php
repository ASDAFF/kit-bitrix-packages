<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arFiles = [];

foreach ($arResult['PROPERTIES'] as &$arProperty) {
    if ($arProperty['PROPERTY_TYPE'] === 'F' && empty($arProperty['USER_TYPE'])) {
        $arValues = $arProperty['VALUE'];

        if (!Type::isArray($arValues))
            $arValues = [$arValues];

        foreach ($arValues as $iValue)
            if (!empty($iValue))
                if (!ArrayHelper::isIn($iValue, $arFiles))
                    $arFiles[] = $iValue;

        unset($iValue);
        unset($arValues);
    }
}

unset($arProperty);

if (!empty($arFiles)) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arFiles)
    ]))->each(function ($iIndex, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arFiles = new Arrays();
}

foreach ($arResult['PROPERTIES'] as &$arProperty) {
    if ($arProperty['PROPERTY_TYPE'] === 'F' && empty($arProperty['USER_TYPE'])) {
        $arValues = $arProperty['VALUE'];

        if (!Type::isArray($arValues)) {
            $arProperty['VALUE'] = $arFiles->get($arValues);
        } else {
            $arProperty['VALUE'] = [];

            foreach ($arValues as $arValue) {
                $arValue = $arFiles->get($arValue);

                if (!empty($arValue))
                    $arProperty['VALUE'][] = $arValue;
            }

            unset($arValue);
        }

        unset($arValues);
    }
}

unset($arProperty);
unset($arFiles);