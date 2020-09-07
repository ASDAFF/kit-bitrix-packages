<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:menu',
    'vertical.1',
    array(
        'MAX_LEVEL' => 2
    ),
    false
); ?>