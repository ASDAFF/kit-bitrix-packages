<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

foreach ($arResult['ITEMS'] as $key => $arItem) {

    /** Convert #SITE_DIR# */
    $sConvertLink = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_LINK'], 'VALUE']);

    if (!empty($sConvertLink)) {
        $sConvertLink = StringHelper::replaceMacros($sConvertLink, array('SITE_DIR' => SITE_DIR));

        $arItem['PROPERTIES'][$arParams['PROPERTY_LINK']]['VALUE'] = $sConvertLink;
        $arResult['ITEMS'][$key] = $arItem;
    }

    /** Resize icons */
    $arIcon = $arItem['PREVIEW_PICTURE'];

    if (!empty($arIcon)) {
        $arIconResize = CFile::ResizeImageGet(
            $arIcon,
            array(
                'width' => 80,
                'height' => 80
            ),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT
        );

        $arResult['ITEMS'][$key]['PREVIEW_PICTURE']['RESIZE_SRC'] = $arIconResize['src'];
    }
}