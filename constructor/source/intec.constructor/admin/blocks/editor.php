<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

define('ADMIN_SECTION', true);
IncludeModuleLangFile(__FILE__);

global $APPLICATION;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Json;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\build\template\Block;
use intec\constructor\models\block\Template;
use intec\constructor\models\Font;
use intec\constructor\structure\Block as Model;
use intec\constructor\structure\block\Elements;
use intec\constructor\structure\block\Element;
use intec\constructor\structure\block\Resolution;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!Loader::includeModule('intec.constructor'))
    return;

include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$record = $request->get('template');
/** @var Model $model */
$model = null;

if (!empty($record)) {
    $record = Template::findOne($record);

    if (empty($record))
        LocalRedirect($arUrlTemplates['blocks.templates']);

    $model = $record->getModel();
} else {
    $record = $request->get('block');
    $record = Block::findOne($record);

    if (empty($record))
        LocalRedirect($arUrlTemplates['blocks.templates']);

    $model = $record->getModel();
}

/** Обработка запросов AJAX */
if ($request->getIsPost() && $request->getIsAjax()) {
    $response = null;
    include('editor/handler.php');

    if (Type::isArray($response))
        $response = ArrayHelper::convertEncoding(
            $response,
            Encoding::UTF8,
            Encoding::getDefault()
        );

    if (Type::isString($response))
        $response = StringHelper::convert(
            $response,
            Encoding::UTF8,
            Encoding::getDefault()
        );

    echo StringHelper::convert(Json::encode($response), null, Encoding::UTF8);
    return;
}

$APPLICATION->SetTitle(Loc::getMessage('title.editor'));

Core::$app->web->js->loadExtensions(['intec_constructor_blocks']);

Core::$app->web->css->addFile('@intec/constructor/resources/icons/constructor/style.css');
Core::$app->web->css->addFile('@intec/constructor/resources/icons/fontawesome/style.css');
Core::$app->web->css->addFile('@intec/constructor/resources/icons/typicons/style.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/interface.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/base.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/editor.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/editor.block.css');

$resolutions = $model->getResolutions();

if ($resolutions->isEmpty()) {
    $resolutions->add(new Resolution(320, 240));
    $resolutions->add(new Resolution(768, 480));
    $resolutions->add(new Resolution(1200, 720));
}

$structure = $model->getStructure();
$data = [
    'elements' => [
        'list' => $structure['elements'],
        'types' => []
    ],
    'resolutions' => $structure['resolutions'],
    'paths' => [
        'resources' => $model
            ->getResources()
            ->getDirectory(true)
            ->getValue('/')
    ]
];
$paths = [];

$elements = Elements::all();

/** @var Element $element */
foreach ($elements as $element) {
    $data['elements']['types'][] = [
        'name' => $element->getName(),
        'code' => $element->getCode(),
        'icon' => $element->hasIcon() ? $element->getIconPath(true, '/') : null,
        'view' => $element->render(false),
        'settings' => [
            'view' => $element->getSettings(),
            'model' => $element->getModel()
        ]
    ];

    $element->includeHeaders(['editor']);
}

$fonts = Font::findAvailable();

foreach ($fonts as $font)
    $font->register();

?><!DOCTYPE html>
<html>
    <head>
        <title><?= Loc::getMessage('title') ?></title>
        <? $APPLICATION->ShowHead() ?>
    </head>
    <body class="editor">
        <? require_once('editor/html.php'); ?>
        <? require_once('editor/script.php'); ?>
    </body>
</html>