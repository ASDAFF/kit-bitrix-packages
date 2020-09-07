<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'COLUMNS' => 3,
    'COUNT_ELEMENTS' => true,
    '~COUNT_ELEMENTS' => 'Y',
    'PICTURE_TYPE' => 'square',
    'PICTURE_INDENTS' => 'N',
    'NAME_POSITION' => 'center',
    'DESCRIPTION_SHOW' => 'Y',
    'DESCRIPTION_POSITION' => 'center',
    'WIDE' => 'Y'
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'COLUMNS' => ArrayHelper::fromRange([3, 2, 4], $arParams['COLUMNS']),
    'NAME' => [
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['NAME_POSITION'])
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['DESCRIPTION_POSITION'])
    ],
    'ELEMENTS' => [
        'QUANTITY' => $arParams['~COUNT_ELEMENTS'] === 'Y'
    ],
    'PICTURE' => [
        'TYPE' => ArrayHelper::fromRange([
            'square',
            'round'
        ], $arParams['PICTURE_TYPE']),
        'INDENTS' => $arParams['PICTURE_INDENTS'] === 'Y'
    ],
    'WIDE' => $arParams['WIDE'] === 'Y'
];

if (!$arVisual['WIDE'] && $arVisual['COLUMNS'] > 3)
    $arVisual['COLUMNS'] = 3;

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