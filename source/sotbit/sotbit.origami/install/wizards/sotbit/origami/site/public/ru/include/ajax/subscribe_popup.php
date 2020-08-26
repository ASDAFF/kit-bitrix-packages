<?php
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
    'bitrix:main.include',
    '',
    [
        'AREA_FILE_SHOW' => 'file',
        'PATH' => SITE_DIR
            . "include/sotbit_origami/files/origami_subscribe/template.php",
        'AREA_FILE_RECURSIVE' => 'N',
        'EDIT_MODE' => 'html',
        'URL_PAGE' => $_POST['url']
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
//W:\domains\origami.local\include\sotbit_origami\files\origami_subscribe\template.php
