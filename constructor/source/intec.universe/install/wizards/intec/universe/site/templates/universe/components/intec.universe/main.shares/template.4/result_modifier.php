<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'LINE_COUNT' => 3,
    'LINK_USE' => 'N',
    'DESCRIPTION_USE' => 'N',
    'STICK_SHOW' => 'N',
    'PROPERTY_STICK' => null,
    'PROPERTY_STICK_COLOR' => null,
    'PROPERTY_STICK_BACKGROUND' => null,
    'PROPERTY_BACKGROUND' => null,
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

/** Обработка настроенных параметров компонента */

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'STICK' => [
        'SHOW' => $arParams['STICK_SHOW'] === 'Y'
    ],
    'COLUMNS' => ArrayHelper::fromRange([4, 3, 2], $arParams['LINE_COUNT']),
    'LINK_USE' => $arParams['LINK_USE'] === 'Y',
    'DESCRIPTION_USE' => $arParams['DESCRIPTION_USE'] === 'Y'
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'STICK' => [
            'SHOW' => false,
            'TEXT' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_STICK'], 'VALUE']),
            'COLOR' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_STICK_COLOR'], 'VALUE']),
            'BACKGROUND' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_STICK_BACKGROUND'], 'VALUE'])
        ],
        'BACKGROUND' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_BACKGROUND'], 'VALUE']),
        'PICTURE' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_PICTURE'], 'VALUE']),
        'TITLE' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_TITLE'], 'VALUE'])
    ];

    if ($arResult['VISUAL']['STICK']['SHOW']) {
        if (!empty($arItem['DATA']['STICK']['TEXT'])) {
            $arItem['DATA']['STICK']['SHOW'] = true;
        }
    }
}


/** Параметры кнопки "Показать все" */
$sFooterText = ArrayHelper::getValue($arParams, 'SEE_ALL_TEXT');
$sFooterText = trim($sFooterText);
$sListPage = ArrayHelper::getValue($arParams, 'LIST_PAGE_URL');

if (!empty($sListPage)) {
    $sListPage = trim($sListPage);
    $sListPage = StringHelper::replaceMacros($sListPage, $arMacros);
} else {
    $sListPage = ArrayHelper::getFirstValue($arResult['ITEMS']);
    $sListPage = $sListPage['LIST_PAGE_URL'];
}

$bFooterShow = ArrayHelper::getValue($arParams, 'SEE_ALL_SHOW');
$bFooterShow = $bFooterShow == 'Y' && !empty($sFooterText) && !empty($sListPage);

$arResult['FOOTER_BLOCK'] = [
    'SHOW' => $bFooterShow,
    'POSITION' => ArrayHelper::getValue($arParams, 'SEE_ALL_POSITION'),
    'TEXT' => $sFooterText,
    'LIST_PAGE' => $sListPage
];