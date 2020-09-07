<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
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
    'SETTINGS_PROFILE' => null,
    'SECTIONS_ROOT_CONTENT_BEGIN_SHOW' => 'N',
    'SECTIONS_ROOT_CONTENT_BEGIN_PATH' => null,
    'SECTIONS_ROOT_CONTENT_END_SHOW' => 'N',
    'SECTIONS_ROOT_CONTENT_END_PATH' => null,
    'SECTIONS_CHILDREN_CONTENT_BEGIN_SHOW' => 'N',
    'SECTIONS_CHILDREN_CONTENT_BEGIN_PATH' => null,
    'SECTIONS_CHILDREN_CONTENT_END_SHOW' => 'N',
    'SECTIONS_CHILDREN_CONTENT_END_PATH' => null,
    'REGIONALITY_USE' => 'N',
    'REGIONALITY_FILTER_USE' => 'N',
    'REGIONALITY_FILTER_PROPERTY' => null,
    'REGIONALITY_FILTER_STRICT' => 'N',
    'REGIONALITY_PRICES_TYPES_USE' => 'N',
    'REGIONALITY_STORES_USE' => 'N'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

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

    if ($oCache->initCache(36000, 'IBLOCK'.serialize($arFilter), '/iblock/services')) {
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

    if ($oCache->initCache(36000, 'SECTION'.serialize($arFilter), '/iblock/services')) {
        $arSection = $oCache->getVars();
    } else if ($oCache->startDataCache()) {
        $arSections = Arrays::fromDBResult(CIBlockSection::GetList([], $arFilter));
        $arSection = $arSections->getFirst();
        $oCache->endDataCache($arSection);
    }
}

$arResult['IBLOCK'] = $arIBlock;
$arResult['SECTION'] = $arSection;
$arResult['CONTENT'] = [
    'ROOT' => [
        'BEGIN' => [
            'SHOW' => $arParams['SECTIONS_ROOT_CONTENT_BEGIN_SHOW'] === 'Y',
            'PATH' => $arParams['SECTIONS_ROOT_CONTENT_BEGIN_PATH']
        ],
        'END' => [
            'SHOW' => $arParams['SECTIONS_ROOT_CONTENT_END_SHOW'] === 'Y',
            'PATH' => $arParams['SECTIONS_ROOT_CONTENT_END_PATH']
        ]
    ],
    'SECTIONS' => [
        'BEGIN' => [
            'SHOW' => $arParams['SECTIONS_CHILDREN_CONTENT_BEGIN_SHOW'] === 'Y',
            'PATH' => $arParams['SECTIONS_CHILDREN_CONTENT_BEGIN_PATH']
        ],
        'END' => [
            'SHOW' => $arParams['SECTIONS_CHILDREN_CONTENT_END_SHOW'] === 'Y',
            'PATH' => $arParams['SECTIONS_CHILDREN_CONTENT_END_PATH']
        ]
    ]
];

if (!empty($arResult['CONTENT']['ROOT']['BEGIN']['PATH'])) {
    $arResult['CONTENT']['ROOT']['BEGIN']['PATH'] = StringHelper::replaceMacros(
        $arResult['CONTENT']['ROOT']['BEGIN']['PATH'],
        $arMacros
    );
} else {
    $arResult['CONTENT']['ROOT']['BEGIN']['SHOW'] = false;
}

if (!empty($arResult['CONTENT']['ROOT']['END']['PATH'])) {
    $arResult['CONTENT']['ROOT']['END']['PATH'] = StringHelper::replaceMacros(
        $arResult['CONTENT']['ROOT']['END']['PATH'],
        $arMacros
    );
} else {
    $arResult['CONTENT']['ROOT']['END']['SHOW'] = false;
}

if (!empty($arResult['CONTENT']['SECTIONS']['BEGIN']['PATH'])) {
    $arResult['CONTENT']['SECTIONS']['BEGIN']['PATH'] = StringHelper::replaceMacros(
        $arResult['CONTENT']['SECTIONS']['BEGIN']['PATH'],
        $arMacros
    );
} else {
    $arResult['CONTENT']['SECTIONS']['BEGIN']['SHOW'] = false;
}

if (!empty($arResult['CONTENT']['SECTIONS']['END']['PATH'])) {
    $arResult['CONTENT']['SECTIONS']['END']['PATH'] = StringHelper::replaceMacros(
        $arResult['CONTENT']['SECTIONS']['END']['PATH'],
        $arMacros
    );
} else {
    $arResult['CONTENT']['SECTIONS']['END']['SHOW'] = false;
}

$arResult['REGIONALITY'] = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y',
    'FILTER' => [
        'USE' => $arParams['REGIONALITY_FILTER_USE'] === 'Y',
        'PROPERTY' => $arParams['REGIONALITY_FILTER_PROPERTY'],
        'STRICT' => $arParams['REGIONALITY_FILTER_STRICT'] === 'Y'
    ]
];

if (empty($arIBlock) || !Loader::includeModule('intec.regionality'))
    $arResult['REGIONALITY']['USE'] = false;

if (empty($arResult['REGIONALITY']['FILTER']['PROPERTY']))
    $arResult['REGIONALITY']['FILTER']['USE'] = false;

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
    }
}