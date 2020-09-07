<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\regionality\models\Region;
use intec\template\Properties;

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
    'LIST_LAZYLOAD_USE' => 'N',
    'DETAIL_LAZYLOAD_USE' => 'N',
    'LIST_TEMPLATE' => null,
    'REGIONALITY_USE' => 'N',
    'REGIONALITY_FILTER_USE' => 'N',
    'REGIONALITY_FILTER_PROPERTY' => null,
    'REGIONALITY_FILTER_STRICT' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

if (empty($arParams['FILTER_NAME']))
    $arParams['FILTER_NAME'] = 'arrProjectsFilter';

$arIBlock = null;

if (!empty($arParams['IBLOCK_ID'])) {
    $oCache = Cache::createInstance();
    $arFilter = [
        'ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ];

    if ($oCache->initCache(36000, 'IBLOCK'.serialize($arFilter), '/iblock/projects')) {
        $arIBlock = $oCache->getVars();
    } else if ($oCache->startDataCache()) {
        $arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], $arFilter));
        $arIBlock = $arIBlocks->getFirst();
        $oCache->endDataCache($arIBlock);
    }
}

$arResult['IBLOCK'] = $arIBlock;
$arResult['LIST'] = [
    'TEMPLATE' => ArrayHelper::fromRange([
        '.default',
        'projects.list'
    ], $arParams['LIST_TEMPLATE'])
];

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