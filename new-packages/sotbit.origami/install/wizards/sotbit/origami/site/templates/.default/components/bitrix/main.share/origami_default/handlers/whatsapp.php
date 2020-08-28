<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

__IncludeLang(dirname(__FILE__) . "/lang/" . LANGUAGE_ID . "/whatsapp.php");
$name = "whatsapp";
$title = GetMessage("BOOKMARK_HANDLER_WHATSAPP");
$icon_url_template = "<script>\n" .
    "if (__function_exists('whatsapp_click') == false) \n" .
    "{\n" .
    "function whatsapp_click(url) \n" .
    "{ \n" .
    "window.open('whatsapp://send?text='+encodeURIComponent(url),'sharer','toolbar=0,status=0,resizable=1,scrollbars=1,width=626,height=436'); \n" .
    "return false; \n" .
    "} \n" .
    "}\n" .
    "</script>\n" .
    "<a class=\"subscribe-popup__button whatsapp\" href=\"whatsapp://send?text=#PAGE_URL#\" data-action=\"share/whatsapp/share\" onclick=\"return whatsapp_click('#PAGE_URL#');\" target=\"_blank\" title=\"" . $title . "\">" .
    "<div class=\"subscribe-popup__button-icon-wrapper\">" .
    "<img src=\"/local/templates/.default/components/bitrix/main.share/origami_default/images/whatsapp_new.png\">" .
    " </div>" .
    "<div class=\"subscribe-popup__button-description\"><span>" .
    GetMessage('BOOKMARK_HANDLER_WHATSAPP') .
    "</span></div>" .
    "</a>\n";
$sort = 600;
?>


