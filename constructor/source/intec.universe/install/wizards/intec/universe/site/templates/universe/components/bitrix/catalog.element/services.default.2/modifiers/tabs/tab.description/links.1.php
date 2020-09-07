<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arId = [];

if (!empty($arParams['PROPERTY_TAB_DESCRIPTION_LINKS_1'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_TAB_DESCRIPTION_LINKS_1']
    ]);

    if (!empty($arProperty['VALUE']) && !empty($arProperty['LINK_IBLOCK_ID'])) {
        if ($arProperty['MULTIPLE'] !== 'Y')
            $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

        $arId = [
            'NAME' => $arProperty['NAME'],
            'ID' => $arProperty['VALUE']
        ];
    }

    if (!empty($arId))
        $arResult['DATA']['TAB']['DESCRIPTION']['VALUE']['LINKS_1'] = $arId;

    unset($arProperty);
}

unset($arId);