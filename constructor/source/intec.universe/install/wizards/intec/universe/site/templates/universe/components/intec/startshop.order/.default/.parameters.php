<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.startshop'))
    return;

$arTemplateParameters = [];

$arTemplateParameters['URL_BASKET'] = [
    'PARENT' => 'URL',
    'NAME' => Loc::getMessage('SO_DEFAULT_URL_BASKET'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['URL_RULES_OF_PERSONAL_DATA_PROCESSING'] = [
    'PARENT' => 'URL',
    'NAME' => Loc::getMessage('SO_DEFAULT_URL_RULES_OF_PERSONAL_DATA_PROCESSING'),
    'TYPE' => 'STRING'
];