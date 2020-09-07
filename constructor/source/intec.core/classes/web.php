<?php
use intec\Core;
use intec\core\helpers\FileHelper;
use intec\core\bitrix\web\JavaScriptExtension as Extension;

$js = Core::$app->web->js;
$directory = Core::getAlias('@intec/core/resources');

if (FileHelper::isDirectory($directory)) {
    $js->addExtensions([
        new Extension([
            'id' => 'jquery',
            'script' => $directory.'/js/jquery-3.2.0.min.js'
        ]),
        new Extension([
            'id' => 'jquery_ui',
            'script' => $directory.'/plugins/jquery_ui/jquery-ui.min.js',
            'style' => $directory.'/plugins/jquery_ui/jquery-ui.all.min.css',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'jquery_extensions',
            'script' => $directory.'/js/jquery.extensions.js',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'jquery_form',
            'script' => $directory.'/js/jquery.form.js',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'ckeditor',
            'script' => $directory.'/plugins/ckeditor/ckeditor.js',
        ]),
        new Extension([
            'id' => 'intec_core',
            'script' => $directory.'/js/core.js',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'intec_core_controls',
            'script' => $directory.'/js/controls.js',
            'dependencies' => ['intec_core']
        ]),
        new Extension([
            'id' => 'knockout',
            'script' => $directory.'/js/knockout-3.4.2.js',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'knockout_extensions',
            'script' => $directory.'/js/knockout-3.4.2-extensions.js',
            'dependencies' => ['knockout', 'intec_core']
        ]),
        new Extension([
            'id' => 'knockout_models',
            'script' => $directory.'/js/knockout-3.4.2-models.js',
            'dependencies' => [
                'knockout',
                'knockout_extensions',
                'intec_core',
                'jquery_ui',
                'colorpicker',
                'nanoscroller',
                'ckeditor',
                'codemirror',
                'codemirror_css',
                'codemirror_javascript',
                'codemirror_xml',
                'codemirror_htmlmixed',
                'codemirror_clike',
                'codemirror_php',
                'codemirror_sass'
            ]
        ]),
        new Extension([
            'id' => 'formstyler',
            'script' => $directory.'/plugins/formstyler/formstyler.min.js',
            'style' => $directory.'/plugins/formstyler/formstyler.css',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'colorpicker',
            'script' => $directory.'/plugins/colorpicker/colorpicker.js',
            'style' => $directory.'/plugins/colorpicker/colorpicker.css',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'codemirror',
            'script' => $directory.'/plugins/codemirror/codemirror.js',
            'style' => $directory.'/plugins/codemirror/codemirror.css',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'codemirror_css',
            'script' => $directory.'/plugins/codemirror/mode/css.js',
            'dependencies' => ['codemirror']
        ]),
        new Extension([
            'id' => 'codemirror_javascript',
            'script' => $directory.'/plugins/codemirror/mode/javascript.js',
            'dependencies' => ['codemirror']
        ]),
        new Extension([
            'id' => 'codemirror_xml',
            'script' => $directory.'/plugins/codemirror/mode/xml.js',
            'dependencies' => ['codemirror']
        ]),
        new Extension([
            'id' => 'codemirror_htmlmixed',
            'script' => $directory.'/plugins/codemirror/mode/xml.js',
            'dependencies' => ['codemirror', 'codemirror_css', 'codemirror_javascript', 'codemirror_xml']
        ]),
        new Extension([
            'id' => 'codemirror_clike',
            'script' => $directory.'/plugins/codemirror/mode/clike.js',
            'dependencies' => ['codemirror']
        ]),
        new Extension([
            'id' => 'codemirror_php',
            'script' => $directory.'/plugins/codemirror/mode/php.js',
            'dependencies' => ['codemirror', 'codemirror_htmlmixed', 'codemirror_clike']
        ]),
        new Extension([
            'id' => 'codemirror_sass',
            'script' => $directory.'/plugins/codemirror/mode/sass.js',
            'dependencies' => ['codemirror']
        ]),
        new Extension([
            'id' => 'interact',
            'script' => $directory.'/js/interact.min.js'
        ]),
        new Extension([
            'id' => 'notify',
            'script' => $directory.'/js/notify.min.js',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'nanoscroller',
            'script' => $directory.'/plugins/nanoscroller/nanoscroller.min.js',
            'style' => $directory.'/plugins/nanoscroller/nanoscroller.css',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'scrollTo',
            'script' => $directory.'/js/jquery.scrollTo.min.js',
            'dependencies' => ['jquery']
        ]),
        new Extension([
            'id' => 'intec',
            'script' => $directory.'/js/common.js',
            'dependencies' => [
                'jquery',
                'jquery_ui',
                'jquery_form',
                'ckeditor',
                'colorpicker',
                'nanoscroller',
                'formstyler',
                'intec_core',
                'knockout',
                'knockout_extensions',
                'knockout_models',
                'interact',
                'notify',
                'scrollTo'
            ]
        ]),
        new Extension([
            'id' => 'intec_bitrix',
            'script' => $directory.'/js/bitrix.extensions.js',
            'dependencies' => ['intec', 'core', 'ajax', 'window']
        ]),
    ]);
}
