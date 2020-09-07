<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\regionality\Module as Regionality;
use intec\regionality\models\Region;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'REGIONALITY_USE' => 'N',
    'REGIONALITY_FILTER_USE' => 'N',
    'REGIONALITY_FILTER_PROPERTY' => null,
    'REGIONALITY_FILTER_STRICT' => 'N',
    'REGIONALITY_PRICES_TYPES_USE' => 'N',
    'REGIONALITY_STORES_USE' => 'N',
    'SEF_TABS_USE' => 'N'
], $arParams);

if (empty($arParams['FILTER_NAME']))
    $arParams['FILTER_NAME'] = 'arrCatalogFilter';

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arIBlock = null;
$arSection = null;

if (!empty($arParams['IBLOCK_ID'])) {
    $oCache = Cache::createInstance();
    $arFilter = [
        'ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ];

    if ($oCache->initCache(36000, 'IBLOCK'.serialize($arFilter), '/iblock/catalog')) {
        $arIBlock = $oCache->getVars();
    } else if ($oCache->startDataCache()) {
        $arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], $arFilter));
        $arIBlock = $arIBlocks->getFirst();
        $oCache->endDataCache($arIBlock);
    }
}

if (
    !empty($arResult['VARIABLES']['SECTION_ID']) ||
    !empty($arResult['VARIABLES']['SECTION_CODE'])
) {
    $oCache = Cache::createInstance();
    $arFilter = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y',
        'GLOBAL_ACTIVE' => 'Y'
    ];

    if (!empty($arResult['VARIABLES']['SECTION_ID'])) {
        $arFilter['ID'] = $arResult['VARIABLES']['SECTION_ID'];
    } else {
        $arFilter['CODE'] = $arResult['VARIABLES']['SECTION_CODE'];
    }

    if ($oCache->initCache(36000, 'SECTION'.serialize($arFilter), '/iblock/catalog')) {
        $arSection = $oCache->getVars();
    } else if ($oCache->startDataCache()) {
        $arSections = Arrays::fromDBResult(CIBlockSection::GetList([], $arFilter));
        $arSection = $arSections->getFirst();
        $oCache->endDataCache($arSection);
    }
}

$arResult['IBLOCK'] = $arIBlock;
$arResult['SECTION'] = $arSection;
$arResult['REGIONALITY'] = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y',
    'FILTER' => [
        'USE' => $arParams['REGIONALITY_FILTER_USE'] === 'Y',
        'PROPERTY' => $arParams['REGIONALITY_FILTER_PROPERTY'],
        'STRICT' => $arParams['REGIONALITY_FILTER_STRICT'] === 'Y'
    ],
    'PRICES' => [
        'USE' => $arParams['REGIONALITY_PRICES_TYPES_USE'] === 'Y'
    ],
    'STORES' => [
        'USE' => $arParams['REGIONALITY_STORES_USE'] === 'Y'
    ]
];

if (empty($arIBlock) || !Loader::includeModule('intec.regionality'))
    $arResult['REGIONALITY']['USE'] = false;

if (empty($arResult['REGIONALITY']['FILTER']['PROPERTY']))
    $arResult['REGIONALITY']['FILTER']['USE'] = false;

$arResult['PARAMETERS'] = [
    'COMMON' => [
        'FORM_ID',
        'FORM_TEMPLATE',
        'FORM_PROPERTY_PRODUCT',
        'PROPERTY_MARKS_RECOMMEND',
        'PROPERTY_MARKS_NEW',
        'PROPERTY_MARKS_HIT',
        'PROPERTY_ORDER_USE',
        'CONSENT_URL',
        'LAZY_LOAD',
        'VOTE_MODE',
        'DELAY_USE',
        'QUANTITY_MODE',
        'QUANTITY_BOUNDS_FEW',
        'QUANTITY_BOUNDS_MANY',

        'VIDEO_IBLOCK_TYPE',
        'VIDEO_IBLOCK_ID',
        'VIDEO_PROPERTY_URL',
        'SERVICES_IBLOCK_TYPE',
        'SERVICES_IBLOCK_ID',
        'REVIEWS_IBLOCK_TYPE',
        'REVIEWS_IBLOCK_ID',
        'REVIEWS_PROPERTY_ELEMENT_ID',
        'REVIEWS_USE_CAPTCHA',
        'PROPERTY_ARTICLE',
        'PROPERTY_BRAND',
        'PROPERTY_PICTURES',
        'PROPERTY_SERVICES',
        'PROPERTY_DOCUMENTS',
        'PROPERTY_ADDITIONAL',
        'PROPERTY_ASSOCIATED',
        'PROPERTY_RECOMMENDED',
        'PROPERTY_VIDEO',
        'OFFERS_PROPERTY_ARTICLE',
        'OFFERS_PROPERTY_PICTURES',

        'CONVERT_CURRENCY',
        'CURRENCY_ID',
        'PRICE_CODE'
    ]
];

if ($arResult['REGIONALITY']['USE']) {
    $oRegion = Region::getCurrent();

    if (!empty($oRegion)) {
        if ($arResult['REGIONALITY']['FILTER']['USE']) {
            if (!isset($GLOBALS[$arParams['FILTER_NAME']]) || !Type::isArray($GLOBALS[$arParams['FILTER_NAME']]))
                $GLOBALS[$arParams['FILTER_NAME']] = [];

            $arConditions = [
                'LOGIC' => 'OR',
                ['PROPERTY_'.$arResult['REGIONALITY']['FILTER']['PROPERTY'] => $oRegion->id]
            ];

            if (!$arResult['REGIONALITY']['FILTER']['STRICT'])
                $arConditions[] = ['PROPERTY_'.$arResult['REGIONALITY']['FILTER']['PROPERTY'] => false];

            $GLOBALS[$arParams['FILTER_NAME']][] = $arConditions;
        }

        if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
            if ($arResult['REGIONALITY']['PRICES']['USE']) {
                $arParams['FILTER_PRICE_CODE'] = $_SESSION[Regionality::VARIABLE][Region::VARIABLE]['PRICES']['CODE'];
                $arParams['PRICE_CODE'] = $_SESSION[Regionality::VARIABLE][Region::VARIABLE]['PRICES']['CODE'];
            }

            if ($arResult['REGIONALITY']['STORES']['USE'])
                $arParams['STORES'] = $_SESSION[Regionality::VARIABLE][Region::VARIABLE]['STORES']['ID'];
        } else if (Loader::includeModule('intec.startshop')) {
            if ($arResult['REGIONALITY']['PRICES']['USE'])
                $arParams['PRICE_CODE'] = $_SESSION[Regionality::VARIABLE][Region::VARIABLE]['PRICES']['CODE'];
        }
    }
}

if ($arParams['SEF_TABS_USE'] !== 'Y') {
    unset($arResult['URL_TEMPLATES']['tabs']);
    unset($arResult['VARIABLES']['TAB']);
}

if ($arParams['SEF_MODE'] === 'N') {
    if ($arParams['SEF_TABS_USE'] === 'Y') {
        $arResult['URL_TEMPLATES']['tabs'] = Html::encode($APPLICATION->GetCurPage()).'?'.
            $arResult['ALIASES']['SECTION_ID'].'=#SECTION_ID#&'.
            $arResult['ALIASES']['ELEMENT_ID'].'=#ELEMENT_ID#&'.
            $arResult['ALIASES']['TAB'].'=#TAB#';
    }
}