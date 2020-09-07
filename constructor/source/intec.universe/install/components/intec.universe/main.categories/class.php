<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

class IntecCategoriesComponent extends IBlockElements
{
    public function onPrepareComponentParams($arParams)
    {
        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'IBLOCK_TYPE' => null,
            'IBLOCK_ID' => null,
            'SECTIONS_MODE' => 'id',
            'SECTIONS' => [],
            'ELEMENTS_COUNT' => null,
            'LINK_MODE' => 'property',
            'PROPERTY_LINK' => null,
            'FILTER' => null,
            'HEADER_SHOW' => 'N',
            'HEADER_POSITION' => null,
            'HEADER_TEXT' => null,
            '~HEADER_TEXT' => null,
            'DESCRIPTION_SHOW' => 'N',
            'DESCRIPTION_POSITION' => null,
            'DESCRIPTION_TEXT' => null,
            '~DESCRIPTION_TEXT' => null,
            'LIST_PAGE_URL' => null,
            'SECTION_URL' => null,
            'DETAIL_URL' => null,
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
                if ($arParams['LINK_MODE'] === 'component') {
                    $this->setUrlTemplates(
                        $arParams['LIST_PAGE_URL'],
                        $arParams['SECTION_URL'],
                        $arParams['DETAIL_URL']
                    );
                }

                $arSort = [];
                $arFilter = $arParams['FILTER'];

                if ($arParams['SECTIONS_MODE'] === 'code') {
                    $this->setSectionsCode($arParams['SECTIONS']);
                } else {
                    $this->setSectionsId($arParams['SECTIONS']);
                }

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

                if ($arParams['LINK_MODE'] === 'property' && !empty($arResult['ITEMS'])) {
                    foreach ($arResult['ITEMS'] as &$arItem) {
                        $sLink = null;

                        if (!empty($arParams['PROPERTY_LINK'])) {
                            $arProperty = ArrayHelper::getValue($arItem, [
                                'PROPERTIES',
                                $arParams['PROPERTY_LINK']
                            ]);

                            if (Type::isArray($arProperty['VALUE']))
                                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

                            if (!empty($arProperty['VALUE']))
                                $sLink = StringHelper::replaceMacros($arProperty['VALUE'], [
                                    'SITE_DIR' => SITE_DIR
                                ]);

                            unset($arProperty);
                        }

                        $arItem['DETAIL_PAGE_URL'] = $sLink;
                    }

                    unset($arItem, $sLink);
                }
            }

            $this->arResult = $arResult;

            unset($arIBlock, $arParams, $arResult);

            $this->includeComponentTemplate();
        }

        return null;
    }
}