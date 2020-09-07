<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var string $sPriceFrom
 */

foreach ($arResult['ITEMS'] as $key => $arItem) {
    /** Обработка цен */
    $arPrice = ArrayHelper::getValue($arItem, 'MIN_PRICE');
    $bOffers = !empty(ArrayHelper::getValue($arItem, 'OFFERS'));

    if ($bOffers) {
        $arMinPrice = null;
        $arOffers = ArrayHelper::getValue($arItem, 'OFFERS');

        foreach ($arOffers as $arOffer) {
            $arNewPrice = ArrayHelper::getValue($arOffer, 'MIN_PRICE');

            if ($arMinPrice === null || $arMinPrice['DISCOUNT_VALUE'] > $arNewPrice['DISCOUNT_VALUE']) {
                $arMinPrice = $arNewPrice;
            }
        }

        $arMinPrice['PRINT_VALUE'] = $sPriceFrom.$arMinPrice['PRINT_VALUE'];
        $arMinPrice['PRINT_DISCOUNT_VALUE'] = $sPriceFrom.$arMinPrice['PRINT_DISCOUNT_VALUE'];

        $arPrice = $arMinPrice;
    }

    $arResult['ITEMS'][$key]['MIN_PRICE'] = $arPrice;

    /** Изменение размеров изображения */
    $arPreviewPicture = ArrayHelper::getValue($arItem, 'PREVIEW_PICTURE');

    if (!empty($arPreviewPicture)) {
        $arResizePicture = CFile::ResizeImageGet(
            $arPreviewPicture,
            array(
                'width' => 80,
                'height' => 80
            ),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT
        );

        $arPreviewPicture['SRC'] = $arResizePicture['src'];
    } else {
        $sNoImage = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
        $arPreviewPicture['SRC'] = $sNoImage;
    }

    $arResult['ITEMS'][$key]['PREVIEW_PICTURE'] = $arPreviewPicture;
}