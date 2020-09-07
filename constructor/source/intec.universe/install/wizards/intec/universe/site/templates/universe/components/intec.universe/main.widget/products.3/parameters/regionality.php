<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\regionality\platform\iblock\properties\RegionProperty;

/**
 * @var array $arCurrentValues
 * @var array $arParametersCommon
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 * @var Arrays $arProperties
 */

if (Loader::includeModule('intec.regionality')) {
    $arTemplateParameters['REGIONALITY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_REGIONALITY_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['REGIONALITY_USE'] === 'Y') {
        if (!empty($arIBlock)) {
            $arTemplateParameters['REGIONALITY_FILTER_USE'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_REGIONALITY_FILTER_USE'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N',
                'REFRESH' => 'Y'
            ];

            if ($arCurrentValues['REGIONALITY_FILTER_USE'] === 'Y') {
                $arTemplateParameters['REGIONALITY_FILTER_PROPERTY'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_REGIONALITY_FILTER_PROPERTY'),
                    'TYPE' => 'LIST',
                    'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
                        if ($arProperty['PROPERTY_TYPE'] === RegionProperty::PROPERTY_TYPE && $arProperty['USER_TYPE'] === RegionProperty::USER_TYPE)
                            return [
                                'key' => $arProperty['CODE'],
                                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                            ];

                        return ['skip' => true];
                    }),
                    'ADDITIONAL_VALUES' => 'Y'
                ];

                $arTemplateParameters['REGIONALITY_FILTER_STRICT'] = [
                    'PARENT' => 'BASE',
                    'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_REGIONALITY_FILTER_STRICT'),
                    'TYPE' => 'CHECKBOX',
                    'DEFAULT' => 'N'
                ];
            }
        }

        if ($bBase || $bLite) {
            $arTemplateParameters['REGIONALITY_PRICES_TYPES_USE'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_REGIONALITY_PRICES_TYPES_USE'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N',
                'REFRESH' => 'Y'
            ];

            if ($arCurrentValues['REGIONALITY_PRICES_TYPES_USE'] === 'Y')
                $arTemplateParameters['PRICE_CODE'] = ['HIDDEN' => 'Y'];
        }
    }
}