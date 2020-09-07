<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$maxCategories = $arParams["COUNT_SECTIONS"];

$arResult["SECTIONS"] = array_slice($arResult["SECTIONS"], 0, $maxCategories);
?>