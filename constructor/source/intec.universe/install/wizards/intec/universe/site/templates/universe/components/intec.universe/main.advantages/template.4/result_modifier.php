<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'LINE_COUNT' => 4
], $arParams);

$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([2, 3, 4], $arParams['LINE_COUNT']);