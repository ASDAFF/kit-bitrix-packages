<?php

use intec\core\bitrix\components\IBlockSections;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class IntecMainSectionsComponent extends IBlockSections
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
            'ELEMENTS_COUNT' => null,
            'SECTIONS_MODE' => 'id',
            'SECTIONS' => null,
            'FILTER' => null,
            'HEADER_TEXT' => null,
            '~HEADER_TEXT' => null,
            'HEADER_SHOW' => 'N',
            'HEADER_POSITION' => null,
            'DESCRIPTION_TEXT' => null,
            '~DESCRIPTION_TEXT' => null,
            'DESCRIPTION_SHOW' => 'N',
            'DESCRIPTION_POSITION' => null,
            'LIST_PAGE_URL' => null,
            'SECTION_URL' => null,
            'QUANTITY' => 'N',
            'DEPTH' => 1,
            'SORT_BY' => 'sort',
            'ORDER_BY' => 'asc'
        ], $arParams);

        return $arParams;
    }

    /**
     * @inheritdoc
     */
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
                'DEPTH' => Type::toInteger($arParams['DEPTH']),
                'SECTIONS' => [],
                'VISUAL' => [
                    'QUANTITY' => [
                        'SHOW' => $arParams['QUANTITY'] === 'Y'
                    ]
                ]
            ];

            if (empty($arResult['BLOCKS']['HEADER']['TEXT']))
                $arResult['BLOCKS']['HEADER']['SHOW'] = false;

            if (empty($arResult['BLOCKS']['DESCRIPTION']['TEXT']))
                $arResult['BLOCKS']['DESCRIPTION']['SHOW'] = false;

            if ($arResult['DEPTH'] < 1)
                $arResult['DEPTH'] = 1;

            $this->setIBlockType($arParams['IBLOCK_TYPE']);
            $this->setIBlockId($arParams['IBLOCK_ID']);

            $arIBlock = $this->getIBlock();

            if (!empty($arIBlock) && $arIBlock['ACTIVE'] === 'Y') {
                if ($arParams['SECTIONS_MODE'] === 'code') {
                    $this->setSectionsCode($arParams['SECTIONS']);
                } else {
                    $this->setSectionsId($arParams['SECTIONS']);
                }

                $this->setUrlTemplates(
                    $arParams['LIST_PAGE_URL'],
                    $arParams['SECTION_URL']
                );

                $arSort = [];
                $arFilter = $arParams['FILTER'];

                if (!empty($arParams['SORT_BY']) && !empty($arParams['ORDER_BY']))
                    $arSort = [$arParams['SORT_BY'] => $arParams['ORDER_BY']];

                if (!Type::isArray($arFilter))
                    $arFilter = [];

                $arFilter = ArrayHelper::merge([
                    'GLOBAL_ACTIVE' => 'Y',
                    'CNT_ACTIVE' => 'Y',
                    'CHECK_PERMISSIONS' => 'Y',
                    'MIN_PERMISSION' => 'R'
                ], $arFilter);

                if (empty($this->getSectionsId()) && empty($this->getSectionsCode()))
                    $arFilter['SECTION_ID'] = false;

                $arSections = $this->getSections(
                    $arSort,
                    $arFilter,
                    $arParams['ELEMENTS_COUNT'],
                    null,
                    $arResult['VISUAL']['QUANTITY']['SHOW']
                );

                if ($arResult['DEPTH'] > 1) {
                    $this->setSectionsId(null);
                    $this->setSectionsCode(null);

                    foreach ($arSections as &$arSection) {
                        $arSection['SECTIONS'] = [];
                        $arList = [];
                        $arFilter = [
                            '>=LEFT_MARGIN' => $arSection['LEFT_MARGIN'] + 1,
                            '<=RIGHT_MARGIN' => $arSection['RIGHT_MARGIN'] - 1,
                            '<=DEPTH_LEVEL' => $arSection['DEPTH_LEVEL'] + $arResult['DEPTH'] - 1,
                            'CNT_ACTIVE' => 'Y'
                        ];

                        $arChildren = Arrays::from($this->getSections([
                            'SORT' => 'LEFT_MARGIN'
                        ], $arFilter, null, null, $arResult['VISUAL']['QUANTITY']['SHOW']))->indexBy('ID');

                        foreach ($arChildren as $arChild) {
                            if ($arChild['IBLOCK_SECTION_ID'] == $arSection['ID']) {
                                $arSection['SECTIONS'][$arChild['ID']] = $arChild;
                                $arList[$arChild['ID']] = &$arSection['SECTIONS'][$arChild['ID']];
                            } else if (!empty($arList[$arChild['IBLOCK_SECTION_ID']])) {
                                $arList[$arChild['IBLOCK_SECTION_ID']]['SECTIONS'][$arChild['ID']] = $arChild;
                                $arList[$arChild['ID']] = &$arList[$arChild['IBLOCK_SECTION_ID']]['SECTIONS'][$arChild['ID']];
                            }
                        }

                        unset($arChild);
                        unset($arList);
                        unset($arSection);
                    }
                }

                $arResult['SECTIONS'] = $arSections;

                unset($arSections);
            }

            $this->arResult = $arResult;

            unset($arParams);
            unset($arResult);

            $this->includeComponentTemplate();
        }

        return null;
    }
}