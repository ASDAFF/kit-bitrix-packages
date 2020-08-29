<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

__IncludeLang(dirname(__FILE__) . "/lang/" . LANGUAGE_ID . "/telegram.php");
$name = "telegram";
$title = GetMessage("BOOKMARK_HANDLER_TELEGRAM");
$icon_url_template = "<script>\n" .
    "if (__function_exists('telegram_click') == false) \n" .
    "{\n" .
    "function telegram_click(url) \n" .
    "{ \n" .
    "window.open('https://telegram.me/share/url?url='+encodeURIComponent(url),'sharer','toolbar=0,status=0,resizable=1,scrollbars=1,width=626,height=436'); \n" .
    "return false; \n" .
    "} \n" .
    "}\n" .
    "</script>\n" .
    "<a class=\"subscribe-popup__button telega\" href=\"https://telegram.me/share/url?url=#PAGE_URL#\" onclick=\"return telegram_click('#PAGE_URL#');\" target=\"_blank\" title=\"" . $title . "\">" .
    "<div class=\"subscribe-popup__button-icon-wrapper\">" .
    "<svg class=\"subscribe-popup__button-icon\" width=\"28\" height=\"22\">" .
    "<use xlink:href=\"/local/templates/sotbit_origami/assets/img/sprite.svg#icon_telegram\"></use>" .
    "</svg>" .
    "</div>" .
    "<div class=\"subscribe-popup__button-description\"><span>" .
        GetMessage('TELEGRAM_TITLE') .
    "</span></div>" .
    "</a>\n";
$sort = 600;
?>
