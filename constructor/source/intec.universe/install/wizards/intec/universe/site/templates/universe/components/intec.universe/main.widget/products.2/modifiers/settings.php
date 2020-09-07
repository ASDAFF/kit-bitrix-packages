<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

if (!defined('EDITOR')) {
    $arParams['ACTION'] = Properties::get('basket-use') ? 'buy' : 'order';
    $arParams['QUICK_VIEW_USE'] = Properties::get('catalog-quick-view-use') ? 'Y' : 'N';
    $arParams['QUICK_VIEW_DETAIL'] = Properties::get('catalog-quick-view-detail') ? 'Y' : 'N';
    $arParams['QUICK_VIEW_TEMPLATE'] = Properties::get('catalog-quick-view-template');
    $arParams['QUICK_VIEW_ACTION'] = Properties::get('basket-use') ? 'buy' : 'detail';
    $arParams['DELAY_USE'] = (!Properties::get('basket-delay-use') || !Properties::get('basket-use')) ? 'N' : 'Y';
    $arParams['USE_COMPARE'] = (!Properties::get('basket-compare-use')) ? 'N' : 'Y';
    $arParams['COLUMNS_MOBILE'] = Properties::get('catalog-elements-tile-mobile-columns');

    if (Properties::get('template-images-lazyload-use'))
        $arParams['LAZYLOAD_USE'] = 'Y';
}