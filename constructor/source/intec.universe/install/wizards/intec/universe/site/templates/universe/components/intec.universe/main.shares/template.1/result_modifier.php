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
    'LAZYLOAD_USE' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

/** Коды свойств */
$arResult['PROPERTY_CODES'] = [
    'PRICE' => ArrayHelper::getValue($arParams, 'PROPERTY_PRICE')
];

/** Обработка настроенных параметров компонента */
$iLineCount = ArrayHelper::getValue($arParams, 'LINE_COUNT');

if ($iLineCount <= 3)
    $iLineCount = 3;

if ($iLineCount >= 5)
    $iLineCount = 5;

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'LINE_COUNT' => $iLineCount,
    'LINK_USE' => ArrayHelper::getValue($arParams, 'LINK_USE') == 'Y',
    'DATE_SHOW' => ArrayHelper::getValue($arParams, 'DATE_SHOW') == 'Y'
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$sDateFormat = $arParams['DATE_FORMAT'];

foreach ($arResult['ITEMS'] as &$arItem) {

    $sDateTo = $arItem['ACTIVE_TO'];
    $sDateFrom = $arItem['ACTIVE_FROM'];
    $arItem['DISPLAY_ACTIVE_TO'] = '';
    $arItem['DISPLAY_ACTIVE_FROM'] = '';

    if (!empty($sDateFormat)){
        if (!empty($sDateTo)) {
            $arItem['DISPLAY_ACTIVE_TO'] = CIBlockFormatProperties::DateFormat(
                $sDateFormat,
                MakeTimeStamp(
                    $sDateTo,
                    CSite::GetDateFormat()
                )
            );
        }
        if (!empty($sDateFrom)) {
            $arItem['DISPLAY_ACTIVE_FROM'] = CIBlockFormatProperties::DateFormat(
                $sDateFormat,
                MakeTimeStamp(
                    $sDateFrom,
                    CSite::GetDateFormat()
                )
            );
        }
    }

    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $sImage = $arItem['PREVIEW_PICTURE'];
    } else if (!empty($arItem['DETAIL_PICTURE'])) {
        $sImage = $arItem['DETAIL_PICTURE'];
    }

    $sImage = CFile::ResizeImageGet($sImage, array(
        'width' => 600,
        'height' => 600
    ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

    $arItem['PREVIEW_PICTURE']['SRC'] = !empty($sImage['src']) ? $sImage['src'] : null;
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