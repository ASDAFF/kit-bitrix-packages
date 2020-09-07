<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
	"intec.universe:sale.basket.small", 
	"notifications.1", 
	array(
		"BASKET_URL" => "#SITE_DIR#personal/basket/"
	),
	false
); ?>