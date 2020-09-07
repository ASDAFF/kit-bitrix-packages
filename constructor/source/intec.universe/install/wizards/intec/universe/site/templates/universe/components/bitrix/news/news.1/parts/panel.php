<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Data\Cache;
use intec\Core;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 */

$arYears = [];
$arFilter = [];
$sVariable = $arParams['PANEL_VARIABLE'];
$arValues = Core::$app->request->get();

$sDatePrefix = 'DATE_';
$sDateType = ArrayHelper::getValue($arParams, 'LIST_DATE_TYPE');

if (empty($sDateType))
    $sDateType = 'DATE_ACTIVE_FROM';

if (!empty($arValues[$sVariable])) {
    $sDateFormat = CDatabase::DateFormatToPHP(CSite::GetDateFormat('SHORT'));

    $arFilter = [
        '>='.$sDateType => date($sDateFormat, strtotime($arValues[$sVariable].'-01-01')),
        '<='.$sDateType => date($sDateFormat, strtotime($arValues[$sVariable].'-12-31'))
    ];

    unset($sDateFormat);
}

$oCache = Cache::createInstance();

if ($oCache->initCache(360000, $arParams['IBLOCK_ID'], '/iblock/news')) {
    $arYears = $oCache->getVars();
} else {
    $arItems = Arrays::fromDBResult(CIBlockElement::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
        'MIN_PERMISSION' => 'R'
    ]));

    if ($arItems->isEmpty())
        return;

    if ($sDateType === 'DATE_ACTIVE_FROM' || $sDateType === 'DATE_ACTIVE_TO')
        $sDateType = StringHelper::cut($sDateType, StringHelper::length($sDatePrefix));

    $arYearsTemp = $arItems->asArray(function ($key, $arValue) use (&$sDateType) {
        if (empty($arValue[$sDateType]))
            return ['skip' => true];

        $arDate = StringHelper::explode($arValue[$sDateType], ' ');
        $arDate = ArrayHelper::getFirstValue($arDate);

        if (empty($arDate))
            return ['skip' => true];

        $arDate = StringHelper::explode($arDate, '.');
        $sYear = Type::toInteger(ArrayHelper::getValue($arDate, 2));

        if (empty($sYear))
            return ['skip' => true];

        return [
            'key' => $key,
            'value' => $sYear
        ];
    });

    unset($arItems);

    if (!empty($arYearsTemp)) {
        foreach ($arYearsTemp as $sYearTemp) {
            if (!ArrayHelper::isIn($sYearTemp, $arYears))
                $arYears[] = $sYearTemp;
        }
        
        unset($sYearTemp);

        usort($arYears, function ($sYear1, $sYear2) {
            if ($sYear1 > $sYear2)
                return -1;

            if ($sYear1 < $sYear2)
                return 1;

            return 0;
        });
    }

    unset($arYearsTemp);

    $oCache->endDataCache($arYears);
}

unset($sDatePrefix, $sDateType);

if (!empty($arYears)) {
    if (!isset($GLOBALS[$arParams['FILTER']]))
        $GLOBALS[$arParams['FILTER']] = [];

    if (!empty($arValues[$sVariable]))
        $GLOBALS[$arParams['FILTER']] = ArrayHelper::merge($GLOBALS[$arParams['FILTER']], $arFilter);

    if ($arVisual['PANEL']['VIEW'] === '1')
        include(__DIR__.'/panel/view.1.php');
    else if ($arVisual['PANEL']['VIEW'] === '2')
        include(__DIR__.'/panel/view.2.php');
}

unset($arYears, $arFilter, $sVariable, $arValues);