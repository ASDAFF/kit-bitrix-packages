<?
use Bitrix\Main\Page\Asset;
use Kit\Origami\Helper\Config;

Asset::getInstance()->addcss("/local/templates/kit_origami/theme/contacts/2/style.css");
CJSCore::Init(array("date"));
?>

<div class="contact__techno_block">

    <?
    if(\KitOrigami::isUseRegions() && $_SESSION['KIT_REGIONS']['MAP_YANDEX']){
        ?>
	    #KIT_REGIONS_MAP_YANDEX#
        <?
    }
    else{
        $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            [
                "AREA_FILE_SHOW" => "file",
                "PATH"           => SITE_DIR."include/kit_origami/contacts_map.php",
            ]
        );
    }
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/contacts_info/contacts_info.php"));
    ?>

    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "origami_webform_2",
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
            "SEF_MODE" => "N",
            "AJAX_MODE" => "Y",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array(
                "RESULT_ID" => "RESULT_ID",
                "WEB_FORM_ID" => "WEB_FORM_ID"
            ),
            "WEB_FORM_ID" => 3
        )
    );?>
</div>
