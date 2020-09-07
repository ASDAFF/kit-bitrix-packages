<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('EDITOR')) {
    $arParams['AUTO'] = Properties::get('basket-fixed-auto') ? 'Y' : 'N';
    $arParams['DELAYED_SHOW'] = (!Properties::get('basket-delay-use') || !Properties::get('basket-use')) ? 'N' : 'Y';
    $arParams['COMPARE_SHOW'] = (!Properties::get('basket-compare-use')) ? 'N' : 'Y';
    $arParams['BASKET_SHOW'] =(!Properties::get('basket-use')) ? 'N' : 'Y';

    if (Properties::get('template-images-lazyload-use'))
        $arParams['LAZYLOAD_USE'] = 'Y';

    $arBlocks = Properties::get('mobile-blocks');

    if (!empty($arBlocks))
        if (!empty($arBlocks['panel']))
            $arParams['PANEL_SHOW'] = $arBlocks['panel']['active'] ? 'N' : 'Y';

    unset($arBlocks);
}