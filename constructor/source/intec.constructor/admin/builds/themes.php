<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php
global $APPLICATION;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\Theme;

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

if ($action == 'delete') {
    $theme = $request->get('theme');

    if ($theme) {
        $theme = Theme::findOne([
            'buildId' => $build->id,
            'code' => $theme
        ]);

        if ($theme)
            $theme->delete();
    }
}

$listId = 'constructor_builds_themes';
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

$themes = Theme::find()
    ->where(['buildId' => $build->id])
    ->indexBy('code');

if (!empty($listSortValue))
    $themes->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$themes = $themes->all();
/** @var Theme[] $themes */

/** @var Template[] $templates */
$templates = $build->getTemplates(true);

$list = new CAdminList($listId, $listSort);
$list->AddHeaders(array(
    array(
        'id' => 'code',
        'content' => GetMessage('list.header.code'),
        'sort' => 'code',
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
    )
));

foreach ($themes as $theme) {
    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => GetMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.themes.edit'],
                array(
                    'build' => $build->id,
                    'theme' => $theme->code
                )
            )
        )
    );

    $templatesToSet = array();

    /*$actions[] = array(
        'TEXT' => GetMessage('list.rows.actions.apply.to'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.themes'],
                array(
                    'build' => $build->id
                )
            ).'&action=apply&theme='.$theme->code
        )
    );*/

    $actions[] = array(
        'SEPARATOR' => 'Y'
    );

    $actions[] = array(
        'ICON' => 'delete',
        'TEXT' => GetMessage('list.rows.actions.delete'),
        'ACTION' => "if(confirm('".GetMessage('list.rows.actions.delete.confirm')."'))".$list->ActionAjaxReload(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.themes'],
                array(
                    'build' => $build->id
                )
            ).'&action=delete&theme='.$theme->code
        )
    );

    $row = $list->AddRow(
        $theme->code,
        array(
            'code' => $theme->code,
            'name' => $theme->name,
            'sort' => $theme->sort
        )
    );

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => GetMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.themes.add'],
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
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>
