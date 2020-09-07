<?php

use Bitrix\Main\Loader;

$directory = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/modules";
$modules = $_POST['modules'];

if (!is_array($modules))
    $modules = [];

if (Loader::includeModule('intec.startshop') && $modules['intec.startshop'] == 'Y') {
    $file = $directory.'/intec.startshop/install/index.php';

    if (is_file($file)) {
        $_REQUEST['startshopUninstall']['TABLES'] = 'Y';
        $_REQUEST['startshopUninstall']['SETTINGS'] = 'Y';
        require_once($file);
        $module = new intec_startshop();
        $module->intec_startshop();
        $module->MODE = 'SILENT';
        $module->DoUninstall();
    }
}

if (Loader::includeModule('intec.constructorlite') && $modules['intec.constructorlite'] == 'Y') {
    $file = $directory.'/intec.constructorlite/install/index.php';

    if (is_file($file)) {
        require_once($file);
        $module = new intec_constructorlite();
        $module->intec_constructorlite();
        $module->DoUninstall();
    }
}