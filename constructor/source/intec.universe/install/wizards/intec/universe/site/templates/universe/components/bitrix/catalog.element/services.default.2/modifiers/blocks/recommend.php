<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!empty($arParams['PROPERTY_RECOMMEND'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_RECOMMEND']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] !== 'Y')
            $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

        $arResult['DATA']['RECOMMEND'] = [
            'NAME' => $arProperty['NAME'],
            'DESCRIPTION' => Html::stripTags(
                Html::decode($arParams['~RECOMMEND_DESCRIPTION']),
                ['br']
            ),
            'IBLOCK' => $arParams['IBLOCK_ID'],
            'ID' => $arProperty['VALUE'],
            'PROPERTY' => [
                'PRICE' => $arParams['PROPERTY_PRICE'],
                'PRICE_OLD' => $arParams['PROPERTY_PRICE_OLD']
            ]
        ];
    }

    unset($arProperty);
}