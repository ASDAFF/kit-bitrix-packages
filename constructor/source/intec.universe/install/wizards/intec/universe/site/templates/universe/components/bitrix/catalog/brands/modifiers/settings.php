<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

if (!defined('EDITOR')) {
    if (Properties::get('template-images-lazyload-use')) {
        $arParams['LIST_LAZYLOAD_USE'] = 'Y';
        $arParams['DETAIL_LAZYLOAD_USE'] = 'Y';
    }
}