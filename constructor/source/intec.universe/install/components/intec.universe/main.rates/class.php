<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class IntecMainRatesComponent extends IBlockElements
{
    /**
     * @inheritdoc
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'IBLOCK_TYPE' => null,
            'IBLOCK_ID' => null,
            'SECTIONS' => [],
            'ELEMENTS_COUNT' => null,
            'FILTER' => null,
            'PROPERTY_LIST' => [],
            'HEADER_TEXT' => null,
            '~HEADER_TEXT' => null,
            'HEADER_SHOW' => 'N',
            'HEADER_POSITION' => null,
            'DESCRIPTION_TEXT' => null,
            '~DESCRIPTION_TEXT' => null,
            'DESCRIPTION_SHOW' => 'N',
            'DESCRIPTION_POSITION' => null,
            'SORT_BY' => 'sort',
            'ORDER_BY' => 'asc'
        ], $arParams);

        return $arParams;
    }

    public function executeComponent()
    {
        global $USER;

        if ($this->startResultCache(false, $USER->GetGroups())) {
            $arParams = $this->arParams;
            $arResult = [
                'BLOCKS' => [
                    'HEADER' => [
                        'SHOW' => $arParams['HEADER_SHOW'] === 'Y',
                        'TEXT' => $arParams['~HEADER_TEXT'],
                        'POSITION' => ArrayHelper::fromRange([
                            'left',
                            'center',
                            'right'
                        ], $arParams['HEADER_POSITION'])
                    ],
                    'DESCRIPTION' => [
                        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
                        'TEXT' => $arParams['~DESCRIPTION_TEXT'],
                        'POSITION' => ArrayHelper::fromRange([
                            'left',
                            'center',
                            'right'
                        ], $arParams['DESCRIPTION_POSITION'])
                    ]
                ],
                'ITEMS' => [],
                'SECTIONS' => [],
                'VISUAL' => []
            ];

            if (empty($arResult['BLOCKS']['HEADER']['TEXT']))
                $arResult['BLOCKS']['HEADER']['SHOW'] = false;

            if (empty($arResult['BLOCKS']['DESCRIPTION']['TEXT']))
                $arResult['BLOCKS']['DESCRIPTION']['SHOW'] = false;

            $this->setIBlockType($arParams['IBLOCK_TYPE']);
            $this->setIBlockId($arParams['IBLOCK_ID']);

            $arIBlock = $this->getIBlock();

            if (!empty($arIBlock) && $arIBlock['ACTIVE'] === 'Y') {
                $this->setSectionsId($arParams['SECTIONS']);

                $arSort = [];
                $arFilter = $arParams['FILTER'];

                if (!empty($arParams['SORT_BY']) && !empty($arParams['ORDER_BY']))
                    $arSort = [$arParams['SORT_BY'] => $arParams['ORDER_BY']];

                if (!Type::isArray($arFilter))
                    $arFilter = [];

                $arFilter = ArrayHelper::merge([
                    'IBLOCK_LID' => $this->getSiteId(),
                    'ACTIVE' => 'Y',
                    'ACTIVE_DATE' => 'Y',
                    'CHECK_PERMISSIONS' => 'Y',
                    'MIN_PERMISSION' => 'R'
                ], $arFilter);

                $arResult['ITEMS'] = $this->getElements($arSort, $arFilter, $arParams['ELEMENTS_COUNT']);
                $arSections = [];

                foreach ($arResult['ITEMS'] as &$arItem) {
                    $arItem['DISPLAY_PROPERTIES'] = [];

                    if (!empty($arParams['PROPERTY_LIST'])) {
                        foreach ($arParams['PROPERTY_LIST'] as $sProperty) {
                            $arProperty = ArrayHelper::getValue($arItem, [
                                'PROPERTIES',
                                $sProperty
                            ]);

                            if (empty($arProperty))
                                continue;

                            $arItem['DISPLAY_PROPERTIES'][$arProperty['CODE']] = CIBlockFormatProperties::GetDisplayValue(
                                $arItem,
                                $arProperty,
                                'mainRates'
                            );
                        }
                    }

                }

                unset($arItem);

                foreach ($arResult['ITEMS'] as $arItem)
                    if (!empty($arItem['IBLOCK_SECTION_ID']))
                        if (!ArrayHelper::isIn($arItem['IBLOCK_SECTION_ID'], $arSections))
                            $arSections[] = $arItem['IBLOCK_SECTION_ID'];

                if (!empty($arSections)) {
                    $this->setSectionsId($arSections);
                    $this->setSectionsCode(null);
                    $arResult['SECTIONS'] = $this->getSections([
                        'SORT' => 'ASC'
                    ]);

                    foreach ($arResult['SECTIONS'] as &$arSection)
                        $arSection['ITEMS'] = [];

                    unset($arSection);

                    foreach ($arResult['ITEMS'] as &$arItem)
                        if (!empty($arItem['IBLOCK_SECTION_ID']))
                            if (!empty($arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]))
                                $arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['ITEMS'][$arItem['ID']] = &$arItem;

                    unset($arItem);
                }

                unset($arSections);
            }

            $this->arResult = $arResult;

            unset($arIBlock);
            unset($arParams);
            unset($arResult);

            $this->includeComponentTemplate();
        }

        return null;
    }
}