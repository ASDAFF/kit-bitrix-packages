<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Area;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

Loc::loadMessages(__FILE__);

include(Core::getAlias('@intec/constructor/module/admin/url.php'));


$request = Core::$app->request;
$action = $request->get('action');
$build = $request->get('build');
$build = Build::findOne($build);

if (empty($build))
    LocalRedirect($arUrlTemplates['builds']);

$APPLICATION->SetTitle(Loc::getMessage('title', [
    '#name#' => $build->name
]));

if ($action == 'delete') {
    $area = $request->get('area');

    if ($area) {
        $area = Area::findOne($area);

        if ($area)
            $area->delete();
    }
}

$listId = 'constructor_builds_areas';
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

$areas = Area::find()
    ->indexBy('id');

if (!empty($listSortValue))
    $areas->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$areas = $areas->all();
/** @var Area[] $areas */

$list = new CAdminList($listId, $listSort);
$list->AddHeaders(array(
    array(
        'id' => 'id',
        'content' => Loc::getMessage('list.header.id'),
        'sort' => 'id',
        'default' => true
    ),
    array(
        'id' => 'code',
        'content' => Loc::getMessage('list.header.code'),
        'sort' => 'code',
        'default' => true
    ),
    array(
        'id' => 'name',
        'content' => Loc::getMessage('list.header.name'),
        'sort' => 'name',
        'default' => true
    ),
    array(
        'id' => 'sort',
        'content' => Loc::getMessage('list.header.sort'),
        'sort' => 'sort',
        'default' => true
    )
));

foreach ($areas as $area) {
    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => Loc::getMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.areas.edit'],
                array(
                    'build' => $build->id,
                    'area' => $area->id
                )
            )
        )
    );

    $actions[] = array(
        'SEPARATOR' => 'Y'
    );

    $actions[] = array(
        'ICON' => 'delete',
        'TEXT' => Loc::getMessage('list.rows.actions.delete'),
        'ACTION' => 'if (confirm(\''.Loc::getMessage('list.rows.actions.delete.confirm').'\')) '.$list->ActionAjaxReload(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.areas'],
                array(
                    'build' => $build->id
                )
            ).'&action=delete&area='.$area->id
        )
    );

    $row = $list->AddRow(
        $area->id,
        array(
            'id' => $area->id,
            'code' => $area->code,
            'name' => $area->name,
            'sort' => $area->sort
        )
    );

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => Loc::getMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.areas.add'],
            array(
                'build' => $build->id
            )
        ),
        'TITLE' => Loc::getMessage('list.actions.add')
    )
);

$list->AddAdminContextMenu($listAdminContextMenu, true, true);
$list->CheckListMode();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>