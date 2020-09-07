<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
global $USER;

use Bitrix\Main\Localization\Loc;
use intec\Core;

/**
 * @var $arUrlTemplates
 */

Loc::loadMessages(__FILE__);

if (!CModule::IncludeModule('intec.core'))
    return;

if (CModule::IncludeModule('intec.constructor'))
    return;

if (!CModule::IncludeModule('intec.constructorlite'))
    return;

Core::$app->web->css->addFile(Core::getAlias('@intec/constructor/resources/css/icons.css'));

$bIsMenu = true;
include('url.php');

$arMenu = array(
    'parent_menu' => 'global_intec',
    'text' => Loc::getMessage('intec.constructorlite.menu'),
    'icon' => "constructor-menu-icon",
    'page_icon' => 'constructor-menu-icon',
    'items_id' => 'intec_constructor',
    'items' => array(
        array(
            'text' => Loc::getMessage('intec.constructorlite.menu.blocks'),
            'icon' => 'constructor-menu-icon-blocks',
            'page_icon' => 'constructor-menu-icon-blocks',
            'items_id' => 'intec_constructor_blocks',
            'items' => array(
                array(
                    'text' => Loc::getMessage('intec.constructorlite.menu.blocks.categories'),
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
                    'text' => Loc::getMessage('intec.constructorlite.menu.blocks.templates'),
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
            'text' => Loc::getMessage('intec.constructorlite.menu.fonts'),
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
