<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class intec_constructor extends CModule
{
    var $MODULE_ID = "intec.constructor";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    function intec_constructor()
    {
        /** @var array $arModuleVersion */
        require('version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('intec.constructor.install.index.name');
        $this->MODULE_DESCRIPTION = Loc::getMessage('intec.constructor.install.index.description');
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
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `code` varchar(255) NOT NULL,
          `name` varchar(255) NOT NULL,
          `css` longtext,
          `less` longtext,
          `js` longtext,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `UNIQUE` (`code`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_areas`(  
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `code` varchar(255) NOT NULL,
          `buildId` int(11) NOT NULL,
          `name` varchar(255) NOT NULL,
          `sort` int(11) NOT NULL DEFAULT 500,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `UNIQUE` (`code`, `buildId`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_areas_links`(  
          `areaId` int(11) NOT NULL,
          `containerId` int(11) NOT NULL,
          PRIMARY KEY (`areaId`, `containerId`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_properties` (
          `buildId` int(11) NOT NULL,
          `code` varchar(128) NOT NULL,
          `name` varchar(255) NOT NULL,
          `sort` int(11) NOT NULL DEFAULT 500,
          `type` enum(\'boolean\',\'string\',\'integer\',\'float\',\'enum\',\'color\') NOT NULL,
          `default` longtext,
          PRIMARY KEY (`code`,`buildId`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_properties_enums` (
          `buildId` int(11) NOT NULL,
          `propertyCode` varchar(128) NOT NULL,
          `code` varchar(128) NOT NULL,
          `name` varchar(255) NOT NULL,
          `sort` int(11) NOT NULL DEFAULT 500,
          PRIMARY KEY (`buildId`,`propertyCode`, `code`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `buildId` int(11) NOT NULL,
          `code` varchar(255) NOT NULL,
          `active` tinyint(1) NOT NULL DEFAULT 1,
          `default` tinyint(1) NOT NULL DEFAULT 0,
          `themeCode` varchar(255) DEFAULT NULL,
          `name` varchar(255) NOT NULL,
          `sort` int(11) NOT NULL,
          `condition` longtext,
          `css` longtext,
          `less` longtext,
          `js` longtext,
          `settings` longtext,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `UNIQUE` (`buildId`, `code`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_blocks` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `templateId` int(11) DEFAULT NULL,
          `areaId` int(11) DEFAULT NULL,
          `containerId` int(11) NOT NULL,
          `name` varchar(255) NOT NULL,
          `data` longtext DEFAULT NULL,
          PRIMARY KEY (`id`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_containers` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `code` varchar(255) DEFAULT NULL,
          `templateId` int(11) DEFAULT NULL,
          `areaId` int(11) DEFAULT NULL,
          `parentId` int(11) DEFAULT NULL,
          `type` enum(\'normal\',\'absolute\') NOT NULL DEFAULT \'normal\',
          `display` tinyint(1) NOT NULL DEFAULT 1,
          `order` int(11) NOT NULL DEFAULT 0,
          `condition` longtext,
          `script` longtext,
          `properties` longtext,
          PRIMARY KEY (`id`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_containers_links` (
          `containerId` int(11) NOT NULL,
          `parentId` int(11) NOT NULL,
          `parentType` enum(\'container\',\'variatorVariant\') NOT NULL,
          PRIMARY KEY (`containerId`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_components` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `templateId` int(11) DEFAULT NULL,
          `areaId` int(11) DEFAULT NULL,
          `containerId` int(11) NOT NULL,
          `code` varchar(255) NOT NULL,
          `template` varchar(255) DEFAULT NULL,
          `properties` longtext,
          PRIMARY KEY (`id`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_values` (
          `buildId` int(11) NOT NULL,
          `propertyCode` varchar(128) NOT NULL,
          `templateId` int(11) NOT NULL,
          `value` longtext,
          PRIMARY KEY (`buildId`, `propertyCode`,`templateId`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_variators` (  
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `templateId` int(11) DEFAULT NULL,
          `areaId` int(11) DEFAULT NULL,
          `containerId` int(11) NOT NULL,
          `variant` int(11),
          PRIMARY KEY (`id`)
        );');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_variators_variants` (  
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `code` varchar(255) DEFAULT NULL,
          `templateId` int(11) DEFAULT NULL,
          `areaId` int(11) DEFAULT NULL,
          `variatorId` int(11) NOT NULL,
          `order` int(11) NOT NULL DEFAULT 0,
          `name` varchar(255),
          PRIMARY KEY (`id`)
        );');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_templates_widgets` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `templateId` int(11) DEFAULT NULL,
          `areaId` int(11) DEFAULT NULL,
          `containerId` int(11) NOT NULL,
          `code` varchar(255) NOT NULL,
          `template` varchar(255) DEFAULT NULL,
          `properties` longtext,
          PRIMARY KEY (`id`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_themes` (
          `buildId` int(11) NOT NULL,
          `code` varchar(128) NOT NULL,
          `name` varchar(255) NOT NULL,
          `sort` int(11) NOT NULL DEFAULT 500,
          PRIMARY KEY (`code`,`buildId`)
        )');
        $DB->Query('CREATE TABLE IF NOT EXISTS `constructor_builds_themes_values` (
          `buildId` int(11) NOT NULL,
          `propertyCode` varchar(128) NOT NULL,
          `themeCode` varchar(128) NOT NULL,
          `value` longtext,
          PRIMARY KEY (`buildId`, `propertyCode`,`themeCode`)
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
        $isInstalled = ModuleManager::isModuleInstalled('intec.constructorlite');
        if (!$isInstalled) {
            $DB->Query('DROP TABLE IF EXISTS
               `constructor_blocks_categories`,
               `constructor_blocks_templates`,
               `constructor_fonts`,
               `constructor_fonts_links`,
               `constructor_fonts_files`');
        }
        $DB->Query('DROP TABLE IF EXISTS
           `constructor_builds`,
           `constructor_builds_areas`,
           `constructor_builds_areas_links`,
           `constructor_builds_properties`,
           `constructor_builds_properties_enums`,
           `constructor_builds_templates`,
           `constructor_builds_templates_blocks`,
           `constructor_builds_templates_containers`,
           `constructor_builds_templates_containers_links`,
           `constructor_builds_templates_components`,
           `constructor_builds_templates_properties`,
           `constructor_builds_templates_values`,
           `constructor_builds_templates_variators`,
           `constructor_builds_templates_variators_variants`,
           `constructor_builds_templates_widgets`,
           `constructor_builds_themes`,
           `constructor_builds_themes_values`');
    }

    function DoInstall()
    {
        require_once(__DIR__ . '/../classes/Loader.php');
        global $APPLICATION;
        parent::DoInstall();
        $this->InstallDB();
        if (!Loader::includeModule('intec.core')) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage('module.install.requires'), __DIR__ . '/requires.php');
            exit;
        }
        include(__DIR__ . '/procedures/install.files.php');
        if (Loader::includeModule('security')) {
            $resMasks = CSecurityFilterMask::GetList();
            $arMasks = [];
            while ($mask = $resMasks->GetNext()) $arMasks[] = ['SITE_ID' => $mask['SITE_ID'], 'MASK' => $mask['FILTER_MASK']];
            unset($mask);
            unset($resMasks);
            $arMasks[] = ['SITE_ID' => '', 'MASK' => '/bitrix/admin/constructor_*'];
            CSecurityFilterMask::Update($arMasks);
        }
        RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        require_once(__DIR__ . '/../classes/Loader.php');
        global $APPLICATION;
        parent::DoUninstall();
        $isInstalled = ModuleManager::isModuleInstalled('intec.constructorlite');
        $continue = $_POST['go'];
        $continue = $continue == 'Y';
        $remove = $_POST['remove'];
        if (!$continue) $APPLICATION->IncludeAdminFile(Loc::getMessage('intec.constructor.install.uninstall.title'), $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/modules/' . $this->MODULE_ID . '/install/unstep.php');
        if ($remove['database'] == 'Y') $this->UnInstallDB();
        if (!Loader::includeModule('intec.core')) {
            UnRegisterModule($this->MODULE_ID);
            return;
        }
        if (!$isInstalled) {
            include(__DIR__ . '/procedures/uninstall.files.php');
        }
        if (Loader::includeModule('security')) {
            $resMasks = CSecurityFilterMask::GetList();
            $arMasks = [];
            while ($mask = $resMasks->GetNext()) if ($mask['FILTER_MASK'] != '/bitrix/admin/constructor_*') $arMasks[] = ['SITE_ID' => $mask['SITE_ID'], 'MASK' => $mask['FILTER_MASK']];
            unset($mask);
            unset($resMasks);
            CSecurityFilterMask::Update($arMasks);
        }
        UnRegisterModule($this->MODULE_ID);
    }

    function GetInstallDirectory()
    {
        return __DIR__;
    }
}