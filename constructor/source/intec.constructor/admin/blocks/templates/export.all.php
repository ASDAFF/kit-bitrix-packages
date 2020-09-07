<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

global $APPLICATION;

use intec\Core;
use intec\core\helpers\FileHelper;
use intec\constructor\models\block\Template;
use intec\constructor\models\block\Category;
use intec\core\io\Path;

if (!CModule::IncludeModule('intec.constructor'))
    return;

if (!extension_loaded('zip'))
    return;

include(Core::getAlias('@intec/constructor/module/admin/url.php'));

/** @var Template $template */
$templates = Template::find()->all();
$categories = Category::find()->indexBy('code')->all();

if (ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');

$archive = new ZipArchive();
$directory = Path::from('@intec/constructor/upload/exchange');

if (FileHelper::isDirectory($directory->value))
    FileHelper::removeDirectory($directory->value);

FileHelper::createDirectory($directory->value);

if (FileHelper::isDirectory($directory->value)) {
    $path = $directory->add('export.zip');

    if (!$archive->open($path->value, ZipArchive::CREATE))
        return;

    foreach ($templates as $template) {
        /** @var Category $category */
        $category = null;

        if (!empty($template->categoryCode))
            $category = $categories->get($template->categoryCode);

        $templatePath = $directory->add('export.'.$template->code.'.zip');

        if (FileHelper::isFile($templatePath->value))
            unlink($templatePath->value);

        if ($template->exportToFile($templatePath))
            $archive->addFile($templatePath->value, (!empty($category) ? $category->code.'/' : null).$template->code.'.zip');
    }

    $archive->close();

    foreach ($templates as $template) {
        $templatePath = $directory->add('export.'.$template->code.'.zip');

        if (FileHelper::isFile($templatePath->value))
            unlink($templatePath->value);
    }

    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Disposition: attachment; filename=blocks.zip');
    header('Content-Transfer-Encoding: binary');
    header("Content-Length: ".filesize($path->value));
    readfile($path->value);

    FileHelper::removeDirectory($directory->value);
}