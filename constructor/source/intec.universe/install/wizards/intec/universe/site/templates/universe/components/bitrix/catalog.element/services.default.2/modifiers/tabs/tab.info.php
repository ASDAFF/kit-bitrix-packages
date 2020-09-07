<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!empty($arParams['PROPERTY_TAB_INFO']))
    $arParams['PROPERTY_TAB_INFO'] = array_filter($arParams['PROPERTY_TAB_INFO']);
else
    $arParams['PROPERTY_TAB_INFO'] = [];

if (!empty($arParams['PROPERTY_TAB_INFO'])) {

    if (empty($arParams['TAB_VIDEO_NAME']))
        $arParams['TAB_VIDEO_NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_INFO_NAME_DEFAULT');

    $arResult['DATA']['TAB']['INFO'] = [
        'SHOW' => false,
        'NAME' => $arParams['TAB_INFO_NAME'],
        'VALUE' => []
    ];

    foreach ($arParams['PROPERTY_TAB_INFO'] as $sProperty) {
        $arProperty = ArrayHelper::getValue($arResult, [
            'PROPERTIES',
            $sProperty
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arResult,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if ($arProperty['MULTIPLE'] === 'Y')
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arResult['DATA']['TAB']['INFO']['VALUE'][] = [
                    'NAME' => $arProperty['NAME'],
                    'VALUE' => $arProperty['DISPLAY_VALUE']
                ];
            }
        }
    }

    unset($sProperty, $arProperty);

    if ($arResult['VISUAL']['TAB']['INFO']['SHOW'] && !empty($arResult['DATA']['TAB']['INFO']['VALUE']))
        $arResult['DATA']['TAB']['INFO']['SHOW'] = true;
}