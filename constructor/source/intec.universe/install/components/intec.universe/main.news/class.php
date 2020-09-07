<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class IntecMainNewsComponent extends IBlockElements
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
            'FILTER' => null,
            'HEADER_BLOCK_TEXT' => null,
            '~HEADER_BLOCK_TEXT' => null,
            'HEADER_BLOCK_SHOW' => 'N',
            'HEADER_BLOCK_POSITION' => null,
            'DESCRIPTION_BLOCK_TEXT' => null,
            '~DESCRIPTION_BLOCK_TEXT' => null,
            'DESCRIPTION_BLOCK_SHOW' => 'N',
            'DESCRIPTION_BLOCK_POSITION' => null,
            'DATE_SHOW' => 'N',
            'DATE_FORMAT' => null,
            'LIST_PAGE_URL' => null,
            'SECTION_URL' => null,
            'DETAIL_URL' => null,
            'SORT_BY' => 'sort',
            'ORDER_BY' => 'asc'
        ], $arParams);

        return $arParams;
    }

    /**
     * @inheritdoc
     */
    function executeComponent()
    {
        global $USER;

        if ($this->startResultCache(false, $USER->GetGroups())) {
            $arParams = $this->arParams;
            $arResult = [
                'BLOCKS' => [
                    'HEADER' => [
                        'SHOW' => $arParams['HEADER_BLOCK_SHOW'] === 'Y',
                        'TEXT' => $arParams['~HEADER_BLOCK_TEXT'],
                        'POSITION' => ArrayHelper::fromRange([
                            'left',
                            'center',
                            'right'
                        ], $arParams['HEADER_BLOCK_POSITION'])
                    ],
                    'DESCRIPTION' => [
                        'SHOW' => $arParams['DESCRIPTION_BLOCK_SHOW'] === 'Y',
                        'TEXT' => $arParams['~DESCRIPTION_BLOCK_TEXT'],
                        'POSITION' => ArrayHelper::fromRange([
                            'left',
                            'center',
                            'right'
                        ], $arParams['DESCRIPTION_BLOCK_POSITION'])
                    ]
                ],
                'ITEMS' => [],
                'VISUAL' => [
                    'DATE' => [
                        'SHOW' => $arParams['DATE_SHOW'] === 'Y',
                        'FORMAT' => $arParams['DATE_FORMAT']
                    ]
                ]
            ];

            if (empty($arResult['BLOCKS']['HEADER']['TEXT']))
                $arResult['BLOCKS']['HEADER']['SHOW'] = false;

            if (empty($arResult['BLOCKS']['DESCRIPTION']['TEXT']))
                $arResult['BLOCKS']['DESCRIPTION']['SHOW'] = false;

            $this->setIBlockType($arParams['IBLOCK_TYPE']);
            $this->setIBlockId($arParams['IBLOCK_ID']);

            $arIBlock = $this->getIBlock();

            if (!empty($arIBlock) && $arIBlock['ACTIVE'] === 'Y') {
                $this->setUrlTemplates(
                    $arParams['LIST_PAGE_URL'],
                    $arParams['SECTION_URL'],
                    $arParams['DETAIL_URL']
                );

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

                foreach ($arResult['ITEMS'] as &$arItem) {
                    $arItem['DATE_CREATE_FORMATTED'] = null;
                    $arItem['DATE_ACTIVE_FROM_FORMATTED'] = null;
                    $arItem['DATE_ACTIVE_TO_FORMATTED'] = null;

                    if (!empty($arResult['VISUAL']['DATE']['FORMAT'])) {
                        if (!empty($arItem['DATE_CREATE']))
                            $arItem['DATE_CREATE_FORMATTED'] = CIBlockFormatProperties::DateFormat(
                                $arResult['VISUAL']['DATE']['FORMAT'],
                                MakeTimeStamp(
                                    $arItem['DATE_CREATE'],
                                    CSite::GetDateFormat()
                                )
                            );

                        if (!empty($arItem['DATE_ACTIVE_FROM']))
                            $arItem['DATE_ACTIVE_FROM_FORMATTED'] = CIBlockFormatProperties::DateFormat(
                                $arResult['VISUAL']['DATE']['FORMAT'],
                                MakeTimeStamp(
                                    $arItem['DATE_ACTIVE_FROM'],
                                    CSite::GetDateFormat()
                                )
                            );

                        if (!empty($arItem['DATE_ACTIVE_TO']))
                            $arItem['DATE_ACTIVE_TO_FORMATTED'] = CIBlockFormatProperties::DateFormat(
                                $arResult['VISUAL']['DATE']['FORMAT'],
                                MakeTimeStamp(
                                    $arItem['DATE_ACTIVE_TO'],
                                    CSite::GetDateFormat()
                                )
                            );
                    } else {
                        if (!empty($arItem['DATE_CREATE']))
                            $arItem['DATE_CREATE_FORMATTED'] = $arItem['DATE_CREATE'];

                        if (!empty($arItem['DATE_ACTIVE_FROM']))
                            $arItem['DATE_ACTIVE_FROM_FORMATTED'] = $arItem['DATE_ACTIVE_FROM'];

                        if (!empty($arItem['DATE_ACTIVE_TO']))
                            $arItem['DATE_ACTIVE_TO_FORMATTED'] = $arItem['DATE_ACTIVE_TO'];
                    }
                }

                unset($arItem);
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