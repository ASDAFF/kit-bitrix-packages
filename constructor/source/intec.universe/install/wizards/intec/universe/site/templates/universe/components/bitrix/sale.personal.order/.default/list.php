<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
	return;

$arParameters = array(
    'PATH_TO_DETAIL' => $arResult['PATH_TO_DETAIL'],
    'PATH_TO_CANCEL' => $arResult['PATH_TO_CANCEL'],
    'PATH_TO_COPY' => $arResult['PATH_TO_LIST'].'?ID=#ID#',
    'PATH_TO_BASKET' => $arParams['PATH_TO_BASKET'],
    'PATH_TO_PAYMENT' => $arParams['PATH_TO_PAYMENT'],
    'SAVE_IN_SESSION' => $arParams['SAVE_IN_SESSION'],
    'ORDERS_PER_PAGE' => $arParams['ORDERS_PER_PAGE'],
    'SET_TITLE' =>$arParams['SET_TITLE'],
    'ID' => $arResult['VARIABLES']['ID'],
    'NAV_TEMPLATE' => $arParams['NAV_TEMPLATE'],
    'ACTIVE_DATE_FORMAT' => $arParams['ACTIVE_DATE_FORMAT'],
    'HISTORIC_STATUSES' => $arParams['HISTORIC_STATUSES'],
    'ALLOW_INNER' => $arParams['ALLOW_INNER'],
    'ONLY_INNER_FULL' => $arParams['ONLY_INNER_FULL'],
    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
    'CACHE_TIME' => $arParams['CACHE_TIME'],
    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
    'DEFAULT_SORT' => $arParams['ORDER_DEFAULT_SORT'],
    'RESTRICT_CHANGE_PAYSYSTEM' => $arParams['RESTRICT_CHANGE_PAYSYSTEM']
);

foreach ($arParams as $sKey => $mValue)
	if (StringHelper::startsWith($sKey, 'STATUS_COLOR_'))
        $arParameters[$sKey] = $mValue;

$APPLICATION->IncludeComponent(
	'bitrix:sale.personal.order.list',
	'.default',
	$arParameters,
	$component
);

unset($arParameters);