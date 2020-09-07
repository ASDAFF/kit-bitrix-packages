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
    'COLUMNS' => 2,
    'LINK_BLANK' => 'N',
    'PICTURE_SHOW' => 'N',
    'PICTURE_SIZE' => 'cover',
    'CHILDREN_SHOW' => 'N',
    'CHILDREN_VIEW' => 1,
    'CHILDREN_ELEMENTS' => 'N',
    'CHILDREN_COUNT' => 5
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    ],
    'COLUMNS' => ArrayHelper::fromRange([2, 3], $arParams['COLUMNS']),
    'LINK' => [
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'PICTURE' => [
        'SHOW' => $arParams['PICTURE_SHOW'] === 'Y',
        'SIZE' => ArrayHelper::fromRange(['cover', 'contain'], $arParams['PICTURE_SIZE'])
    ],
    'CHILDREN' => [
        'SHOW' => $arParams['CHILDREN_SHOW'] === 'Y',
        'VIEW' => ArrayHelper::fromRange([1, 2], $arParams['CHILDREN_VIEW']),
        'ELEMENTS' => $arParams['CHILDREN_ELEMENTS'] === 'Y',
        'COUNT' => [
            'USE' => false,
            'VALUE' => Type::toInteger($arParams['CHILDREN_COUNT'])
        ]
    ]
];

if ($arVisual['CHILDREN']['COUNT']['VALUE'] > 0)
    $arVisual['CHILDREN']['COUNT']['USE'] = true;

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
    if (empty($arSections))
        return [];

    $bFirst = true;

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