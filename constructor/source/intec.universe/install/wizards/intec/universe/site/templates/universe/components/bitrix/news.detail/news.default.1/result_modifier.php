<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_TAGS' => null,
    'PROPERTY_ADDITIONAL_NEWS' => null,
    'DATE_SHOW' => 'N',
    'DATE_TYPE' => 'DATE_ACTIVE_FROM',
    'DATE_FORMAT' => 'd.m.Y',
    'TAGS_SHOW' => 'N',
    'TAGS_POSITION' => 'top',
    'PRINT_SHOW' => 'N',
    'PREVIEW_SHOW' => 'N',
    'IMAGE_SHOW' => 'N',
    'ADDITIONAL_NEWS_SHOW' => 'N',
    'ADDITIONAL_NEWS_HEADER_SHOW' => 'N',
    'ADDITIONAL_NEWS_HEADER_TEXT' => null,
    'BUTTON_BACK_SHOW' => 'N',
    'BUTTON_SOCIAL_SHOW' => 'N',
    'BUTTON_SOCIAL_HANDLERS' => [],
    'MICRODATA_TYPE' => 'Article',
    'MICRODATA_AUTHOR' => null,
    'MICRODATA_PUBLISHER' => null,
], $arParams);

if (!empty($arParams['BUTTON_SOCIAL_HANDLERS']))
    $arParams['BUTTON_SOCIAL_HANDLERS'] = array_filter($arParams['BUTTON_SOCIAL_HANDLERS']);

$arParams['TAGS_POSITION'] = ArrayHelper::fromRange([
    'top',
    'bottom',
    'both'
], $arParams['TAGS_POSITION']);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'DATE' => [
        'SHOW' => $arParams['DATE_SHOW'] === 'Y',
        'TYPE' => ArrayHelper::fromRange([
            'DATE_ACTIVE_FROM',
            'DATE_CREATE',
            'DATE_ACTIVE_TO',
            'TIMESTAMP_X'
        ],$arParams['DATE_TYPE']),
        'FORMAT' => $arParams['DATE_FORMAT']
    ],
    'TAGS' => [
        'SHOW' => $arParams['TAGS_SHOW'] === 'Y' && !empty($arParams['PROPERTY_TAGS']),
        'POSITION' => [
            'TOP' => $arParams['TAGS_POSITION'] !== 'bottom',
            'BOTTOM' => $arParams['TAGS_POSITION'] !== 'top'
        ]
    ],
    'PRINT' => [
        'SHOW' => $arParams['PRINT_SHOW'] === 'Y'
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y' && !empty($arResult['PREVIEW_TEXT'])
    ],
    'IMAGE' => [
        'SHOW' => $arParams['IMAGE_SHOW'] === 'Y' && !empty($arResult['DETAIL_PICTURE'])
    ],
    'ADDITIONAL' => [
        'NEWS' => [
            'SHOW' => $arParams['ADDITIONAL_NEWS_SHOW'] === 'Y' && !empty($arParams['PROPERTY_ADDITIONAL_NEWS']),
            'HEADER' => [
                'SHOW' => $arParams['ADDITIONAL_NEWS_HEADER_SHOW'] === 'Y',
                'TEXT' => $arParams['~ADDITIONAL_NEWS_HEADER_TEXT']
            ]
        ]
    ],
    'BUTTON' => [
        'BACK' => [
            'SHOW' => $arParams['BUTTON_BACK_SHOW'] === 'Y'
        ],
        'SOCIAL' => [
            'SHOW' => $arParams['BUTTON_SOCIAL_SHOW'] === 'Y' && !empty($arParams['BUTTON_SOCIAL_HANDLERS'])
        ]
    ],
    'MICRODATA' => [
        'TYPE' => ArrayHelper::fromRange(['Article', 'NewsArticle', 'BlogPosting'],$arParams['MICRODATA_TYPE']),
        'AUTHOR' => $arParams['MICRODATA_AUTHOR'],
        'PUBLISHER' => $arParams['MICRODATA_PUBLISHER']
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['ADDITIONAL']['NEWS']['HEADER']['SHOW'] && empty($arVisual['ADDITIONAL']['NEWS']['HEADER']['TEXT']))
    $arVisual['ADDITIONAL']['NEWS']['HEADER']['SHOW'] = false;

$arData = [
    'DATE' => null,
    'TAGS' => [],
    'ADDITIONAL' => [
        'NEWS' => []
    ]
];

/** Дата */
$sDate = $arResult['DATE_CREATE'];

if (!empty($arResult[$arVisual['DATE']['TYPE']]))
    $sDate = $arResult[$arVisual['DATE']['TYPE']];

if (!empty($sDate))
    $arData['DATE'] = CIBlockFormatProperties::DateFormat(
        $arVisual['DATE']['FORMAT'],
        MakeTimeStamp($sDate, CSite::GetDateFormat())
    );

if ($arVisual['DATE']['SHOW'] && empty($sDate))
    $arVisual['DATE']['SHOW'] = false;

unset($sDate);

/** Теги */
$arTags = [];

if (!empty($arParams['PROPERTY_TAGS'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_TAGS']
    ]);

    if (!empty($arProperty['VALUE']) && !Type::isArray($arProperty['VALUE']))
        $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

    if (!empty($arProperty['VALUE'])) {

        foreach ($arProperty['VALUE'] as $sValue) {
            if (!empty($sValue))
                $arTags[] = $sValue;
        }

        unset($sValue);
    }

    unset($arProperty);
}

if ($arVisual['TAGS']['SHOW'] && empty($arTags))
    $arVisual['TAGS']['SHOW'] = false;

if (!empty($arTags))
    $arData['TAGS'] = $arTags;

unset($arTags);

/** Читайте также */
$arAdditionalNews = [];

if (!empty($arParams['PROPERTY_ADDITIONAL_NEWS'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_ADDITIONAL_NEWS']
    ]);

    if (!empty($arProperty['VALUE']) && !Type::isArray($arProperty['VALUE']))
        $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

    if (!empty($arProperty['VALUE'])) {
        foreach ($arProperty['VALUE'] as $sValue) {
            if (!empty($sValue))
                $arAdditionalNews[] = $sValue;
        }

        unset($sValue);
    }

    unset($arProperty);
}

if ($arVisual['ADDITIONAL']['NEWS']['SHOW'] && empty($arAdditionalNews))
    $arVisual['ADDITIONAL']['NEWS']['SHOW'] = false;

if (!empty($arAdditionalNews))
    $arData['ADDITIONAL']['NEWS'] = $arAdditionalNews;

unset($arAdditionalNews);

/** Результирующий массив */
$arResult['VISUAL'] = $arVisual;
$arResult['DATA'] = $arData;

unset($arVisual, $arData);

$this->__component->SetResultCacheKeys(['PREVIEW_PICTURE', 'DETAIL_PICTURE']);