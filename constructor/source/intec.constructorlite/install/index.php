<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class intec_constructorlite extends CModule
{
    var $MODULE_ID = "intec.constructorlite";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    function intec_constructorlite()
    {
        /** @var array $arModuleVersion */
        require('version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('intec.constructorlite.install.index.name');
        $this->MODULE_DESCRIPTION = Loc::getMessage('intec.constructorlite.install.index.description');
        $this->PARTNER_NAME = "Intec";
        $this->PARTNER_URI = "http://intecweb.ru";
    }

    function InstallDB()
    {
        global $DB;

        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_blocks_categories` (  
          `code` varchar(255) NOT NULL,
          `sort` int(11) NOT NULL DEFAULT 500,
          `name` varchar(255) NOT NULL,
          PRIMARY KEY (`code`)
        )');

        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_blocks_templates` (  
          `code` varchar(255) NOT NULL,
          `categoryCode` varchar(255),
          `sort` int(11) NOT NULL DEFAULT 500,
          `name` varchar(255) NOT NULL,
          `image` int(11),
          `data` longtext,
          PRIMARY KEY (`code`)
        )');

        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_fonts` (  
          `code` varchar(255) NOT NULL,
          `active` tinyint(1) NOT NULL DEFAULT 1,
          `name` varchar(255) NOT NULL,
          `sort` int(11) NOT NULL DEFAULT 500,
          `type` int(11) NOT NULL,
          PRIMARY KEY (`code`)
        )');

        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_fonts_links` (
          `fontCode` varchar(255) NOT NULL,
          `value` varchar(255) NOT NULL,
          PRIMARY KEY (`fontCode`)
        )');

        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_fonts_files` (
          `fontCode` varchar(255) NOT NULL,
          `weight` int(11) NOT NULL,
          `style` enum(\'normal\',\'italic\',\'oblique\') NOT NULL,
          `format` enum(\'truetype\',\'opentype\',\'embedded-opentype\',\'woff\',\'woff2\',\'svg\') NOT NULL,
          `fileId` int(11) NOT NULL,
          UNIQUE KEY `UNIQUE` (`fontCode`,`weight`,`style`, `format`)
        )');
    }

    function UnInstallDB()
    {
        global $DB;

        $DB->Query('DROP TABLE IF EXISTS
           `constructor_blocks_categories`,
           `constructor_blocks_templates`,
           `constructor_fonts`,
           `constructor_fonts_links`,
           `constructor_fonts_files`');
    }

    function DoInstall()
    {
        require_once(__DIR__.'/../classes/Loader.php');

        global $APPLICATION;
        parent::DoInstall();

        $isInstalled = ModuleManager::isModuleInstalled('intec.constructor');

        if (!$isInstalled) {
            $this->InstallDB();
        }

        if (!Loader::includeModule('intec.core')) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("module.install.requires"),
                __DIR__."/requires.php"
            );
            exit;
        }

        if (!$isInstalled) {
            include(__DIR__ . '/procedures/install.files.php');
        }

        include(__DIR__ . '/procedures/install.files.admin.php');

        if (Loader::includeModule('security')) {
            $rsFilterMasks = CSecurityFilterMask::GetList();
            $arFilterMasks = [];

            while ($arFilterMask = $rsFilterMasks->GetNext())
                $arFilterMasks[] = [
                    'SITE_ID' => $arFilterMask['SITE_ID'],
                    'MASK' => $arFilterMask['FILTER_MASK']
                ];

            unset($arFilterMask);
            unset($rsFilterMasks);

            $arFilterMasks[] = [
                'SITE_ID' => '',
                'MASK' => '/bitrix/admin/constructorlite_*'
            ];

            CSecurityFilterMask::Update($arFilterMasks);
        }

        RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        require_once(__DIR__.'/../classes/Loader.php');

        global $APPLICATION;
        parent::DoUninstall();

        $isInstalled = ModuleManager::isModuleInstalled('intec.constructor');
        $continue = $_POST['go'];
        $continue = $continue == 'Y';
        $remove = $_POST['remove'];

        if (!$continue)
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('intec.constructorlite.install.uninstall.title'),
                $_SERVER['DOCUMENT_ROOT'].BX_PERSONAL_ROOT.'/modules/'.$this->MODULE_ID.'/install/unstep.php'
            );

        if (!$isInstalled) {
            if ($remove['database'] == 'Y')
                $this->UnInstallDB();
        }

        if (!Loader::includeModule('intec.core')) {
            UnRegisterModule($this->MODULE_ID);
            return;
        }

        if (!$isInstalled) {
            include(__DIR__.'/procedures/uninstall.files.php');
        }

        include(__DIR__.'/procedures/uninstall.files.admin.php');

        if (Loader::includeModule('security')) {
            $rsFilterMasks = CSecurityFilterMask::GetList();
            $arFilterMasks = [];

            while ($arFilterMask = $rsFilterMasks->GetNext())
                if ($arFilterMask['FILTER_MASK'] != '/bitrix/admin/constructorlite_*')
                    $arFilterMasks[] = [
                        'SITE_ID' => $arFilterMask['SITE_ID'],
                        'MASK' => $arFilterMask['FILTER_MASK']
                    ];

            unset($arFilterMask);
            unset($rsFilterMasks);

            CSecurityFilterMask::Update($arFilterMasks);
        }

        UnRegisterModule($this->MODULE_ID);
    }

    function GetInstallDirectory()
    {
        return __DIR__;
    }
}