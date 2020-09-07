<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

__IncludeLang(dirname(__FILE__) . "/lang/" . LANGUAGE_ID . "/viber.php");
$name = "viber";
$title = GetMessage("BOOKMARK_HANDLER_VIBER");
$icon_url_template = "<script>\n" .
    "if (__function_exists('viber_click') == false) \n" .
    "{\n" .
    "function viber_click(url) \n" .
    "{ \n" .
    "window.open('viber://forward?text='+encodeURIComponent(url),'sharer','toolbar=0,status=0,resizable=1,scrollbars=1,width=626,height=436'); \n" .
    "return false; \n" .
    "} \n" .
    "}\n" .
    "</script>\n" .
    "<a  class=\"subscribe-popup__button viber\" href=\"viber://forward?text=#PAGE_URL#\" onclick=\"return viber_click('#PAGE_URL#');\" target=\"_blank\" title=\"" . $title . "\">" .
    "<div class=\"subscribe-popup__button-icon-wrapper\">" .
    "<img src=\"/local/templates/.default/components/bitrix/main.share/origami_default/images/viber_new.png\">" .
    " </div>" .
    "<div class=\"subscribe-popup__button-description\"><span>" .
        GetMessage('BOOKMARK_HANDLER_VIBER') .
    "</span></div>" .
    "</a>\n";
$sort = 600;
?>
