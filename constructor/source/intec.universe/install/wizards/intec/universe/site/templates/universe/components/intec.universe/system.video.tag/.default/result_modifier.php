<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'ADAPTATION_USE' => 'Y',
    'ADAPTATION_MODE' => 'cover',
    'WIDTH' => 'parent',
    'HEIGHT' => 'parent'
], $arParams);

$arResult['ADAPTATION'] = [
    'USE' => $arParams['ADAPTATION_USE'] === 'Y',
    'MODE' => ArrayHelper::fromRange([
        'cover',
        'contain'
    ], $arParams['ADAPTATION_MODE'])
];

$arResult['WIDTH'] = $arParams['WIDTH'];
$arResult['HEIGHT'] = $arParams['HEIGHT'];

if (Type::isNumeric($arResult['WIDTH'])) {
    if ($arResult['WIDTH'] == Type::toInteger($arResult['WIDTH']))
        $arResult['WIDTH'] .= 'px';
} else {
    $arResult['WIDTH'] = '100%';
}

if (Type::isNumeric($arResult['HEIGHT'])) {
    if ($arResult['HEIGHT'] == Type::toInteger($arResult['HEIGHT']))
        $arResult['HEIGHT'] .= 'px';
} else {
    $arResult['HEIGHT'] = '100%';
}

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);