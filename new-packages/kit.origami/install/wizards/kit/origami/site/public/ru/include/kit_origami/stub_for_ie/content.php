<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Loader;

Asset::getInstance()->addCss("/include/kit_origami/stub_for_ie/style.css");
CJSCore::Init(array("jquery", "popup"));
?>
<div id="stub_popup_content" style="display: none">
    <p class="stub__title">
        <?=Loc::getMessage("STUB_TITLE")?>
    </p>
    <p class="stub__message">
        <?=Loc::getMessage("STUB_MESSAGE")?>
    </p>
    <div class="stub__list-browser">
        <a class="stub__browser-chrome" href="https://www.google.by/chrome/">Google Chrome</a>
        <a class="stub__browser-opera" href="https://www.opera.com/ru/computer/thanks?ni=stable&os=windows">Opera</a>
        <a class="stub__browser-firefox" href="https://yandex.ru/firefox/download?from=lp_s">Firefox</a>
        <a class="stub__browser-edge" href="https://www.microsoft.com/ru-ru/edge">Microsoft Edge</a>
    </div>
    <?if(Loader::includeModule('kit.b2bshop')):?>
        <div class="stub__to_b2b-wrapper">
            <a class="stub__to_b2b" href="/b2bcabinet"><?=Loc::getMessage('GO_TO_B2B')?></a>
        </div>
    <?endif;?>
</div>
<script>
    BX.ready(function() {
        const stubPopup = new BX.PopupWindow(
            "stub_popup",
            null,
            {
                content: BX('stub_popup_content'),
                closeIcon: {right: "20px", top: "20px" },
                autoHide: true,
                closeByEsc: true,
                zIndex: 0,
                offsetLeft: 0,
                offsetTop: 0,
                overlay: true,
                className: 'stub_popup',
            });
        stubPopup.show();
    });
</script>
<?

