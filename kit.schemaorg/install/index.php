<?
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Class kit_schemaorg extends CModule
{
    const MODULE_ID = 'kit.schemaorg';
    var $MODULE_ID = 'kit.schemaorg';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $_133079340 = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('KIT_SCHEMA_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('KIT_SCHEMA_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('KIT_SCHEMA_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('KIT_SCHEMA_PARTNER_URI');
    }

    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        RegisterModule(self::MODULE_ID);
    }

    function InstallDB($_57237851 = array())
    {


        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/install.sql');
        return true;
    }

    function InstallEvents()
    {
        EventManager::getInstance()->unregisterEventHandler('main', 'OnBeforeProlog', self::MODULE_ID, '\Kit\Schemaorg\EventHandlers', 'OnBeforeProlog');
        EventManager::getInstance()->registerEventHandler('main', 'OnEndBufferContent', self::MODULE_ID, '\Kit\Schemaorg\EventHandlers', 'ChangeContent');
        EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', self::MODULE_ID, '\Kit\Schemaorg\EventHandlers', 'OnBuildGlobalMenuHandler');
        return true;
    }

    function InstallFiles($_57237851 = array())
    {
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', Application::getDocumentRoot() . '/bitrix/admin', true);
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/css', Application::getDocumentRoot() . '/bitrix/css', true, true);
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/js', Application::getDocumentRoot() . '/bitrix/js', true, true);
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/', Application::getDocumentRoot() . '/bitrix/themes/', true, true);
        return true;
    }

    function DoUninstall()
    {
        UnRegisterModule(self::MODULE_ID);
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallDB();
    }

    function UnInstallFiles()
    {
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', Application::getDocumentRoot() . '/bitrix/admin');
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/css', Application::getDocumentRoot() . '/bitrix/css');
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/js', Application::getDocumentRoot() . '/bitrix/js');
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/', Application::getDocumentRoot() . '/bitrix/themes/');
        return true;
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unregisterEventHandler('main', 'OnEndBufferContent', self::MODULE_ID, '\Kit\Schemaorg\EventHandlers', 'ChangeContent');
        EventManager::getInstance()->unregisterEventHandler('main', 'OnBuildGlobalMenu', self::MODULE_ID, '\Kit\Schemaorg\EventHandlers', 'OnBuildGlobalMenuHandler');
        return true;
    }

    function UnInstallDB($_57237851 = array())
    {
        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/uninstall.sql');
        return true;
    }
} ?>