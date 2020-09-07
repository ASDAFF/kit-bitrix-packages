<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'THEME' => 'dark'
], $arParams);

$arResult['VISUAL'] = [
    'THEME' => ArrayHelper::fromRange([
        'dark',
        'light'
    ], $arParams['THEME'])
];