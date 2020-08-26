<?php

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Class sotbit_opengraph extends CModule
{
    const MODULE_ID = 'sotbit.opengraph';
    var $MODULE_ID = 'sotbit.opengraph';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $_1866956022 = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('SOTBIT_OPENGRAPH_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SOTBIT_OPENGRAPH_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('SOTBIT_OPENGRAPH_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('SOTBIT_OPENGRAPH_PARTNER_URI');
    }

    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        RegisterModule(self::MODULE_ID);
    }

    function InstallDB($_1620744099 = array())
    {
        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/install.sql');
        return true;
    }

    function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeProlog', self::MODULE_ID, '\Sotbit\Opengraph\EventHandlers', 'OnBeforeProlog');
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeEndBufferContent', self::MODULE_ID, '\Sotbit\Opengraph\EventHandlers', 'OnBeforeEndBufferContent');
        EventManager::getInstance()->registerEventHandler('main', 'OnPanelCreate', self::MODULE_ID, '\Sotbit\Opengraph\EventHandlers', 'onPanelCreate');
        return true;
    }

    function InstallFiles($_1620744099 = array())
    {
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', Application::getDocumentRoot() . '/bitrix/admin', true);
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/css', Application::getDocumentRoot() . '/bitrix/css', true, true);
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/js', Application::getDocumentRoot() . '/bitrix/js', true, true);
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/images', Application::getDocumentRoot() . '/bitrix/images', true, true);
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
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/images', Application::getDocumentRoot() . '/bitrix/images');
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default/icons/', Application::getDocumentRoot() . '/bitrix/themes/.default/icons');
        return true;
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unregisterEventHandler('main', 'OnBeforeProlog', self::MODULE_ID, '\Sotbit\Opengraph\EventHandlers', 'OnBeforeProlog');
        EventManager::getInstance()->unregisterEventHandler('main', 'OnBeforeEndBufferContent', self::MODULE_ID, '\Sotbit\Opengraph\EventHandlers', 'OnBeforeEndBufferContent');
        EventManager::getInstance()->unregisterEventHandler('main', 'OnPanelCreate', self::MODULE_ID, '\Sotbit\Opengraph\EventHandlers', 'onPanelCreate');
    }

    function UnInstallDB($_1620744099 = array())
    {
        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/uninstall.sql');
        return true;
    }
} ?>