<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$APPLICATION->IncludeComponent(
	'bitrix:sale.personal.order.cancel',
	'.default', [
		'PATH_TO_LIST' => $arResult['PATH_TO_LIST'],
		'PATH_TO_DETAIL' => $arResult['PATH_TO_DETAIL'],
		'SET_TITLE' => $arParams['SET_TITLE'],
		'ID' => $arResult['VARIABLES']['ID'],
	],
	$component
);