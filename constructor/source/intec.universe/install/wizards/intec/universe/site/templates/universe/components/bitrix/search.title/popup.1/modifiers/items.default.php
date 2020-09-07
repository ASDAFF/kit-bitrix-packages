<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arItems
 * @var array $arItemsId
 */

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

$rsItems = CIBlockElement::GetList(
    ['SORT' => 'ASC'],
    $arFilter,
    false,
    false,
    $arSelect
);

while ($rsItem = $rsItems->GetNextElement()) {
    $arItem = $rsItem->GetFields();
    $arItems[$arItem['ID']] = $arItem;
}