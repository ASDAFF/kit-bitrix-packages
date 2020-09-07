<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCodes
 * @var array $arResult
 */

$arResult['MARKS'] = [
    'NEW' => ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_MARKS_NEW'],
        'VALUE'
    ]),
    'HIT' => ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_MARKS_HIT'],
        'VALUE'
    ]),
    'RECOMMEND' => ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_MARKS_RECOMMEND'],
        'VALUE'
    ])
];

$arResult['MARKS']['NEW'] = !empty($arResult['MARKS']['NEW']);
$arResult['MARKS']['HIT'] = !empty($arResult['MARKS']['HIT']);
$arResult['MARKS']['RECOMMEND'] = !empty($arResult['MARKS']['RECOMMEND']);