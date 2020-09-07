<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!empty($arParams['PROPERTY_ADDITIONAL_TEXT'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_ADDITIONAL_TEXT']
    ]);

    if (!empty($arProperty['VALUE'])) {
        $arProperty = CIBlockFormatProperties::GetDisplayValue(
            $arResult,
            $arProperty,
            false
        );

        if ($arProperty['MULTIPLE'] === 'Y')
            $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

        $arResult['DATA']['ADDITIONAL_TEXT'] = [
            'VALUE' => $arProperty['DISPLAY_VALUE']
        ];
    }

    if (!empty($arResult['DATA']['ADDITIONAL_TEXT']['VALUE']) && !empty($arParams['PROPERTY_ADDITIONAL_TEXT_PICTURE'])) {
        $arProperty = ArrayHelper::getValue($arResult, [
            'PROPERTIES',
            $arParams['PROPERTY_ADDITIONAL_TEXT_PICTURE']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

            if (Type::isArray($arProperty['VALUE']) && !empty($arProperty['VALUE']['SRC']))
                $arResult['DATA']['ADDITIONAL_TEXT']['PICTURE'] = $arProperty['VALUE'];
        }
    }

    unset($arProperty);
}