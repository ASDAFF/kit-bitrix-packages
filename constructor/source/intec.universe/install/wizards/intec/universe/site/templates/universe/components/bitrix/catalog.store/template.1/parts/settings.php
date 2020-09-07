<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

$arParams['MAP_TYPE'] = Properties::get('base-map-vendor') == 'yandex' ? 0 : 1;
$arParams['~MAP_TYPE'] = $arParams['MAP_TYPE'];