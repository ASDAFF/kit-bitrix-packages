<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arVisual = [
    'ORIENTATION' => ArrayHelper::fromRange([
        'vertical',
        'horizontal'
    ], ArrayHelper::getValue($arParams, 'ORIENTATION'))
];

$arResult['VISUAL'] = $arVisual;