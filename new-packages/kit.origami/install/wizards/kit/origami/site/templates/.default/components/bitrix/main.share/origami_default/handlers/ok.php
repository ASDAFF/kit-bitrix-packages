<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

__IncludeLang(dirname(__FILE__) . "/lang/" . LANGUAGE_ID . "/ok.php");
$name = "ok";
$title = GetMessage("BOOKMARK_HANDLER_OK");
$icon_url_template = "<script>\n" .
    "if (__function_exists('ok_click') == false) \n" .
    "{\n" .
    "function ok_click(url) \n" .
    "{ \n" .
    "window.open('https://connect.ok.ru/offer?url='+encodeURIComponent(url),'sharer','toolbar=0,status=0,resizable=1,scrollbars=1,width=626,height=436'); \n" .
    "return false; \n" .
    "} \n" .
    "}\n" .
    "</script>\n" .
    "<a class=\"subscribe-popup__button ok\" href=\"https://connect.ok.ru/offer?url=#PAGE_URL#\" onclick=\"return ok_click('#PAGE_URL#');\" target=\"_blank\" title=\"" . $title . "\">" .
    "<div class=\"subscribe-popup__button-icon-wrapper\">" .
    "<svg class=\"subscribe-popup__button-icon\" width=\"20\" height=\"32\">" .
    "<use xlink:href = \"/local/templates/kit_origami/assets/img/sprite.svg#icon_odnoklassniki\"></use>" .
    "</svg>" .
    "</div>" .
    "<div class=\"subscribe-popup__button-description\"><span>" .
    GetMessage('OK_TITLE') .
    "</span></div> " .
    "</a>\n";
$sort = 600;
?>
