<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'URL_BASKET' => null,
    'URL_RULES_OF_PERSONAL_DATA_PROCESSING' => null
], $arParams);



if (!empty($arResult['ITEMS'])) {


    $arItemsSKUid = [];

    foreach ($arResult['ITEMS'] as $arItem) {
        if ($arItem['STARTSHOP']['OFFER']['OFFER'] && !ArrayHelper::isIn($arItem['STARTSHOP']['OFFER']['LINK'], $arItemsSKUid)) {
            $arItemsSKUid[] = $arItem['STARTSHOP']['OFFER']['LINK'];
        }
    }

    if (!empty($arItemsSKUid)) {
        $arSectionsItemSKU = Arrays::fromDBResult(CIBlockElement::GetList(
            [],
            ['ID' => $arItemsSKUid],
            false,
            false,
            ['ID', 'IBLOCK_SECTION_ID']
        ))->indexBy('ID')->asArray();
    }

    foreach ($arResult['ITEMS'] as $itemKey => $itemValue) {
        if ($itemValue['STARTSHOP']['OFFER']['OFFER']) {
            $arResult['ITEMS'][$itemKey]['IBLOCK_SECTION_ID'] = $arSectionsItemSKU[$itemValue['STARTSHOP']['OFFER']['LINK']]['IBLOCK_SECTION_ID'];
        }
    }

    $arSectionsID = [];

    foreach ($arResult['ITEMS'] as $arItem) {
        if (!empty($arItem['IBLOCK_SECTION_ID']) && !ArrayHelper::isIn($arItem['IBLOCK_SECTION_ID'], $arSectionsID)) {
            $arSectionsID[] = $arItem['IBLOCK_SECTION_ID'];
        }
    }

    if (!empty($arSectionsID)) {
        $arSections = Arrays::fromDBResult(
            CIBlockSection::GetList(
                ["SORT"=>"ASC"],
                ['ID' => $arSectionsID],
                false,
                ['ID', 'NAME','SECTION_PAGE_URL']
            ),
            true
        )->indexBy('ID')->asArray();

        foreach ($arResult['ITEMS'] as $itemKey => $itemValue) {
            $arResult['ITEMS'][$itemKey]['SECTION_INFO'] = $arSections[$itemValue['IBLOCK_SECTION_ID']];
        }
    }

}