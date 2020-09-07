<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class IntecMainSliderComponent extends IBlockElements
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
            'SECTIONS_MODE' => 'N',
            'SECTIONS' => [],
            'PROPERTY_HEADER' => null,
            'PROPERTY_DESCRIPTION' => null,
            'HEADER_SHOW' => 'N',
            'DESCRIPTION_SHOW' => 'N',
            'FILTER' => null,
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
                'ITEMS' => [],
                'VISUAL' => []
            ];

            $this->setIBlockType($arParams['IBLOCK_TYPE']);
            $this->setIBlockId($arParams['IBLOCK_ID']);

            $arIBlock = $this->getIBlock();

            if (!empty($arIBlock) && $arIBlock['ACTIVE'] === 'Y') {
                if ($arParams['SECTIONS_MODE'] === 'code')
                    $this->setSectionsCode($arParams['SECTIONS']);
                else
                    $this->setSectionsId($arParams['SECTIONS']);

                $this->setUrlTemplates(
                    $arParams['LIST_PAGE_URL'],
                    $arParams['SECTION_URL'],
                    $arParams['DETAIL_URL']
                );

                $arSort = [];

                if (!empty($arParams['SORT_BY']) && !empty($arParams['ORDER_BY']))
                    $arSort = [$arParams['SORT_BY'] => $arParams['ORDER_BY']];

                $arFilter = $arParams['FILTER'];

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

                unset($arSort, $arFilter);
            }

            $this->arResult = $arResult;

            unset($arParams, $arResult, $arIBlock);

            $this->includeComponentTemplate();
        }

        return null;
    }
}