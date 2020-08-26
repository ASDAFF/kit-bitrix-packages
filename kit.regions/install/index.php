<?
/**
 * Copyright (c) 22/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use Bitrix\Main\SiteTable;
use Bitrix\Sale\Location\TypeTable;

Loc::loadMessages(__FILE__);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client_partner.php');

class kit_regions extends CModule
{
    const MODULE_ID = 'kit.regions';
    var $MODULE_ID = 'kit.regions';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $_302731813 = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage(self::MODULE_ID . '_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage(self::MODULE_ID . '_MODULE_DESC');
        $this->PARTNER_NAME = GetMessage('kit.regions_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('kit.regions_PARTNER_URI');
    }

    function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler('sale', 'OnSaleComponentOrderProperties', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnSaleComponentOrderPropertiesHandler');
        EventManager::getInstance()->registerEventHandler('main', 'OnEndBufferContent', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnEndBufferContentHandler', 999);
        EventManager::getInstance()->registerEventHandler('main', 'OnUserTypeBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnUserTypeBuildListHandlerHtml');
        EventManager::getInstance()->registerEventHandler('main', 'OnProlog', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnPrologHandler');
        EventManager::getInstance()->registerEventHandler('iblock', 'OnIBlockPropertyBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnIBlockPropertyBuildListHandler');
        EventManager::getInstance()->registerEventHandler('catalog', 'OnGetOptimalPrice', 'main', '\Kit\Regions\EventHandlers', 'OnGetOptimalPriceHandler', 100, '/modules/kit.regions/lib/eventhandlers.php');
        EventManager::getInstance()->registerEventHandler('sale', 'onSaleDeliveryRestrictionsClassNamesBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'onSaleDeliveryRestrictionsClassNamesBuildListHandler');
        EventManager::getInstance()->registerEventHandler('sale', 'onSalePaySystemRestrictionsClassNamesBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'onSalePaySystemRestrictionsClassNamesBuildListHandler');
        EventManager::getInstance()->registerEventHandler('sale', 'OnSaleOrderBeforeSaved', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnSaleOrderBeforeSavedHandler');
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeEventAdd', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnBeforeEventAddHandler');
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeMailSend', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnBeforeMailSendHandler');
        EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', self::MODULE_ID, '\Kit\Regions\EventHandlers', 'OnBuildGlobalMenuHandler');
        EventManager::getInstance()->registerEventHandler('sale', 'OnCondSaleControlBuildList', self::MODULE_ID, '\Kit\Regions\EventHandlers', 'OnCondSaleControlBuildListHandler');
        $_870088534 = SiteTable::getList(array('filter' => array('ACTIVE' => 'Y')));
        while ($_598634165 = $_870088534->fetch()) {
            $this->KitRegionsInstallData($_598634165['LID']);
            $this->KitRegionsSetSettings($_598634165['LID']);
        }
        return true;
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleComponentOrderProperties', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnSaleComponentOrderPropertiesHandler');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnEndBufferContent', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnEndBufferContentHandler');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnUserTypeBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnUserTypeBuildListHandlerHtml');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnProlog', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnPrologHandler');
        EventManager::getInstance()->unRegisterEventHandler('iblock', 'OnIBlockPropertyBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnIBlockPropertyBuildListHandler');
        EventManager::getInstance()->unRegisterEventHandler('catalog', 'OnGetOptimalPrice', 'main', '\Kit\Regions\EventHandlers', 'OnGetOptimalPriceHandler', '/modules/kit.regions/lib/eventhandlers.php');
        EventManager::getInstance()->unRegisterEventHandler('sale', 'onSaleDeliveryRestrictionsClassNamesBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'onSaleDeliveryRestrictionsClassNamesBuildListHandler');
        EventManager::getInstance()->unRegisterEventHandler('sale', 'onSalePaySystemRestrictionsClassNamesBuildList', 'kit.regions', '\Kit\Regions\EventHandlers', 'onSalePaySystemRestrictionsClassNamesBuildListHandler');
        EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleOrderBeforeSaved', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnSaleOrderBeforeSavedHandler');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBeforeEventAdd', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnBeforeEventAddHandler');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBeforeMailSend', 'kit.regions', '\Kit\Regions\EventHandlers', 'OnBeforeMailSendHandler');
        EventManager::getInstance()->unregisterEventHandler('main', 'OnBuildGlobalMenu', self::MODULE_ID, '\Kit\Regions\EventHandlers', 'OnBuildGlobalMenuHandler');
        EventManager::getInstance()->unregisterEventHandler('sale', 'OnCondSaleControlBuildList', self::MODULE_ID, '\Kit\Regions\EventHandlers', 'OnCondSaleControlBuildListHandler');
        return true;
    }

    function InstallFiles($_73691528 = array())
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/', true, true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
        if (is_dir($_221050173 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default')) {
            if ($_773267122 = opendir($_221050173)) {
                while (false !== $_474060830 = readdir($_773267122)) {
                    if ($_474060830 == '..' || $_474060830 == '.') continue;
                    CopyDirFiles($_221050173 . '/' . $_474060830, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default/' . $_474060830, $_1067687582 = True, $_2052090008 = True);
                }
                closedir($_773267122);
            }
        }
        if (is_dir($_221050173 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_773267122 = opendir($_221050173)) {
                while (false !== $_474060830 = readdir($_773267122)) {
                    if ($_474060830 == '..' || $_474060830 == '.') continue;
                    CopyDirFiles($_221050173 . '/' . $_474060830, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $_474060830, $_1067687582 = True, $_2052090008 = True);
                }
                closedir($_773267122);
            }
        }
        if (is_dir($_221050173 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/local')) {
            if ($_773267122 = opendir($_221050173)) {
                while (false !== $_474060830 = readdir($_773267122)) {
                    if ($_474060830 == '..' || $_474060830 == '.') continue;
                    CopyDirFiles($_221050173 . '/' . $_474060830, $_SERVER['DOCUMENT_ROOT'] . '/local/' . $_474060830, $_1067687582 = True, $_2052090008 = True);
                }
                closedir($_773267122);
            }
        }
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default');
        if (is_dir($_221050173 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default/')) {
            if ($_773267122 = opendir($_221050173)) {
                while (false !== $_474060830 = readdir($_773267122)) {
                    if ($_474060830 == '..' || $_474060830 == '.' || !is_dir($_96965823 = $_221050173 . '/' . $_474060830)) continue;
                    $_11995181 = opendir($_96965823);
                    while (false !== $_2122231840 = readdir($_11995181)) {
                        if ($_2122231840 == '..' || $_2122231840 == '.') continue;
                        DeleteDirFilesEx('/bitrix/themes/.default/' . $_474060830 . '/' . $_2122231840);
                    }
                    closedir($_11995181);
                }
                closedir($_773267122);
            }
        }
        if (is_dir($_221050173 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_773267122 = opendir($_221050173)) {
                while (false !== $_474060830 = readdir($_773267122)) {
                    if ($_474060830 == '..' || $_474060830 == '.' || !is_dir($_96965823 = $_221050173 . '/' . $_474060830)) continue;
                    $_11995181 = opendir($_96965823);
                    while (false !== $_2122231840 = readdir($_11995181)) {
                        if ($_2122231840 == '..' || $_2122231840 == '.') continue;
                        DeleteDirFilesEx('/bitrix/components/' . $_474060830 . '/' . $_2122231840);
                    }
                    closedir($_11995181);
                }
                closedir($_773267122);
            }
        }
        DeleteDirFilesEx('/local/tests/' . self::MODULE_ID . '/');
        DeleteDirFilesEx('/local/templates/.default/components/bitrix/map.google.view/kit_regions/');
        DeleteDirFilesEx('/local/templates/.default/components/bitrix/map.google.view/kit_regions/');
        return true;
    }

    function installDB()
    {


        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/install.sql');
    }

    function UnInstallDB()
    {
        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/uninstall.sql');
    }

    function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallDB();
        ModuleManager::registerModule(self::MODULE_ID);
        $this->InstallEvents();
    }

    function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $_870088534 = SiteTable::getList(array('filter' => array('ACTIVE' => 'Y')));
        while ($_598634165 = $_870088534->fetch()) {
            $this->unInstallData($_598634165['LID']);
        }
        ModuleManager::unRegisterModule(self::MODULE_ID);
    }

    function unInstallData($_1117533846)
    {
        $_872752286 = new \CUserTypeEntity;
        $_870088534 = $_872752286->GetList(array(), array('ENTITY_ID' => 'KIT_REGIONS'));
        while ($_562033071 = $_870088534->Fetch()) {
            $_1171238117 = $_872752286->Delete($_562033071['ID']);
        }
        return true;
    }

    function KitRegionsInstallData($_1117533846 = '')
    {
        $this->KitRegionsInstallProperties($_1117533846);
        $this->KitRegionsInstallDomains($_1117533846);
        $this->KitRegionsInstallFavorites($_1117533846);
        return true;
    }

    function KitRegionsInstallProperties($_1117533846)
    {
        $_83672819 = array();
        $_563172606 = CLanguage::GetList($_1821524424, $_1484748136, array());
        while ($_1399347088 = $_870088534 = $_563172606->Fetch()) {
            $_83672819[] = htmlspecialcharsbx($_1399347088['LID']);
        }
        $_872752286 = new \CUserTypeEntity;
        $_185124254 = array('ENTITY_ID' => 'KIT_REGIONS', 'SORT' => 100, 'MULTIPLE' => 'N', 'MANDATORY' => 'N', 'SHOW_FILTER' => 'N', 'SHOW_IN_LIST' => 'Y', 'EDIT_IN_LIST' => 'Y', 'IS_SEARCHABLE' => 'N', 'SETTINGS' => array(), 'EDIT_FORM_LABEL' => array(), 'LIST_COLUMN_LABEL' => array(), 'LIST_FILTER_LABEL' => array(), 'ERROR_MESSAGE' => array(), 'HELP_MESSAGE' => array(),);
        $_1293476724 = array('PHONE' => array('FIELD_NAME' => 'UF_PHONE', 'USER_TYPE_ID' => 'string', 'MULTIPLE' => 'Y'), 'ADDRESS' => array('FIELD_NAME' => 'UF_ADDRESS', 'USER_TYPE_ID' => 'html',), 'EMAIL' => array('FIELD_NAME' => 'UF_EMAIL', 'USER_TYPE_ID' => 'string', 'MULTIPLE' => 'Y'), 'ROBOTS' => array('FIELD_NAME' => 'UF_ROBOTS', 'USER_TYPE_ID' => 'html',),);
        foreach ($_1293476724 as $_283758071 => $_753604381) {
            $_1470270628 = array_merge($_185124254, $_753604381);
            foreach ($_83672819 as $_1126291632) {
                $_1470270628['EDIT_FORM_LABEL'][$_1126291632] = Loc::getMessage('kit.regions_PROP_' . $_283758071);
                $_1470270628['LIST_COLUMN_LABEL'][$_1126291632] = Loc::getMessage('kit.regions_PROP_' . $_283758071);
                $_1470270628['LIST_FILTER_LABEL'][$_1126291632] = Loc::getMessage('kit.regions_PROP_' . $_283758071);
                $_1470270628['ERROR_MESSAGE'][$_1126291632] = Loc::getMessage('kit.regions_PROP_' . $_283758071);
                $_1470270628['HELP_MESSAGE'][$_1126291632] = Loc::getMessage('kit.regions_PROP_' . $_283758071);
            }
            $_1556584304 = $_872752286->Add($_1470270628);
        }
    }

    function KitRegionsInstallDomains($_1117533846)
    {
        $_2032066276 = array();
        $_91637380 = array();
        if (Loader::includeModule('catalog')) {
            $_870088534 = \CCatalogGroup::GetList(array(), array('ACTIVE' => 'Y'));
            while ($_593365907 = $_870088534->Fetch()) {
                $_2032066276[] = $_593365907['NAME'];
            }
            $_870088534 = \CCatalogStore::GetList(array(), array('ISSUING_CENTER' => 'Y', 'ACTIVE' => 'Y'), false, false, array('ID'));
            while ($_236933247 = $_870088534->Fetch()) {
                $_91637380[] = $_236933247['ID'];
            }
        }
        $_1152271029 = array('', 'spb', 'sochi', 'pyatigorsk', 'voronezh', 'krasnodar', 'samara', 'rostov', 'ufa', 'kaluga', 'kazan', 'stavropol', 'nn');
        $_524180966 = Bitrix\Main\Application::getInstance()->getContext();
        $_1606345715 = $_524180966->getServer();
        $_1395317437 = $_1606345715->getServerName();
        $_1702421293 = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) ? 'https://' : 'http://';
        global $DB;
        foreach ($_1152271029 as $_985790079 => $_511175772) {
            if (!empty($_511175772)) {
                $_1106395561 = $_511175772 . '.';
            } else {
                $_1106395561 = $_511175772;
            }
            $_479394219 = $_1702421293 . $_1106395561 . $_1395317437;
            $_1473844007 = array('CODE' => $_479394219, 'NAME' => Loc::getMessage('kit.regions_DOMEN_' . $_511175772), 'SORT' => 100, 'PRICE_CODE' => $_2032066276, 'STORE' => $_91637380, 'SITE_ID' => [$_1117533846]);
            if ($_985790079 == 0) $_1166177176 = "'Y'"; else $_1166177176 = 'NULL';
            $DB->Query("INSERT INTO `kit_regions` VALUES (NULL,'" . $_1473844007["CODE"] . "', '" . $_1473844007["NAME"] . "', 100, '" . serialize($_1473844007["SITE_ID"]) . "', '" . serialize($_1473844007["PRICE_CODE"]) . "', '" . serialize($_1473844007["STORE"]) . "', NULL, NULL, NULL, NULL, NULL, NULL, " . $_1166177176 . ");");
            $_869891527 = intval($DB->LastID());
            if ($_869891527 > 0) {
                $_34756370 = '+7(495)';
                switch ($_511175772) {
                    case 'spb':
                        $_34756370 = '+7 (812) ';
                        break;
                    case 'sochi':
                        $_34756370 = '+7 (8622) ';
                        break;
                    case 'pyatigorsk':
                        $_34756370 = '+7 (8793) ';
                        break;
                    case 'voronezh':
                        $_34756370 = '+7 (4732) ';
                        break;
                    case 'krasnodar':
                        $_34756370 = '+7 (861) ';
                        break;
                    case 'samara':
                        $_34756370 = '+7 (846) ';
                        break;
                    case 'rostov':
                        $_34756370 = '+7 (863) ';
                        break;
                    case 'ufa':
                        $_34756370 = '+7 (347) ';
                        break;
                    case 'kaluga':
                        $_34756370 = '+7 (4842) ';
                        break;
                    case 'kazan':
                        $_34756370 = '+7 (843) ';
                        break;
                    case 'stavropol':
                        $_34756370 = '+7 (8652) ';
                        break;
                    case 'nn':
                        $_34756370 = '+7 (831) ';
                        break;
                }
                if (strlen($_34756370) == 9) {
                    $_167336515 = array($_34756370 . '111-11-11', $_34756370 . '222-22-22');
                } else {
                    $_167336515 = array($_34756370 . '11-11-11', $_34756370 . '22-22-22');
                }
                $DB->Query("INSERT INTO `kit_regions_fields` VALUES (NULL," . $_869891527 . ", 'UF_PHONE', '" . serialize($_167336515) . "');");
                $DB->Query("INSERT INTO `kit_regions_fields` VALUES (NULL," . $_869891527 . ", 'UF_ADDRESS', '" . Loc::getMessage("kit.regions_ADDRESS", array("#HOME#" => rand(1, 50))) . "');");
                $DB->Query("INSERT INTO `kit_regions_fields` VALUES (NULL," . $_869891527 . ", 'UF_EMAIL', '" . serialize(["sales@" . $_1106395561 . $_1395317437]) . "');");
                $DB->Query("INSERT INTO `kit_regions_fields` VALUES (NULL," . $_869891527 . ", 'UF_ROBOTS', '');");
                if (Loader::includeModule('sale')) {
                    $_989329412 = \Bitrix\Sale\Location\LocationTable::getList(['filter' => ['=NAME.NAME' => Loc::getMessage('kit.regions_LOCATION_' . $_511175772), '=NAME.LANGUAGE_ID' => LANGUAGE_ID,], 'select' => ['*', 'NAME.*',], 'cache' => ['ttl' => 36000000,],])->fetch();
                    if ($_989329412['ID'] > 0) {
                        $DB->Query("INSERT INTO `kit_regions_locations` VALUES (NULL," . $_869891527 . "," . $_989329412["ID"] . ");");
                    }
                    if ($_511175772 == '') {
                        $_989329412 = \Bitrix\Sale\Location\LocationTable::getList(['filter' => ['=NAME.NAME' => Loc::getMessage('kit.regions_LOCATION_MOSCOW'), '=NAME.LANGUAGE_ID' => LANGUAGE_ID,], 'select' => ['*', 'NAME.*',], 'cache' => ['ttl' => 36000000,],])->fetch();
                    }
                    if ($_989329412['ID'] > 0) {
                        $DB->Query("INSERT INTO `kit_regions_locations` VALUES (NULL," . $_869891527 . "," . $_989329412["ID"] . ");");
                    }
                }
            }
        }
    }

    function KitRegionsSetSettings($_1117533846)
    {
        global $DB;
        $DB->Query("INSERT INTO `kit_regions_options` VALUES ('SINGLE_DOMAIN','Y', '" . $_1117533846 . "');");
        if (Loader::includeModule("statistic")) {
            $DB->Query("INSERT INTO `kit_regions_options` VALUES ('FIND_USER_METHOD','statistic', '" . $_1117533846 . "');");
        } elseif (function_exists("curl_version")) {
            $DB->Query("INSERT INTO `kit_regions_options` VALUES ('FIND_USER_METHOD','ipgeobase', '" . $_1117533846 . "');");
        } else {
            $DB->Query("INSERT INTO `kit_regions_options` VALUES ('FIND_USER_METHOD','geoip','" . $_1117533846 . "');");
        }
        $DB->Query("INSERT INTO `kit_regions_options` VALUES ('INSERT_SALE_LOCATION','N', '" . $_1117533846 . "');");
        $DB->Query("INSERT INTO `kit_regions_options` VALUES ('MULTIPLE_DELIMITER',', ', '" . $_1117533846 . "');");
        $_1389722182 = 5;
        if (Loader::includeModule("sale")) {
            $_2069164710 = TypeTable::getList(["select" => ["ID"], "filter" => ["CODE" => "CITY"]])->fetch();
            if (!empty($_2069164710["ID"])) $_1389722182 = $_2069164710["ID"];
        }
        $DB->Query("INSERT INTO `kit_regions_options` VALUES ('LOCATION_TYPE', '" . $_1389722182 . "', '" . $_1117533846 . "');");
    }

    function KitRegionsInstallFavorites($_1117533846)
    {
        if (Loader::includeModule('sale')) {
            $_748595033 = ['MOSCOW', 'KALUGA', 'KAZAN', 'KRASNODAR', 'NN', 'PYATIGORSK', 'ROSTOV_NA_DONY', 'SAMARA', 'SOCHI', 'SP', 'STAVROPOL', 'UFA', 'VORONEG',];
            foreach ($_748595033 as $_1452412573) {
                $_989329412 = \Bitrix\Sale\Location\LocationTable::getList(['filter' => ['=NAME.NAME' => Loc::getMessage('kit.regions_FAVORITE_' . $_1452412573), '=NAME.LANGUAGE_ID' => LANGUAGE_ID,], 'select' => ['*', 'NAME.*',], 'cache' => ['ttl' => 36000000,],])->fetch();
                if ($_989329412['CODE']) {
                    $_1264115547 = \Bitrix\Sale\Location\DefaultSiteTable::getList(['filter' => ['LOCATION_CODE' => $_989329412['CODE'], 'SITE_ID' => $_1117533846]])->getSelectedRowsCount();
                    if ($_1264115547 == 0) \Bitrix\Sale\Location\DefaultSiteTable::add(['SORT' => 100, 'LOCATION_CODE' => $_989329412['CODE'], 'SITE_ID' => $_1117533846]);
                }
            }
        }
    }

    function KitRegionsGetAllFiles($_1358015652)
    {
    }

    function KitRegionsDeleteFiles($_543546171)
    {
    }
} ?>