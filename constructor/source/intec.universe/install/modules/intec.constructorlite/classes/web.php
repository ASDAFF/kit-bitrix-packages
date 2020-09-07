<?php
use intec\Core;
use intec\core\helpers\FileHelper;
use intec\core\bitrix\web\JavaScriptExtension as Extension;

$js = Core::$app->web->js;
$directory = Core::getAlias('@intec/constructor/resources');

if (FileHelper::isDirectory($directory)) {
    $js->addExtensions([
        new Extension([
            'id' => 'intec_constructor_blocks',
            'script' => $directory.'/js/blocks.js',
            'dependencies' => ['intec']
        ])
    ]);
}

CUtil::InitJSCore( array('ajax' , 'popup' ));