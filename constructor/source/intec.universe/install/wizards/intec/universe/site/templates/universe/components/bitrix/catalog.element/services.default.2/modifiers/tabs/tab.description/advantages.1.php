<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arId = [];

if (!empty($arParams['PROPERTY_TAB_DESCRIPTION_ADVANTAGES_1'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_TAB_DESCRIPTION_ADVANTAGES_1']
    ]);

    if (!empty($arProperty['VALUE']) && !empty($arProperty['LINK_IBLOCK_ID'])) {
        if ($arProperty['MULTIPLE'] !== 'Y')
            $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

        $arId = [
            'IBLOCK' => [
                'ID' => $arProperty['LINK_IBLOCK_ID'],
            ],
            'ID' => $arProperty['VALUE']
        ];
    }

    if (!empty($arId))
        $arResult['DATA']['TAB']['DESCRIPTION']['VALUE']['ADVANTAGES_1'] = $arId;

    unset($arProperty);
}

unset($arId);