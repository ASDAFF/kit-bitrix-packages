<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['OVERLAY'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAP_YANDEX_VIEW_DEFAULT_OVERLAY'),
    'TYPE' => 'CHECKBOX'
];