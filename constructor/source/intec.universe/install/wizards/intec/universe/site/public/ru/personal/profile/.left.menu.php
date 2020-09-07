<?

use Bitrix\Main\ModuleManager;

$aMenuLinks = Array(
	Array(
		"Личные данные", 
		"#SITE_DIR#personal/profile/user/",
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Мои заказы", 
		"#SITE_DIR#personal/profile/orders/",
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Сменить пароль", 
		"#SITE_DIR#personal/profile/user/pass.php",
		Array(), 
		Array(), 
		"" 
	)
);

if (ModuleManager::isModuleInstalled('subscribes')) {
    $aMenuLinks[] = Array(
        "Рассылки",
        "#SITE_DIR#personal/profile/mailing/",
        Array(),
        Array(),
        ""
    );
}

?>