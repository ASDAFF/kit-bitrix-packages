<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

if (!Loader::includeModule('catalog'))
    return;


$arTemplateParameters['DELAYED_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DELAYED_SHOW'),
    'TYPE' => 'CHECKBOX'
);