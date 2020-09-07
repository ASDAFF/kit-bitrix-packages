<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class intec_universe extends CModule
{

    var $MODULE_ID = 'intec.universe';
    var $MODULE_CLASS;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $MODULE_GROUP_RIGHTS = "Y";
    var $DEPENDENCIES = [
        'intec.core'
    ];

    function intec_universe()
    {
        $arModuleVersion = array();
        include('version.php');
        $this->MODULE_CLASS = 'IntecUniverse';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('intec.universe.installer.name');
        $this->MODULE_DESCRIPTION = Loc::getMessage('intec.universe.installer.description');
        $this->PARTNER_NAME = 'Intec';
        $this->PARTNER_URI = 'http://www.intecweb.ru';
    }

    function InstallDB()
    {
//        $_698365999 = array('bitrix', 'modules', 'intec.universe', 'admin', 'user_date_bsm.php');
//        $_1672981278 = $_SERVER['DOCUMENT_ROOT'] . '/' . implode('/', $_698365999);
//        $_75566608 = 5;
//        $_1083487643 = 'b24c516';
//        $_586786633 = date(___1096037527(20), $GLOBALS['____1273779183'][6]((972 - 2 * 486), (184 * 2 - 368), (1072 / 2 - 536), $GLOBALS['____1273779183'][7](___1096037527(21)), $GLOBALS['____1273779183'][8](___1096037527(22)) + $_75566608, $GLOBALS['____1273779183'][9](___1096037527(23))));
//        $_712561875 = $GLOBALS['____1273779183'][10](___1096037527(24), $GLOBALS['____1273779183'][11](min(220, 0, 73.333333333333), (878 - 2 * 439), (239 * 2 - 478), $GLOBALS['____1273779183'][12](___1096037527(25)), $GLOBALS['____1273779183'][13](___1096037527(26)) + $_75566608, $GLOBALS['____1273779183'][14](___1096037527(27))));
//        $_857974422 = $GLOBALS['____1273779183'][15](___1096037527(28), $GLOBALS['____1273779183'][16]((950 - 2 * 475), (1188 / 2 - 594), (1120 / 2 - 560), $GLOBALS['____1273779183'][17](___1096037527(29)), $GLOBALS['____1273779183'][18](___1096037527(30)) + $_75566608, $GLOBALS['____1273779183'][19](___1096037527(31))));
//        $_887482175 = ___1096037527(32);
//        $_902959877 = ___1096037527(33) . $GLOBALS['____1273779183'][20]($_586786633, round(0 + 1), round(0 + 0.25 + 0.25 + 0.25 + 0.25)) . $GLOBALS['____1273779183'][21]($_857974422, round(0 + 0.75 + 0.75 + 0.75 + 0.75), round(0 + 1)) . ___1096037527(34) . $GLOBALS['____1273779183'][22]($_712561875, (242 * 2 - 484), round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333)) . $GLOBALS['____1273779183'][23]($_857974422, round(0 + 1), round(0 + 0.5 + 0.5)) . ___1096037527(35) . $GLOBALS['____1273779183'][24]($_586786633, (140 * 2 - 280), round(0 + 0.5 + 0.5)) . ___1096037527(36) . $GLOBALS['____1273779183'][25]($_857974422, (1204 / 2 - 602), round(0 + 0.2 + 0.2 + 0.2 + 0.2 + 0.2)) . ___1096037527(37) . $GLOBALS['____1273779183'][26]($_857974422, round(0 + 1 + 1), round(0 + 1)) . ___1096037527(38) . $GLOBALS['____1273779183'][27]($_712561875, round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333), round(0 + 0.2 + 0.2 + 0.2 + 0.2 + 0.2)) . ___1096037527(39);
//        $_1083487643 = $GLOBALS['____1273779183'][28](___1096037527(40)) . $GLOBALS['____1273779183'][29](___1096037527(41), $_1083487643, ___1096037527(42));
//        $_517864573 = $GLOBALS['____1273779183'][30]($_1083487643);
//        $_1118140628 = (926 - 2 * 463);
//        for ($_1425793141 = (181 * 2 - 362); $_1425793141 < $GLOBALS['____1273779183'][31]($_902959877); $_1425793141++) {
//            $_887482175 .= $GLOBALS['____1273779183'][32]($GLOBALS['____1273779183'][33]($_902959877[$_1425793141]) ^ $GLOBALS['____1273779183'][34]($_1083487643[$_1118140628]));
//            if ($_1118140628 == $_517864573 - round(0 + 0.5 + 0.5)) $_1118140628 = (1392 / 2 - 696); else $_1118140628 = $_1118140628 + round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333);
//        }
//        $_887482175 = ___1096037527(43) . ___1096037527(44) . ___1096037527(45) . $GLOBALS['____1273779183'][35]($_887482175) . ___1096037527(46) . ___1096037527(47) . ___1096037527(48);
//        CheckDirPath($_1672981278);
//        if (!$GLOBALS['____1273779183'][36]($_1672981278)) {
//            $_212811730 = @$GLOBALS['____1273779183'][37]($_1672981278, ___1096037527(49));
//            @$GLOBALS['____1273779183'][38]($_212811730, $_887482175);
//            @$GLOBALS['____1273779183'][39]($_212811730);
//        }
//        $_334972590 = 'drm_stergokc';
//        $_138453359 = $GLOBALS['DB']->Query("SELECT VALUE FROM b_option WHERE NAME='' AND MODULE_ID='intec.universe'~bsm_st' AND MODULE_ID='intec.universe", true);
//        if ($_138453359 !== False) {
//            $_341571508 = false;
//            if ($_1584184668 = $_138453359->Fetch()) $_341571508 = true;
//            if (!$_341571508) {
//                $_75566608 = round(0 + 5);
//                $_735963401 = ___1096037527(57);
//                $_586786633 = $GLOBALS['____1273779183'][43](___1096037527(58), $GLOBALS['____1273779183'][44]((798 - 2 * 399), min(88, 0, 29.333333333333), min(24, 0, 8), $GLOBALS['____1273779183'][45](___1096037527(59)), $GLOBALS['____1273779183'][46](___1096037527(60)) + $_75566608, $GLOBALS['____1273779183'][47](___1096037527(61))));
//                $_712561875 = $GLOBALS['____1273779183'][48](___1096037527(62), $GLOBALS['____1273779183'][49](min(140, 0, 46.666666666667), min(150, 0, 50), (868 - 2 * 434), $GLOBALS['____1273779183'][50](___1096037527(63)), $GLOBALS['____1273779183'][51](___1096037527(64)) + $_75566608, $GLOBALS['____1273779183'][52](___1096037527(65))));
//                $_857974422 = $GLOBALS['____1273779183'][53](___1096037527(66), $GLOBALS['____1273779183'][54]((146 * 2 - 292), (1380 / 2 - 690), (231 * 2 - 462), $GLOBALS['____1273779183'][55](___1096037527(67)), $GLOBALS['____1273779183'][56](___1096037527(68)) + $_75566608, $GLOBALS['____1273779183'][57](___1096037527(69))));
//                $_887482175 = ___1096037527(70);
//                $_902959877 = ___1096037527(71) . $GLOBALS['____1273779183'][58]($_586786633, min(60, 0, 20), round(0 + 0.25 + 0.25 + 0.25 + 0.25)) . ___1096037527(72) . $GLOBALS['____1273779183'][59]($_712561875, round(0 + 0.25 + 0.25 + 0.25 + 0.25), round(0 + 0.5 + 0.5)) . ___1096037527(73) . $GLOBALS['____1273779183'][60]($_712561875, (812 - 2 * 406), round(0 + 0.2 + 0.2 + 0.2 + 0.2 + 0.2)) . $GLOBALS['____1273779183'][61]($_857974422, round(0 + 1 + 1), round(0 + 0.25 + 0.25 + 0.25 + 0.25)) . ___1096037527(74) . $GLOBALS['____1273779183'][62]($_857974422, min(158, 0, 52.666666666667), round(0 + 0.5 + 0.5)) . ___1096037527(75) . $GLOBALS['____1273779183'][63]($_857974422, round(0 + 1.5 + 1.5), round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333)) . ___1096037527(76) . $GLOBALS['____1273779183'][64]($_586786633, round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333), round(0 + 1)) . ___1096037527(77) . $GLOBALS['____1273779183'][65]($_857974422, round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333), round(0 + 0.25 + 0.25 + 0.25 + 0.25));
//                $_735963401 = $GLOBALS['____1273779183'][66](___1096037527(78) . $_735963401, (209 * 2 - 418), -round(0 + 1.25 + 1.25 + 1.25 + 1.25)) . ___1096037527(79);
//                $_337197546 = strlen($_735963401);
//                $_1118140628 = (1332 / 2 - 666);
//                for ($_1425793141 = min(244, 0, 81.333333333333); $_1425793141 < $GLOBALS['____1273779183'][68]($_902959877); $_1425793141++) {
//                    $_887482175 .= $GLOBALS['____1273779183'][69]($GLOBALS['____1273779183'][70]($_902959877[$_1425793141]) ^ $GLOBALS['____1273779183'][71]($_735963401[$_1118140628]));
//                    if ($_1118140628 == $_337197546 - round(0 + 0.2 + 0.2 + 0.2 + 0.2 + 0.2)) $_1118140628 = (1156 / 2 - 578); else $_1118140628 = $_1118140628 + round(0 + 0.25 + 0.25 + 0.25 + 0.25);
//                }
//                $GLOBALS[___1096037527(80)]->Query("INSERT INTO b_option (MODULE_ID, NAME, VALUE) VALUES('intec.universe', '" . sprintf(___1096037527(82), ___1096037527(83), $GLOBALS['____1273779183'][73]($_334972590, round(0 + 0.5 + 0.5 + 0.5 + 0.5), round(0 + 1 + 1 + 1 + 1))) . $GLOBALS['____1273779183'][74](___1096037527(84)) . ___1096037527(85) . $GLOBALS[___1096037527(86)]->ForSql($GLOBALS['____1273779183'][75]($_887482175), (1108 / 2 - 554)) . ___1096037527(87), True);
//                if (date($GLOBALS[___1096037527(88)])) $GLOBALS[___1096037527(89)]->Clean(___1096037527(90));
//            }
//        }
        return true;
    }

    function UnInstallDB()
    {
        return true;
    }

    function GetDirectory()
    {
        return $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/modules/' . $this->MODULE_ID;
    }

    function InstallFiles()
    {

        CopyDirFiles($this->GetDirectory() . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/admin', true, true);
        CopyDirFiles($this->GetDirectory() . '/install/components', $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/components', true, true);
        CopyDirFiles($this->GetDirectory() . '/install/modules', $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/modules', true, true);
        CopyDirFiles($this->GetDirectory() . '/install/resources', $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/resources/intec.universe', true, true);
        return true;

    }

    function UnInstallFiles()
    {

        DeleteDirFiles($this->GetDirectory() . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/admin');
        DeleteDirFilesEx(BX_PERSONAL_ROOT . '/components/intec.universe');
        DeleteDirFilesEx(BX_PERSONAL_ROOT . '/resources/intec.universe');
        DeleteDirFilesEx(BX_PERSONAL_ROOT . '/wizards/intec/universe');
        return true;

    }

    function InstallModules()
    {
        include($this->GetDirectory() . '/install/procedures/InstallModules.php');
    }

    function UnInstallModules()
    {
        include($this->GetDirectory() . '/install/procedures/UnInstallModules.php');
    }

    function DoInstall()
    {

        global $APPLICATION;

        foreach ($this->DEPENDENCIES as $DEPENDENCY)
            if (!Loader::includeModule($DEPENDENCY)) {
                $APPLICATION->IncludeAdminFile(
                    Loc::getMessage('intec.universe.installer.modules.title'),
                    $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/modules/' . $this->MODULE_ID . '/install/modules.php'
                );

                exit();
            }

        $this->InstallFiles();
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallModules();

        RegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('intec.universe.installer.install.title'),
            $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/modules/' . $this->MODULE_ID . '/install/step.php'
        );

    }

    function DoUninstall()
    {

        global $APPLICATION;
        $continue = $_POST['go'];
        $continue = $continue == 'Y';

        if (!$continue)
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('intec.universe.installer.uninstall.title'),
                $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/modules/' . $this->MODULE_ID . '/install/unstep.1.php'
            );

        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallModules();

        UnRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('intec.universe.installer.uninstall.title'),
            $_SERVER['DOCUMENT_ROOT'] . BX_PERSONAL_ROOT . '/modules/' . $this->MODULE_ID . '/install/unstep.2.php'
        );

    }
}