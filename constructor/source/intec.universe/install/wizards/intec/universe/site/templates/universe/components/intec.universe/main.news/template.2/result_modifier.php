<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'DATE_TYPE' => 'DATE_ACTIVE_FROM',
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N',
    'COLUMNS' => 2,
    'PREVIEW_SHOW' => 'N',
    'PREVIEW_TRUNCATE_USE' => 'N',
    'PREVIEW_TRUNCATE_COUNT' => 20,
    'SLIDER_LOOP' => 'N',
    'SLIDER_AUTO_USE' => 'N',
    'SLIDER_AUTO_TIME' => 10000,
    'SLIDER_AUTO_HOVER' => 'N',
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'DATE' => [
        'TYPE' => ArrayHelper::fromRange([
            'DATE_ACTIVE_FROM',
            'DATE_CREATE',
            'DATE_ACTIVE_TO',
            'TIMESTAMP_X'
        ], $arParams['DATE_TYPE'])
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y',
        'TRUNCATE' => [
            'USE' => $arParams['PREVIEW_TRUNCATE_USE'] === 'Y',
            'COUNT' => Type::toInteger($arParams['PREVIEW_TRUNCATE_COUNT'])
        ]
    ],
    'SLIDER' => [
        'ITEMS' => ArrayHelper::fromRange([2, 3, 4], $arParams['COLUMNS']),
        'LOOP' => $arParams['SLIDER_LOOP'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTO_USE'] === 'Y',
            'TIME' => Type::toInteger($arParams['SLIDER_AUTO_TIME']),
            'HOVER' => $arParams['SLIDER_AUTO_HOVER'] === 'Y'
        ]
    ]
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['PREVIEW']['SHOW'] && $arVisual['SLIDER']['ITEMS'] > 3)
    $arVisual['PREVIEW']['SHOW'] = false;

if ($arVisual['PREVIEW']['TRUNCATE']['USE'] && $arVisual['PREVIEW']['TRUNCATE']['COUNT'] < 1)
    $arVisual['PREVIEW']['TRUNCATE']['USE'] = false;

if ($arVisual['SLIDER']['AUTO']['USE'] || $arVisual['SLIDER']['AUTO']['TIME'] < 1)
    $arVisual['SLIDER']['AUTO']['TIME'] = 10000;

foreach ($arResult['ITEMS'] as &$arItem) {
    $arData = [
        'PREVIEW' => null,
        'DATE' => null
    ];

    /** Превью */
    if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) {
        $sPreview = $arItem['PREVIEW_TEXT'];

        if ($arVisual['PREVIEW']['TRUNCATE']['USE']) {
            $sPreview = Html::stripTags($sPreview);

            if (!empty($sPreview)) {
                $sPreview = preg_split(
                    '/[\s]+/',
                    $sPreview,
                    $arVisual['PREVIEW']['TRUNCATE']['COUNT'] + 1
                );

                if (count($sPreview) > $arVisual['PREVIEW']['TRUNCATE']['COUNT']) {
                    $sPreview = ArrayHelper::slice(
                        $sPreview,
                        0,
                        $arVisual['PREVIEW']['TRUNCATE']['COUNT']
                    );

                    $sPreview = implode(' ', $sPreview).'...';
                } else {
                    $sPreview = $arItem['PREVIEW_TEXT'];
                }
            }
        }

        $arData['PREVIEW'] = $sPreview;

        unset($sPreview);
    }

    /** Дата */
    if ($arVisual['DATE']['TYPE'] !== 'TIMESTAMP_X') {
        $sDate = $arItem[$arVisual['DATE']['TYPE'].'_FORMATTED'];
    } else {
        $sDate = CIBlockFormatProperties::DateFormat(
            $arVisual['DATE']['FORMAT'],
            MakeTimeStamp(
                $arItem['TIMESTAMP_X'],
                CSite::GetDateFormat()
            )
        );
    }

    if (!empty($sDate)) {
        $arData['DATE'] = $sDate;
    } else {
        $arData['DATE'] = $arItem['DATE_CREATE_FORMATTED'];
    }

    unset($sDate);

    $arItem['DATA'] = $arData;

    unset($arData);
}

unset($arItem);

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
        ['SITE_DIR' => SITE_DIR]
    );

if (empty($arFooter['BUTTON']['TEXT']) || empty($arFooter['BUTTON']['LINK']))
    $arFooter['BUTTON']['SHOW'] = false;

if (!$arFooter['BUTTON']['SHOW'])
    $arFooter['SHOW'] = false;

$arResult['BLOCKS']['FOOTER'] = $arFooter;
$arResult['VISUAL'] = $arVisual;

unset($arVisual, $arFooter);