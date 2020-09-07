<?
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client_partner.php');

Class sotbit_crosssell extends CModule
{
    const MODULE_ID = 'sotbit.crosssell';
    var $MODULE_ID = 'sotbit.crosssell';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $_776472411 = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage(self::MODULE_ID . '_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage(self::MODULE_ID . '_MODULE_DESC');
        $this->PARTNER_NAME = GetMessage('sotbit.crosssell_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('sotbit.crosssell_PARTNER_URI');
    }

    function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(self::MODULE_ID, 'OnCondCatControlBuildListSotbitCrosssell', self::MODULE_ID, 'SotbitCrosssellCatalogCondCtrlGroup', 'GetControlDescr');
        EventManager::getInstance()->registerEventHandler(self::MODULE_ID, 'OnCondCatControlBuildListSotbitCrosssell', self::MODULE_ID, 'SotbitCrosssellCatalogCondCtrlIBlockFields', 'GetControlDescr');
        EventManager::getInstance()->registerEventHandler(self::MODULE_ID, 'OnCondCatControlBuildListSotbitCrosssell', self::MODULE_ID, 'SotbitCrosssellCatalogCondCtrlIBlockProps', 'GetControlDescr');
        EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', 'sotbit.crosssell', '\Sotbit\Crosssell\EventHandlers', 'OnBuildGlobalMenuHandler');
        return true;
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(self::MODULE_ID, 'OnCondCatControlBuildListSotbitCrosssell', self::MODULE_ID, 'SotbitCrosssellCatalogCondCtrlGroup', 'GetControlDescr');
        EventManager::getInstance()->unRegisterEventHandler(self::MODULE_ID, 'OnCondCatControlBuildListSotbitCrosssell', self::MODULE_ID, 'SotbitCrosssellCatalogCondCtrlIBlockFields', 'GetControlDescr');
        EventManager::getInstance()->unRegisterEventHandler(self::MODULE_ID, 'OnCondCatControlBuildListSotbitCrosssell', self::MODULE_ID, 'SotbitCrosssellCatalogCondCtrlIBlockProps', 'GetControlDescr');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBuildGlobalMenu', 'sotbit.crosssell', '\Sotbit\Crosssell\EventHandlers', 'OnBuildGlobalMenuHandler');
        return true;
    }

    function installDB()
    {

        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/install.sql');
        return true;
    }

    function UnInstallDB()
    {
    }

    function InstallFiles($_466267647 = array())
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/', true, true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/css', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/css', true, true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/local', $_SERVER['DOCUMENT_ROOT'] . '/local', true, true);
        if (is_dir($_743573520 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_1945642041 = opendir($_743573520)) {
                while (false !== $_1143087954 = readdir($_1945642041)) {
                    if ($_1143087954 == '..' || $_1143087954 == '.') continue;
                    CopyDirFiles($_743573520 . '/' . $_1143087954, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $_1143087954, $_1331846771 = True, $_1574965145 = True);
                }
                closedir($_1945642041);
            }
        }
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/css', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/css');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/local', $_SERVER['DOCUMENT_ROOT'] . '/local');
        if (is_dir($_743573520 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_1945642041 = opendir($_743573520)) {
                while (false !== $_1143087954 = readdir($_1945642041)) {
                    if ($_1143087954 == '..' || $_1143087954 == '.' || !is_dir($_39045368 = $_743573520 . '/' . $_1143087954)) continue;
                    $_2044246393 = opendir($_39045368);
                    while (false !== $_1865933454 = readdir($_2044246393)) {
                        if ($_1865933454 == '..' || $_1865933454 == '.') continue;
                        DeleteDirFilesEx('/bitrix/components/' . $_1143087954 . '/' . $_1865933454);
                    }
                    closedir($_2044246393);
                }
                closedir($_1945642041);
            }
        }
        return true;
    }

    function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallDB();
        $this->InstallEvents();
        ModuleManager::registerModule(self::MODULE_ID);
    }

    function DoUninstall()
    {
        global $DB, $APPLICATION, $unstep;
        $unstep = IntVal($unstep);
        if ($unstep < 2) $APPLICATION->IncludeAdminFile(GetMessage('SOTBIT_CROSSSELL_FORM_UNINSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/unstep.php');
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule(self::MODULE_ID);
        if ($unstep > 2 && $_REQUEST['save'] != 'on') $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/uninstall.sql');
    }
} ?>