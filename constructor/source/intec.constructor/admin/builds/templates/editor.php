<?php include('editor/constants.php') ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php') ?>
<?php

define('ADMIN_SECTION', true);
IncludeModuleLangFile(__FILE__);

global $APPLICATION;

use intec\Core;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Json;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\block\Category as BlockCategory;
use intec\constructor\models\block\Template as BlockTemplate;
use intec\constructor\models\Font;
use intec\constructor\models\Build;
use intec\constructor\models\build\File;
use intec\constructor\models\build\Property;
use intec\constructor\models\build\Template as BuildTemplate;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Containers;
use intec\constructor\models\build\Theme;
use intec\constructor\models\build\theme\Value as ThemeValue;
use intec\constructor\structure\Widget;
use intec\constructor\structure\widget\Template as WidgetTemplate;
use intec\constructor\structure\Widgets;use intec\core\io\Path;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

Core::$app->web->js->loadExtensions(['intec']);
include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$build = $request->get('build');
$build = Build::find()
    ->where(['id' => $build])
    ->one();
/** @var Build $build */

if (empty($build))
    LocalRedirect($arUrlTemplates['builds']);

$directory = $build->getDirectory(false, true);
$template = $request->get('template');
$template = BuildTemplate::findOne($template);
/** @var BuildTemplate $template */

if (empty($template))
    LocalRedirect(
        StringHelper::replaceMacros(
            $arUrlTemplates['builds.templates'],
            array(
                'build' => $build->id
            )
        )
    );

/** @var File[] $files */
$files = $build->getFiles();

