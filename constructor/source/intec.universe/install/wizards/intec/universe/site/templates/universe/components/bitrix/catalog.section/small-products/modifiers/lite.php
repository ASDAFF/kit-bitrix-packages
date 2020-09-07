<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var string $sPriceFrom
 */

$arElements = ArrayHelper::getValue($arResult, 'ELEMENTS');
$bUseCommonCurrency = ArrayHelper::getValue($arParams, 'USE_COMMON_CURRENCY') == 'Y';
$sCurrency = ArrayHelper::getValue($arParams, 'CURRENCY');
$sPriceCode = ArrayHelper::getValue($arParams, 'PRICE_CODE');

/** Создание массива стартшопа */
if (!empty($arElements)) {
    $arProducts = [];
    $dbProducts = CStartShopCatalogProduct::GetList(
        array(),
        array('ID' => $arElements),
        array(),
        array(),
        $bUseCommonCurrency && !empty($sCurrency) ? $sCurrency : false,
        $sPriceCode
    );

    while ($arStartShopItem = $dbProducts->GetNext()) {
        $arProducts[$arStartShopItem['ID']] = $arStartShopItem;
    }

    foreach ($arResult['ITEMS'] as $sKey => $arItem) {
        $sItemID = ArrayHelper::getValue($arItem, 'ID');
        $arProduct = ArrayHelper::getValue($arProducts, $sItemID);

        /** Обработка цен */
        if (!empty($arProduct)) {

            $arPrice = ArrayHelper::getValue($arProduct, ['STARTSHOP', 'PRICES', 'MINIMAL']);
            $bOffers = !empty(ArrayHelper::getValue($arProduct, ['STARTSHOP', 'OFFERS']));

            if ($bOffers) {
                $arMinPrice = null;
                $arOffers = ArrayHelper::getValue($arProduct, ['STARTSHOP', 'OFFERS']);

                foreach ($arOffers as $arOffer) {
                    $arNewPrice = ArrayHelper::getValue($arOffer, ['STARTSHOP', 'PRICES', 'MINIMAL']);

                    if ($arMinPrice === null || $arMinPrice['VALUE'] > $arNewPrice['VALUE']) {
                        $arMinPrice = $arNewPrice;
                    }
                }

                $arMinPrice['PRINT_VALUE'] = $sPriceFrom.$arMinPrice['PRINT_VALUE'];

                /** Минимальная цена предложений */
                $arPrice = [
                    'VALUE' => $arMinPrice['VALUE'],
                    'CURRENCY' => $arMinPrice['CURRENCY'],
                    'PRINT_VALUE' => $arMinPrice['PRINT_VALUE'],
                    'TYPE' => $arMinPrice['TYPE'],
                    'PRINT_DISCOUNT_VALUE' => $arMinPrice['PRINT_VALUE'],
                    'DISCOUNT_VALUE' => $arMinPrice['VALUE']
                ];
                unset($arOffers);

                $arResult['ITEMS'][$sKey]['MIN_PRICE'] = $arPrice;
            } else {
                /** Создание массива мин. цены по типу старшей редакции */
                $arPrice = [
                    'VALUE' => $arPrice['VALUE'],
                    'CURRENCY' => $arPrice['CURRENCY'],
                    'PRINT_VALUE' => $arPrice['PRINT_VALUE'],
                    'TYPE' => $arPrice['TYPE'],
                    'PRINT_DISCOUNT_VALUE' => $arPrice['PRINT_VALUE'],
                    'DISCOUNT_VALUE' => $arPrice['VALUE']
                ];

                $arResult['ITEMS'][$sKey]['MIN_PRICE'] = $arPrice;
            }
        }

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

        $arResult['ITEMS'][$sKey]['PREVIEW_PICTURE'] = $arPreviewPicture;
    }
}