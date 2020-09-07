<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CMain $APPLICATION
 * @var array $arResult
 */

$APPLICATION->RestartBuffer();

unset($arResult["COMBO"]);

echo CUtil::PHPToJSObject($arResult, true);