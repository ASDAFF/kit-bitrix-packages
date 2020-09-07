<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

$arDefaultParams = array(
    'URL_CATALOG' => ''
);

$arParams = array_merge($arDefaultParams, $arParams);
