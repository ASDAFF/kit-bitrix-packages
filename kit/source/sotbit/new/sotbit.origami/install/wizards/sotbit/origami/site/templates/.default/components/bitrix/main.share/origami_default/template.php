<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Conversion\Internals\MobileDetect;
$detect = new MobileDetect;
?>

<?
if (strlen($arResult["PAGE_URL"]) > 0) {
    if (is_array($arResult["BOOKMARKS"]) && count($arResult["BOOKMARKS"]) > 0) {
        foreach ($arResult["BOOKMARKS"] as $name => $arBookmark) {

            if ($name == 'viber' || $name == 'whatsapp') {
                if ($detect->isMobile()) {
                    echo $arBookmark["ICON"];
                }
            } else {
                echo $arBookmark["ICON"];
            }

        }
    }
} else {
    ?><?= GetMessage("SHARE_ERROR_EMPTY_SERVER") ?><?
}
?>
