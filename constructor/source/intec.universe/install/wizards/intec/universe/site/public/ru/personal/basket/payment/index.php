<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->SetTitle("Оплата заказа");

?>
<?php $APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment",
	"",
	Array(
	)
); ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php") ?>