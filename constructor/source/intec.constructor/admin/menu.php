<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
global $USER;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;

/**
 * @var $arUrlTemplates
 */

Loc::loadMessages(__FILE__);

if (!CModule::IncludeModule('intec.core'))
    return;

if (!CModule::IncludeModule('intec.constructor'))
    return;

Core::$app->web->css->addFile(Core::getAlias('@intec/constructor/resources/css/icons.css'));

$bIsMenu = true;
include('url.php');

$builds = Build::find()->all();
/** @var Build[] $builds */
$arMenuItems = array();

foreach ($builds as $build) {
    $arMenuItems[] = array(
        'text' => $build->name,
        'url' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.edit'],
            array(
                'build' => $build->id
            )
        ),
        'more_url' => array(),
        'items_id' => 'intec_constructor_build_'.$build->id,
        'icon' => 'constructor-menu-icon-build',
        'page_icon' => 'constructor-menu-icon-build',
        'items' => array(
            array(
                'text' => Loc::getMessage('intec.constructor.menu.build.areas'),
                'url' => StringHelper::replaceMacros(
                    $arUrlTemplates['builds.areas'],
                    array(
                        'build' => $build->id
                    )
                ),
                'more_url' => array(
                    'constructor_builds_areas_edit.php?build='.$build->id
                ),
                'items_id' => 'intec_constructor_build_'.$build->id.'_areas',
                'icon' => 'constructor-menu-icon-build-areas',
                'page_icon' => 'constructor-menu-icon-build-areas',
                'items' => array()
            ),
            array(
                'text' => Loc::getMessage('intec.constructor.menu.build.properties'),
                'url' => StringHelper::replaceMacros(
                    $arUrlTemplates['builds.properties'],
                    array(
                        'build' => $build->id
                    )
                ),
                'more_url' => array(
                    'constructor_builds_properties_edit.php?build='.$build->id,
                    'constructor_builds_properties_import.php?build='.$build->id,
                    'constructor_builds_properties_export.php?build='.$build->id
                ),
                'items_id' => 'intec_constructor_build_'.$build->id.'_properties',
                'icon' => 'constructor-menu-icon-build-properties',
                'page_icon' => 'constructor-menu-icon-build-properties',
                'items' => array()
            ),
            array(
                'text' => Loc::getMessage('intec.constructor.menu.build.templates'),
                'url' => StringHelper::replaceMacros(
                    $arUrlTemplates['builds.templates'],
                    array(
                        'build' => $build->id
                    )
                ),
                'more_url' => array(
                    'constructor_builds_templates_edit.php?build='.$build->id,
                    'constructor_builds_templates_editor.php?build='.$build->id,
                    'constructor_builds_templates_copy.php?build='.$build->id
                ),
                'items_id' => 'intec_constructor_build_'.$build->id.'_templates',
                'icon' => 'constructor-menu-icon-build-templates',
                'page_icon' => 'constructor-menu-icon-build-templates',
                'items' => array()
            ),
            array(
                'text' => Loc::getMessage('intec.constructor.menu.build.themes'),
                'url' => StringHelper::replaceMacros(
                    $arUrlTemplates['builds.themes'],
                    array(
                        'build' => $build->id
                    )
                ),
                'more_url' => array(
                    'constructor_builds_themes_edit.php?build='.$build->id
                ),
                'items_id' => 'intec_constructor_build_'.$build->id.'_themes',
                'icon' => 'constructor-menu-icon-build-themes',
                'page_icon' => 'constructor-menu-icon-build-themes',
                'items' => array()
            )
        )
    );
}

$arMenu = array(
    'parent_menu' => 'global_intec',
    'text' => Loc::getMessage('intec.constructor.menu'),
    'icon' => "constructor-menu-icon",
    'page_icon' => 'constructor-menu-icon',
    'items_id' => 'intec_constructor',
    'items' => array(
        array(
            'text' => Loc::getMessage('intec.constructor.menu.builds'),
            'icon' => 'constructor-menu-icon-builds',
            'page_icon' => 'constructor-menu-icon-builds',
            'url' => $arUrlTemplates['builds'],
            'more_url' => array(
                'constructor_builds_import'
            ),
            'items_id' => 'intec_constructor_builds',
            'items' => $arMenuItems
        ),
        array(
            'text' => Loc::getMessage('intec.constructor.menu.blocks'),
            'icon' => 'constructor-menu-icon-blocks',
            'page_icon' => 'constructor-menu-icon-blocks',
            'items_id' => 'intec_constructor_blocks',
            'items' => array(
                array(
                    'text' => Loc::getMessage('intec.constructor.menu.blocks.categories'),
                    'icon' => 'constructor-menu-icon-blocks-categories',
                    'page_icon' => 'constructor-menu-icon-blocks-categories',
                    'url' => $arUrlTemplates['blocks.categories'],
                    'more_url' => array(
                        $arUrlTemplates['blocks.categories.add'],
                        $arUrlTemplates['blocks.categories.import'],
                        $arUrlTemplates['blocks.categories.export'],
                        $arUrlTemplates['blocks.categories.import.all'],
                        $arUrlTemplates['blocks.categories.export.all']
                    ),
                    'items_id' => 'intec_constructor_blocks'
                ),
                array(
                    'text' => Loc::getMessage('intec.constructor.menu.blocks.templates'),
                    'icon' => 'constructor-menu-icon-blocks-templates',
                    'page_icon' => 'constructor-menu-icon-blocks-templates',
                    'url' => $arUrlTemplates['blocks.templates'],
                    'more_url' => array(
                        $arUrlTemplates['blocks.templates.add'],
                        $arUrlTemplates['blocks.templates.import'],
                        $arUrlTemplates['blocks.templates.export'],
                        $arUrlTemplates['blocks.templates.import.all'],
                        $arUrlTemplates['blocks.templates.export.all']
                    ),
                    'items_id' => 'intec_constructor_blocks'
                )
            )
        ),
        array(
            'text' => Loc::getMessage('intec.constructor.menu.fonts'),
            'icon' => 'constructor-menu-icon-fonts',
            'page_icon' => 'constructor-menu-icon-fonts',
            'url' => $arUrlTemplates['fonts'],
            'more_url' => array(
                'constructor_fonts_edit'
            )
        )
    )
);

return $arMenu;
