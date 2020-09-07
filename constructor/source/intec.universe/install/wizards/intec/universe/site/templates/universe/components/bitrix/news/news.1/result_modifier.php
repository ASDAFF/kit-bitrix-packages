<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\regionality\models\Region;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$bSubscribeInstalled = false;
$bRegionInstalled = false;

if (Loader::includeModule('subscribe'))
    $bSubscribeInstalled = true;

if (Loader::includeModule('intec.regionality'))
    $bRegionInstalled = true;

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'SETTINGS_PROFILE' => null,
    'REGIONALITY_USE' => 'N',
    'REGIONALITY_FILTER_USE' => 'N',
    'REGIONALITY_FILTER_PROPERTY' => null,
    'REGIONALITY_FILTER_STRICT' => 'N',
    'FILTER' => 'arrFilter',
    'PROPERTY_TAGS' => null,
    'TAGS_USE' => 'N',
    'TAGS_VARIABLE' => 'tags',
    'TAGS_HEADER_SHOW' => 'N',
    'TAGS_HEADER_TEXT' => null,
    'TAGS_TEMPLATE' => null,
    'TOP_USE' => 'N',
    'TOP_PAGES' => 'list',
    'TOP_COUNT' => 5,
    'TOP_HEADER_SHOW' => 'N',
    'TOP_HEADER_TEXT' => null,
    'SUBSCRIBE_USE' => 'N',
    'SUBSCRIBE_PAGES' => 'list',
    'PANEL_SHOW' => 'N',
    'PANEL_VARIABLE' => 'year',
    'PANEL_VIEW' => 1,
    'LIST_TEMPLATE' => null,
    'DETAIL_TEMPLATE' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

if (!empty($arParams['PROPERTY_TAGS'])) {
    if (!ArrayHelper::isIn($arParams['PROPERTY_TAGS'], $arParams['LIST_PROPERTY_CODE']))
        $arParams['LIST_PROPERTY_CODE'][] = $arParams['PROPERTY_TAGS'];

    if (!ArrayHelper::isIn($arParams['PROPERTY_TAGS'], $arParams['DETAIL_PROPERTY_CODE']))
        $arParams['DETAIL_PROPERTY_CODE'][] = $arParams['PROPERTY_TAGS'];
}

if (!empty($arParams['LIST_DATE_TYPE']) && !ArrayHelper::isIn($arParams['LIST_DATE_TYPE'], $arParams['LIST_FIELD_CODE']))
    $arParams['LIST_FIELD_CODE'][] = $arParams['LIST_DATE_TYPE'];

if (!empty($arParams['DETAIL_DATE_TYPE']) && !ArrayHelper::isIn($arParams['DETAIL_DATE_TYPE'], $arParams['DETAIL_FIELD_CODE']))
    $arParams['DETAIL_FIELD_CODE'][] = $arParams['DETAIL_DATE_TYPE'];

if (empty($arParams['FILTER']))
    $arParams['FILTER'] = 'arrFilter';

if (empty($arParams['TAGS_VARIABLE']))
    $arParams['TAGS_VARIABLE'] = 'tags';

$arParams['TOP_PAGES'] = ArrayHelper::fromRange(['list', 'detail', 'all'], $arParams['TOP_PAGES']);
$arParams['TOP_COUNT'] = Type::toInteger($arParams['TOP_COUNT']);

if ($arParams['TOP_COUNT'] < 1)
    $arParams['TOP_COUNT'] = 5;

$arParams['SUBSCRIBE_PAGES'] = ArrayHelper::fromRange(['list', 'detail', 'all'], $arParams['SUBSCRIBE_PAGES']);

