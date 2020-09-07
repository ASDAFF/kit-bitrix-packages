<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

$APPLICATION->IncludeComponent(
	'bitrix:catalog.store.detail',
	'template.1',
	array(
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'STORE' => $arResult['STORE'],
		'PATH_TO_LISTSTORES' => $arResult['PATH_TO_LISTSTORES'],
		'SET_TITLE' => $arParams['SET_TITLE'],
		'MAP_TYPE' => $arParams['MAP_TYPE'],
        'MAP_ID' => $arParams['MAP_ID'],
        'SETTINGS_USE' => $arParams['SETTINGS_USE']
	),
	$component
);