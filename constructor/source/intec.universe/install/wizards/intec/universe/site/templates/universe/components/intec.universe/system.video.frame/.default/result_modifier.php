<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'ADAPTATION_USE' => 'Y',
    'ADAPTATION_RATIO' => '16:9',
    'ADAPTATION_MODE' => 'cover',
    'WIDTH' => 'parent',
    'HEIGHT' => 'parent'
], $arParams);

$arResult['ADAPTATION'] = [
    'USE' => $arParams['ADAPTATION_USE'] === 'Y',
    'MODE' => ArrayHelper::fromRange([
        'cover',
        'contain'
    ], $arParams['ADAPTATION_MODE']),
    'RATIO' => [
        'HORIZONTAL' => 16,
        'VERTICAL' => 9
    ]
];

$arResult['WIDTH'] = $arParams['WIDTH'];
$arResult['HEIGHT'] = $arParams['HEIGHT'];

$arRatio = explode(':', $arParams['ADAPTATION_RATIO']);

if (count($arRatio) >= 2) {
    $arResult['ADAPTATION']['RATIO']['HORIZONTAL'] = Type::toInteger($arRatio[0]);
    $arResult['ADAPTATION']['RATIO']['VERTICAL'] = Type::toInteger($arRatio[1]);

    if ($arResult['ADAPTATION']['RATIO']['HORIZONTAL'] < 0)
        $arResult['ADAPTATION']['RATIO']['HORIZONTAL'] = 1;

    if ($arResult['ADAPTATION']['RATIO']['VERTICAL'] < 0)
        $arResult['ADAPTATION']['RATIO']['VERTICAL'] = 1;
}

unset($arRatio);

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