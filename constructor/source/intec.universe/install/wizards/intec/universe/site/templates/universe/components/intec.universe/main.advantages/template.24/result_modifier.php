<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_CATEGORY' => null,
    'CATEGORY_SHOW' => 'N',
    'PROPERTY_PICTURES' => null,
    'PROPERTY_PICTURES_TEXT' => null,
    'PICTURES_SHOW' => 'N',
    'PREVIEW_SHOW' => 'N',
    'HIDING_USE' => 'N',
    'HIDING_VISIBLE' => 2
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y'
    ],
    'CATEGORY' => [
        'SHOW' => $arParams['CATEGORY_SHOW'] === 'Y'
    ],
    'PICTURES' => [
        'SHOW' => $arParams['PICTURES_SHOW'] === 'Y'
    ],
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL']['HIDING'] = [
    'USE' => $arParams['HIDING_USE'] === 'Y',
    'VISIBLE' => Type::toInteger($arParams['HIDING_VISIBLE'])
];

if ($arResult['VISUAL']['HIDING']['VISIBLE'] < 1)
    $arResult['VISUAL']['HIDING']['VISIBLE'] = 1;

$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$hSetCategory = function (&$arItem) use (&$arParams) {
    $arReturn = [
        'SHOW' => false,
        'VALUE' => ''
    ];

    if (!empty($arParams['PROPERTY_CATEGORY'])) {
        $arReturn['VALUE'] = true;

        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_CATEGORY']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arReturn['VALUE'] = Html::decode($arProperty['VALUE']);
        }
    }

    return $arReturn;
};

$hSetPictures = function (&$arItem) use (&$arParams) {
    $arReturn = [];

    if (!empty($arParams['PROPERTY_PICTURES'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PICTURES'],
            'VALUE'
        ]);

        if (!empty($arProperty) && Type::isArray($arProperty)) {
            $arPictures = Arrays::fromDBResult(CFile::getList([], [
                '@ID' => implode(',', $arProperty)
            ]))->asArray();

            if (!empty($arPictures)) {
                foreach ($arPictures as $sKey => $arPicture) {
                    $arReturn[$sKey]['PICTURE'] = CFile::getFileSRC($arPicture);
                }
            }
        }
    }

    if (!empty($arParams['PROPERTY_PICTURES_TEXT'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PICTURES_TEXT']
        ]);

        if (!empty($arProperty['VALUE']) && Type::isArray($arProperty['VALUE'])) {
            foreach ($arProperty['VALUE'] as $sKey => $arText) {
                $arReturn[$sKey]['TEXT'] = [
                    'TITLE' => $arText,
                    'DESCRIPTION' => ArrayHelper::getValue($arProperty, ['DESCRIPTION', $sKey])
                ];
            }
        }
    }

    return $arReturn;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'CATEGORY' => $hSetCategory($arItem),
        'PICTURES' => $hSetPictures($arItem)
    ];
}

unset($arItem);