if (empty($arParams['PANEL_VARIABLE']))
    $arParams['PANEL_VARIABLE'] = 'years';

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'TAGS' => [
        'USE' => $arParams['TAGS_USE'] === 'Y',
        'HEADER' => [
            'SHOW' => $arParams['TAGS_HEADER_SHOW'] === 'Y',
            'TEXT' => $arParams['~TAGS_HEADER_TEXT']
        ]
    ],
    'TOP' => [
        'USE' => $arParams['TOP_USE'] === 'Y',
        'PAGES' => [
            'LIST' => false,
            'DETAIL' => false
        ],
        'HEADER' => [
            'SHOW' => $arParams['TOP_HEADER_SHOW'] === 'Y',
            'TEXT' => $arParams['~TOP_HEADER_TEXT']
        ]
    ],
    'SUBSCRIBE' => [
        'USE' => $arParams['SUBSCRIBE_USE'] === 'Y' && $bSubscribeInstalled,
        'PAGES' => [
            'LIST' => false,
            'DETAIL' => false
        ]
    ],
    'PANEL' => [
        'SHOW' => $arParams['PANEL_SHOW'] === 'Y' && !empty($arParams['IBLOCK_ID']),
        'VIEW' => ArrayHelper::fromRange([1, 2], $arParams['PANEL_VIEW'])
    ],
    'ADDITIONAL' => [
        'LIST' => false,
        'DETAIL' => false
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['TAGS']['USE'] && empty($arParams['PROPERTY_TAGS']))
    $arVisual['TAGS']['USE'] = false;

if (!$arVisual['TAGS']['USE'])
    $arParams['PROPERTY_TAGS'] = null;

if (empty($arParams['TAGS_VARIABLE']))
    $arParams['TAGS_VARIABLE'] = 'tags';

if ($arVisual['TAGS']['HEADER']['SHOW'] && empty($arVisual['TAGS']['HEADER']['TEXT']))
    $arVisual['TAGS']['HEADER']['SHOW'] = false;

if ($arVisual['TOP']['USE']) {
    $arVisual['TOP']['PAGES']['LIST'] = $arParams['TOP_PAGES'] !== 'detail';
    $arVisual['TOP']['PAGES']['DETAIL'] = $arParams['TOP_PAGES'] !== 'list';
}

if ($arVisual['TOP']['HEADER']['SHOW'] && empty($arVisual['TOP']['HEADER']['TEXT']))
    $arVisual['TOP']['HEADER']['SHOW'] = false;

if ($arVisual['SUBSCRIBE']['USE']) {
    $arVisual['SUBSCRIBE']['PAGES']['LIST'] = $arParams['SUBSCRIBE_PAGES'] !== 'detail';
    $arVisual['SUBSCRIBE']['PAGES']['DETAIL'] = $arParams['SUBSCRIBE_PAGES'] !== 'list';
}

if ($arVisual['TAGS']['USE'] || $arVisual['TOP']['PAGES']['LIST'] || $arVisual['SUBSCRIBE']['PAGES']['LIST'])
    $arVisual['ADDITIONAL']['LIST'] = true;

if ($arVisual['TOP']['PAGES']['DETAIL'] || $arVisual['SUBSCRIBE']['PAGES']['DETAIL'])
    $arVisual['ADDITIONAL']['DETAIL'] = true;

$arResult['REGIONALITY'] = [
    'USE' => false
];

/** Мультирегиональность */
if ($bRegionInstalled) {
    $arRegion = [
        'USE' => $arParams['REGIONALITY_USE'] === 'Y',
        'FILTER' => [
            'USE' => $arParams['REGIONALITY_FILTER_USE'] === 'Y',
            'PROPERTY' => $arParams['REGIONALITY_FILTER_PROPERTY'],
            'STRICT' => $arParams['REGIONALITY_FILTER_STRICT'] === 'Y'
        ]
    ];

    if (empty($arParams['IBLOCK_ID']))
        $arRegion['USE'] = false;

    if (empty($arRegion['FILTER']['PROPERTY']))
        $arRegion['FILTER']['USE'] = false;

    if ($arRegion['USE']) {
        $oRegion = Region::getCurrent();

        if (!empty($oRegion)) {
            if ($arRegion['FILTER']['USE']) {
                if (!isset($GLOBALS[$arParams['FILTER']]) || !Type::isArray($GLOBALS[$arParams['FILTER']]))
                    $GLOBALS[$arParams['FILTER']] = [];

                $arConditions = [
                    'LOGIC' => 'OR',
                    ['PROPERTY_'.$arRegion['FILTER']['PROPERTY'] => $oRegion->id]
                ];

                if (!$arRegion['FILTER']['STRICT'])
                    $arConditions[] = ['PROPERTY_'.$arRegion['FILTER']['PROPERTY'] => false];

                $GLOBALS[$arParams['FILTER']][] = $arConditions;

                unset($arConditions);
            }
        }

        unset($oRegion);
    }

    $arResult['REGIONALITY'] = $arRegion;

    unset($arRegion);
}

$arResult['VISUAL'] = $arVisual;

unset($bSubscribeInstalled, $arVisual);