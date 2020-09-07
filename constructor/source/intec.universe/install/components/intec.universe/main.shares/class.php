<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

class IntecSharesComponent extends IBlockElements
{
    function executeComponent()
    {
        if ($this->startResultCache()) {
            $arParams = $this->arParams;

            $this->arResult = [
                'HEADER_BLOCK' => [],
                'DESCRIPTION_BLOCK' => [],
                'ITEMS' => [],
                'VIEW_PARAMETERS' => [],
                'FOOTER_BLOCK' => []
            ];

            $sHeaderText = ArrayHelper::getValue($arParams, 'HEADER_BLOCK_TEXT');
            $sHeaderText = trim($sHeaderText);
            $bHeaderShow = ArrayHelper::getValue($arParams, 'HEADER_BLOCK_SHOW');
            $bHeaderShow = $bHeaderShow == 'Y' && !empty($sHeaderText);

            $this->arResult['HEADER_BLOCK'] = [
                'SHOW' => $bHeaderShow,
                'POSITION' => ArrayHelper::getValue($arParams, 'HEADER_BLOCK_POSITION'),
                'TEXT' => Html::encode($sHeaderText)
            ];

            $sDescriptionText = ArrayHelper::getValue($arParams, 'DESCRIPTION_BLOCK_TEXT');
            $sDescriptionText = trim($sDescriptionText);
            $bDescriptionShow = ArrayHelper::getValue($arParams, 'DESCRIPTION_BLOCK_SHOW');
            $bDescriptionShow = $bDescriptionShow == 'Y' && !empty($sDescriptionText);

            $this->arResult['DESCRIPTION_BLOCK'] = [
                'SHOW' => $bDescriptionShow,
                'POSITION' => ArrayHelper::getValue($arParams, 'DESCRIPTION_BLOCK_POSITION'),
                'TEXT' => Html::encode($sDescriptionText)
            ];

            $sListPageUrl = ArrayHelper::getValue($arParams, 'LIST_PAGE_URL');
            $sSectionUrl = ArrayHelper::getValue($arParams, 'SECTION_URL');
            $sDetailUrl = ArrayHelper::getValue($arParams, 'DETAIL_URL');

            $iCountElement = ArrayHelper::getValue($arParams, 'ELEMENTS_COUNT');

            $sSortBy = ArrayHelper::getValue($arParams, 'SORT_BY');
            $sOrderBy = ArrayHelper::getValue($arParams, 'ORDER_BY');

            $arSort = [$sSortBy => $sOrderBy];
            $arFilter = [
                'ACTIVE' => 'Y'
            ];

            if (!empty($arParams['SECTIONS']))
                $arFilter['IBLOCK_SECTION_ID'] = $arParams['SECTIONS'];

            $this->setIBlockType(ArrayHelper::getValue($arParams, 'IBLOCK_TYPE'));
            $this->setIBlockId(ArrayHelper::getValue($arParams, 'IBLOCK_ID'));
            $this->setUrlTemplates($sListPageUrl, $sSectionUrl, $sDetailUrl);
            $this->arResult['ITEMS'] = $this->getElements($arSort, $arFilter, $iCountElement);

            $this->includeComponentTemplate();
        }

        return null;
    }
}