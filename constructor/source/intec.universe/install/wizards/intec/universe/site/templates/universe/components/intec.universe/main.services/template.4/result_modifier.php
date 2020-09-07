<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_ICON' => null,
    'ICON_SHOW' => 'N',
    'DESCRIPTION_SHOW' => 'N',
    'NUMBER_SHOW' => 'N',
    'DETAIL_SHOW' => 'N',
    'DETAIL_TEXT' => null,
    'PARALLAX_USE' => 'N',
    'PARALLAX_RATIO' => 30,
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];


if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'ICON' => [
        'SHOW' => $arParams['ICON_SHOW'] === 'Y'
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y'
    ],
    'NUMBER' => [
        'SHOW' => $arParams['NUMBER_SHOW'] === 'Y'
    ],
    'DETAIL' => [
        'SHOW' => $arParams['DETAIL_SHOW'] === 'Y',
        'TEXT' => $arParams['DETAIL_TEXT']
    ],
    'PARALLAX' => [
        'USE' => $arParams['PARALLAX_USE'] === 'Y',
        'RATIO' => $arParams['PARALLAX_RATIO']
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');


if ($arVisual['DETAIL']['SHOW'] && empty($arVisual['DETAIL']['TEXT']))
    $arVisual['DETAIL']['SHOW'] = false;

if ($arVisual['PARALLAX']['RATIO'] < 0)
    $arVisual['PARALLAX']['RATIO'] = 0;

if ($arVisual['PARALLAX']['RATIO'] > 100)
    $arVisual['PARALLAX']['RATIO'] = 100;

$arVisual['PARALLAX']['RATIO'] = (100 - $arVisual['PARALLAX']['RATIO']) / 100;

$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$arFooter = [
    'SHOW' => $arParams['FOOTER_SHOW'] === 'Y',
    'POSITION' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['FOOTER_POSITION']),
    'BUTTON' => [
        'SHOW' => $arParams['FOOTER_BUTTON_SHOW'] === 'Y',
        'TEXT' => $arParams['FOOTER_BUTTON_TEXT'],
        'LINK' => null
    ]
];

if (!empty($arParams['LIST_PAGE_URL']))
    $arFooter['BUTTON']['LINK'] = StringHelper::replaceMacros(
        $arParams['LIST_PAGE_URL'],
        $arMacros
    );

if (empty($arFooter['BUTTON']['TEXT']) || empty($arFooter['BUTTON']['LINK']))
    $arFooter['BUTTON']['SHOW'] = false;

if (!$arFooter['BUTTON']['SHOW'])
    $arFooter['SHOW'] = false;

$arResult['BLOCKS']['FOOTER'] = $arFooter;

unset($arFooter, $arMacros);

$arPictures = [];

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'ICON' => null
    ];

    $sIcon = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $arParams['PROPERTY_ICON'],
        'VALUE'
    ]);

    if (Type::isNumeric($sIcon))
        $arPictures[] = $sIcon;
}

unset($arItem);

if (!empty($arPictures)) {
    $arPictures = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arPictures)
    ]))->indexBy('ID');

    foreach ($arResult['ITEMS'] as &$arItem) {
        $sIcon = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_ICON'],
            'VALUE'
        ]);

        if ($arPictures->exists($sIcon)) {
            $arItem['DATA']['ICON'] = $arPictures->get($sIcon);

            if (!empty($arItem['DATA']['ICON']))
                $arItem['DATA']['ICON']['SRC'] = CFile::GetFileSRC($arItem['DATA']['ICON']);
        }
    }

    unset($arItem, $arPictures);
}

unset($arPictures);