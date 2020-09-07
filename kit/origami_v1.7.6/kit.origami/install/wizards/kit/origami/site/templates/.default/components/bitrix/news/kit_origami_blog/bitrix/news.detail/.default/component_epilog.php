<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if(Bitrix\Main\Loader::includeModule('kit.opengraph')) {
    OpengraphMain::setImageMeta('og:image', $arResult['DETAIL_PICTURE']["SRC"]);
    OpengraphMain::setImageMeta('twitter:image', $arResult['DETAIL_PICTURE']["SRC"]);
    OpengraphMain::setMeta('og:type', 'article');
    OpengraphMain::setMeta('og:title', $arResult["NAME"]);
    OpengraphMain::setMeta('og:description', $arResult["PREVIEW_TEXT"]);
    OpengraphMain::setMeta('twitter:title', $arResult["NAME"]);
    OpengraphMain::setMeta('twitter:description', $arResult["PREVIEW_TEXT"]);
    OpengraphMain::setMeta('twitter:image:alt', $arResult['DETAIL_PICTURE']["ALT"]);
}


