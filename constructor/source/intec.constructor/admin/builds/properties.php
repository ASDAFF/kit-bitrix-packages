<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php
global $APPLICATION;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Property;

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
    $property = $request->get('property');

    if ($property) {
        $property = Property::findOne([
            'buildId' => $build->id,
            'code' => $property
        ]);

        if ($property)
            $property->delete();
    }
}

$listId = 'constructor_builds_properties';
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

$properties = Property::find()
    ->where(['buildId' => $build->id])
    ->indexBy('code');

if (!empty($listSortValue))
    $properties->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$properties = $properties->all();
/** @var Property[] $properties */

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
    ),
    array(
        'id' => 'type',
        'content' => GetMessage('list.header.type'),
        'sort' => 'type',
        'default' => true
    ),
    array(
        'id' => 'default',
        'content' => GetMessage('list.header.default'),
        'sort' => 'default',
        'default' => true
    )
));

foreach ($properties as $property) {
    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => GetMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.properties.edit'],
                array(
                    'build' => $build->id,
                    'property' => $property->code
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
        'ACTION' => "if(confirm('".GetMessage("list.rows.actions.delete.confirm")."')) ".$list->ActionAjaxReload(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.properties'],
                array(
                    'build' => $build->id
                )
            ).'&action=delete&property='.$property->code
        )
    );

    $row = $list->AddRow(
        $property->code,
        array(
            'code' => $property->code,
            'name' => $property->name,
            'sort' => $property->sort,
            'type' => $property->getType(),
            'default' => $property->default
        )
    );

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => GetMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.properties.add'],
            array(
                'build' => $build->id
            )
        ),
        'TITLE' => GetMessage('list.actions.add')
    ),
    array(
        'TEXT' => GetMessage('list.actions.export'),
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.properties.export'],
            array(
                'build' => $build->id
            )
        ),
        'TITLE' => GetMessage('list.actions.export')
    ),
    array(
        'TEXT' => GetMessage('list.actions.import'),
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.properties.import'],
            array(
                'build' => $build->id
            )
        ),
        'TITLE' => GetMessage('list.actions.import')
    )
);

$list->AddAdminContextMenu($listAdminContextMenu, true, true);
$list->CheckListMode();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>
