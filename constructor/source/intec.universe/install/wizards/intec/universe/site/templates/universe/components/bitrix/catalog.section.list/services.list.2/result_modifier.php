<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'COLUMNS' => 2,
    'COUNT_ELEMENTS' => true,
    '~COUNT_ELEMENTS' => 'Y',
    'ROUNDING_USE' => 'N',
    'ROUNDING_VALUE' => 0,
    'WIDE' => 'Y'
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'COLUMNS' => ArrayHelper::fromRange([2, 3], $arParams['COLUMNS']),
    'ELEMENTS' => [
        'QUANTITY' => $arParams['~COUNT_ELEMENTS'] === 'Y'
    ],
    'ROUNDING' => [
        'USE' => $arParams['ROUNDING_USE'] === 'Y',
        'VALUE' => Type::toInteger($arParams['ROUNDING_VALUE'])
    ],
    'WIDE' => $arParams['WIDE'] === 'Y'
];

if ($arVisual['ROUNDING']['VALUE'] < 0)
    $arVisual['ROUNDING']['VALUE'] = 0;

if ($arVisual['ROUNDING']['VALUE'] > 100)
    $arVisual['ROUNDING']['VALUE'] = 100;

if ($arVisual['ROUNDING']['VALUE'] == 0)
    $arVisual['ROUNDING']['USE'] = false;

if ($arVisual['COLUMNS'] > 2 && !$arVisual['WIDE'])
    $arVisual['COLUMNS'] = 2;

$arSections = [];

foreach($arResult['SECTIONS'] as $arSection) {
    if (!empty($arSection['PICTURE'])) {
        $arSection['PICTURE']['TITLE'] = ArrayHelper::getValue($arSection, ['IPROPERTY_VALUES', 'SECTION_PICTURE_FILE_TITLE']);
        $arSection['PICTURE']['ALT'] = ArrayHelper::getValue($arSection, ['IPROPERTY_VALUES', 'SECTION_PICTURE_FILE_ALT']);

        if (empty($arSection['PICTURE']['TITLE']))
            $arSection['PICTURE']['TITLE'] = $arSection['NAME'];

        if (empty($arSection['PICTURE']['ALT']))
            $arSection['PICTURE']['ALT'] = $arSection['NAME'];
    }

    $arSection['SECTIONS'] = [];
    $arSections[$arSection['ID']] = $arSection;
}

unset($arSection);

$fBuild = function ($arSections) {
    $bFirst = true;

    if (empty($arSections))
        return [];

    $fBuild = function () use (&$fBuild, &$bFirst, &$arSections) {
        $iLevel = null;
        $arItems = array();
        $arItem = null;

        if ($bFirst) {
            $arItem = reset($arSections);
            $bFirst = false;
        }

        while (true) {
            if ($arItem === null) {
                $arItem = next($arSections);

                if (empty($arItem))
                    break;
            }

            if ($iLevel === null)
                $iLevel = $arItem['DEPTH_LEVEL'];

            if ($arItem['DEPTH_LEVEL'] < $iLevel) {
                prev($arSections);
                break;
            }

            if ($arItem['DEPTH_LEVEL'] > $iLevel) {
                $arItem = prev($arSections);
                $arItem['SECTIONS'] = $fBuild();
                $arItems[count($arItems) - 1] = $arItem;
            } else {
                $arItems[] = $arItem;
            }

            $arItem = null;
        }

        return $arItems;
    };

    return $fBuild();
};

$arResult['VISUAL'] = $arVisual;
$arResult['SECTIONS'] = $fBuild($arSections);

unset($arVisual, $arSections, $fBuild);