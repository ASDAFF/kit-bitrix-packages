<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\regionality\models\Region;

/**
 * @var $arParams
 * @var $arResult
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
    'REGIONALITY_FILTER_STRICT' => 'N'
], $arParams);

if (empty($arParams['FILTER_NAME']))
    $arParams['FILTER_NAME'] = 'arrSharesFilter';

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$rsIBlock = CIBlock::GetByID($arParams['IBLOCK_ID']);
$arIBlock = $rsIBlock->Fetch();

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