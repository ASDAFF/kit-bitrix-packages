<?php
require_once(__DIR__.'/../classes/Core.php');

use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use intec\Core;
use intec\core\helpers\FileHelper;

Loc::loadMessages(__FILE__);

class intec_core extends CModule
{
    var $MODULE_ID = "intec.core";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    protected $directories = [
        '@intec/core/module/install/resources' => '@intec/core/resources'
    ];

    function intec_core ()
    {
        $arModuleVersion = array();

        include('version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('intec.core.installer.name');
        $this->MODULE_DESCRIPTION = Loc::getMessage('intec.core.installer.description');
        $this->PARTNER_NAME = "Intec";
        $this->PARTNER_URI = "http://intecweb.ru";
    }

    function InstallDB()
    {

    }

    function UnInstallDB()
    {

    }

    function DoInstall()
    {
        parent::DoInstall();

        global $APPLICATION;

        if (!extension_loaded('pdo') || !extension_loaded('pdo_mysql')) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('intec.core.installer.requires.title'),
                __DIR__.'/requires.php'
            );
            exit;
        }

        require(__DIR__.'/procedures/database.php');

        $this->InstallDB();

        foreach ($this->directories as $directoryFrom => $directoryTo) {
            FileHelper::copyDirectory(
                Core::getAlias($directoryFrom),
                Core::getAlias($directoryTo)
            );
        }

        ModuleManager::registerModule($this->MODULE_ID);

        $events = EventManager::getInstance();
        $events->registerEventHandler(
            'main',
            'OnBuildGlobalMenu',
            $this->MODULE_ID,
            '\\intec\\core\\Callbacks',
            'mainOnBuildGlobalMenu'
        );

        $events->registerEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            '\\intec\\core\\Callbacks',
            'mainOnEndBufferContent'
        );

        $events->registerEventHandler(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            '\\intec\\core\\bitrix\\main\\properties\\HtmlProperty',
            'getDefinition'
        );

        $events->registerEventHandler(
            'iblock',
            'OnTemplateGetFunctionClass',
            $this->MODULE_ID,
            '\\intec\\core\\bitrix\\iblock\\Tags',
            'resolve'
        );
    }

    function DoUninstall()
    {
        parent::DoUninstall();
        $this->UnInstallDB();

        foreach ($this->directories as $directory) {
            $directory = Core::getAlias($directory);
            FileHelper::removeDirectory($directory);
        }

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $events = EventManager::getInstance();
        $events->unRegisterEventHandler(
            'main',
            'OnBuildGlobalMenu',
            $this->MODULE_ID,
            '\\intec\\core\\Callbacks',
            'mainOnBuildGlobalMenu'
        );

        $events->unRegisterEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            '\\intec\\core\\Callbacks',
            'mainOnEndBufferContent'
        );

        $events->unRegisterEventHandler(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            '\\intec\\core\\bitrix\\main\\properties\\HtmlProperty',
            'getDefinition'
        );

        $events->unRegisterEventHandler(
            'iblock',
            'OnTemplateGetFunctionClass',
            $this->MODULE_ID,
            '\\intec\\core\\bitrix\\iblock\\Tags',
            'resolve'
        );
    }
}