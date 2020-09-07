<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arFilter = [
    'IBLOCK_ID' => $arParams['BLOCKS_IBLOCK_ID'],
    'ACTIVE' => 'Y'
];

if (!empty($arParams['BLOCKS_IBLOCK_TYPE']))
    $arFilter['IBLOCK_TYPE'] = $arParams['BLOCKS_IBLOCK_TYPE'];

$arParams['BLOCKS_SECTIONS'] = array_filter($arParams['BLOCKS_SECTIONS']);

if (!empty($arParams['BLOCKS_SECTIONS'])) {
    if ($arParams['BLOCKS_MODE'] === 'Y')
        $arFilter['SECTION_CODE'] = $arParams['BLOCKS_SECTIONS'];
    else
        $arFilter['SECTION_ID'] = $arParams['BLOCKS_SECTIONS'];
}

$arSort = [
    $arParams['SORT_BY'] => $arParams['ORDER_BY']
];

$arCount = [
    'nPageSize' => ArrayHelper::fromRange(
        $arResult['VISUAL']['BLOCKS']['POSITION'] === 'right' ? [2, 1, 3] : [3, 1, 2, 4],
        $arParams['BLOCKS_ELEMENTS_COUNT']
    )
];

$arBlocks = [];
$rsBlocks = CIBlockElement::GetList(
    $arSort,
    $arFilter,
    false,
    $arCount
);

while ($rsBlock = $rsBlocks->GetNextElement()) {
    $arBlock = $rsBlock->GetFields();
    $arBlock['PROPERTIES'] = $rsBlock->GetProperties();
    $arBlocks[$arBlock['ID']] = $arBlock;
}

unset($rsBlocks, $arBlock, $rsBlock, $arSort, $arFilter, $arCount);

if (!empty($arBlocks)) {
    $arBlockImages = [];

    foreach ($arBlocks as &$arBlock) {
        $arLink = [
            'VALUE' => null,
            'BLANK' => false
        ];

        if (!empty($arParams['BLOCKS_PROPERTY_LINK'])) {
            $arProperty = ArrayHelper::getValue($arBlock, [
                'PROPERTIES',
                $arParams['BLOCKS_PROPERTY_LINK']
            ]);

            if (!empty($arProperty['VALUE'])) {
                $arProperty = CIBlockFormatProperties::GetDisplayValue(
                    $arBlock,
                    $arProperty,
                    false
                );

                if (!empty($arProperty['DISPLAY_VALUE'])) {
                    if (Type::isArray($arProperty['DISPLAY_VALUE']))
                        $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                    $arLink['VALUE'] = StringHelper::replaceMacros(
                        $arProperty['DISPLAY_VALUE'],
                        ['SITE_DIR' => SITE_DIR]
                    );
                }
            }
        }

        if (!empty($arParams['BLOCKS_PROPERTY_LINK_BLANK'])) {
            $arProperty = ArrayHelper::getValue($arBlock, [
                'PROPERTIES',
                $arParams['BLOCKS_PROPERTY_LINK_BLANK'],
                'VALUE_XML_ID'
            ]);

            if (!empty($arProperty))
                $arLink['BLANK'] = true;
        }

        $arBlock['DATA']['LINK'] = $arLink;
        unset($arLink);

        $sPicture = $arBlock['PREVIEW_PICTURE'];

        if (empty($arBlock['PREVIEW_PICTURE']))
            $sPicture = $arBlock['DETAIL_PICTURE'];

        if (!empty($sPicture))
            $arBlockImages[] = $sPicture;
    }

    unset($arBlock, $sPicture);

    if (!empty($arBlockImages)) {
        $arBlockImages = Arrays::fromDBResult(CFile::GetList([], [
            '@ID' => implode(',', $arBlockImages)
        ]))->indexBy('ID');

        if (!$arBlockImages->isEmpty()) {
            foreach ($arBlocks as &$arBlock) {
                if (!empty($arBlock['PREVIEW_PICTURE']))
                    if ($arBlockImages->exists($arBlock['PREVIEW_PICTURE'])) {
                        $arBlock['PREVIEW_PICTURE'] = $arBlockImages->get($arBlock['PREVIEW_PICTURE']);
                        $arBlock['PREVIEW_PICTURE']['SRC'] = CFile::GetFileSRC($arBlock['PREVIEW_PICTURE']);
                    }

                if (!empty($arBlock['DETAIL_PICTURE']))
                    if ($arBlockImages->exists($arBlock['DETAIL_PICTURE'])) {
                        $arBlock['DETAIL_PICTURE'] = $arBlockImages->get($arBlock['DETAIL_PICTURE']);
                        $arBlock['DETAIL_PICTURE']['SRC'] = CFile::GetFileSRC($arBlock['DETAIL_PICTURE']);
                    }
            }

            unset($arBlock);
        }
    }

    unset($arBlockImages);
}

$arResult['BLOCKS'] = $arBlocks;

unset($arBlocks);