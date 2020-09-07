<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

global $APPLICATION;

use intec\Core;
use intec\core\helpers\FileHelper;
use intec\constructor\models\block\Template;
use intec\core\io\Path;

if (!CModule::IncludeModule('intec.constructor'))
    return;

if (!extension_loaded('zip'))
    return;

include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$template = $request->get('template');

/** @var Template $template */
$template = Template::findOne($template);

if (empty($template))
    return;


if (ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');

$directory = Path::from('@intec/constructor/upload/exchange');

if (FileHelper::isDirectory($directory->value))
    FileHelper::removeDirectory($directory->value);

FileHelper::createDirectory($directory->value);

if (FileHelper::isDirectory($directory)) {
    $path = $directory->add('export.zip');

    if ($template->exportToFile($path->value)) {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Disposition: attachment; filename=block.'. $template->code .'.zip');
        header('Content-Transfer-Encoding: binary');
        header("Content-Length: ".filesize($path));
        readfile($path);
    }

    FileHelper::removeDirectory($directory->value);
}