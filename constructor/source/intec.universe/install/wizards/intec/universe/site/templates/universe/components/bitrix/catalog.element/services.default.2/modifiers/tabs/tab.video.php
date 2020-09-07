<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!empty($arParams['PROPERTY_TAB_VIDEO']))
    $arParams['PROPERTY_TAB_VIDEO'] = array_filter($arParams['PROPERTY_TAB_VIDEO']);
else
    $arParams['PROPERTY_TAB_VIDEO'] = [];

if ($arResult['VIDEO']['USE'] && !empty($arParams['PROPERTY_TAB_VIDEO'])) {

    if (empty($arParams['TAB_VIDEO_NAME']))
        $arParams['TAB_VIDEO_NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_VIDEO_NAME_DEFAULT');

    $arResult['DATA']['TAB']['VIDEO'] = [
        'SHOW' => false,
        'NAME' => $arParams['TAB_VIDEO_NAME'],
        'VALUE' => []
    ];

    foreach ($arParams['PROPERTY_TAB_VIDEO'] as $sProperty) {
        $arProperty = ArrayHelper::getValue($arResult, [
            'PROPERTIES',
            $sProperty
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] !== 'Y')
                $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

            $arResult['DATA']['TAB']['VIDEO']['VALUE'][] = [
                'NAME' => $arProperty['NAME'],
                'ID' => $arProperty['VALUE']
            ];
        }
    }

    unset($sProperty, $arProperty);

    if ($arResult['VISUAL']['TAB']['VIDEO']['SHOW'] && !empty($arResult['DATA']['TAB']['VIDEO']['VALUE']))
        $arResult['DATA']['TAB']['VIDEO']['SHOW'] = true;
}