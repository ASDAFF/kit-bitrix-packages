<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var CBitrixComponent $this
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] == 'Y')
    include(__DIR__.'/modifiers/settings.php');

$cache = true;