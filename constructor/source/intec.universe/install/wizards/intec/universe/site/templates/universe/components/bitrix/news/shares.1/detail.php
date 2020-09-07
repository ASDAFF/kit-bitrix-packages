<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use intec\template\Properties;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('iblock') || !Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);

$sPrefix = 'DETAIL_';
$arDetail['TEMPLATE'] = ArrayHelper::getValue($arParams, $sPrefix.'TEMPLATE');
$arDetail['PARAMETERS'] = [];

if (!empty($arDetail['TEMPLATE'])) {
    $arDetail['TEMPLATE'] = 'shares.' . $arDetail['TEMPLATE'];

    foreach ($arParams as $sKey => $mValue) {
        if (StringHelper::startsWith($sKey, $sPrefix)) {
            $sKey = StringHelper::cut(
                $sKey,
                StringHelper::length($sPrefix)
            );

            if ($sKey === 'TEMPLATE')
                continue;

            $arDetail['PARAMETERS'][$sKey] = $mValue;
        }
    }
}

$arDetail['PARAMETERS'] = ArrayHelper::merge($arDetail['PARAMETERS'] , [
    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
    "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
    "META_KEYWORDS" => $arParams["META_KEYWORDS"],
    "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
    "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
    "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
    "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
    "SET_TITLE" => $arParams["SET_TITLE"],
    "MESSAGE_404" => $arParams["MESSAGE_404"],
    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
    "SHOW_404" => $arParams["SHOW_404"],
    "FILE_404" => $arParams["FILE_404"],
    "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
    "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
    "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
    "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
    "CHECK_DATES" => $arParams["CHECK_DATES"],
    "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
    "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
    "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
    "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
    'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : '')
]);
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    $arDetail['TEMPLATE'],
    $arDetail['PARAMETERS'],
    $component
);?>
