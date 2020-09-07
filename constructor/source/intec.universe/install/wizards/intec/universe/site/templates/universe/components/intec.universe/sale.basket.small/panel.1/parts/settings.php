<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('EDITOR')) {
    $arParams['DELAYED_SHOW'] = (!Properties::get('basket-delay-use') || !Properties::get('basket-use')) ? 'N' : 'Y';
    $arParams['COMPARE_SHOW'] = (!Properties::get('basket-compare-use')) ? 'N' : 'Y';
    $arParams['BASKET_SHOW'] =(!Properties::get('basket-use')) ? 'N' : 'Y';
    $arParams['HIDDEN'] =(!Properties::get('mobile-panel-hidden')) ? 'N' : 'Y';
}