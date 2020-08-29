<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

__IncludeLang(dirname(__FILE__) . "/lang/" . LANGUAGE_ID . "/vk.php");
$name = "vk";
$title = GetMessage("BOOKMARK_HANDLER_VK");
$icon_url_template = "<script>\n" .
    "if (__function_exists('vk_click') == false) \n" .
    "{\n" .
    "function vk_click(url) \n" .
    "{ \n" .
    "window.open('http://vkontakte.ru/share.php?url='+encodeURIComponent(url),'sharer','toolbar=0,status=0,width=626,height=436'); \n" .
    "return false; \n" .
    "} \n" .
    "}\n" .
    "</script>\n" .
    "<a class='subscribe-popup__button vk'	href=\"http://vkontakte.ru/share.php?url=#PAGE_URL#\" 	onclick=\"return vk_click('#PAGE_URL#');\" 	target=\"_blank\" title=\"" . $title . "\"> " .
    "<div class=\"subscribe-popup__button-icon-wrapper\">" .
    "<svg class=\"subscribe-popup__button-icon\" width=\"30\" height=\"17\">" .
    "<use xlink:href=\"/local/templates/kit_origami/assets/img/sprite.svg#icon_vk\"></use>" .
    "</svg>" .
    " </div>" .
    "<div class=\"subscribe-popup__button-description\"><span>" .
        GetMessage('VK_TITLE') .
    "</span></div>" .
    "</a>\n";
$sort = 400;
?>
