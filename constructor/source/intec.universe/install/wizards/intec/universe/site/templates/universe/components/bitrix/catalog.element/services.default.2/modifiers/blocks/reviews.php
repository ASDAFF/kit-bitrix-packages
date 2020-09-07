<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!empty($arParams['PROPERTY_REVIEWS'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_REVIEWS']
    ]);

    if (!empty($arProperty['VALUE']) && !empty($arProperty['LINK_IBLOCK_ID'])) {
        if ($arProperty['MULTIPLE'] !== 'Y')
            $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

        $arResult['DATA']['REVIEWS'] = [
            'NAME' => $arProperty['NAME'],
            'ID' => $arProperty['VALUE']
        ];
    }

    unset($arProperty);
}