<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\EventManager;
use \Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client_partner.php');

class kit_origami extends CModule
{
    const MODULE_ID = 'kit.origami';
    var $MODULE_ID = 'kit.origami';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $_1595429158 = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage(self::MODULE_ID . '_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage(self::MODULE_ID . '_MODULE_DESC');
        $this->PARTNER_NAME = GetMessage('kit.origami_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('kit.origami_PARTNER_URI');
    }

    function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnBuildGlobalMenuHandler');
        EventManager::getInstance()->registerEventHandler('main', 'OnEndBufferContent', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnEndBufferContentHandler');
        EventManager::getInstance()->registerEventHandler('sale', 'OnSaleComponentOrderProperties', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnSaleComponentOrderPropertiesHandler');
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeProlog', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnBeforePrologHandler');
        EventManager::getInstance()->registerEventHandler('sale', 'OnSaleBasketItemSetField', 'kit.origami', '\Kit\Origami\EventHandlers', 'onChangeOfferNameBasket');
        EventManager::getInstance()->registerEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        EventManager::getInstance()->registerEventHandler('iblock', 'OnAfterIBlockElementAdd', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        EventManager::getInstance()->registerEventHandler('catalog', 'OnPriceAdd', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        EventManager::getInstance()->registerEventHandler('catalog', 'OnPriceUpdate', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        return true;
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBuildGlobalMenu', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnBuildGlobalMenuHandler');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnEndBufferContent', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnEndBufferContentHandler');
        EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleComponentOrderProperties', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnSaleComponentOrderPropertiesHandler');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBeforeProlog', 'kit.origami', '\Kit\Origami\EventHandlers', 'OnBeforePrologHandler');
        EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleBasketItemSetField', 'kit.origami', '\Kit\Origami\EventHandlers', 'onChangeOfferNameBasket');
        EventManager::getInstance()->unRegisterEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        EventManager::getInstance()->unRegisterEventHandler('iblock', 'OnAfterIBlockElementAdd', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        EventManager::getInstance()->unRegisterEventHandler('catalog', 'OnPriceAdd', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        EventManager::getInstance()->unRegisterEventHandler('catalog', 'OnPriceUpdate', 'kit.origami', 'KitOrigami', 'DoIBlockAfterSave');
        return true;
    }

    function InstallFiles($_826024681 = array())
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/', true, true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
        if (is_dir($_2093502418 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_1193811113 = opendir($_2093502418)) {
                while (false !== $_1264003444 = readdir($_1193811113)) {
                    if ($_1264003444 == '..' || $_1264003444 == '.') continue;
                    CopyDirFiles($_2093502418 . '/' . $_1264003444, $_SERVER['DOCUMENT_ROOT'] . '/local/components/' . $_1264003444, $_474050157 = True, $_1421015224 = True);
                }
                closedir($_1193811113);
            }
        }
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'] . '/bitrix/wizards/kit');
        if (is_dir($_2093502418 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_1193811113 = opendir($_2093502418)) {
                while (false !== $_1264003444 = readdir($_1193811113)) {
                    if ($_1264003444 == '..' || $_1264003444 == '.' || !is_dir($_878681744 = $_2093502418 . '/' . $_1264003444)) continue;
                    $_1375706258 = opendir($_878681744);
                    while (false !== $_1706908690 = readdir($_1375706258)) {
                        if ($_1706908690 == '..' || $_1706908690 == '.') continue;
                        DeleteDirFilesEx('/bitrix/components/' . $_1264003444 . '/' . $_1706908690);
                    }
                    closedir($_1375706258);
                }
                closedir($_1193811113);
            }
        }
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
        global $DB;
        $DB->runSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/uninstall.sql');
        return true;
    }

    function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallAgents();
        ModuleManager::registerModule(self::MODULE_ID);
    }

    function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallAgents();
        $this->DeleteWizard($_SERVER['DOCUMENT_ROOT'] . '/bitrix/wizards/kit');
        ModuleManager::unRegisterModule(self::MODULE_ID);
    }

    function InstallAgents()
    {
        return true;
    }

    function UnInstallAgents()
    {
        return true;
    }

    function DeleteWizard($_879311432)
    {
        if (is_dir($_879311432) === true) {
            $_1868885755 = array_diff(scandir($_879311432), array('.', '..'));
            foreach ($_1868885755 as $_113605619) {
                $this->DeleteWizard(realpath($_879311432) . '/' . $_113605619);
            }
            return rmdir($_879311432);
        } else if (is_file($_879311432) === true) {
            return unlink($_879311432);
        }
        return true;
    }
} ?>