<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Loader::includeModule('sotbit.origami');
$aMenuLinks = Array(
	Array(
		"Текущий заказ",
		"personal/cart/",
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Личный счет",
		"personal/account/",
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Подписка",
		"personal/subscribe/",
		Array(), 
		Array(), 
		"" 
	),	
	Array(
		"Выйти",
		"?logout=yes",
		Array(), 
		Array(), 
		"" 
	),

);
?>