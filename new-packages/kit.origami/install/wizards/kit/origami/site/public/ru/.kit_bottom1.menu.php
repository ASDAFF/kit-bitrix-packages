<?
\Bitrix\Main\Loader::includeModule('kit.origami');
$aMenuLinks = Array(
	Array(
		"Оплата", 
		"help/payment/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Возврат товара", 
		"help/return/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Доставка", 
		"help/delivery/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Как оформить заказ", 
		"help/checkout/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Правила продажи товаров", 
		"help/rules/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Публичная оферта", 
		"help/oferta/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Конфиденциальность", 
		\Kit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE'),
		Array(), 
		Array(), 
		"" 
	),
);
?>