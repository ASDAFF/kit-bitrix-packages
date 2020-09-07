<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\UnsetArrayValue;

$arReturn = [];

$arReturn['ADDRESS_SHOW'] = new UnsetArrayValue();
$arReturn['AUTHORIZATION_SHOW'] = new UnsetArrayValue();
$arReturn['TAGLINE_SHOW'] = new UnsetArrayValue();
$arReturn['BASKET_SHOW'] = new UnsetArrayValue();
$arReturn['DELAY_SHOW'] = new UnsetArrayValue();
$arReturn['COMPARE_SHOW'] = new UnsetArrayValue();
$arReturn['SEARCH_SHOW'] = new UnsetArrayValue();

return $arReturn;