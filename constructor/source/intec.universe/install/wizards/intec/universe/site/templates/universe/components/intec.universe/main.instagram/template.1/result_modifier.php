<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Type;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];
if (defined('EDITOR'))
    $arResult['LAZYLOAD']['USE'] = false;

if ($arResult['LAZYLOAD']['USE'])
    $arResult['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

/** Параметры заголовка */
$sHeaderText = ArrayHelper::getValue($arParams, 'HEADER_TEXT');
$sHeaderText = trim($sHeaderText);
$sHeaderText = !empty($sHeaderText) ? $sHeaderText : Loc::getMessage('GALLERY_TEMP1_HEADER_DEFAULT');
$bHeaderShow = ArrayHelper::getValue($arParams, 'HEADER_SHOW');
$bHeaderShow = $bHeaderShow == 'Y' && !empty($sHeaderText);

$arResult['HEADER_BLOCK'] = [
    'SHOW' => $bHeaderShow,
    'POSITION' => ArrayHelper::getValue($arParams, 'HEADER_POSITION'),
    'TEXT' => Html::encode($sHeaderText)
];

/** Параметры описания */
$sDescriptionText = ArrayHelper::getValue($arParams, 'DESCRIPTION_TEXT');
$sDescriptionText = trim($sDescriptionText);
$bDescriptionShow = ArrayHelper::getValue($arParams, 'DESCRIPTION_SHOW');
$bDescriptionShow = $bDescriptionShow == 'Y' && !empty($sDescriptionText);

$arResult['DESCRIPTION_BLOCK'] = [
    'SHOW' => $bDescriptionShow,
    'POSITION' => ArrayHelper::getValue($arParams, 'DESCRIPTION_POSITION'),
    'TEXT' => Html::encode($sDescriptionText)
];

$iLineCount = ArrayHelper::getValue($arParams, 'LINE_COUNT');
$iMaxElements = ArrayHelper::getValue($arParams, 'COUNT_ITEMS');

if (empty($iMaxElements)) {
    $iMaxElements = null;
} else if (!Type::isNumeric($iMaxElements) || $iMaxElements <= $iLineCount) {
    $iMaxElements = $iLineCount;
}

$arResult['VIEW_PARAMETERS'] = [
    'LINE_COUNT' => $iLineCount,
    'DESCRIPTION_ITEM_SHOW' => ArrayHelper::getValue($arParams, 'DESCRIPTION_ITEM_SHOW') == 'Y',
    'PADDING_USE' => ArrayHelper::getValue($arParams, 'PADDING_USE') == 'Y',
    'WIDE' => ArrayHelper::getValue($arParams, 'WIDE') == 'Y'
];

/** Параметры "Смотреть все" */
$sFooterText = ArrayHelper::getValue($arParams, 'FOOTER_TEXT');
$sFooterText = trim($sFooterText);
$sFooterText = !empty($sFooterText) ? $sFooterText : Loc::getMessage('GALLERY_TEMP1_FOOTER_DEFAULT');
$bFooterShow = ArrayHelper::getValue($arParams, 'FOOTER_SHOW');
$bFooterShow = $bFooterShow == 'Y' && !empty($sFooterText);
$sListPage = ArrayHelper::getValue($arParams, 'LIST_PAGE');
$sListPage = trim($sListPage);
$sListPage = StringHelper::replaceMacros($sListPage, $arMacros);

$arResult['FOOTER_BLOCK'] = [
    'SHOW' => $bFooterShow,
    'POSITION' => ArrayHelper::getValue($arParams, 'FOOTER_POSITION'),
    'TEXT' => Html::encode($sFooterText),
    'LIST_PAGE' => $sListPage
];