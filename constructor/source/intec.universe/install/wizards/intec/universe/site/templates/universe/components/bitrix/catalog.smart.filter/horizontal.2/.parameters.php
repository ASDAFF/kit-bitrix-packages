<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

/** VISUAL */
$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];
$arTemplateParameters['TYPE_A_PRECISION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_TYPE_A_PRECISION'),
    'TYPE' => 'STRING',
    'DEFAULT' => '2'
];
$arTemplateParameters['TYPE_B_PRECISION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_HORIZONTAL_2_TYPE_B_PRECISION'),
    'TYPE' => 'STRING',
    'DEFAULT' => '2'
];