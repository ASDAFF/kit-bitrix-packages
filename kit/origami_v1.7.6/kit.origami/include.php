<?
use Bitrix\Main\Loader;
use Kit\Origami\Config\Option;
use Kit\Origami\Helper\Config;

class KitOrigami
{
    const moduleId = 'kit.origami';
    const blockDir = SITE_DIR . 'include/blocks';
    const scssDir = '/local/templates/kit_origami/assets/scss';
    const defaultTheme = '/local/templates/kit_origami/assets/css';
    const customTheme = '/local/templates/kit_origami/assets/themes/custom';
    const headersDir = '/local/templates/kit_origami/theme/headers';
    const footersDir = '/local/templates/kit_origami/theme/footers';
    const contactsDir = '/local/templates/kit_origami/theme/contacts';
    const promotionsDir = '/local/templates/kit_origami/theme/promotions';
    const blogDir = '/local/templates/kit_origami/theme/blog';
    const ordersDir = '/local/templates/kit_origami/theme/orders';
    const filtersDir = '/local/templates/kit_origami/theme/filters';
    const detailsDir = '/local/templates/kit_origami/theme/details';
    const chunksDir = '/local/templates/kit_origami/theme/chunks';
    const templateDir = '/local/templates/kit_origami';
    const sectionRootTemplateDir = '/local/templates/kit_origami/theme/category_root';
    public static $_1258532776 = 0;
    public static $_1227326863 = false;
    public static $_140594632 = false;
    public static $_1191450594 = array();
    public static $_353950835 = true;
    static private $_1139810130 = null;


    private static function __881263698()
    {
        self::$_1139810130 = \Bitrix\Main\Loader::includeSharewareModule(self::moduleId);
    }

