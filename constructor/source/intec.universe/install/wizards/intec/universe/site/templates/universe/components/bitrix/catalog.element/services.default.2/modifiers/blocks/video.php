<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if ($arResult['VIDEO']['USE'] && !empty($arParams['PROPERTY_VIDEO'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_VIDEO']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] !== 'Y')
            $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

        $arResult['DATA']['VIDEO'] = [
            'NAME' => $arProperty['NAME'],
            'ID' => $arProperty['VALUE']
        ];
    }

    unset($arProperty);
}