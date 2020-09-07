<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\Core;
use intec\constructor\models\Build;
use intec\constructor\models\build\File;
use intec\constructor\models\build\Template;

if (!Loader::includeModule('intec.core') || !Loader::includeModule('intec.constructor'))
    return;

global $APPLICATION, $template, $data;

$request = Core::$app->request;
$build = Build::getCurrent();

if (empty($build))
    return;

$page = $build->getPage();
$page->getProperties()->setRange($settings);
$page->execute(['state' => 'loading']);

/** @var Template $template */
$template = $build->getTemplate();

if (empty($template))
    return;

$template->populateRelation('build', $build);

/** @var File[] $files */
$files = $build->getFiles();
$directory = $build->getDirectory();
$directoryRelative = $build->getDirectory(false, true, '/');

Core::$app->web->js->loadExtensions(['jquery']);
?>
<html>
    <head>
        <? $APPLICATION->ShowHead() ?>
        <?php foreach ($files as $file) { ?>
            <?php if ($file->getType() == File::TYPE_JAVASCRIPT) { ?>
                <script type="text/javascript" src="<?= $file->getPath(true, '/') ?>"></script>
            <?php } else if ($file->getType() == File::TYPE_CSS) { ?>
            <link rel="stylesheet" href="<?= $file->getPath(true, '/') ?>" />
            <?php } else if ($file->getType() == File::TYPE_SCSS) { ?>
                <style type="text/css"><?= Core::$app->web->scss->compileFile($file->getPath(), null, $template->getPropertiesValues()) ?></style>
            <?php } ?>
        <?php } ?>
        <style type="text/css"><?= $template->getCss() ?></style>
        <style type="text/css"><?= $template->getLess() ?></style>
        <script type="text/javascript"><?= $template->getJs() ?></script>
    </head>
    <body class="public">
        <? $APPLICATION->ShowPanel() ?>
        <? $data = $APPLICATION->IncludeComponent(
            'intec.constructor:template',
            '',
            array(
                'TEMPLATE_ID' => $template->id,
                'DISPLAY' => 'HEADER',
                'DATA' => [
                    'template' => $template
                ]
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        ) ?>