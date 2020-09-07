<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'BORDERS' => 'Y',
    'COLUMNS' => 3,
    'CHILDREN_SHOW' => 'Y',
    'CHILDREN_COUNT' => 4,
    'PICTURE_SHOW' => 'Y',
    'PICTURE_SIZE' => 'large',
    'DESCRIPTION_SHOW' => 'Y',
    'COUNT_ELEMENTS' => true,
    '~COUNT_ELEMENTS' => 'Y',
    'WIDE' => 'Y'
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'BORDERS' => $arParams['BORDERS'] === 'Y',
    'COLUMNS' => ArrayHelper::fromRange([3, 1, 2], $arParams['COLUMNS']),
    'PICTURE' => [
        'SHOW' => $arParams['PICTURE_SHOW'] === 'Y',
        'SIZE' => ArrayHelper::fromRange([
            'small',
            'medium',
            'large'
        ], $arParams['PICTURE_SIZE'])
    ],
    'CHILDREN' => [
        'SHOW' => $arParams['CHILDREN_SHOW'] === 'Y',
        'COUNT' => Type::toInteger($arParams['CHILDREN_COUNT'])
    ],
    'ELEMENTS' => [
        'QUANTITY' => $arParams['~COUNT_ELEMENTS'] === 'Y'
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y'
    ],
    'WIDE' => $arParams['WIDE'] === 'Y'
];

if (!$arVisual['WIDE'] && $arVisual['COLUMNS'] > 2)
    $arVisual['COLUMNS'] = 2;

if ($arVisual['CHILDREN']['COUNT'] < 1)
    $arVisual['CHILDREN']['COUNT'] = false;

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

unset($arVisual, $arSections);