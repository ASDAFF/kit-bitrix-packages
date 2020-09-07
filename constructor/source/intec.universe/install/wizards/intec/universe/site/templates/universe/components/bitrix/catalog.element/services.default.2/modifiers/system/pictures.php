<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\collections\Arrays;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arPicturesProperties = [
    'PROPERTY_BANNER_PICTURE',
    'PROPERTY_ADDITIONAL_TEXT_PICTURE',
];

$arImages = [];

foreach ($arPicturesProperties as $sProperty) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams[$sProperty],
        'VALUE'
    ]);

    if (!empty($arProperty)) {
        if (Type::isArray($arProperty))
            foreach ($arProperty as $sID)
                $arImages[] = $sID;
        else
            $arImages[] = $arProperty;
    }
}

if (!empty($arImages)) {
    $arImages = Arrays::fromDBResult(CFile::GetList(['SORT' => 'ASC'], [
        '@ID' => implode(',', $arImages)
    ]))->indexBy('ID');

    if (!$arImages->isEmpty()) {
        foreach ($arPicturesProperties as $sProperty) {
            $arProperty = ArrayHelper::getValue($arResult, [
                'PROPERTIES',
                $arParams[$sProperty],
                'VALUE'
            ]);

            if (!empty($arProperty)) {
                $arImage = [];

                if (Type::isArray($arProperty)) {
                    foreach ($arProperty as $sID)
                        if ($arImages->exists($sID)) {
                            $arImage[$sID] = $arImages->get($sID);
                            $arImage[$sID]['SRC'] = CFile::GetFileSRC($arImage[$sID]);
                        }
                } else if ($arImages->exists($arProperty)) {
                    $arImage = $arImages->get($arProperty);
                    $arImage['SRC'] = CFile::GetFileSRC($arImage);
                }

                if (!empty($arImage))
                    $arResult['PROPERTIES'][$arParams[$sProperty]]['VALUE'] = $arImage;

                unset($arImage);
            }
        }
    }
}

unset($arPicturesProperties, $arImages, $arProperty, $sProperty, $sID);