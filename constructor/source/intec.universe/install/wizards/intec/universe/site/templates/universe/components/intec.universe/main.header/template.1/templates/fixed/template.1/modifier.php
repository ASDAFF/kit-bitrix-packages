<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$arParams = ArrayHelper::merge([
    'FIXED_MENU_POPUP_SHOW' => 'Y'
], $arParams);

$arResult['FIXED'] = [
    'MENU' => [
        'POPUP' => [
            'SHOW' => $arParams['FIXED_MENU_POPUP_SHOW'] === 'Y'
        ]
    ]
];