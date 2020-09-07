<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock') || !Loader::includeModule('intec.core'))
    return;

Loc::loadMessages(__FILE__);

include(__DIR__.'/parameters/list.php');
include(__DIR__.'/parameters/detail.php');

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_SHARES_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

include(__DIR__.'/parameters/regionality.php');