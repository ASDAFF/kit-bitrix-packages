<?

use Bitrix\Main\Page\Asset;
use Sotbit\Origami\Helper\Config;

$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
Asset::getInstance()->addcss(SITE_DIR . "include/sotbit_origami/contact_page_block/contacts_info_new/style.css");
//$useRegion = false;
?>

<?
if (($useRegion && $_SESSION['SOTBIT_REGIONS']['MAP_YANDEX']) || ($useRegion && $_SESSION['SOTBIT_REGIONS']['MAP_GOOGLE']) ) {
    if ($_SESSION['SOTBIT_REGIONS']['MAP_YANDEX']) {
        $yandex_lat = explode(',', $_SESSION['SOTBIT_REGIONS']['MAP_YANDEX'][0]['VALUE'])[0];
        $yandex_lon = explode(',', $_SESSION['SOTBIT_REGIONS']['MAP_YANDEX'][0]['VALUE'])[1];
        $position['yandex_lat'] = $yandex_lat;
        $position['yandex_lon'] = $yandex_lon;
        $position['yandex_scale'] = 14;
        $position['PLACEMARKS'] = array(
            array(
                'LON' => $yandex_lon,
                'LAT' => $yandex_lat,
                'TEXT' => $_SESSION['SOTBIT_REGIONS']['MAP_YANDEX'][0]['DESCRIPTION']
            )
        );
        $APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
            array(
                "KEY" => $_SESSION["SOTBIT_REGIONS"]["MAP_YANDEX"]["API_KEY"],
                "INIT_MAP_TYPE" => "MAP",
                "MAP_DATA" => serialize($position),
                "MAP_WIDTH" => "100%",
                "MAP_HEIGHT" => "400",
                "CONTROLS" => array(
                    0 => "ZOOM",
                    1 => "MINIMAP",
                    2 => "TYPECONTROL",
                    3 => "SCALELINE",
                ),
                "OPTIONS" => array(
                    0 => "ENABLE_SCROLL_ZOOM",
                    1 => "ENABLE_DBLCLICK_ZOOM",
                    2 => "ENABLE_DRAGGING",
                ),
                "MAP_ID" => "main_region",
                "COMPONENT_TEMPLATE" => ".default",
                "API_KEY" => $_SESSION["SOTBIT_REGIONS"]["MAP_YANDEX"]["API_KEY"]
            ),
	false
        );
    } elseif ($_SESSION['SOTBIT_REGIONS']['MAP_GOOGLE']) {
        $google_lat = explode(',', $_SESSION['SOTBIT_REGIONS']['MAP_GOOGLE'][0]['VALUE'])[0];
        $google_lon = explode(',', $_SESSION['SOTBIT_REGIONS']['MAP_GOOGLE'][0]['VALUE'])[1];
        $position['google_lat'] = $google_lat;
        $position['google_lon'] = $google_lon;
        $position['google_scale'] = 14;
        $position['PLACEMARKS'] = array(
            array(
                'LON' => $google_lon,
                'LAT' => $google_lat,
                'TEXT' => $_SESSION['SOTBIT_REGIONS']['MAP_GOOGLE'][0]['DESCRIPTION']
            )
        );
        $APPLICATION->IncludeComponent(
            "bitrix:map.google.view",
            ".default",
            array(
                "KEY" => $_SESSION["SOTBIT_REGIONS"]["MAP_GOOGLE"]["API_KEY"],
                "INIT_MAP_TYPE" => "MAP",
                "MAP_DATA" => serialize($position),
                "MAP_WIDTH" => "100%",
                "MAP_HEIGHT" => "400",
                "CONTROLS" => array(
                    0 => "ZOOM",
                    1 => "MINIMAP",
                    2 => "TYPECONTROL",
                    3 => "SCALELINE",
                ),
                "OPTIONS" => array(
                    0 => "ENABLE_SCROLL_ZOOM",
                    1 => "ENABLE_DBLCLICK_ZOOM",
                    2 => "ENABLE_DRAGGING",
                ),
                "MAP_ID" => "main_region",
                "COMPONENT_TEMPLATE" => ".default",
                "API_KEY" => $_SESSION["SOTBIT_REGIONS"]["MAP_GOOGLE"]["API_KEY"]
            ),
            false
        );
    }
} else {
    $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/sotbit_origami/contacts_map.php")
    );
}

?>

