<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'LINE_COUNT' => 4,
    'BUTTON_SHOW' => 'N',
    'BUTTON_LINK' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['VISUAL']['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];
$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([3, 4, 5], $arParams['LINE_COUNT']);
$arResult['VISUAL']['BUTTON'] = [
    'SHOW' => $arParams['BUTTON_SHOW'] === 'Y',
    'LINK' => $arParams['BUTTON_LINK']
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if (!empty($arResult['VISUAL']['BUTTON']['LINK'])) {
    $arResult['VISUAL']['BUTTON']['LINK'] = StringHelper::replaceMacros($arResult['VISUAL']['BUTTON']['LINK'], $arMacros);
} else {
    $arResult['VISUAL']['BUTTON']['SHOW'] = false;
}