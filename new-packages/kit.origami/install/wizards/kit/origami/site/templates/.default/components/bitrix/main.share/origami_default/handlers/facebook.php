<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

__IncludeLang(dirname(__FILE__) . "/lang/" . LANGUAGE_ID . "/facebook.php");
$name = "facebook";
$title = GetMessage("BOOKMARK_HANDLER_FACEBOOK");
$icon_url_template = "<script>\n" .
    "if (__function_exists('fbs_click') == false) \n" .
    "{\n" .
    "function fbs_click(url, title) \n" .
    "{ \n" .
    "window.open('http://www.facebook.com/share.php?u='+encodeURIComponent(url)+'&t='+encodeURIComponent(title),'sharer','toolbar=0,status=0,width=626,height=436'); \n" .
    "return false; \n" .
    "} \n" .
    "}\n" .
    "</script>\n" .
    "<a class=\"subscribe-popup__button fb\" href=\"http://www.facebook.com/share.php?u=#PAGE_URL#&t=#PAGE_TITLE#\" onclick=\"return fbs_click('#PAGE_URL#', '#PAGE_TITLE#');\" target=\"_blank\" title=\"" . $title . "\">" .
    "<div class=\"subscribe-popup__button-icon-wrapper\">" .
    "<svg class=\"subscribe-popup__button-icon\" width=\"12\" height=\"28\">" .
    "<use xlink:href=\"/local/templates/kit_origami/assets/img/sprite.svg#icon_facebook\"></use>" .
    "</svg>" .
    "</div>" .
    "<div class=\"subscribe-popup__button-description\"><span>" .
    GetMessage('BOOKMARK_HANDLER_FACEBOOK') .
    "</span></div>" .
    "</a>\n";
$sort = 100;
?>