<div class="contact-techno_block">
    <div class="contact-techno_block-item">
        <div class="contact-techno_block-img">
            <svg class="contact__techno_block-icon contacts_icon_location">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_contacts_pin"></use>
            </svg>
        </div>
        <div class="contact-techno-item_content">
            <div class="contact-techno-content-title">
                <?= GetMessage('ADDRESS') ?>
            </div>
            <div class="contact-techno-content-text">
                <? if ($useRegion && $_SESSION['SOTBIT_REGIONS']['UF_ADDRESS']) {
                    echo $_SESSION["SOTBIT_REGIONS"]["NAME"] . ', ' . $_SESSION["SOTBIT_REGIONS"]["UF_ADDRESS"];
                } else {
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/sotbit_origami/contact_areas/contact_page_address.php"
                        )
                    );
                }
                ?>
                <br>
            </div>
        </div>
    </div>
    <div class="contact-techno_block-item">
        <div class="contact-techno_block-img">
            <svg class="contact__techno_block-icon contacts_icon_phone">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_contacts_phone"></use>
            </svg>
        </div>
        <div class="contact-techno-item_content">
            <div class="contact-techno-content-title">
                <?= GetMessage('PHONE') ?>
            </div>
            <div class="contact-techno-content-text">
                <?
                if ($useRegion && $_SESSION["SOTBIT_REGIONS"]["UF_PHONE"]) {
                    if (is_array($_SESSION["SOTBIT_REGIONS"]["UF_PHONE"])) {
                        foreach (
                            $_SESSION["SOTBIT_REGIONS"]["UF_PHONE"] as $numtel
                        ) {
                            echo $numtel . '<br>';
                        }
                    } else {
                        echo $_SESSION["SOTBIT_REGIONS"]["UF_PHONE"];
                    }
                } else {
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/sotbit_origami/contact_areas/contact_page_phone.php"
                        )
                    );
                }
                ?>
            </div>
        </div>
    </div>
    <div class="contact-techno_block-item">
        <div class="contact-techno_block-img">
            <svg class="contact__techno_block-icon contacts_icon_mail">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_contacts_mail"></use>
            </svg>
        </div>
        <div class="contact-techno-item_content">
            <div class="contact-techno-content-title">
                <?= GetMessage('EMAIL') ?>
            </div>
            <div class="contact-techno-content-text">
                <? if ($useRegion && $_SESSION["SOTBIT_REGIONS"]["UF_EMAIL"]) {
                    if (is_array($_SESSION["SOTBIT_REGIONS"]["UF_EMAIL"])) {
                        foreach ($_SESSION["SOTBIT_REGIONS"]["UF_EMAIL"] as $email) {
                            if ($email) {
                                ?>
                                <a href="mailto:<?= $email ?>">
                                    <?= $email ?>
                                </a>
                                <?
                            }
                        }
                    } else {
                        ?>
                        <a href="mailto:<?= $_SESSION["SOTBIT_REGIONS"]["UF_EMAIL"] ?>">
                            <?=$_SESSION["SOTBIT_REGIONS"]["UF_EMAIL"]; ?>
                        </a>
                        <?
                    }
                    ?>
                    <?
                } else {
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/sotbit_origami/contact_areas/contact_page_email.php"
                        )
                    );
                }
                ?>
            </div>
        </div>
    </div>
    <div class="contact-techno_block-item">
        <div class="contact-techno_block-img">
            <svg class="contact__techno_block-icon contacts_icon_clock">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_contacts_clock"></use>
            </svg>
        </div>
        <div class="contact-techno-item_content">
            <div class="contact-techno-content-title">
                <?= GetMessage('TIMES') ?>
            </div>
            <div class="contact-techno-content-text">
                <?
                if ($useRegion && $_SESSION["SOTBIT_REGIONS"]["UF_WORKTIME"]) {
                    if (is_array($_SESSION["SOTBIT_REGIONS"]["UF_WORKTIME"])) {
                        foreach (
                            $_SESSION["SOTBIT_REGIONS"]["UF_WORKTIME"] as $workTime
                        ) {
                            echo $workTime . '<br>';
                        }
                    } else {
                        echo $_SESSION["SOTBIT_REGIONS"]["UF_WORKTIME"];
                    }
                } else {
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/sotbit_origami/contact_areas/contact_page_timework.php"
                        )
                    );
                }
                ?>
            </div>
        </div>
    </div>
</div>
