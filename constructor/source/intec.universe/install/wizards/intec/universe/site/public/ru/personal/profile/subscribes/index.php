<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Подписки");

?>
<?php $APPLICATION->IncludeComponent(
	"bitrix:catalog.product.subscribe.list", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"LINE_ELEMENT_COUNT" => "3",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000"
	),
	false
); ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
