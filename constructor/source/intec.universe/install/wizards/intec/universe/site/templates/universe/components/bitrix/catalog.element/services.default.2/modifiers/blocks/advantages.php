<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!empty($arParams['PROPERTY_ADVANTAGES'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_ADVANTAGES']
    ]);

    if (!empty($arProperty['VALUE']) && !empty($arProperty['LINK_IBLOCK_ID'])) {
        if ($arProperty['MULTIPLE'] !== 'Y')
            $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

        $arResult['DATA']['ADVANTAGES'] = [
            'NAME' => $arProperty['NAME'],
            'IBLOCK' => $arProperty['LINK_IBLOCK_ID'],
            'ID' => $arProperty['VALUE']
        ];
    }

    unset($arProperty);
}