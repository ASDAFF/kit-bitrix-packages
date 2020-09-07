<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Sotbit\Origami\Helper\Config;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addCss(SITE_DIR . "include/sotbit_origami/files/origami_subscribe/style.css");

Loader::includeModule('sotbit.origami');
$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . $arParams['URL_PAGE'];

?>
<div class="subscribe-popup">
    <div class="subscribe-popup__title">
        <span><?= GetMessage('SUBSCRIBE_TITLE'); ?></span>
    </div>
    <?
    $rsHandlers = Config::get('SHARE_HANDLERS');
    $arHandlers = array();
    if (unserialize($rsHandlers)) {
        foreach (unserialize($rsHandlers) as $item) {
            $arHandlers[] = $item;
        }
    }

    ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.share",
        "origami_default",
        Array(
            "HANDLERS" => $arHandlers,
            "HIDE" => "N",
            "PAGE_TITLE" => "",
            "PAGE_URL" => "",
            "SHORTEN_URL_KEY" => "",
            "SHORTEN_URL_LOGIN" => ""
        )
    ); ?>
</div>
