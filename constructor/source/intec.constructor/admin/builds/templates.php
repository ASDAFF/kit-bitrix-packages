<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php
global $APPLICATION;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$action = $request->get('action');
$build = $request->get('build');
$build = Build::findOne($build);

if (empty($build))
    LocalRedirect($arUrlTemplates['builds']);

$APPLICATION->SetTitle(GetMessage('title', array(
    '#name#' => $build->name
)));

if (ArrayHelper::isIn($action, array(
    'activate',
    'deactivate',
    'default',
    'delete'
))) {
    $template = $request->get('template');

    if ($template) {
        $template = Template::findOne([
            'id' => $template,
            'buildId' => $build->id
        ]);

        if ($template) {
            if ($action === 'activate') {
                $template->active = 1;
                $template->save();
            } else if ($action === 'deactivate') {
                $template->active = 0;
                $template->save();
            } else if ($action === 'default') {
                $template->default = 1;
                $template->save();
            } else {
                $template->delete();
            }
        }
    }
}

$listId = 'constructor_builds_templates';
$listSortVariable = $listId.'_by';
$listSortValue = $request->get($listSortVariable, 'sort');
$listOrderVariable = $listId.'_order';
$listOrderValue = $request->get($listOrderVariable, 'asc');
$listSort = new CAdminSorting(
    $listId,
    $listSortValue,
    $listOrderValue,
    $listSortVariable,
    $listOrderVariable
);

$templates = Template::find()
    ->with(['theme'])
    ->where(['buildId' => $build->id])
    ->indexBy('id');

if (!empty($listSortValue))
    $templates->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$templates = $templates->all();
/** @var Template[] $templates */

$list = new CAdminList($listId, $listSort);
$list->AddHeaders(array(
    array(
        'id' => 'id',
        'content' => GetMessage('list.header.id'),
        'sort' => 'id',
        'default' => true
    ),
    array(
        'id' => 'code',
        'content' => GetMessage('list.header.code'),
        'sort' => 'code',
        'default' => true
    ),
    array(
        'id' => 'active',
        'content' => GetMessage('list.header.active'),
        'sort' => 'active',
        'default' => true
    ),
    array(
        'id' => 'default',
        'content' => GetMessage('list.header.default'),
        'sort' => 'default',
        'default' => true
    ),
    array(
        'id' => 'name',
        'content' => GetMessage('list.header.name'),
        'sort' => 'name',
        'default' => true
    ),
    array(
        'id' => 'sort',
        'content' => GetMessage('list.header.sort'),
        'sort' => 'sort',
        'default' => true
    ),
    array(
        'id' => 'theme',
        'content' => GetMessage('list.header.theme'),
        'sort' => 'themeCode',
        'default' => true
    )
));

foreach ($templates as $template) {
    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => GetMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.templates.edit'],
                array(
                    'build' => $build->id,
                    'template' => $template->id
                )
            )
        )
    );

    $actions[] = array(
        'TEXT' => GetMessage('list.rows.actions.editor'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.templates.editor'],
                array(
                    'build' => $build->id,
                    'template' => $template->id
                )
            )
        )
    );

    $actions[] = array(
        'ICON' => 'copy',
        'TEXT' => GetMessage('list.rows.actions.copy'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.templates.copy'],
                array(
                    'build' => $build->id,
                    'template' => $template->id
                )
            )
        )
    );

    $actions[] = array(
        'SEPARATOR' => 'Y'
    );

    if ($template->active == 1) {
        $actions[] = array(
            'TEXT' => GetMessage('list.rows.actions.deactivate'),
            'ACTION' => $list->ActionAjaxReload(
                StringHelper::replaceMacros(
                    $arUrlTemplates['builds.templates'],
                    array(
                        'build' => $build->id
                    )
                ) . '&action=deactivate&template=' . $template->id
            )
        );
    } else {
        $actions[] = array(
            'TEXT' => GetMessage('list.rows.actions.activate'),
            'ACTION' => $list->ActionAjaxReload(
                StringHelper::replaceMacros(
                    $arUrlTemplates['builds.templates'],
                    array(
                        'build' => $build->id
                    )
                ) . '&action=activate&template=' . $template->id
            )
        );
    }

    if ($template->default != 1)
        $actions[] = array(
            'TEXT' => GetMessage('list.rows.actions.default'),
            'ACTION' => $list->ActionAjaxReload(
                StringHelper::replaceMacros(
                    $arUrlTemplates['builds.templates'],
                    array(
                        'build' => $build->id
                    )
                ).'&action=default&template='.$template->id
            )
        );

    $actions[] = array(
        'SEPARATOR' => 'Y'
    );

    $actions[] = array(
        'ICON' => 'delete',
        'TEXT' => GetMessage('list.rows.actions.delete'),
        'ACTION' => "if(confirm('".GetMessage("list.rows.actions.delete.confirm")."'))".$list->ActionAjaxReload(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.templates'],
                array(
                    'build' => $build->id
                )
            ).'&action=delete&template='.$template->id
        )
    );

    $theme = $template->getTheme(true);
    $theme = !empty($theme) ? $theme->name : GetMessage('list.header.theme.unset');

    $row = $list->AddRow(
        $template->id,
        array(
            'id' => $template->id,
            'code' => $template->code,
            'active' => $template->active ? GetMessage('list.rows.yes') : GetMessage('list.rows.no'),
            'default' => $template->default ? GetMessage('list.rows.yes') : GetMessage('list.rows.no'),
            'name' => $template->name,
            'sort' => $template->sort,
            'theme' => $theme
        )
    );

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => GetMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.templates.add'],
            array(
                'build' => $build->id
            )
        ),
        'TITLE' => GetMessage('list.actions.add')
    )
);

$list->AddAdminContextMenu($listAdminContextMenu, true, true);
$list->CheckListMode();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php") ?>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php") ?>