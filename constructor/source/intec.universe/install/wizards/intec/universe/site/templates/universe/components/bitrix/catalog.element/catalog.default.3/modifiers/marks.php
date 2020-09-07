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
        $arCodes['MARKS']['NEW'],
        'VALUE'
    ]),
    'HIT' => ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arCodes['MARKS']['HIT'],
        'VALUE'
    ]),
    'RECOMMEND' => ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arCodes['MARKS']['RECOMMEND'],
        'VALUE'
    ]),
    'ORIENTATION' => $arVisual['MARKS']['ORIENTATION']
];

$arResult['MARKS']['NEW'] = !empty($arResult['MARKS']['NEW']) ? 'Y' : 'N';
$arResult['MARKS']['HIT'] = !empty($arResult['MARKS']['HIT']) ? 'Y' : 'N';
$arResult['MARKS']['RECOMMEND'] = !empty($arResult['MARKS']['RECOMMEND']) ? 'Y' : 'N';