<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\template\Properties;

/**
 * @var array $arParams
 */

if (!defined('EDITOR')) {
    $arSettings = [
        'LIST' => [
            'TEMPLATE' => Properties::get('sections-shares-template')
        ]
    ];

    switch ($arSettings['LIST']['TEMPLATE']) {
        case 'blocks.1': {
            $arParams['LIST_TEMPLATE'] = 'blocks.1';
            break;
        }
        case 'blocks.2': {
            $arParams['LIST_TEMPLATE'] = 'blocks.2';
            break;
        }
        case 'list.1': {
            $arParams['LIST_TEMPLATE'] = 'list.1';
            $arParams['LIST_DESCRIPTION_SHOW'] = 'Y';
            $arParams['LIST_DATE_SHOW'] = 'Y';
            break;
        }
    }

    $arParams['DETAIL_PRODUCTS_DELAY_USE'] = Properties::get('basket-delay-use') ? 'Y' : 'N';
    $arParams['DETAIL_PRODUCTS_USE_COMPARE'] = Properties::get('basket-compare-use') ? 'Y' : 'N';

    if (Properties::get('template-images-lazyload-use')) {
        $arParams['LIST_LAZYLOAD_USE'] = 'Y';
        $arParams['DETAIL_LAZYLOAD_USE'] = 'Y';
    }

    if (Properties::get('basket-use')) {
        $arParams['DETAIL_PRODUCTS_ACTION'] = 'buy';
        $arParams['DETAIL_PRODUCTS_QUICK_VIEW_ACTION'] = 'buy';
    } else {
        $arParams['DETAIL_PRODUCTS_DELAY_USE'] = 'N';
        $arParams['DETAIL_PRODUCTS_ACTION'] = 'order';
        $arParams['DETAIL_PRODUCTS_QUICK_VIEW_ACTION'] = 'detail';
    }
}