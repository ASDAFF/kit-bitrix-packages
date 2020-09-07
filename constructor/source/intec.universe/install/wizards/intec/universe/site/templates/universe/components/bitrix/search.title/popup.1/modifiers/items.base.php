<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arItems
 * @var array $arItemsId
 */

use Bitrix\Catalog\ProductTable;

$arSelect = [
    'ID',
    'IBLOCK_ID',
    'PREVIEW_TEXT',
    'PREVIEW_PICTURE',
    'DETAIL_PICTURE',
    'IBLOCK_SECTION_ID'
];

$arFilter = [
    'ID' => $arItemsId,
    'IBLOCK_LID' => SITE_ID,
    'IBLOCK_ACTIVE' => 'Y',
    'ACTIVE_DATE' => 'Y',
    'ACTIVE' => 'Y',
    'CHECK_PERMISSIONS' => 'Y',
    'MIN_PERMISSION' => 'R'
];

foreach ($arResult['PRICES'] as $arPrice) {
    if (!$arPrice['CAN_VIEW'] && !$arPrice['CAN_BUY'])
        continue;

    $arSelect[] = $arPrice['SELECT'];
    $arFilter['CATALOG_SHOP_QUANTITY_'.$arPrice['ID']] = 1;
}

$rsItems = CIBlockElement::GetList(
    ['SORT' => 'ASC'],
    $arFilter,
    false,
    false,
    $arSelect
);

while ($rsItem = $rsItems->GetNextElement()) {
    $arItem = $rsItem->GetFields();
    //$arItem['PROPERTIES'] = $rsItem->GetProperties();
    $arItem['PRICES'] = [];

    if ($arItem['CATALOG_TYPE'] != ProductTable::TYPE_SKU)
        $arItem['PRICES'] = CIBlockPriceTools::GetItemPrices(
            $arItem['IBLOCK_ID'],
            $arResult['PRICES'],
            $arItem,
            $arParams['PRICE_VAT_INCLUDE'] == 'Y',
            $arResult['CURRENCY']['CONVERT'] ? [
                'CURRENCY_ID' => $arResult['CURRENCY']['VALUE']['CURRENCY']
            ] : []
        );

    $arItems[$arItem['ID']] = $arItem;
}