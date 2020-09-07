<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class IntecMainBrandsComponent extends IBlockElements
{
    /**
     * @inheritdoc
     */
    public function onPrepareComponentParams($arParams) {
        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'IBLOCK_TYPE' => null,
            'IBLOCK_ID' => null,
            'SECTIONS_MODE' => 'id',
            'SECTIONS' => [],
            'ELEMENTS_COUNT' => null,
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
            'DETAIL_URL' => null,
            'SORT_BY' => 'SORT',
            'ORDER_BY' => 'ASC',
            'FILTER' => [],
        ], $arParams);

        if (!Type::isArray($arParams['SECTIONS']))
            $arParams['SECTIONS'] = [];

        if (!Type::isArray($arParams['FILTER']))
            $arParams['FILTER'] = [];

        return $arParams;
    }

    /**
     * @inheritdoc
     */
    public function executeComponent() {
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
                'VISUAL' => [],
                'ITEMS' => []
            ];

            if (empty($arResult['BLOCKS']['HEADER']['TEXT']))
                $arResult['BLOCKS']['HEADER']['SHOW'] = false;

            if (empty($arResult['BLOCKS']['DESCRIPTION']['TEXT']))
                $arResult['BLOCKS']['DESCRIPTION']['SHOW'] = false;

            $this->setIBlockType($arParams['IBLOCK_TYPE']);
            $this->setIBlockId($arParams['IBLOCK_ID']);

            if ($arParams['SECTIONS_MODE'] === 'code') {
                $this->setSectionsCode($arParams['SECTIONS']);
            } else {
                $this->setSectionsId($arParams['SECTIONS']);
            }

            $arIBlock = $this->getIBlock();

            if (!empty($arIBlock) && $arIBlock['ACTIVE'] === 'Y') {
                $this->setUrlTemplates(
                    $arParams['LIST_PAGE_URL'],
                    $arParams['SECTION_URL'],
                    $arParams['DETAIL_URL']
                );

                $arSort = [];

                if (!empty($arParams['SORT_BY']) && !empty($arParams['ORDER_BY'])) {
                    $arSort = [
                        $arParams['SORT_BY'] => $arParams['ORDER_BY']
                    ];
                }

                $arFilter = ArrayHelper::merge([
                    'IBLOCK_LID' => $this->getSiteId(),
                    'ACTIVE' => 'Y',
                    'ACTIVE_DATE' => 'Y',
                    'CHECK_PERMISSIONS' => 'Y',
                    'MIN_PERMISSION' => 'R'
                ], $arParams['FILTER']);

                $arResult['ITEMS'] = $this->getElements($arSort, $arFilter, $arParams['ELEMENTS_COUNT']);

                unset($arSort, $arFilter);
            }

            $this->arResult = $arResult;

            unset($arResult, $arParams, $arIBlock);

            $this->includeComponentTemplate();
        }

        return null;
    }
}