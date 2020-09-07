<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

if (!defined('EDITOR')) {
    $arParams['MAP_VENDOR'] = Properties::get('base-map-vendor');
    $arParams['~MAP_VENDOR'] = $arParams['MAP_VENDOR'];
}