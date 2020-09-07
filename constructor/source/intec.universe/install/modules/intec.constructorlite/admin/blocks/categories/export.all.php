<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

global $APPLICATION;

use intec\Core;
use intec\core\helpers\FileHelper;
use intec\constructor\models\block\Category;
use intec\core\io\Path;

if (!CModule::IncludeModule('intec.constructorlite'))
    return;

if (!extension_loaded('zip'))
    return;

include(Core::getAlias('@intec/constructor/module/admin/url.php'));


if (ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');

$directory = Path::from('@intec/constructor/upload/exchange');

if (!FileHelper::isDirectory($directory->value))
    FileHelper::createDirectory($directory->value);

if (FileHelper::isDirectory($directory)) {
    $path = $directory->add('categories.json');

    if (FileHelper::isFile($path->value))
        unlink($path->value);

    if (Category::exportAllToFile($path)) {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Disposition: attachment; filename=categories.json');
        header('Content-Transfer-Encoding: binary');
        header("Content-Length: ".filesize($path->value));
        readfile($path->value);
    }

    if (FileHelper::isFile($path->value))
        unlink($path->value);
}

