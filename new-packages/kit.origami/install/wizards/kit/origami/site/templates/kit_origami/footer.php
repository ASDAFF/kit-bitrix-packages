<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Kit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

$page = \KitOrigami::getCurrentPage();

if(!\KitOrigami::needShowFullWidth($page)) {
    if(\KitOrigami::needShowSide($page)) {
    ?>
        </div>
    </div>

    <?} else {
        ?>
        </div>
    </div>
        <?
    }
}
?>

    <!-- </div>
</div> -->

<!-- footer -->
<?
include $_SERVER['DOCUMENT_ROOT'].'/'.\KitOrigami::footersDir.'/'.Config::get('FOOTER').'/content.php';
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.\KitOrigami::footersDir.'/'
    .Config::get('FOOTER').'/style.css')
) {
    Asset::getInstance()->addCss(\KitOrigami::footersDir.'/'
        .Config::get('FOOTER').'/style.css');
}
?>

<?
//Schema org breadcrumb
if( \Bitrix\Main\Loader::includeModule('kit.schemaorg') && (strpos($APPLICATION->GetCurPage(), "bitrix") === false) ) {
    Kit\Schemaorg\EventHandlers::makeContent($APPLICATION->GetCurPage(false), 'breadcrumblist');

    $data = SchemaMain::getData();
    if($data) {
        foreach ($data as $k => &$dat) {
            if ($dat['@type'] == 'breadcrumblist') {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

                if(!empty($APPLICATION->arAdditionalChain)) {
                    $arChain = $APPLICATION->arAdditionalChain;
                    foreach ($arChain as $key => $item) {
                        unlink($dat['itemListElement'][$key]);
                        $dat['itemListElement'][$key]['@type'] = "ListItems";
                        $dat['itemListElement'][$key]['name'] = $item['TITLE'];
                        $dat['itemListElement'][$key]['item'] = $protocol . $_SERVER['SERVER_NAME'] . $item['LINK'];
                        $dat['itemListElement'][$key]['position'] = $key + 1;
                    }
                }

                SchemaMain::setData($data);
            }
        }
    }
}
?>

<!-- end footer -->
<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."include/kit_origami/after_footer.php"
    )
);?>

<!-- btn go top -->
<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."include/kit_origami/btn_go_top/btn_top.php"
    )
);?>

<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."include/kit_origami/files/btn_error-share/btn_error-share.php"
    )
);?>

<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR."include/kit_origami/files/metric.php"
    )
);?>
<!-- end btn go top -->

<?
if (Config::get('BASKET_TYPE') == 'origami_top_without_basket') {
    Asset::getInstance()->addJs(SITE_DIR . "local/react/kit/rightpanel.basket/dist/main.js");
    Asset::getInstance()->addCss(SITE_DIR . "local/react/kit/rightpanel.basket/dist/main.css");
}
?>

</body>
</html>
