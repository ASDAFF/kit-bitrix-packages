<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\bitrix\Component;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!CModule::IncludeModule('iblock'))
    return;

if (!CModule::IncludeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N'
], $arParams);

$arParams['PAGE_URL_REVIEWS'] = StringHelper::replaceMacros(
    ArrayHelper::getValue($arParams, 'PAGE_URL_REVIEWS', ''), [
        'SITE_DIR' => SITE_DIR
    ]
);

$arResult = Component::SetElementProperties(
    $arResult,
    ArrayHelper::replaceKeys(
        array(
            'PROPERTY_GALLERY' => 'GALLERY',
            'PROPERTY_OBJECTIVE' => 'OBJECTIVE',
            'PROPERTY_SERVICES' => 'SERVICES',
            'PROPERTY_IMAGES' => 'IMAGES',
            'PROPERTY_SOLUTION_BEGIN' => 'SOLUTION_BEGIN',
            'PROPERTY_SOLUTION_IMAGE' => 'SOLUTION_IMAGE',
            'PROPERTY_SOLUTION_END' => 'SOLUTION_END'
        ),
        $arParams
    )
);

$arResult['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['LAZYLOAD']['USE'] = false;

if ($arResult['LAZYLOAD']['USE'])
    $arResult['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$getImages = function ($arProperty) {
    $arImages = array();

    if (!empty($arProperty)) {
        $arValue = ArrayHelper::getValue($arProperty, 'VALUE');
        $arDescription = array();

        if (!empty($arValue)) {
            foreach ($arValue as $sImageKey => $iImageId) {
                $arDescription[$iImageId] = ArrayHelper::getValue(
                    $arProperty, [
                        'DESCRIPTION', $sImageKey
                    ]
                );
            }

            $rsImages = CFile::GetList(array('ID' => 'ASC'), array(
                '@ID' => implode(',', $arValue)
            ));

            while ($arImage = $rsImages->Fetch()) {
                $arImage['SRC'] = CFile::GetFileSRC($arImage, false, false);
                $arImage['DESCRIPTION'] = $arDescription[$arImage['ID']];
                $arImages[] = $arImage;
            }
        }
    }

    return $arImages;
};

$arGallery = $getImages(ArrayHelper::getValue($arResult, ['SYSTEM_PROPERTIES', 'GALLERY']));
$arImages = $getImages(ArrayHelper::getValue($arResult, ['SYSTEM_PROPERTIES', 'IMAGES']));

$iServicesIBlockId = ArrayHelper::getValue($arParams, 'IBLOCK_ID_SERVICES');
$arServicesProperty = ArrayHelper::getValue($arResult, ['SYSTEM_PROPERTIES', 'SERVICES']);
$arServices = array();

if (!empty($arServicesProperty) && !empty($iServicesIBlockId)) {
    $arServicesValue = ArrayHelper::getValue($arServicesProperty, 'VALUE');

    if (!empty($arServicesValue)) {
        if (!Type::isArray($arServicesValue))
            $arServicesValue = array($arServicesValue);

        $rsServices = CIBlockElement::GetList(array('SORT' => 'ASC'), array(
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iServicesIBlockId,
            'ID' => $arServicesValue
        ));

        $rsServices->SetUrlTemplates($arParams['DETAIL_URL_SERVICES']);

        while ($arService = $rsServices->GetNext())
            $arServices[$arService['ID']] = $arService;
    }
}

$iReviewsIBlockId = ArrayHelper::getValue($arParams, 'IBLOCK_ID_REVIEWS');
$sReviewProperty = ArrayHelper::getValue($arParams, 'PROPERTY_REVIEW');
$arReview = null;

if (!empty($sReviewProperty) && !empty($iReviewsIBlockId)) {
    $rsReviews = CIBlockElement::GetList(array('SORT' => 'ASC'), array(
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $iReviewsIBlockId,
        'PROPERTY_'.$sReviewProperty => $arResult['ID']
    ));

    $rsReviews->SetUrlTemplates($arParams['DETAIL_URL_REVIEWS']);
    $arReview = $rsReviews->GetNext();
}

$getText = function ($arProperty) use (&$arResult) {
    $sResult = null;

    if (!empty($arProperty) && $arProperty['PROPERTY_TYPE'] === 'S') {
        $arProperty = CIBlockFormatProperties::GetDisplayValue($arResult, $arProperty, null);
        $sResult = $arProperty['DISPLAY_VALUE'];
    }

    return $sResult;
};

$sObjective = $getText(ArrayHelper::getValue($arResult, ['SYSTEM_PROPERTIES', 'OBJECTIVE']));
$sSolutionBegin = $getText(ArrayHelper::getValue($arResult, ['SYSTEM_PROPERTIES', 'SOLUTION_BEGIN']));
$arSolutionImage = ArrayHelper::getValue($arResult, ['SYSTEM_PROPERTIES', 'SOLUTION_IMAGE', 'VALUE']);
$sSolutionEnd = $getText(ArrayHelper::getValue($arResult, ['SYSTEM_PROPERTIES', 'SOLUTION_END']));

if (!empty($arSolutionImage))
    $arSolutionImage = CFile::GetFileArray($arSolutionImage);

$arResult['GALLERY'] = $arGallery;
$arResult['OBJECTIVE'] = $sObjective;
$arResult['IMAGES'] = $arImages;
$arResult['SERVICES'] = $arServices;
$arResult['REVIEW'] = $arReview;
$arResult['SOLUTION'] = array(
    'BEGIN' => $sSolutionBegin,
    'IMAGE' => $arSolutionImage,
    'END' => $sSolutionEnd
);

$this->__component->arResult['PREVIEW_TEXT'] = $arResult['PREVIEW_TEXT'];
$this->__component->arResult['PREVIEW_PICTURE'] = $arResult['PREVIEW_PICTURE']['SRC'];

$this->__component->SetResultCacheKeys(['PREVIEW_PICTURE', 'DETAIL_PICTURE']);