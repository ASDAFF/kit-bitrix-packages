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
    $properties = Properties::getCollection();

    if (!empty($properties)) {
        $arParams['REGIONALITY_USE'] = $properties->get('base-regionality-use') ? 'Y' : 'N';
        $arParams['SEARCH_MODE'] = $properties->get('base-search-mode');
        $arParams['PRODUCTS_VIEWED_SHOW'] = $properties->get('footer-products-viewed-show') ? 'Y' : 'N';
        $arParams['TEMPLATE'] = 'template.'.$properties->get('footer-template');
        $arParams['THEME'] = $properties->get('footer-theme');

        if ($properties->get('template-images-lazyload-use')) {
            $arParams['PRODUCTS_VIEWED_LAZYLOAD_USE'] = 'Y';
        }

        switch ($arParams['TEMPLATE']) {
            case 'template.2': {
                $arParams['SEARCH_SHOW'] = 'N';
                break;
            }
            case 'template.4': {
                $arParams['SEARCH_SHOW'] = 'N';
                break;
            }
            case 'template.5': {
                $arParams['LOGOTYPE_SHOW'] = 'N';
                $arResult['LOGOTYPE']['SHOW'] = false;
                break;
            }
            case 'template.6': {
                $arParams['LOGOTYPE_SHOW'] = 'N';
                $arResult['LOGOTYPE']['SHOW'] = false;
                break;
            }
        }
    }
}