    public static function genTheme($_1025411178 = [], $_910798875 = '')
    {

        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . \KitOrigami::moduleId . '/classes/scss.php';
        $_1420397029 = new scssc();
        $_117692537 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . \KitOrigami::scssDir . '/variables.scss');
        foreach ($_1025411178 as $_1187391137 => $_1156836584) {
            switch ($_1187391137) {
                case 'COLOR_BASE':
                    $_117692537 = str_replace('$main_color: #fb0040;', '$main_color: ' . $_1156836584 . ';', $_117692537);
                    break;
                case 'FONT_BASE':
                    $_117692537 = str_replace('$main_font: \'Open Sans\', Arial, sans-serif;', '$main_font: "' . $_1156836584 . '";', $_117692537);
                    break;
                case 'FONT_BASE_SIZE':
                    $_117692537 = str_replace('$main_content_font_size: 14px;', '$main_content_font_size: ' . $_1156836584 . ';', $_117692537);
                    $_67407681 = $_1156836584 - 1;
                    $_117692537 = str_replace('$main_content_font_size_mobile: 13px !important;', '$main_content_font_size_mobile: ' . $_67407681 . ' !important;', $_117692537);
                    break;
                case 'WIDTH':
                    $_117692537 = str_replace('$main_width: 1344px;', '$main_width: ' . $_1156836584 . ';', $_117692537);
                    break;
            }
        }
        $_1745162285 = scandir($_SERVER['DOCUMENT_ROOT'] . \KitOrigami::scssDir);
        foreach ($_1745162285 as $_113718537) {
            if (in_array($_113718537, ['.', '..', 'variables.scss',]) || strpos($_113718537, '.scss') === false) {
                continue;
            }
            $_143942720 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . KitOrigami::scssDir . '/' . $_113718537);
            $_449085564 = $_1420397029->compile($_117692537 . $_143942720);
            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $_910798875)) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . $_910798875);
            }
            $_449085564 = str_replace('@import "variables.scss";', '', $_449085564);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . $_910798875 . '/' . str_replace('.scss', '.css', $_113718537), $_449085564);
        }
    }

    public static function showAddress()
    {

        if (self::isUseRegions() && $_SESSION['KIT_REGIONS']['UF_ADDRESS']) {
            echo $_SESSION['KIT_REGIONS']['UF_ADDRESS'];
        } else {
            global $APPLICATION;
            $APPLICATION->IncludeComponent('bitrix:main.include', '', ['AREA_FILE_SHOW' => 'file', 'PATH' => SITE_DIR . 'include/kit_origami/contacts_address.php',]);
        }
    }

    public static function showPhone($_171918145 = '')
    {

        if (self::isUseRegions() && $_SESSION['KIT_REGIONS']['UF_PHONE']) {
            if (is_array($_SESSION['KIT_REGIONS']['UF_PHONE'])) {
                $_SESSION['KIT_REGIONS']['UF_PHONE'] = array_diff($_SESSION['KIT_REGIONS']['UF_PHONE'], ['', null]);
            }
            if (is_array($_SESSION['KIT_REGIONS']['UF_PHONE']) && count($_SESSION['KIT_REGIONS']['UF_PHONE']) > 1) {
                if (strpos($_171918145, 'footer') !== false) {
                    foreach ($_SESSION['KIT_REGIONS']['UF_PHONE'] as $_1960288759 => $_276922068) {
                        if ($_1960288759 == 0) {
                            continue;
                        }
                        echo '' . self::showDigitalPhone($_276922068) . '" class="' . $_171918145 . '">' . $_276922068 . '';
                    }
                } else {
                    $_276922068 = reset($_SESSION['KIT_REGIONS']['UF_PHONE']);
                    echo '' . self::showDigitalPhone($_276922068) . '" class="' . $_171918145 . ' origami_icons_button">' . $_276922068 . '';
                    if (!empty($_SESSION['KIT_REGIONS']['UF_PHONE'])) {
                        echo '';
                        foreach ($_SESSION['KIT_REGIONS']['UF_PHONE'] as $_1960288759 => $_276922068) {
                            if ($_1960288759 == 0) {
                                continue;
                            }
                            echo '' . self::showDigitalPhone($_276922068) . '" class="header_top_block__phone__number origami_icons_button">' . $_276922068 . '';
                        }
                        echo '';
                    }
                }
            } elseif (is_array($_SESSION['KIT_REGIONS']['UF_PHONE'])) {
                echo '' . self::showDigitalPhone(reset($_SESSION['KIT_REGIONS']['UF_PHONE'])) . '" class="' . $_171918145 . '">' . reset($_SESSION['KIT_REGIONS']['UF_PHONE']) . '';
            } else {
                echo '' . self::showDigitalPhone($_SESSION['KIT_REGIONS']['UF_PHONE']) . '" class="' . $_171918145 . '">' . $_SESSION['KIT_REGIONS']['UF_PHONE'] . '';
            }
        } else {
            $_276922068 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/kit_origami/contacts_phone.php');
            echo '' . $_171918145 . '" href="tel:' . self::showDigitalPhone($_276922068) . '">';
            global $APPLICATION;
            $APPLICATION->IncludeComponent('bitrix:main.include', '', ['AREA_FILE_SHOW' => 'file', 'PATH' => SITE_DIR . 'include/kit_origami/contacts_phone.php',]);
            echo '';
        }
    }

    public static function showDropDownPhones($_1334522753 = '', $_1592436967 = '', $_1313454871 = "")
    {
        if (self::isUseRegions() && $_SESSION["KIT_REGIONS"]["UF_PHONE"]) {
            self::showDropDownBlock($_SESSION["KIT_REGIONS"]["UF_PHONE"], $_1334522753, $_1592436967, $_1313454871, "tel:");
        } else {
            $_276922068 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/kit_origami/contacts_phone.php');
            echo '' . $_1334522753 . '">';
            global $APPLICATION;
            $APPLICATION->IncludeComponent('bitrix:main.include', '', ['AREA_FILE_SHOW' => 'file', 'PATH' => SITE_DIR . 'include/kit_origami/contacts_phone.php',]);
            echo '';
        }
    }

    public static function showDropDownEmails($_1334522753 = '', $_1592436967 = '', $_1313454871 = "")
    {
        if (self::isUseRegions() && $_SESSION["KIT_REGIONS"]["UF_EMAIL"]) {
            self::showDropDownBlock($_SESSION["KIT_REGIONS"]["UF_EMAIL"], $_1334522753, $_1592436967, $_1313454871, "mailto:");
        } else {
            $_410007577 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/kit_origami/contacts_email.php');
            echo '' . $_1334522753 . '">';
            global $APPLICATION;
            $APPLICATION->IncludeComponent('bitrix:main.include', '', ['AREA_FILE_SHOW' => 'file', 'PATH' => SITE_DIR . 'include/kit_origami/contacts_email.php',]);
            echo '';
        }
    }

    public static function showDropDownBlock($_447014131, $_1334522753 = '', $_1592436967 = '', $_1313454871 = "", $_55259947 = "")
    {

        if (is_array($_447014131)) {
            $_447014131 = array_unique(array_filter($_447014131, function ($_1708650976, $_494494843) {
                return !empty(trim($_1708650976));
            }, ARRAY_FILTER_USE_BOTH));
            if (empty($_447014131)) {
                return;
            }
            if (is_array($_447014131)) {
                if (count($_447014131) > 1) {
                    $_1334522753 .= (empty($_1334522753) ? '' : '') . 'dropdown_list';
                }
                $_1197286628 = reset($_447014131);
                echo "<div class='$_1334522753'><div class='main_element_wrapper'><a href='$_55259947" . $_1197286628 . "' class='$_1592436967'>" . $_1197286628 . '';
                if (!empty($_447014131)) {
                    echo '';
                    foreach ($_447014131 as $_791793763) {
                        echo "<a href='$_55259947" . $_791793763 . "' class='$_1313454871'>$_791793763</a>";
                    }
                    echo '';
                }
                echo '';
            } else {
                echo "<div class='$_1334522753'><div class='main_element_wrapper'>";
                echo "<a href='$_55259947" . $_447014131 . "'>$_447014131</a>";
                echo '';
            }
        } else {
            echo "<div class='$_1334522753'><div class='main_element_wrapper'>";
            echo "<a href='$_55259947" . $_447014131 . "'>$_447014131</a>";
            echo '';
        }
    }

    public static function showDigitalPhone($_276922068 = '')
    {

        $_276922068 = preg_replace('/[^0-9]/', '', $_276922068);
        $_276922068 = '+' . $_276922068;
        return $_276922068;
    }

    public static function isUseRegions($_14188243 = SITE_ID)
    {

        if (Loader::includeModule('kit.regions')/* && Config::get('USE_REGIONS') == 'Y'*/) {
            return true;
        } else {
            return false;
        }
    }

    public static function getAllPrices($_1851368466 = [])
    {

        global $USER;
        $_735641757 = [];
        if ($_1851368466['ITEMS']) {
            foreach ($_1851368466['ITEMS'] as $_1487017693) {
                if ($_1487017693['OFFERS']) {
                    foreach ($_1487017693['OFFERS'] as $_911141999) {
                        $_735641757[] = $_911141999['ID'];
                    }
                } else {
                    $_735641757[] = $_1487017693['ID'];
                }
            }
        } else {
            if ($_1851368466['OFFERS']) {
                foreach ($_1851368466['OFFERS'] as $_911141999) {
                    $_735641757[] = $_911141999['ID'];
                }
            } else {
                $_735641757[] = $_1851368466['ID'];
            }
        }
        $_1341982944 = [];
        if ($_1851368466['PRICES']) {
            foreach ($_1851368466['PRICES'] as $_1036628273) {
                $_1341982944[] = $_1036628273['ID'];
            }
        }
        if ($_1851368466['PRICES_ALLOW']) {
            $_1341982944 = [];
            foreach ($_1851368466['PRICES_ALLOW'] as $_1036628273) {
                $_1341982944[] = $_1036628273;
            }
        }
        $_420697642 = [];
        if (\KitOrigami::isUseRegions()) {
            if ($_SESSION['KIT_REGIONS']['PRICE_CODE']) {
                $_420697642 = $_SESSION['KIT_REGIONS']['PRICE_CODE'];
            }
        }
        if ($_735641757 && $_1341982944) {
            $_24297693 = [];
            $_908836893 = \CCatalogGroup::GetList([], ['ID' => $_1341982944]);
            while ($_1318862373 = $_908836893->Fetch()) {
                if ($_420697642 && !in_array($_1318862373['NAME'], $_420697642)) {
                    unset($_1341982944[array_search($_1318862373['ID'], $_1341982944)]);
                    continue;
                }
                $_24297693[$_1318862373['ID']] = ($_1318862373['NAME_LANG']) ? $_1318862373['NAME_LANG'] : $_1318862373['NAME'];
            }
            $_1019447164 = [];
            $_908836893 = \Bitrix\Catalog\PriceTable::getList(['filter' => ['PRODUCT_ID' => $_735641757, 'CATALOG_GROUP_ID' => $_1341982944,], 'select' => ['CATALOG_GROUP_ID', 'PRODUCT_ID', 'PRICE', 'CURRENCY', 'ID',],]);
            while ($_1091665988 = $_908836893->Fetch()) {
                $_331888933 = \CCatalogDiscount::GetDiscountByPrice($_1091665988['ID'], $USER->GetUserGroupArray(), 'N', SITE_ID);
                $_46201518 = \CCatalogProduct::CountPriceWithDiscount($_1091665988['PRICE'], $_1091665988['CURRENCY'], $_331888933);
                $_1091665988['DISCOUNT_PRICE'] = $_46201518;
                $_1091665988['PRINT_PRICE'] = \CCurrencyLang::CurrencyFormat($_1091665988['PRICE'], $_1091665988['CURRENCY']);
                $_1091665988['PRINT_DISCOUNT_PRICE'] = \CCurrencyLang::CurrencyFormat($_1091665988['DISCOUNT_PRICE'], $_1091665988['CURRENCY']);
                $_1019447164[$_1091665988['PRODUCT_ID']][$_1091665988['CATALOG_GROUP_ID']] = $_1091665988;
            }
            if ($_1851368466['ITEMS']) {
                foreach ($_1851368466['ITEMS'] as &$_1487017693) {
                    if ($_1487017693['OFFERS']) {
                        foreach ($_1487017693['OFFERS'] as &$_911141999) {
                            $_911141999['ALL_PRICES'] = $_1019447164[$_911141999['ID']];
                            $_911141999['ALL_PRICES_NAMES'] = $_24297693;
                        }
                    } else {
                        $_1487017693['ALL_PRICES'] = $_1019447164[$_1487017693['ID']];
                        $_1487017693['ALL_PRICES_NAMES'] = $_24297693;
                    }
                }
            } else {
                if ($_1851368466['OFFERS']) {
                    foreach ($_1851368466['OFFERS'] as &$_911141999) {
                        $_911141999['ALL_PRICES'] = $_1019447164[$_911141999['ID']];
                        $_911141999['ALL_PRICES_NAMES'] = $_24297693;
                    }
                } else {
                    $_1851368466['ALL_PRICES'] = $_1019447164[$_1851368466['ID']];
                    $_1851368466['ALL_PRICES_NAMES'] = $_24297693;
                }
            }
        }
        return $_1851368466;
    }

    public static function clearJSParams(&$_593082166 = [], $_14696249, $_300735641 = true)
    {
        if (isset($_593082166["OFFERS"])) {
            $_1897558165 = array();
            foreach ($_593082166['OFFERS'] as &$_92420712) {
                foreach ($_92420712 as $_1187391137 => $_1515536874) {
                    switch ($_1187391137) {
                        case 'PROPERTIES':
                            unset($_92420712[$_1187391137]);
                            break;
                        case 'TREE':
                            if (isset($_593082166['TREE_PROPS'])) {
                                foreach ($_593082166['TREE_PROPS'] as $_241618154 => $_924661907) {
                                    $_156466444 = $_924661907['ID'];
                                    if (isset($_924661907['VALUES']) && isset($_92420712[$_1187391137]['PROP_' . $_156466444])) {
                                        foreach ($_924661907['VALUES'] as $_2057363994 => &$_1031202569) {
                                            if ($_92420712[$_1187391137]['PROP_' . $_156466444] == $_2057363994) {
                                                $_1897558165[$_156466444][$_2057363994] = $_2057363994;
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        case 'MORE_PHOTO':
                            if (!$_300735641) unset($_92420712[$_1187391137]);
                            break;
                    }
                }
            }
            if (isset($_593082166['TREE_PROPS']) && !empty($_1897558165)) {
                foreach ($_593082166['TREE_PROPS'] as $_241618154 => &$_924661907) {
                    $_156466444 = $_924661907['ID'];
                    if (isset($_1897558165[$_156466444])) {
                        foreach ($_924661907['VALUES'] as $_2057363994 => &$_1031202569) {
                            if (!isset($_1897558165[$_156466444][$_2057363994])) {
                                unset($_924661907['VALUES'][$_2057363994]);
                            }
                        }
                    } else unset($_593082166['TREE_PROPS'][$_241618154]);
                }
            }
        }
    }

    public static function getAllNamePrices(&$_1851368466 = [])
    {
        $_1100295972 = array();
        if (isset($_1851368466['CAT_PRICES']) && !empty($_1851368466['CAT_PRICES'])) {
            foreach ($_1851368466['CAT_PRICES'] as $_1091665988) {
                $_1100295972[$_1091665988['ID']] = $_1091665988['TITLE'];
            }
        } elseif (isset($_1851368466['PRICES']) && !empty($_1851368466['PRICES'])) {
            foreach ($_1851368466['PRICES'] as $_1091665988) {
                $_1100295972[$_1091665988['ID']] = $_1091665988['TITLE'];
            }
            if (isset($_1851368466['ITEMS']) && !empty($_1100295972)) {
                foreach ($_1851368466['ITEMS'] as &$_1706386077) {
                    $_1706386077['ALL_PRICES_NAMES'] = $_1100295972;
                }
            }
        }
        return $_1100295972;
    }

    public static function getPriceDelta($_1851368466 = [], $_560213636)
    {
        $_1091665988 = $_2145267970 = array();
        if (Config::get('SKU_TYPE_' . $_560213636) == 'LIST_OF_MODIFICATIONS' && $_1851368466['OFFERS']) {
            $_1938214814 = '';
            $_1036628273 = 0;
            foreach ($_1851368466['OFFERS'] as $_92420712) {
                $_1036628273 = $_92420712['ITEM_PRICES'][0]['PRICE'];
                $_941493782 = $_92420712['ITEM_PRICES'][0]['PRINT_PRICE'];
                $_1938214814 = $_92420712['ITEM_PRICES'][0]['CURRENCY'];
                $_1091665988[$_1938214814][$_1036628273] = $_92420712['ITEM_PRICES'][0];
                ksort($_1091665988[$_1938214814]);
            }
            foreach ($_1091665988 as $_1938214814 => $_1392704888) {
                foreach ($_1392704888 as $_1392704888) {
                    $_2145267970[] = $_1392704888;
                    break 1;
                }
            }
        }
        return $_2145267970;
    }

    public static function fixBugWithPrice(&$_1851368466 = [])
    {
        if ($_1851368466["OFFERS"]) {
            foreach ($_1851368466["OFFERS"] as &$_92420712) {
                if (isset($_92420712["ITEM_ALL_PRICES"])) {
                    foreach ($_92420712["ITEM_ALL_PRICES"] as &$_977856884) {
                        foreach ($_977856884["PRICES"] as $_432033205 => &$_1091665988) {
                            if ($_1091665988["DISCOUNT"] > 0) {
                                $_1091665988["PRINT_RATIO_PRICE"] = CCurrencyLang::CurrencyFormat($_1091665988['RATIO_PRICE'], $_1091665988['CURRENCY']);
                            }
                        }
                    }
                }
            }
        }
    }

    public static function checkPriceDiscount(&$_1851368466 = [])
    {
        if ($_1851368466["OFFERS"]) {
            $_1447135835 = false;
            foreach ($_1851368466['OFFERS'] as &$_92420712) {
                if (isset($_92420712['ITEM_ALL_PRICES'])) {
                    foreach ($_92420712['ITEM_ALL_PRICES'] as &$_977856884) {
                        foreach ($_977856884['PRICES'] as $_432033205 => &$_1091665988) {
                            if ($_1091665988['DISCOUNT'] > 0) {
                                $_1447135835 = true;
                            }
                        }
                    }
                } elseif (isset($_92420712['ITEM_PRICES'])) {
                    foreach ($_92420712['ITEM_PRICES'] as $_432033205 => &$_1091665988) {
                        if ($_1091665988['DISCOUNT'] > 0) {
                            $_1447135835 = true;
                        }
                    }
                }
            }
            if ($_1447135835) $_1851368466['CHECK_DISCOUNT'] = 1;
        }
    }

    public static function getRootComponentPath($_1033840757, &$_1851368466, $_14696249)
    {
        $_560213636 = Config::get('SECTION_ROOT_TEMPLATE');
        if (($_560213636 == 'products' || $_560213636 = 'combine') && ERROR_404 == 'Y') {
            self::$_353950835 = true;
            $_11078454 = array();
            $_1934145423 = new CComponentEngine($_1033840757);
            $_1934145423->addGreedyPart('SMART_FILTER_PATH');
            $_1379122332 = array('section_smart_filter' => str_replace(array('#SECTION_CODE#/', '#SECTION_CODE_PATH#/'), '', $_14696249['SEF_URL_TEMPLATES']['smart_filter']));
            $_1684652930 = $_1934145423->guessComponentPath($_14696249['SEF_FOLDER'], $_1379122332, $_11078454);
            if ($_1684652930 == 'section_smart_filter' && $_11078454['SMART_FILTER_PATH']) {
                $_1851368466['VARIABLES']['SMART_FILTER_PATH'] = $_11078454['SMART_FILTER_PATH'];
                define('ERROR_404', 'N');
                self::$_353950835 = false;
                \CHTTP::setStatus('');
                return true;
            }
        }
        return false;
    }

    public static function getOfferUrlComponentPath($_1033840757, &$_1851368466, $_14696249)
    {
        if (Config::get('OFFER_LANDING') == "Y" && ERROR_404 == "Y" && self::$_353950835 && $_14696249["SEF_URL_TEMPLATES"]["element"]) {
            $_1816899410 = $_14696249["SEF_URL_TEMPLATES"]["element"];
            $_1050029727 = self::getUrlOfferIblock()['URL'];
            self::$_353950835 = true;
            if ($_1050029727) {
                $_993499289 = $_11078454 = [];
                foreach ($_14696249['SEF_URL_TEMPLATES'] as $_241618154 => $_16746331) {
                    $_993499289[$_241618154] = $_16746331;
                    if ($_241618154 == 'sections') {
                        $_993499289['offer'] = str_replace('#PRODUCT_URL#', $_1816899410, $_1050029727);
                    }
                }
                $_1934145423 = new CComponentEngine($_1033840757);
                $_1934145423->addGreedyPart('SECTION_CODE_PATH');
                $_1934145423->addGreedyPart('SMART_FILTER_PATH');
                $_1934145423->setResolveCallback(array('CIBlockFindTools', 'resolveComponentEngine'));
                $_1684652930 = $_1934145423->guessComponentPath($_14696249['SEF_FOLDER'], $_993499289, $_11078454);
                if ($_1684652930 == 'offer' && $_11078454) {
                    define('ERROR_404', 'N');
                    \CHTTP::setStatus('');
                    self::$_353950835 = false;
                    $_1851368466 = array('FOLDER' => $_14696249['SEF_FOLDER'], 'URL_TEMPLATES' => $_993499289, 'VARIABLES' => $_11078454, 'ALIASES' => $_1687347554);
                    $_1851368466['VARIABLES']['OFFER_ID'] = $_11078454['ID'];
                    $_1851368466['VARIABLES']['OFFER_CODE'] = $_11078454['CODE'];
                    self::$_140594632 = true;
                    return true;
                }
            }
        }
        return false;
    }

    public static function process404($_1033840757, &$_1851368466, $_14696249)
    {
        if (ERROR_404 == "Y" && self::$_353950835) {
            \Bitrix\Iblock\Component\Tools::process404("", ($_14696249["SET_STATUS_404"] === "Y"), ($_14696249["SET_STATUS_404"] === "Y"), true, $_14696249["FILE_404"]);
        }
    }

    public static function checkSef($_1458517449 = '')
    {
        if (strpos($_1458517449, '?') === false) return true; else return false;
    }

    public static function checkOfferLanding(&$_1851368466, $_14696249)
    {
        if (self::$_140594632 && Config::get('OFFER_LANDING') == "Y" && $_1851368466["OFFERS"] && $_1851368466["OFFERS"][0]["DETAIL_PAGE_URL"]) {
            global $APPLICATION;
            self::$_1258532776 = $_1851368466['FIRST_OFFERS_SELECTED'];
            if (Config::get('OFFER_LANDING_SEO') == 'Y') {
                if ($_1851368466['IPROPERTY_VALUES']) {
                    self::$_1191450594 = $_1851368466['IPROPERTY_VALUES'];
                }
            }
            if (!self::$_1227326863) {
                switch (Config::get('OFFER_LANDING_404')) {
                    case '404':
                        define('ERROR_404', 'Y');
                        self::$_353950835 = true;
                        \CHTTP::setStatus('404 Not Found');
                        self::process404($this, $_1851368466, $_14696249);
                        break;
                    case 'ELEMENT':
                        LocalRedirect($_1851368466['DETAIL_PAGE_URL'], true, '200 OK');
                        break;
                    case 'OFFER':
                        LocalRedirect($_1851368466['OFFERS'][self::$_1258532776]['DETAIL_PAGE_URL'], true, '200 OK');
                        break;
                }
            }
        }
    }

    public static function checkOfferPage(&$_1851368466, &$_14696249)
    {
        if (!self::$_140594632 && $_1851368466["OFFERS"] && $_1851368466["OFFERS"][0]["DETAIL_PAGE_URL"] && Config::get('OFFER_LANDING') == "Y") {
            $_1050029727 = self::getUrlOfferIblock()['URL'];
            if (!self::checkSef($_1050029727)) {
                $_2138480592 = str_replace(array('#PRODUCT_URL#', '#ID#', '#CODE#', '?', '='), '', $_1050029727);
                if ($_REQUEST[$_2138480592]) {
                    if (strpos($_1050029727, '#ID#')) $_14696249['OFFER_ID'] = $_REQUEST[$_2138480592]; elseif (strpos($_1050029727, '#CODE#')) $_14696249['OFFER_CODE'] = $_REQUEST[$_2138480592];
                    self::$_140594632 = true;
                }
            }
        }
    }

    public static function getSeoOffer($_1851368466)
    {
        if (self::$_140594632 && Config::get('OFFER_LANDING') == "Y" && Config::get('OFFER_LANDING_SEO') == "Y") {
            foreach ($_1851368466["OFFERS"] as $_241618154 => &$_92420712) {
                if ($_241618154 == $_1851368466['OFFERS_SELECTED']) {
                    $_1727989512 = new \Bitrix\Iblock\InheritedProperty\ElementValues($_92420712['IBLOCK_ID'], $_92420712['ID']);
                    $_1851368466['IPROPERTY_VALUES'] = $_1727989512->getValues();
                }
            }
        }
        return $_1851368466['IPROPERTY_VALUES'];
    }

    public static function setSeoOffer()
    {
        global $APPLICATION;
        if (self::$_140594632 && !empty(self::$_1191450594)) {
            if (self::$_1191450594['ELEMENT_META_TITLE']) $APPLICATION->SetPageProperty('title', self::$_1191450594['ELEMENT_META_TITLE']);
            if (self::$_1191450594['ELEMENT_META_KEYWORDS']) $APPLICATION->SetPageProperty('keywords', self::$_1191450594['ELEMENT_META_KEYWORDS']);
            if (self::$_1191450594['ELEMENT_META_DESCRIPTION']) $APPLICATION->SetPageProperty('description', self::$_1191450594['ELEMENT_META_DESCRIPTION']);
            if (self::$_1191450594['ELEMENT_PAGE_TITLE']) $APPLICATION->SetTitle(self::$_1191450594['ELEMENT_PAGE_TITLE']);
        }
    }

    public static function getOffersSelected(&$_1851368466, $_14696249)
    {
        global $APPLICATION;
        self::$_1258532776 = $_241618154 = $_1851368466['FIRST_OFFERS_SELECTED'];
        if (self::$_140594632 && $_1851368466['OFFERS'] && $_1851368466['OFFERS'][0]['DETAIL_PAGE_URL']) {
            $_1080005853 = self::checkSef($_1851368466['OFFERS'][0]['DETAIL_PAGE_URL']);
            if (1 || $_1080005853) {
                if ($_14696249['OFFER_ID']) {
                    $_2057363994 = $_14696249['OFFER_ID'];
                    foreach ($_1851368466['OFFERS'] as $_1960288759 => $_92420712) {
                        if ($_2057363994 == $_92420712['ID']) {
                            $_241618154 = $_1960288759;
                            self::$_1227326863 = true;
                        }
                    }
                } elseif ($_14696249['OFFER_CODE']) {
                    $_862506935 = $_14696249['OFFER_CODE'];
                    foreach ($_1851368466['OFFERS'] as $_1960288759 => $_92420712) {
                        if ($_862506935 == $_92420712['CODE']) {
                            $_241618154 = $_1960288759;
                            self::$_1227326863 = true;
                        }
                    }
                }
            } else {
                $_1051549173 = $APPLICATION->GetCurPageParam();
                foreach ($_1851368466['OFFERS'] as $_1960288759 => $_92420712) {
                    if (strpos($_1051549173, $_92420712['DETAIL_PAGE_URL']) !== false) {
                        $_241618154 = $_1960288759;
                        self::$_1227326863 = true;
                    }
                }
            }
        }
        return $_241618154;
    }

    public static function getUrlOfferIblock($_1864100049 = 0)
    {
        if (!$_1864100049) $_252352283 = Config::get('IBLOCK_ID');
        $_110167465 = new \CPHPCache();
        if ($_110167465->InitCache(36000, serialize(array($_1864100049)), '/kit.origami/iblock_offer')) {
            $_1704156904 = $_110167465->GetVars();
        } elseif ($_110167465->StartDataCache()) {
            $_1704156904 = CCatalogSKU::GetInfoByProductIBlock($_252352283);
            if ($_1704156904) {
                $_1685894503 = \CIBlock::GetList(array('SORT' => 'ASC'), array('ID' => $_1704156904['IBLOCK_ID']), false);
                while ($_1904968643 = $_1685894503->Fetch()) {
                    $_1704156904['URL'] = $_1904968643['DETAIL_PAGE_URL'];
                }
            }
            $_110167465->EndDataCache($_1704156904);
        }
        return $_1704156904;
    }

    public static function prepareJSData($_1033840757, $_14696249)
    {
        global $APPLICATION;
        $_1712848426 = array();
        $_1572170804 = '';
        if ($_14696249['AJAX_OPTION_HISTORY'] == 'Y') {
            $_1572170804 = '' . '' . '';
        }
        $_1712848426['TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle());
        $_1712848426['WINDOW_TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle('title'));
        $_129452932 = '' . '';
        $_129452932 .= 'var arAjaxPageData = ' . CUtil::PhpToJSObject($_1712848426) . ';';
        $_129452932 .= 'parent.BX.ajax.UpdatePageData(arAjaxPageData)' . ';';
        $_129452932 .= '';
        if ($_14696249['AJAX_OPTION_HISTORY'] == 'Y') {
            $_129452932 .= 'top.BX.ajax.history.put(window.AJAX_PAGE_STATE.getState(), \'' . CUtil::JSEscape(CAjax::encodeURI($APPLICATION->GetCurPageParam('', array('ajaxFilter'), false))) . '\');' . ';';
        }
        if ($_14696249['AJAX_OPTION_JUMP'] == 'Y') {
        }
        $_129452932 .= '';
        $_1572170804 .= $_129452932;
        return $_1572170804;
    }

    public static function changeColorImages($_1851368466 = [], $type = "detail", $_300735641 = true)
    {

        $_1602539334 = array();
        $_1703894955 = false;
        if ($_300735641 && $_1851368466['OFFERS'] && Config::get('IMAGE_FOR_OFFER') == 'PRODUCT') {
            if (isset($_1851368466['JS_OFFERS']) && is_array($_1851368466['JS_OFFERS'])) $_1703894955 = true;
            if (!isset($_1851368466['MORE_PHOTO']) || !is_array($_1851368466['MORE_PHOTO'])) $_1851368466['MORE_PHOTO'] = array();
            if ($type == 'preview' && $_1851368466['PREVIEW_PICTURE']) {
                array_unshift($_1851368466['MORE_PHOTO'], $_1851368466['PREVIEW_PICTURE']);
            } elseif ($_1851368466['DETAIL_PICTURE']) {
                array_unshift($_1851368466['MORE_PHOTO'], $_1851368466['DETAIL_PICTURE']);
            }
            $_1475010966 = \Kit\Origami\Helper\Color::getInstance(SITE_ID);
            if ($type == 'preview') $_214952677 = $_1475010966->findColors($_1851368466['MORE_PHOTO'], false); else $_214952677 = $_1475010966->findColors($_1851368466['MORE_PHOTO'], true);
            if ($_214952677) {
                $_102239898 = \Kit\Origami\Helper\Config::get('COLOR');
                foreach ($_1851368466['OFFERS'] as &$_911141999) {
                    $_399380824 = $_911141999['PROPERTIES'][$_102239898]['VALUE'];
                    if ($_399380824 && $_214952677[$_399380824]) {
                        $_911141999['MORE_PHOTO'] = $_214952677[$_399380824];
                        foreach ($_214952677[$_399380824] as $_531029480) $_1602539334[$_531029480['ID']] = $_531029480['ID'];
                        if ($_1703894955) {
                            foreach ($_1851368466['JS_OFFERS'] as $_1960288759 => &$_1222940332) {
                                if ($_1222940332['ID'] == $_911141999['ID']) {
                                    $_1222940332['SLIDER'] = $_214952677[$_399380824];
                                }
                            }
                        }
                    }
                }
                if (!empty($_1602539334)) {
                    foreach ($_1851368466['MORE_PHOTO'] as $_494494843 => &$_1173888550) {
                        if (isset($_1602539334[$_1173888550['ID']])) {
                            unset($_1851368466['MORE_PHOTO'][$_494494843]);
                        }
                    }
                }
                foreach ($_1851368466['OFFERS'] as &$_911141999) {
                    $_399380824 = $_911141999['PROPERTIES'][$_102239898]['VALUE'];
                    if (!empty($_1851368466['MORE_PHOTO']) && $_911141999['MORE_PHOTO']) {
                        $_911141999['MORE_PHOTO'] = array_merge($_911141999['MORE_PHOTO'], $_1851368466['MORE_PHOTO']);
                        if ($_1703894955) {
                            foreach ($_1851368466['JS_OFFERS'] as $_1960288759 => &$_1222940332) {
                                if ($_1222940332['ID'] == $_911141999['ID']) {
                                    $_1222940332['SLIDER'] = array_merge($_1222940332['SLIDER'], $_1851368466['MORE_PHOTO']);
                                }
                            }
                        }
                    }
                    if (!isset($_214952677[$_399380824])) {
                        $_911141999['MORE_PHOTO'] = $_1851368466['MORE_PHOTO'];
                        if ($_1703894955) {
                            foreach ($_1851368466['JS_OFFERS'] as $_1960288759 => &$_1222940332) {
                                if ($_1222940332['ID'] == $_911141999['ID']) {
                                    $_1222940332['SLIDER'] = $_1851368466['MORE_PHOTO'];
                                }
                            }
                        }
                    }
                    if (!empty($_1851368466['MORE_PHOTO'])) {
                        $_911141999['MORE_PHOTO'] = array_merge($_911141999['MORE_PHOTO'], $_1851368466['MORE_PHOTO']);
                    }
                }
            } else {
                foreach ($_1851368466['OFFERS'] as $_1960288759 => &$_92420712) {
                    if ($_1851368466['PREVIEW_PICTURE'] && $type == 'preview') {
                        $_92420712['MORE_PHOTO'][] = $_1851368466['PREVIEW_PICTURE'];
                    } elseif ($_1851368466['DETAIL_PICTURE']) $_92420712['MORE_PHOTO'][] = $_1851368466['DETAIL_PICTURE'];
                    if ($_1851368466['MORE_PHOTO'] && !$_92420712['MORE_PHOTO']) $_92420712['MORE_PHOTO'] = $_1851368466['MORE_PHOTO'];
                }
                if ($_1703894955) {
                    foreach ($_1851368466['JS_OFFERS'] as $_1960288759 => &$_1222940332) {
                        $_1222940332['SLIDER'] = array();
                        if ($_1851368466['PREVIEW_PICTURE'] && $type == 'preview') $_1222940332['SLIDER'][] = $_1851368466['PREVIEW_PICTURE']; elseif ($_1851368466['DETAIL_PICTURE']) $_1222940332['SLIDER'][] = $_1851368466['DETAIL_PICTURE'];
                        if ($_1851368466['MORE_PHOTO']) $_1222940332['SLIDER'] = $_1851368466['MORE_PHOTO'];
                    }
                }
            }
        } elseif (!$_300735641 && $_1851368466['OFFERS'] && Config::get('IMAGE_FOR_OFFER') == 'PRODUCT') {
            if (isset($_1851368466['JS_OFFERS']) && is_array($_1851368466['JS_OFFERS'])) $_1703894955 = true;
            if (!isset($_1851368466['MORE_PHOTO']) || !is_array($_1851368466['MORE_PHOTO'])) $_1851368466['MORE_PHOTO'] = array();
            if ($type == 'preview' && $_1851368466['PREVIEW_PICTURE']) {
                array_unshift($_1851368466['MORE_PHOTO'], $_1851368466['PREVIEW_PICTURE']);
            } elseif ($_1851368466['DETAIL_PICTURE']) {
                array_unshift($_1851368466['MORE_PHOTO'], $_1851368466['DETAIL_PICTURE']);
            }
            if ($_1851368466['MORE_PHOTO']) {
                array_splice($_1851368466['MORE_PHOTO'], 1);
            }
            foreach ($_1851368466['OFFERS'] as &$_911141999) {
                if ($_1851368466['MORE_PHOTO']) $_911141999['MORE_PHOTO'] = $_1851368466['MORE_PHOTO'];
            }
            if ($_1703894955) {
                foreach ($_1851368466['JS_OFFERS'] as $_1960288759 => &$_1222940332) {
                    if ($_1851368466['MORE_PHOTO']) $_1222940332['SLIDER'] = $_1851368466['MORE_PHOTO'];
                }
            }
        }
        return $_1851368466;
    }

    public static function blockIncludeAvailable($_1046363615, $_617195126, $_1080113230)
    {

        global $APPLICATION;
        $_225755897 = [];
        foreach ($_1046363615 as $_286888804) {
            if (is_dir($_617195126 . '/' . $_286888804) && !in_array($_286888804, ['.', '..', 'lang'])) {
                $_205907113['CONTENT'] = file_get_contents($_617195126 . '/' . $_286888804 . '/content.php');
                $_205907113['CODE'] = $_286888804;
                $_205907113['PREVIEW'] = \KitOrigami::blockDir . '/' . $_286888804 . '/preview.jpg';
                $_205907113['SETTINGS'] = include $_617195126 . '/' . $_286888804 . '/settings.php';
                $_711692109 = $_205907113['SETTINGS']['block']['section'];
                if (!in_array($_711692109, array_keys($_1080113230))) {
                    continue;
                }
                if ($_225755897[$_711692109]) {
                    $_225755897[$_711692109][] = $_205907113;
                } else {
                    $_225755897[$_711692109] = [$_205907113];
                }
            }
        }
        return $_225755897;
    }

    public static function FrontUser()
    {

        $_1028488268 = new \Kit\Origami\Front\User(SITE_ID);
        return $_1028488268;
    }

    public static function ClearTmp($_29826423 = '')
    {
        if (!$_29826423) {
            $_29826423 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/kit_origami';
        }
        $_1745162285 = glob($_29826423 . '/*');
        foreach ($_1745162285 as $_113718537) {
            is_dir($_113718537) ? \KitOrigami::ClearTmp($_113718537) : unlink($_113718537);
        }
        if ($_29826423 != $_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/kit_origami') {
            rmdir($_29826423);
        }
        return 'KitOrigami::ClearTmp();';
    }

    public static function FormatFileSize($_2038695769, $_354791801 = 2)
    {
        $_2041167345 = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $_106289902 = (int)floor((strlen($_2038695769) - 1) / 3);
        return sprintf("%.{$_354791801}f", $_2038695769 / pow('1024', $_106289902)) . '' . @$_2041167345[$_106289902];
    }

    public static function GetComponentPrices($_65703936 = [])
    {
        if (\KitOrigami::isUseRegions()) {
            if ($_SESSION["KIT_REGIONS"]["PRICE_CODE"]) {
                $_65703936 = $_SESSION["KIT_REGIONS"]["PRICE_CODE"];
            }
        }
        return $_65703936;
    }

    public static function showBreadCrumbs($_972379907 = SITE_DIR)
    {
        global $APPLICATION;
        $_225755897 = true;
        if ($APPLICATION->GetProperty('SHOW_BREADCRUMBS') == 'N') {
            $_225755897 = false;
        }
        return $_225755897;
    }

    public static function needShowFullWidth($_972379907 = SITE_DIR)
    {

        $_225755897 = false;
        if ($_972379907 == SITE_DIR) {
            $_225755897 = true;
        }
        return $_225755897;
    }

    public static function needShowSide($_972379907 = SITE_DIR)
    {

        $_225755897 = true;
        global $APPLICATION;
        if (($_972379907 == SITE_DIR || $APPLICATION->GetProperty('SHOW_SIDE_BLOCK') == 'N')) {
            $_225755897 = false;
        }
        if (self::checkDynamicSection($_972379907)) {
            if ($APPLICATION->GetProperty('SHOW_SIDE_BLOCK_SUBSECTION') == 'N') $_225755897 = false;
            if ($APPLICATION->GetProperty('SHOW_SIDE_BLOCK_SUBSECTION') == 'RIGHT') $_225755897 = true;
            if ($APPLICATION->GetProperty('SHOW_SIDE_BLOCK_SUBSECTION') == 'LEFT') $_225755897 = true;
        }
        return $_225755897;
    }

    public static function checkDynamicSection($_972379907 = SITE_DIR)
    {
        $_1354070377 = explode("/", $_972379907);
        if (count($_1354070377) > 3) {
            return true;
        }
        return false;
    }

    public static function getSide($_972379907 = SITE_DIR)
    {

        global $APPLICATION;
        $_275839199 = Config::get('MENU_SIDE');
        if ($APPLICATION->GetProperty('SHOW_SIDE_BLOCK') == 'LEFT') $_275839199 = 'left'; elseif ($APPLICATION->GetProperty('SHOW_SIDE_BLOCK') == 'RIGHT') $_275839199 = 'right';
        $_99221141 = self::checkDynamicSection($_972379907);
        if ($_99221141 && $APPLICATION->GetProperty('SHOW_SIDE_BLOCK_SUBSECTION') == 'LEFT') $_275839199 = 'left';
        if ($_99221141 && $APPLICATION->GetProperty('SHOW_SIDE_BLOCK_SUBSECTION') == 'RIGHT') $_275839199 = 'right';
        return $_275839199;
    }

    public static function needShowBreadcrumbs($_972379907 = SITE_DIR)
    {

        $_225755897 = false;
        if ($_972379907 != SITE_DIR) {
            $_225755897 = true;
        }
        return $_225755897;
    }

    public static function getCurrentPage()
    {

        global $APPLICATION;
        $_225755897 = $APPLICATION->GetCurDir();
        if (!$_225755897) {
            $_225755897 = SITE_DIR;
        }
        return $_225755897;
    }

    public function DoIBlockAfterSave($_425863815, $_1585110416 = false)
    {
        $_903491882 = false;
        $_873335815 = false;
        $_2144971637 = false;
        $_1874443776 = false;
        if (CModule::IncludeModule('currency')) $_1358705902 = CCurrency::GetBaseCurrency();
        if (is_array($_1585110416) && $_1585110416['PRODUCT_ID'] > 0) {
            $_1987493878 = CIBlockElement::GetList(array(), array('ID' => $_1585110416['PRODUCT_ID'],), false, false, array('ID', 'IBLOCK_ID'));
            if ($_2047126907 = $_1987493878->Fetch()) {
                $_868787651 = CCatalog::GetByID($_2047126907['IBLOCK_ID']);
                if (is_array($_868787651)) {
                    if ($_868787651['OFFERS'] == 'Y') {
                        $_1726526712 = CIBlockElement::GetProperty($_2047126907['IBLOCK_ID'], $_2047126907['ID'], 'sort', 'asc', array('ID' => $_868787651['SKU_PROPERTY_ID']));
                        $_330842423 = $_1726526712->Fetch();
                        if ($_330842423 && $_330842423['VALUE'] > 0) {
                            $_903491882 = $_330842423['VALUE'];
                            $_873335815 = $_868787651['PRODUCT_IBLOCK_ID'];
                            $_2144971637 = $_868787651['IBLOCK_ID'];
                            $_1874443776 = $_868787651['SKU_PROPERTY_ID'];
                        }
                    } elseif ($_868787651['OFFERS_IBLOCK_ID'] > 0) {
                        $_903491882 = $_2047126907['ID'];
                        $_873335815 = $_2047126907['IBLOCK_ID'];
                        $_2144971637 = $_868787651['OFFERS_IBLOCK_ID'];
                        $_1874443776 = $_868787651['OFFERS_PROPERTY_ID'];
                    } else {
                        $_903491882 = $_2047126907['ID'];
                        $_873335815 = $_2047126907['IBLOCK_ID'];
                        $_2144971637 = false;
                        $_1874443776 = false;
                    }
                }
            }
        } elseif (is_array($_425863815) && $_425863815['ID'] > 0 && $_425863815['IBLOCK_ID'] > 0) {
            $_1901288990 = CIBlockPriceTools::GetOffersIBlock($_425863815['IBLOCK_ID']);
            if (is_array($_1901288990)) {
                $_903491882 = $_425863815['ID'];
                $_873335815 = $_425863815['IBLOCK_ID'];
                $_2144971637 = $_1901288990['OFFERS_IBLOCK_ID'];
                $_1874443776 = $_1901288990['OFFERS_PROPERTY_ID'];
            }
        }
        $_1342066891 = false;
        $_2042883832 = CIBlock::GetList(Array(), Array('ID' => $_873335815,), true);
        if ($_205076156 = $_2042883832->Fetch()) {
            $_260359155 = $_205076156['LID'];
        }
        if (\Bitrix\Main\Config\Option::get('sale', 'use_sale_discount_only', 'N') == 'Y') {
            $_387281016 = array();
            $_1404916314 = array('ID');
            $_1910868225 = \CCatalogSKU::getOffersList($_903491882, 0, array('ACTIVE' => 'Y'), $_1404916314, $_387281016);
            if ($_1910868225[$_903491882]) {
                foreach ($_1910868225[$_903491882] as $_958863122) {
                    if ($_958863122['ID']) {
                        $_331888933 = CCatalogDiscount::GetDiscountByProduct($_958863122['ID'], array(), 'N', array(), $_260359155);
                        if (isset($_331888933) && is_array($_331888933) && count($_331888933) > 0) {
                            break;
                        }
                    }
                }
            }
        } else {
            $_331888933 = CCatalogDiscount::GetDiscountByProduct($_903491882, array(), 'N', array(), $_260359155);
        }
        if (isset($_331888933) && is_array($_331888933) && count($_331888933) > 0) $_1342066891 = true;
        if ($_1342066891) {
            $_1635915203 = 'SALE';
            $_462197824 = CIBlockPropertyEnum::GetList(Array('DEF' => 'DESC', 'SORT' => 'ASC'), Array('IBLOCK_ID' => $_873335815, 'CODE' => $_1635915203));
            if ($_1221029514 = $_462197824->Fetch()) {
                $_677442031 = Array($_1635915203 => $_1221029514['ID'],);
                CIBlockElement::SetPropertyValuesEx($_903491882, false, $_677442031);
            }
        }
        if ($_903491882) {
            static $_1140633464 = array();
            if (!array_key_exists($_873335815, $_1140633464)) {
                $_2122420909 = CIBlockProperty::GetByID('MINIMUM_PRICE', $_873335815);
                $_677442031 = $_2122420909->Fetch();
                if ($_677442031) $_1140633464[$_873335815] = $_677442031['ID']; else $_1140633464[$_873335815] = false;
            }
            if ($_1140633464[$_873335815]) {
                if ($_2144971637) {
                    $_1946222239 = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $_2144971637, 'PROPERTY_' . $_1874443776 => $_903491882,), false, false, array('ID'));
                    while ($_92420712 = $_1946222239->Fetch()) $_1247672865[] = $_92420712['ID'];
                    if (!is_array($_1247672865)) $_1247672865 = array($_903491882);
                } else $_1247672865 = array($_903491882);
                $_849481474 = false;
                $_902674456 = false;
                $_372275711 = CPrice::GetList(array(), array('PRODUCT_ID' => $_1247672865,));
                while ($_1091665988 = $_372275711->Fetch()) {
                    if ($_1342066891) {
                        $_331888933 = CCatalogDiscount::GetDiscountByPrice($_1091665988['ID'], array(), 'N', $_260359155);
                        $_46201518 = CCatalogProduct::CountPriceWithDiscount($_1091665988['PRICE'], $_1091665988['CURRENCY'], $_331888933);
                        $_1091665988['DISCOUNT_PRICE'] = $_46201518;
                    }
                    if (isset($_46201518)) {
                        $_403552288 = $_46201518;
                        unset($_46201518);
                    } else {
                        $_403552288 = $_1091665988['PRICE'];
                    }
                    if (CModule::IncludeModule('currency') && $_1358705902 != $_1091665988['CURRENCY']) $_403552288 = CCurrencyRates::ConvertCurrency($_403552288, $_1091665988['CURRENCY'], $_1358705902);
                    $_1136074569 = $_403552288;
                    if ($_849481474 === false || $_849481474 > $_1136074569) $_849481474 = $_1136074569;
                    if ($_902674456 === false || $_902674456 < $_1136074569) $_902674456 = $_1136074569;
                }
                if ($_849481474 !== false) {
                    CIBlockElement::SetPropertyValuesEx($_903491882, $_873335815, array('MINIMUM_PRICE' => $_849481474, 'MAXIMUM_PRICE' => $_902674456,));
                }
            }
        }
    }

    private function __1871197832($_2137682369)
    {
        $_252744812 = Config::get('INLINE_CSS_EXCEPTION_FILE_NAME');
        if (isset($_252744812) && !empty($_252744812)) {
            foreach ($_252744812 as $_1495477353) {
                if (strpos($_2137682369, $_1495477353) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    private function __57872944($_143942720)
    {
        preg_match_all('/\<link\s+href\=\"([\S\w]+\.css)[\S\w]*\"[^\>]+\>/', $_143942720, $_1847910540);
        if (isset($_1847910540[1]) && is_array($_1847910540[1])) {
            foreach ($_1847910540[1] as $_241618154 => $_1487017693) {
                if (strpos($_1487017693, '/assets/css/style-icons.css') === false && strpos($_1487017693, 'style.css') === false) {
                    $_1487017693 = $_SERVER['DOCUMENT_ROOT'] . $_1487017693;
                    $_908521315 = Config::get('INLINE_CSS_EXCLUDE_FILE');
                    if (file_exists($_1487017693) && (is_numeric($_908521315) && ($_908521315 * 1000) >= filesize($_1487017693) || $_908521315 == '')) {
                        $_1456380333 = file_get_contents($_1487017693);
                        $_2096204513 = self::compressCSS($_1456380333);
                        $_143942720 = str_replace($_1847910540[0][$_241618154], '<style>' . $_2096204513 . '</style>', $_143942720);
                    }
                }
            }
        }
        return $_143942720;
    }

    public function compressCSS($_449085564, $_1695721621 = Array())
    {
        $_1850202586 = $_449085564;
        return $_1850202586;
    }

    public function DoInlineCss(&$_143942720)
    {
        global $APPLICATION, $USER;
        if (strpos($APPLICATION->GetCurDir(), '/bitrix/') === false && (strpos($APPLICATION->GetCurDir(), '/bitrix/subws/') === false || strpos($APPLICATION->GetCurDir(), '/bitrix/subws/') === false)) {
            if (!(is_object($USER) && $USER->IsAuthorized()) && Config::get('INLINE_CSS_REMOVE_KERNEL_CSS_JS') == 'Y') {
                $_539677309 = Array('/\]+\>/', '/\]+\>/');
                $_143942720 = preg_replace($_539677309, '', $_143942720);
                $_143942720 = preg_replace('/
{2,}/', '', $_143942720);
            }
            if (Config::get('INLINE_CSS_CHECKBOX') == 'Y' && is_objec($USER) && !$USER->isAdmin()) $_143942720 = self::__57872944($_143942720); else if (Config::get('INLINE_CSS_CHECKBOX') == 'Y' && is_objec($USER) && $USER->IsAuthorized() && Config::get('INLINE_CSS_ADMIN_CHECKBOX') != 'Y') $_143942720 = self::__57872944($_143942720);
        }
        return;
    }
} ?>