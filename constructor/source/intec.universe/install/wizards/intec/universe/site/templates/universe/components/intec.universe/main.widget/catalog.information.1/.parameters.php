<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['PATH'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CATALOG_INFORMATION_1_PATH'),
    'TYPE' => 'STRING'
];