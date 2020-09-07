<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arCodes
 */

$arResult['TEXT'] = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arCodes['TEXT'],
    'VALUE'
]);