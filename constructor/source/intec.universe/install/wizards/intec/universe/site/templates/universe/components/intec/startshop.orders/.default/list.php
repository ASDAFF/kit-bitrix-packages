<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

global $APPLICATION, $USER;

$this->setFrameMode(false);

if (!empty($arParams['ORDERS_LIST_HEADER']))
    $APPLICATION->SetTitle($arParams['ORDERS_LIST_HEADER']);

if ($USER->IsAuthorized()) {
    $arFilter = ArrayHelper::merge($arParams['FILTER'], [
        "USER" => $USER->GetID()
    ]);

    $APPLICATION->IncludeComponent(
        "intec:startshop.orders.list",
        ".default", [
        "FILTER" => $arFilter,
        "CURRENCY" => $arParams['CURRENCY'],
        "SORT" => ['DATE_CREATE' => 'DESC'],
        "DETAIL_PAGE_URL" => $APPLICATION->GetCurPageParam($arParams['REQUEST_VARIABLE_ORDER_ID']."=#ID#", array($arParams['ORDER_ID_VARIABLE']))
        ],
        $component
    );

} else if (!empty($arParams['URL_AUTHORIZE'])) {
    LocalRedirect($arParams['URL_AUTHORIZE']);
    die();
} else {
    $APPLICATION->ShowAuthForm(null);
}
