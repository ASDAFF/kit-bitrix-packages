<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

if (!defined('EDITOR')) {
    $arParams['MAP_VENDOR'] = Properties::get('base-map-vendor');
    $arParams['REGIONALITY_USE'] = Properties::get('base-regionality-use') ? 'Y' : 'N';

    switch (Properties::get('sections-contacts-template')) {
        case 'shops.1': $arParams['SHOW_LIST'] = 'SHOPS'; break;
        case 'offices.1': $arParams['SHOW_LIST'] = 'OFFICES'; break;
        default: $arParams['SHOW_LIST'] = 'NONE'; break;
    }

    if (Properties::get('template-images-lazyload-use'))
        $arParams['LAZYLOAD_USE'] = 'Y';
}