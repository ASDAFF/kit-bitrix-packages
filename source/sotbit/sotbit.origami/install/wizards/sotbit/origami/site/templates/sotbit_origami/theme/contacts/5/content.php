<?

use Bitrix\Main\Page\Asset;
use Sotbit\Origami\Helper\Config;

$theme = new \Sotbit\Origami\Front\Theme();

Asset::getInstance()->addcss("/local/templates/sotbit_origami/theme/contacts/5/style.css");
Asset::getInstance()->addCss($theme->getTheme() . "/style-contacts.css");
$shopId = \Sotbit\Origami\Config\Option::get('IBLOCK_ID_SHOP');
$mainSection = CIBlock::GetList("ASC", array("ID" => $shopId))->Fetch();

?>
<div class="contacts-block">
    <div class="contacts-content-size-wrapper">

        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/sotbit_origami/contact_page_block/contacts_info_new/contacts_info_new.php"));
        ?>

        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/sotbit_origami/contact_page_block/contacts_description_slider/contacts_description_slider.php"));
        ?>

        <h2 class="contacts-block__title"><?= $mainSection['NAME'] ?></h2>
        <?= empty($mainSection['DESCRIPTION']) ? '' : '<p class="contacts-block__regions-description">'. $mainSection['DESCRIPTION'] .'</p>' ?>
    </div>

    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/sotbit_origami/contact_page_block/regional_office_5/regional_office.php"));
    ?>

    <div class="contacts-callback-wrapper">
        <div class="contacts-content-size-wrapper">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:form.result.new",
                "origami_webform_new",
                Array(
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "A",
                    "CHAIN_ITEM_LINK" => "",
                    "CHAIN_ITEM_TEXT" => "",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "EDIT_URL" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "N",
                    "LIST_URL" => "",
                    "AJAX_MODE" => "Y",
                    "SEF_MODE" => "N",
                    "SUCCESS_URL" => "",
                    "USE_EXTENDED_ERRORS" => "N",
                    "VARIABLE_ALIASES" => Array(
                        "RESULT_ID" => "RESULT_ID",
                        "WEB_FORM_ID" => "WEB_FORM_ID"
                    ),
                    "WEB_FORM_ID" => 3
                )
            ); ?>
        </div>
    </div>
</div>
