<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (empty($arParams['TAB_DESCRIPTION_NAME']))
    $arParams['TAB_DESCRIPTION_NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NAME_DEFAULT');

$arResult['DATA']['TAB']['DESCRIPTION'] = [
    'SHOW' => false,
    'NAME' => $arParams['TAB_DESCRIPTION_NAME'],
    'VALUE' => []
];

include(__DIR__.'/tab.description/advantages.1.php');
include(__DIR__.'/tab.description/news.1.php');
include(__DIR__.'/tab.description/blocks.1.php');
include(__DIR__.'/tab.description/advantages.2.php');
include(__DIR__.'/tab.description/blocks.2.php');
include(__DIR__.'/tab.description/links.1.php');

if ($arResult['VISUAL']['TAB']['DESCRIPTION']['SHOW'] && !empty($arResult['DATA']['TAB']['DESCRIPTION']['VALUE']))
    $arResult['DATA']['TAB']['DESCRIPTION']['SHOW'] = true;