/** Обработка запросов AJAX */
if ($request->getIsPost() && $request->getIsAjax()) {
    ini_set('max_execution_time', 0);

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

Core::$app->web->js->loadExtensions(['intec_constructor']);

Core::$app->web->css->addFile('@intec/constructor/resources/icons/constructor/style.css');
Core::$app->web->css->addFile('@intec/constructor/resources/icons/fontawesome/style.css');
Core::$app->web->css->addFile('@intec/constructor/resources/icons/typicons/style.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/interface.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/editor.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/editor.template.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/component.properties.css');
Core::$app->web->css->addFile('@intec/constructor/resources/css/component.list.css');

$areas = $build->getAreas(true);
$areasId = [];

foreach ($areas as $area)
    $areasId[] = $area->id;

$conditions = [
    'or',
    ['templateId' => $template->id]
];

if (!empty($areasId))
    $conditions[] = ['areaId' => $areasId];

$containers = Container::find()
    ->with([
        'link',
        'area',
        'component',
        'widget',
        'block',
        'variator',
        'variator.variants'
    ])
    ->where($conditions)
    ->indexBy('id')
    ->all();

/** @var Containers $containers */

$widgets = Widgets::all();
/** @var Widgets $widgets */

/** @var Container $container */
$container = $containers->getTree($build, $template);

if (empty($container)) {
    $container = new Container();
    $container->display = 1;
    $container->type = Container::TYPE_NORMAL;
    $container->setStyleHeight(100, '%');
}

$properties = $template->getProperties(true);
/** @var ActiveRecords $properties */

$themes = $build->getThemes(true);
/** @var ActiveRecords $themes */


$blocksCategories = BlockCategory::find()
    ->orderBy('sort')
    ->indexBy('code')
    ->all();
/** @var BlockCategory[] $blocksCategories */
$blocksTemplates = BlockTemplate::find()
    ->orderBy('sort')
    ->all();
/** @var BlockTemplate[] $blocksTemplates */

$images = [];

foreach ($blocksTemplates as $block)
    if (!empty($block->image))
        $images[] = $block->image;

$result = CFile::GetList([], ['@ID' => implode(',', $images)]);
$images = [];

while ($image = $result->GetNext()) {
    $image['SRC'] = CFile::GetFileSRC($image);
    $images[$image['ID']] = $image;
}

unset($image, $result);

$blocksData = [];
$blocksData['categories'] = [];
$blocksData['templates'] = [];

$blocksData['categories'][] = (new BlockCategory([
    'code' => null,
    'sort' => 0,
    'name' => GetMessage('container.blocks.group.all')
]))->toArray();

foreach ($blocksCategories as $blocksCategory)
    $blocksData['categories'][] = $blocksCategory->toArray();

foreach ($blocksTemplates as $blocksTemplate) {
    /** @var BlockCategory $blocksCategory */
    $blocksCategory = null;
    $image = null;

    if (!empty($blocksTemplate->categoryCode))
        $blocksCategory = $blocksCategories
            ->get($blocksTemplate->categoryCode);

    if (!empty($blocksTemplate->image))
        $image = ArrayHelper::getValue($images, $blocksTemplate->image);

    $blocksData['templates'][] = ArrayHelper::merge(
        $blocksTemplate->toArray(['code', 'sort', 'name']), [
            'category' => !empty($blocksCategory) ? $blocksCategory->code : null,
            'image' => !empty($image) ? $image['SRC'] : null
        ]
    );
}

unset($image, $images);

$data = [
    'container' => $container->getStructure(),
    'settings' => $template->settings,
    'widgets' => [],
    'blocks' => $blocksData
];

/** @var Widget $widget */
foreach ($widgets as $widget)
{
    $widgetData = [];
    $widgetData['id'] = $widget->getId();
    $widgetData['author'] = $widget->getNamespace();
    $widgetData['name'] = $widget->getName();
    $widgetData['model'] = $widget->getModel();
    $widgetData['icon'] = $widget->hasIcon() ?
        $widget->getIconPath(true, '/') :
        null;
    $widgetData['templates'] = [];
    $widgetTemplates = $widget->getTemplates($build);

    foreach ($widgetTemplates as $widgetTemplate) {
        /** @var WidgetTemplate $widgetTemplate */
        $widgetTemplateData = [];
        $widgetTemplateData['code'] = $widgetTemplate->code;
        $widgetTemplateData['view'] = $widgetTemplate->render([], $build, $template, false);
        $widgetTemplateData['settings'] = $widgetTemplate->getSettings();
        $widgetTemplateData['built'] = $widgetTemplate->build === null;
        $widgetTemplateData['model'] = $widgetTemplate->getModel();

        $widgetData['templates'][] = $widgetTemplateData;
    }

    $data['widgets'][] = $widgetData;
    unset($widgetData);
}

$data = JavaScript::toObject($data);

$colors = [];

$scheme = [];
$scheme['properties'] = [];
$scheme['themes'] = [];
$scheme['theme'] = $template->themeCode;

/** @var Property $property */
foreach ($properties as $property) {
    if ($property->type != Property::TYPE_COLOR)
        continue;

    $colors[] = $property->code;
    $property = $property->toArray([
        'code', 'name', 'sort', 'default'
    ]);

    $scheme['properties'][] = $property;
}

$theme = [
    'code' => null,
    'name' => GetMessage('scheme.settings.theme.empty'),
    'values' => []
];

$values = $template->getValues(true);

foreach ($values as $value) {
    if (ArrayHelper::isIn($value->propertyCode, $colors)) {
        $theme['values'][] = [
            'code' => $value->propertyCode,
            'value' => $value->value
        ];
    }
}

$scheme['themes'][] = $theme;

/** @var Theme $theme */
foreach ($themes as $theme) {
    /** @var ThemeValue[] $values */
    $values = $theme->getValues(true);
    $theme = $theme->toArray(['code', 'name']);
    $theme['values'] = [];

    foreach ($values as $value) {
        if (ArrayHelper::isIn($value->propertyCode, $colors)) {
            $theme['values'][] = [
                'code' => $value->propertyCode,
                'value' => $value->value
            ];
        }
    }

    $scheme['themes'][] = $theme;
}

$scheme = JavaScript::toObject($scheme);
$properties = $template->getPropertiesValues();
$sites = [];

$result = CSite::GetList($by = 'sort', $order = 'asc', [
    'ACTIVE' => 'Y'
]);

while ($site = $result->GetNext()) {
    $sites[] = [
        'id' => ArrayHelper::getValue($site, 'LID'),
        'name' => ArrayHelper::getValue($site, 'NAME'),
        'directory' => ArrayHelper::getValue($site, 'DIR')
    ];
}

unset($result);
$sites = JavaScript::toObject($sites, true);

/** TODO: Fix that */

$conditionOperators = [];
$conditionTypes = [];

foreach (Container::getConditionOperators() as $key => $value) {
    $conditionOperators[] = [
        'key' => $key,
        'value' => $value
    ];
}

foreach (Container::getConditionTypes() as $key => $value) {
    $conditionTypes[] = [
        'key' => $key,
        'value' => $value
    ];
}

$directions = ['top', 'right', 'bottom', 'left'];
$fontStyles = ['inherit', 'regular', 'italic', 'bold'];
$fontSubsets = ['cirillic', 'latin'];

$lang = array(
    'type' => Container::getTypes(),
    'float' => Container::getStyleFloats(),
    'border' => Container::getStyleBorderStyles(),
    'overflow' => Container::getStyleOverflows(),
    'repeat' => Container::getStyleBackgroundRepeats()
);
foreach ($lang as $code => &$prop) {
    if (is_array($prop)) {
        $newProp = array();

        if ($code != 'type') {
            $newProp[] = array(
                'key' => '',
                'value' => GetMessage('select.empty')
            );
        }

        foreach ($prop as $key => $val) {
            $newProp[] = array(
                'key' => $key,
                'value' => $val
            );
        }
        $prop = JavaScript::toObject($newProp);
    }
}

$fonts = Font::findAvailable();

foreach ($fonts as $font)
    $font->register();

?><!DOCTYPE html>
<html>
    <head>
        <title><?= GetMessage('title') ?></title>
        <? $APPLICATION->ShowHead() ?>
        <?php foreach ($files as $file) { ?>
            <?php if ($file->getType() == File::TYPE_JAVASCRIPT) { ?>
                <script type="text/javascript" src="<?= $file->getPath(true, '/') ?>"></script>
            <?php } else if ($file->getType() == File::TYPE_CSS) { ?>
                <link rel="stylesheet" href="<?= $file->getPath(true, '/') ?>" />
            <?php } else if ($file->getType() == File::TYPE_SCSS) { ?>
                <style type="text/css"><?= Core::$app->web->scss->compileFile($file->getPath(), null, $properties) ?></style>
            <?php } ?>
        <?php } ?>
        <style type="text/css"><?= $template->getCss() ?></style>
        <style type="text/css"><?= $template->getLess() ?></style>
    </head>
    <body class="editor">
        <? include('editor/html.php') ?>
        <? include('editor/script.php') ?>
    </body>
</html>