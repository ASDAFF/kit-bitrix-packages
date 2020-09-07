<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

$GLOBALS['BreadCrumbIBlockType'] = '#PRODUCTS_IBLOCK_TYPE#';
$GLOBALS['BreadCrumbIBlockId'] = '#PRODUCTS_IBLOCK_ID#';

?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:breadcrumb',
    '',
    array(),
    false
); ?>