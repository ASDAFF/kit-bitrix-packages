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
    'THEME' => 'light',
    'ICON_SHOW' => 'N',
    'BACKGROUND_USE' => 'N',
    'BACKGROUND_PATH' => '#TEMPLATE_PATH#images/background.png'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['VISUAL']['THEME'] = ArrayHelper::fromRange([
    'dark',
    'light'
], $arParams['THEME']);

$arResult['VISUAL']['ICON'] = [
    'SHOW' => $arParams['ICON_SHOW'] === 'Y'
];

$arResult['VISUAL']['BACKGROUND'] = [
    'USE' => $arParams['BACKGROUND_USE'] === 'Y',
    'PATH' => $arParams['BACKGROUND_PATH']
];

if (!empty($arResult['VISUAL']['BACKGROUND']['PATH'])) {
    $arResult['VISUAL']['BACKGROUND']['PATH'] = StringHelper::replaceMacros(
        $arResult['VISUAL']['BACKGROUND']['PATH'],
        $arMacros
    );
} else {
    $arResult['VISUAL']['BACKGROUND']['USE'] = false;
}

$arResult['VISUAL']['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['VISUAL']['LAZYLOAD']['USE'] = false;

if ($arResult['VISUAL']['LAZYLOAD']['USE'])
    $arResult['VISUAL']['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');
