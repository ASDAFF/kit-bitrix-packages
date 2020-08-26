<?

if($arParams["FACEBOOK"])
    $arResult["SOCSERV"]["FACEBOOK"]["CLASS"] = "icon_facebook";
if($arParams["VKONTAKTE"])
    $arResult["SOCSERV"]["VKONTAKTE"]["CLASS"] = "icon_vk";
if($arParams["TWITTER"])
    $arResult["SOCSERV"]["TWITTER"]["CLASS"] = "icon_twitter";
if($arParams["GOOGLE"])
    $arResult["SOCSERV"]["GOOGLE"]["CLASS"] = "icon_google";
if($arParams["INSTAGRAM"])
    $arResult["SOCSERV"]["INSTAGRAM"]["CLASS"] = "icon_instagram";

if($arParams["YOUTUBE"])
{
    $arResult["SOCSERV"]["YOUTUBE"] = array(
        "LINK" => $arParams["YOUTUBE"],
        "CLASS" => "icon_youtube"
    );
}
if($arParams["ODNOKLASSNIKI"])
{
    $arResult["SOCSERV"]["ODNOKLASSNIKI"] = array(
		"LINK" => $arParams["ODNOKLASSNIKI"],
		"CLASS" => "icon_odnoklassniki"
	);
}
if($arParams["TELEGRAM"])
{
    $arResult["SOCSERV"]["TELEGRAM"] = array(
		"LINK" => $arParams["TELEGRAM"],
		"CLASS" => "icon_telegram"
	);
}
?>