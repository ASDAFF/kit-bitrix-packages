<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php
global $APPLICATION;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$APPLICATION->SetTitle(GetMessage('title'));

$request = Core::$app->request;
$action = $request->get('action');

if ($action == 'delete') {
    $build = $request->get('build');

    if ($build) {
        $build = Build::findOne($build);

        if ($build)
            $build->delete();
    }
}

$listId = 'constructor_builds';
$listSortVariable = $listId.'_by';
$listSortValue = $request->get($listSortVariable, 'id');
$listOrderVariable = $listId.'_order';
$listOrderValue = $request->get($listOrderVariable, 'asc');
$listSort = new CAdminSorting(
    $listId,
    $listSortValue,
    $listOrderValue,
    $listSortVariable,
    $listOrderVariable
);

$builds = Build::find()
    ->indexBy('id');

if (!empty($listSortValue))
    $builds->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$builds = $builds->all();
/** @var Build[] $builds */

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
        'id' => 'name',
        'content' => GetMessage('list.header.name'),
        'sort' => 'name',
        'default' => true
    )
));

foreach ($builds as $build) {
    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => GetMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.edit'],
                array(
                    'build' => $build->id
                )
            )
        )
    );

    if (extension_loaded('zip')) {
        $actions[] = array(
            'TEXT' => GetMessage('list.rows.actions.export'),
            'ACTION' => $list->ActionRedirect(
                StringHelper::replaceMacros(
                    $arUrlTemplates['builds.export'],
                    array(
                        'build' => $build->id
                    )
                )
            )
        );
    }

    $actions[] = array(
        'SEPARATOR' => 'Y'
    );

    $actions[] = array(
        'TEXT' => GetMessage('list.rows.actions.properties'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.properties'],
                array(
                    'build' => $build->id
                )
            )
        )
    );

    $actions[] = array(
        'TEXT' => GetMessage('list.rows.actions.templates'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.templates'],
                array(
                    'build' => $build->id
                )
            )
        )
    );

    $actions[] = array(
        'TEXT' => GetMessage('list.rows.actions.themes'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.themes'],
                array(
                    'build' => $build->id
                )
            )
        )
    );

    $actions[] = array(
        'SEPARATOR' => 'Y'
    );

    $actions[] = array(
        'ICON' => 'delete',
        'TEXT' => GetMessage('list.rows.actions.delete'),
        'ACTION' => "if(confirm('".GetMessage("list.rows.actions.delete.confirm")."'))".$list->ActionAjaxReload(
            $arUrlTemplates['builds'].'&action=delete&build='.$build->id
        )
    );

    $row = $list->AddRow(
        $build->id,
        array(
            'id' => $build->id,
            'code' => $build->code,
            'name' => $build->name
        )
    );

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => GetMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['builds.add'],
        'TITLE' => GetMessage('list.actions.add')
    )
);

if (extension_loaded('zip')) {
    $listAdminContextMenu[] = array(
        'TEXT' => GetMessage('list.actions.import'),
        'ICON' => 'btn',
        'LINK' => $arUrlTemplates['builds.import'],
        'TITLE' => GetMessage('list.actions.import')
    );
}

$list->AddAdminContextMenu($listAdminContextMenu, true, true);
$list->CheckListMode();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>
