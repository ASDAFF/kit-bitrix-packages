<?
\Bitrix\Main\Loader::includeModule('sotbit.origami');
$aMenuLinks = Array(
	Array(
		"Мой кабинет",
        \Sotbit\Origami\Helper\Config::get('PERSONAL_PAGE'),
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Текущие заказы",
        \Sotbit\Origami\Helper\Config::get('PERSONAL_ORDER_PAGE'),
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Личный счет", 
		"personal/account/", 
		Array(), 
		Array(), 
		"CBXFeatures::IsFeatureEnabled('SaleAccounts')" 
	),
	Array(
		"Личные данные", 
		"personal/private/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"История заказов",
        \Sotbit\Origami\Helper\Config::get('PERSONAL_ORDER_PAGE')."?filter_history=Y",
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Профили заказов", 
		"personal/profiles/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Корзина",
        \Sotbit\Origami\Helper\Config::get('BASKET_PAGE'),
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Подписки", 
		"personal/subscribe/", 
		Array(), 
		Array(), 
		"" 
	)
);
?>