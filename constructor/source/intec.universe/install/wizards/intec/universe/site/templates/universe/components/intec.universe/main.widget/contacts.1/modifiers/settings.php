<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

if (!defined('EDITOR')) {
    $arParams['MAP_VENDOR'] = Properties::get('base-map-vendor');
    if (Properties::get('template-images-lazyload-use'))
        $arParams['LAZYLOAD_USE'] = 'Y';
}