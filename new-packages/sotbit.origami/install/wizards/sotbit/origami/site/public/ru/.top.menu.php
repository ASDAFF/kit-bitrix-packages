<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Loader::includeModule('sotbit.origami');
$aMenuLinks = Array(
	Array(
		"Как купить", 
		"about/howto/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Доставка", 
		"about/delivery/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"О магазине", 
		"about/", 
		Array(), 
		Array(), 
		"" 
	),	
	Array(
		"Гарантия", 
		"about/guaranty/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Контакты",
		"about/contacts/",
		Array(),
		Array(),
		""
	),
	Array(
		"Мой кабинет",
        \Sotbit\Origami\Helper\Config::get('PERSONAL_PAGE'),
		Array(),
		Array(),
		"CUser::IsAuthorized()"
	),
);
?>