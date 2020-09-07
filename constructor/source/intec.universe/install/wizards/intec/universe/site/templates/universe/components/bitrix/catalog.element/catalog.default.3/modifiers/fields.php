<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCodes
 * @var array $arResult
 * @var array $arParams
 * @var array $arVisual
 */

$arResult['ARTICLE'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arCodes['ARTICLE'],
    'VALUE'
]);

$arResult['BRAND'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_BRAND'],
    'VALUE'
]);

if (!empty($arResult['BRAND'])) {
    $arResult['BRAND'] = CIBlockElement::GetByID($arResult['BRAND'])->GetNext();
    $arResult['BRAND']['PICTURE'] = null;

    if (!empty($arResult['BRAND']['PREVIEW_PICTURE'])) {
        $arResult['BRAND']['PREVIEW_PICTURE'] = CFile::GetFileArray($arResult['BRAND']['PREVIEW_PICTURE']);
        $arResult['BRAND']['PICTURE'] = $arResult['BRAND']['PREVIEW_PICTURE'];
    } else if (!empty($arResult['BRAND']['DETAIL_PICTURE'])) {
        $arResult['BRAND']['DETAIL_PICTURE'] = CFile::GetFileArray($arResult['BRAND']['DETAIL_PICTURE']);

        if (empty($arResult['BRAND']['PICTURE']))
            $arResult['BRAND']['PICTURE'] = $arResult['BRAND']['DETAIL_PICTURE'];
    }
}

if (empty($arResult['BRAND']))
    $arResult['BRAND'] = null;

$arResult['ADDITIONAL'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arCodes['ADDITIONAL'],
    'VALUE'
]);

$arResult['ASSOCIATED'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arCodes['ASSOCIATED'],
    'VALUE'
]);

$arResult['RECOMMENDED'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arCodes['RECOMMENDED'],
    'VALUE'
]);

$arResult['ADVANTAGES'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_ADVANTAGES'],
    'VALUE'
]);

if (!empty($arResult['OFFERS'])) {
    foreach ($arResult['OFFERS'] as &$arOffer) {
        $arOffer['ARTICLE'] = ArrayHelper::getValue($arOffer, [
            'PROPERTIES',
            $arCodes['OFFERS']['ARTICLE'],
            'VALUE'
        ]);

        unset($arOffer);
    }
}