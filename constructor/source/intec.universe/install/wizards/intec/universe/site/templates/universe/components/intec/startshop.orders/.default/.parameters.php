<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.startshop'))
    return;

$arTemplateParameters = [];

$arTemplateParameters['URL_AUTHORIZE'] = [
    "PARENT" => "URL",
    "NAME" => Loc::getMessage("SO_DEFAULT_URL_AUTHORIZE"),
    "TYPE" => "STRING"
];