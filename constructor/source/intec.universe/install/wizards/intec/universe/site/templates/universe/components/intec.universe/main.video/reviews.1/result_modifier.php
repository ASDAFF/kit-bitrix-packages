<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$bTemplateShow = ArrayHelper::getValue($arParams, 'ELEMENT');

$arParallax = [
    'USE' => ArrayHelper::getValue($arParams, 'PARALLAX_USE') == 'Y',
    'RATIO' => ArrayHelper::getValue($arParams, 'PARALLAX_RATIO')
];

if ($arParallax['RATIO'] < 0) $arParallax['RATIO'] = 0;
if ($arParallax['RATIO'] > 100) $arParallax['RATIO'] = 100;

$arParallax['RATIO'] = (100 - $arParallax['RATIO']) / 100;

$sHeight = ArrayHelper::getValue($arParams, 'HEIGHT');
$sHeight = StringHelper::replaceMacros($sHeight, ['px' => '']);
$sHeight = Type::isNumeric($sHeight) ? $sHeight : 400;

$sButtonColorTheme = ArrayHelper::getValue($arParams, 'BUTTON_COLOR_THEME');
$sButtonColor = null;

switch ($sButtonColorTheme) {
    case 'dark':
        $sButtonColor = '#000'; break;
    case 'light':
        $sButtonColor = '#FFF'; break;
    case 'custom':
        $sButtonColor = ArrayHelper::getValue($arParams, 'BUTTON_COLOR_CUSTOM');
}

$arResult['VISUAL']['TEMPLATE_SHOW'] = !empty($bTemplateShow);
$arResult['VISUAL']['WIDTH'] = ArrayHelper::getValue($arParams, 'WIDTH') == 'Y';
$arResult['VISUAL']['HEIGHT'] = $sHeight;
$arResult['VISUAL']['COLOR_THEME'] = $sButtonColor;
$arResult['VISUAL']['PARALLAX'] = $arParallax;

$arResult['VISUAL']['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['VISUAL']['LAZYLOAD']['USE'] = false;

if ($arResult['VISUAL']['LAZYLOAD']['USE'])
    $arResult['VISUAL']['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');