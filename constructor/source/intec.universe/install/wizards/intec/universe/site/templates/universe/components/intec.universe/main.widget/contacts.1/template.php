<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

$this->setFrameMode(true);

if (!CModule::IncludeModule('iblock'))
    return;

if (!CModule::IncludeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'IBLOCK_TYPE' => null,
    'IBLOCK_ID' => null,
    'NEWS_COUNT' => null,
    'PROPERTY_CODE' => null,
    'CACHE_TIME' => null,
    'CACHE_TYPE' => null,
    'CACHE_GROUPS' => null,
    'PROPERTY_ADDRESS' => null,
    'PROPERTY_PHONE' => null,
    'PROPERTY_CITY' => null,
    'PROPERTY_MAP' => null,
    'MAP_VENDOR' => 'yandex',
    'WEB_FORM_ID' => null,
    'WEB_FORM_CONSENT_URL' => null,
    'WEB_FORM_TEMPLATE' => null,
    'WEB_FORM_NAME' => null,
    'FEEDBACK_BUTTON_TEXT' => null,
    'FEEDBACK_TEXT' => null,
    'FEEDBACK_IMAGE' => null,
    'MAIN' => null,
    'SHOW_MAP' => "N",
    'SHOW_FORM' => "N",
    'ADDRESS_SHOW' => "N",
    'PHONE_SHOW' => "N",
    'FEEDBACK_TEXT_SHOW' => "N",
    'FEEDBACK_IMAGE_SHOW' => "N",
    'SETTINGS_USE' => "N",
    'LAZYLOAD_USE' => 'N',
], $arParams);

if (empty($arParams['IBLOCK_ID']))
    return;

$arParameters = ArrayHelper::merge([
    'FILTER_NAME' => 'arFilter'
], $arParams, [
    "SORT_BY1" => "ACTIVE_FROM",
    "SORT_ORDER1" => "DESC",
    "SORT_BY2" => "SORT",
    "SORT_ORDER2" => "ASC",
    "CHECK_DATES" => "N",
    "SHOW_LIST" => "SETTINGS",
    "AJAX_MODE" => "N",
    "AJAX_OPTION_JUMP" => "N",
    "AJAX_OPTION_STYLE" => "Y",
    "AJAX_OPTION_HISTORY" => "N",
    "AJAX_OPTION_ADDITIONAL" => "",
    "PREVIEW_TRUNCATE_LEN" => "",
    "ACTIVE_DATE_FORMAT" => "d.m.Y",
    "SET_TITLE" => "N",
    "SET_BROWSER_TITLE" => "N",
    "SET_META_KEYWORDS" => "N",
    "SET_META_DESCRIPTION" => "N",
    "SET_LAST_MODIFIED" => "N",
    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
    "ADD_SECTIONS_CHAIN" => "N",
    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
    "PARENT_SECTION" => "",
    "PARENT_SECTION_CODE" => "",
    "INCLUDE_SUBSECTIONS" => "Y",
    "PAGER_TEMPLATE" => ".default",
    "DISPLAY_TOP_PAGER" => "N",
    "DISPLAY_BOTTOM_PAGER" => "N",
    "PAGER_TITLE" => "",
    "PAGER_SHOW_ALWAYS" => "N",
    "PAGER_DESC_NUMBERING" => "N",
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
    "PAGER_SHOW_ALL" => "N",
    "PAGER_BASE_LINK_ENABLE" => "N",
    "SET_STATUS_404" => "N",
    "SHOW_404" => "N",
    "MESSAGE_404" => "",
    "API_KEY_MAP" => "",
]);

$APPLICATION->IncludeComponent(
    'bitrix:news.list',
    '.default',
    $arParameters,
    $component
);
