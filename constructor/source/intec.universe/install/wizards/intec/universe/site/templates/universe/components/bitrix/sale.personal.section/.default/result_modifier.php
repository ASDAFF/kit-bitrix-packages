<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\template\Properties;

if ($arParams['SETTINGS_USE'] === 'Y') {
    if (!Properties::get('basket-use')) {
        $arParams['SHOW_BASKET_PAGE'] = 'N';
    }
}