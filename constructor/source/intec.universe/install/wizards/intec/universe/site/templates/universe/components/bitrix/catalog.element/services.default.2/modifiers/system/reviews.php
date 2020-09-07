<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arReviews = [
    'USE' => false,
    'IBLOCK' => [
        'TYPE' => $arParams['REVIEWS_IBLOCK_TYPE'],
        'ID' => $arParams['REVIEWS_IBLOCK_ID']
    ],
    'SETTINGS' => [
        'PROPERTY_POSITION' => $arParams['REVIEWS_PROPERTY_POSITION'],
        'POSITION_SHOW' => $arParams['REVIEWS_POSITION_SHOW'],
        'FOOTER_BUTTON_SHOW' => $arParams['REVIEWS_FOOTER_BUTTON_SHOW'],
        'FOOTER_BUTTON_TEXT' => $arParams['REVIEW_FOOTER_BUTTON_TEXT'],
        'LIST_PAGE_URL' => $arParams['REVIEW_LIST_PAGE_URL']
    ]
];

if (!empty($arReviews['IBLOCK']['ID']))
    $arReviews['USE'] = true;

if (empty($arReviews['SETTINGS']['FOOTER_BUTTON_TEXT']))
    $arResult['SETTINGS']['FOOTER_BUTTON_TEXT'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEWS_FOOTER_SHOW');

$arResult['REVIEWS'] = $arReviews;

unset($arReviews);