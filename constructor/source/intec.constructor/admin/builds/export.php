<? require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php'); ?>
<?php
if (!CModule::IncludeModule('intec.constructor'))
    return;

use intec\Core;
use intec\core\helpers\FileHelper;
use intec\constructor\models\Build;
use intec\core\io\Path;

$request = Core::$app->request;
$build = $request->get('build');

/** @var Build $build */
$build = Build::findOne($build);

if (!$build)
    return;

if (!extension_loaded('zip'))
    return;

ini_set('max_execution_time', 0);

if (ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');

$directory = Path::from('@intec/constructor/upload/exchange');

if (FileHelper::isDirectory($directory->value))
    FileHelper::removeDirectory($directory->value);

FileHelper::createDirectory($directory->value);

if (FileHelper::isDirectory($directory->value)) {
    $path = $directory->add('export.zip');

    if ($build->exportToFile($path)) {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Disposition: attachment; filename=build.'.$build->code.'.zip');
        header('Content-Transfer-Encoding: binary');
        header("Content-Length: ".filesize($path));
        readfile($path);
    }

    FileHelper::removeDirectory($directory->value);
}