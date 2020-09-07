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
    if (Properties::get('template-images-lazyload-use'))
        $arParams['LAZYLOAD_USE'] = 'Y';

    $sTemplate = Properties::get('sections-certificates-template');

    if ($sTemplate === 'tiles.1') {
        $arParams['DESKTOP_TEMPLATE'] = 'tiles';
    } else if ($sTemplate === 'list.1') {
        $arParams['DESKTOP_TEMPLATE'] = 'list';
    }
}