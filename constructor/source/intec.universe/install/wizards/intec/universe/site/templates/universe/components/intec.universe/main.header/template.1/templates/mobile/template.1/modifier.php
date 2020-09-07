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

$arResult['MOBILE']['FILLED'] = ArrayHelper::getValue($arParams, 'MOBILE_FILLED') == 'Y';
$arResult['MOBILE']['SEARCH']['TYPE'] = ArrayHelper::fromRange(['page', 'popup'], $arParams['MOBILE_SEARCH_TYPE']);
