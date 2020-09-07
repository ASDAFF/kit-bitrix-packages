<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arResult['ARTICLE'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_ARTICLE'],
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

$arResult['DOCUMENTS'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_DOCUMENTS'],
    'VALUE'
]);

if (!Type::isArray($arResult['DOCUMENTS']))
    $arResult['DOCUMENTS'] = [];

if (!empty($arResult['DOCUMENTS'])) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arResult['DOCUMENTS'])
    ]));

    $arResult['DOCUMENTS'] = [];

    if (!$arFiles->isEmpty())
        foreach ($arFiles as $arFile) {
            $arFile['SRC'] = CFile::GetFileSRC($arFile);
            $arResult['DOCUMENTS'][] = $arFile;
        }

    unset($arFile);
    unset($arFiles);
}

$arResult['VIDEO'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_VIDEO'],
    'VALUE'
]);

$arResult['ASSOCIATED'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_ASSOCIATED'],
    'VALUE'
]);

$arResult['RECOMMENDED'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_RECOMMENDED'],
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
            $arParams['OFFERS_PROPERTY_ARTICLE'],
            'VALUE'
        ]);

        unset($arOffer);
    }
}