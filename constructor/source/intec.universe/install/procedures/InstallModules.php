<?php

use Bitrix\Main\Loader;

$directory = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/modules";
        
if (!Loader::includeModule('catalog') && !Loader::includeModule('intec.startshop')) {
    $file = $directory.'/intec.startshop/install/index.php';

    if (is_file($file)) {
        require_once($file);
        $module = new intec_startshop();
        $module->intec_startshop();
        $module->MODE = 'SILENT';
        $module->DoInstall();
    }
}

if (!Loader::includeModule('intec.constructorlite')) {
    $file = $directory.'/intec.constructorlite/install/index.php';

    if (is_file($file)) {
        require_once($file);
        $module = new intec_constructorlite();
        $module->intec_constructorlite();
        $module->DoInstall();
    }
}