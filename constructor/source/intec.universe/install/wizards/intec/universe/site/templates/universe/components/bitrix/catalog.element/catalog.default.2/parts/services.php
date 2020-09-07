<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 */

$APPLICATION->IncludeComponent(
    'intec.universe:main.services',
    'template.17',
    $arResult['SERVICES']['PARAMETERS'],
    $component
);