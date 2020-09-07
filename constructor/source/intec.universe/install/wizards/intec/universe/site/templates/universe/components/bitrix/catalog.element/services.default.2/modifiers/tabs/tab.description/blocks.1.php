<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arId = [];

if (!empty($arParams['PROPERTY_TAB_DESCRIPTION_BLOCKS_1'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_TAB_DESCRIPTION_BLOCKS_1']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] !== 'Y')
            $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

        if (Type::isArray($arProperty['VALUE']))
            $arId = [
                'ID' => $arProperty['VALUE']
            ];
    }

    if (!empty($arId))
        $arResult['DATA']['TAB']['DESCRIPTION']['VALUE']['BLOCKS_1'] = $arId;

    unset($arProperty);
}

unset($arId);