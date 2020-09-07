<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 */

global $APPLICATION, $USER;

$this->setFrameMode(false);

$arFilter = $arParams['FILTER'];

if (!is_array($arFilter))
    $arFilter = [];

if ($USER->IsAuthorized()) {
    $arFilter['USER'] = $USER->GetID();
} else {
    $arFilter['USER'] = null;
}

$APPLICATION->IncludeComponent(
    "intec:startshop.orders.detail",
    ".default", [
    "FILTER" => $arFilter,
    "SORT" => $arParams['SORT'],
    "LIST_PAGE_URL" => $APPLICATION->GetCurPageParam("", array($arParams['REQUEST_VARIABLE_ORDER_ID'])),
    "ORDER_ID" => $_REQUEST[$arParams['REQUEST_VARIABLE_ORDER_ID']],
    "CURRENCY" => $arParams['CURRENCY'],
    "404_SET_STATUS" => $arParams['404_SET_STATUS'],
    "404_REDIRECT" => $arParams['404_REDIRECT'],
    "404_PAGE" => $arParams['404_PAGE']
    ],
    $component
);