<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Data\Cache;
use intec\Core;
use intec\core\base\Collection;
use intec\core\bitrix\web\JavaScriptExtension;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\File;

/**
 * @var Build $build
 * @var Collection $properties
 * @var string $directory
 */

$files = $build->getFiles();
$web = Core::$app->web;
$web->js->addExtension(new JavaScriptExtension([
    'id' => 'jquery',
    'script' => $directory.'/plugins/jquery/jquery-2.2.4.js'
]), true);

if (FileHelper::isFile($directory.'/parts/custom/assets.start.php'))
    include($directory.'/parts/custom/assets.start.php');

$web->js->loadExtensions(['jquery', 'intec_core', 'intec_core_controls', 'ajax', 'popup']);
$web->css->addFile($directory.'/plugins/bootstrap/css/bootstrap.css');
$web->css->addFile($directory.'/plugins/bootstrap/css/bootstrap-theme.css');
$web->css->addFile($directory.'/plugins/jquery.colorpicker/jquery.colorpicker.css');
$web->css->addFile($directory.'/css/public.css'); // Deprecated
$web->js->addFile($directory.'/plugins/bootstrap/js/bootstrap.js');
$web->js->addFile($directory.'/plugins/jquery.colorpicker/jquery.colorpicker.js');
$web->js->addFile($directory.'/plugins/jquery.mousewheel/jquery.mousewheel.js');
$web->js->addFile($directory.'/plugins/jquery.zoom/jquery.zoom.js');
$web->js->addFile($directory.'/plugins/jquery.scrollTo/jquery.scrollTo.js');
$web->js->addFile($directory.'/plugins/sly/sly.js');

if (Core::$app->browser->isDesktop)
    $web->js->addFile($directory.'/plugins/jquery.stellar/jquery.stellar.js');

$files = ArrayHelper::merge($files, [
    new File($build, File::TYPE_JAVASCRIPT, 'js/universe.js'),
    new File($build, File::TYPE_JAVASCRIPT, 'js/basket.js'),
    new File($build, File::TYPE_JAVASCRIPT, 'js/compare.js'),
    new File($build, File::TYPE_JAVASCRIPT, 'js/catalog.js'),
    new File($build, File::TYPE_JAVASCRIPT, 'js/common.js'),
    new File($build, File::TYPE_JAVASCRIPT, 'js/forms.js'),
    new File($build, File::TYPE_JAVASCRIPT, 'js/components.js')
]);

if (FileHelper::isFile($directory.'/css/custom.css'))
    $files[] = new File($build, File::TYPE_CSS, 'css/custom.css');

if (FileHelper::isFile($directory.'/css/custom.scss'))
    $files[] = new File($build, File::TYPE_SCSS, 'css/custom.scss');

if (FileHelper::isFile($directory.'/js/custom.js'))
    $files[] = new File($build, File::TYPE_JAVASCRIPT, 'js/custom.js');

$hash = md5(serialize($properties->asArray()));

/**
 * @var File[] $files
 */
foreach ($files as $file) {
    if ($file->getType() == File::TYPE_JAVASCRIPT) {
        Core::$app->web->js->addFile($file->getPath(true, '/'));
    } else if ($file->getType() == File::TYPE_CSS) {
        Core::$app->web->css->addFile($file->getPath(true, '/'));
    } else if ($file->getType() == File::TYPE_SCSS) {
        $fileCache = Cache::createInstance();
        $fileData = null;

        if ($fileCache->initCache(360000, $file->getPath(true, '/').$hash, SITE_ID.'/templates/'.SITE_TEMPLATE_ID.'/scss')) {
            $fileData = $fileCache->getVars();
        } else if ($fileCache->startDataCache()) {
            $fileData = [
                'hash' => $hash,
                'content' => Core::$app->web->scss->compileFile(
                    $file->getPath(),
                    null,
                    $properties->asArray(),
                    true
                )
            ];

            $fileCache->endDataCache($fileData);
        }

        if (!empty($fileData))
            Core::$app->web->css->addString($fileData['content']);

        unset($fileData);
        unset($fileCache);
    }
}

if (FileHelper::isFile($directory.'/parts/custom/assets.end.php'))
    include($directory.'/parts/custom/assets.end.php');

unset($hash);
unset($web);
unset($files);