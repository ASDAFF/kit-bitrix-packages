<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

/** Параметры отображения */
$iLineCount = ArrayHelper::getValue($arParams, 'LINE_COUNT');

if ($iLineCount <= 2)
    $iLineCount = 2;

if ($iLineCount >= 4)
    $iLineCount = 4;

$arResult['VISUAL'] = [
    'COLUMNS' => $iLineCount,
    'DESCRIPTION' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'ELEMENT_SHOW_DESCRIPTION') == 'Y'
    ]
];