<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arData
 */

$arGallery = [];

/**
 * Собирает изображения с товара и предложений в общую коллекцию
 * @param array $arItem
 * @param string $sPropertyName
 */
$hCollectPictures = function (&$arItem, $sPropertyName = '') use (&$arGallery) {
    $sPictureStandard = null;

    if (!empty($arItem['DETAIL_PICTURE'])) {
        $sPictureStandard = $arItem['DETAIL_PICTURE'];
    } else if (!empty($arItem['PREVIEW_PICTURE'])) {
        $sPictureStandard = $arItem['PREVIEW_PICTURE'];
    }

    if (!empty($sPictureStandard)) {
        if (Type::isArray($sPictureStandard))
            $sPictureStandard = $sPictureStandard['ID'];

        $arGallery[] = $sPictureStandard;
    }

    if (empty($sPropertyName))
        return;

    $arProperty = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $sPropertyName
    ]);

    if (!empty($arProperty['VALUE']) && $arProperty['PROPERTY_TYPE'] === 'F' && Type::isArray($arProperty['VALUE'])) {
        foreach ($arProperty['VALUE'] as $sValue) {
            if (!empty($sValue))
                $arGallery[] = $sValue;
        }
    }
};

$hCollectPictures($arResult, $arParams['PROPERTY_PICTURES']);

if (!empty($arResult['OFFERS'])) {
    foreach ($arResult['OFFERS'] as &$arOffer)
        $hCollectPictures($arOffer, $arParams['OFFERS_PROPERTY_PICTURES']);

    unset($arOffer);
}

if (!empty($arGallery))
    $arGallery = Arrays::fromDBResult(CFile::GetList(['ID' => 'ASC'], [
        '@ID' => implode(',', $arGallery)
    ]))->indexBy('ID');
else
    $arGallery = Arrays::from([]);

/**
 * Обрабатывает и возвращает изображения переданного элемента из общей коллекции
 * @param array $arItem
 * @param string $sPropertyName
 * @return array
 */
$hSetPictures = function (&$arItem, $sPropertyName = '') use (&$arGallery) {
    $arItemPictures = [];

    $arPictureStandard = null;

    if (!empty($arItem['DETAIL_PICTURE'])) {
        $arPictureStandard = $arItem['DETAIL_PICTURE'];
    } else if (!empty($arItem['PREVIEW_PICTURE'])) {
        $arPictureStandard = $arItem['PREVIEW_PICTURE'];
    }

    if (!empty($arPictureStandard)) {
        if (Type::isArray($arPictureStandard))
            $arPictureStandard = $arPictureStandard['ID'];

        if ($arGallery->exists($arPictureStandard)) {
            $arPictureStandard = $arGallery->get($arPictureStandard);
            $arPictureStandard['SRC'] = CFile::GetFileSRC($arPictureStandard);

            $arItemPictures[] = $arPictureStandard;
        }
    }

    if (empty($sPropertyName))
        return $arItemPictures;

    $arProperty = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $sPropertyName
    ]);

    if (!empty($arProperty['VALUE']) && $arProperty['PROPERTY_TYPE'] === 'F' && Type::isArray($arProperty['VALUE'])) {
        $arPictures = [];

        foreach ($arProperty['VALUE'] as $sValue) {
            if ($arGallery->exists($sValue))
                $arPictures[] = $arGallery->get($sValue);
        }

        if (!empty($arPictures)) {
            foreach ($arPictures as &$arPicture) {
                $arPicture['SRC'] = CFile::GetFileSRC($arPicture);
                $arItemPictures[] = $arPicture;
            }
        }
    }

    return $arItemPictures;
};

$arResult['DATA']['GALLERY'] = $hSetPictures($arResult, $arParams['PROPERTY_PICTURES']);

if (!empty($arResult['OFFERS'])) {
    foreach ($arResult['OFFERS'] as &$arOffer)
        $arOffer['DATA']['GALLERY'] = $hSetPictures($arOffer, $arParams['OFFERS_PROPERTY_PICTURES']);

    unset($arOffer);
}

unset($arGallery);