<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php
global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\block\Category;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructorlite'))
    return;

Loc::loadMessages(__FILE__);

include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$action = $request->get('action');

/**
 * @var CMain $APPLICATION
 */

$APPLICATION->SetTitle(Loc::getMessage('title'));

if ($action == 'delete') {
    $category = $request->get('category');

    if ($category) {
        $category = Category::findOne($category);

        if ($category)
            $category->delete();
    }

    unset($category);
}

$listId = 'constructor_blocks_categories';
$listSortVariable = $listId.'_by';
$listSortValue = $request->get($listSortVariable, 'code');
$listOrderVariable = $listId.'_order';
$listOrderValue = $request->get($listOrderVariable, 'asc');
$listSort = new CAdminSorting(
    $listId,
    $listSortValue,
    $listOrderValue,
    $listSortVariable,
    $listOrderVariable
);

$categories = Category::find()
    ->indexBy('code');

if (!empty($listSortValue))
    $categories->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$categories = $categories->all();
/** @var Category[] $categories */

$list = new CAdminList($listId, $listSort);
$list->AddHeaders(array(
    array(
        'id' => 'code',
        'content' => Loc::getMessage('list.header.code'),
        'sort' => 'code',
        'default' => true
    ),
    array(
        'id' => 'sort',
        'content' => Loc::getMessage('list.header.sort'),
        'sort' => 'sort',
        'default' => true
    ),
    array(
        'id' => 'name',
        'content' => Loc::getMessage('list.header.name'),
        'sort' => 'name',
        'default' => true
    )
));

foreach ($categories as $category) {
    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => Loc::getMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['blocks.categories.edit'],
                array(
                    'category' => $category->code
                )
            )
        )
    );

    if (extension_loaded('zip')) {
        $actions[] = array(
            'TEXT' => Loc::getMessage('list.rows.actions.export'),
            'ACTION' => $list->ActionRedirect(
                StringHelper::replaceMacros(
                    $arUrlTemplates['blocks.categories.export'],
                    array(
                        'category' => $category->code
                    )
                )
            )
        );
    }

    $actions[] = array(
        'ICON' => 'delete',
        'TEXT' => Loc::getMessage('list.rows.actions.delete'),
        'ACTION' => "if(confirm('".Loc::getMessage("list.rows.actions.delete.confirm")."'))".$list->ActionAjaxReload(
            $arUrlTemplates['blocks.categories'].'&action=delete&category='.$category->code
        )
    );

    $row = $list->AddRow(
        $category->code,
        array(
            'code' => $category->code,
            'sort' => $category->sort,
            'name' => $category->name
        )
    );

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => Loc::getMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['blocks.categories.add'],
        'TITLE' => Loc::getMessage('list.actions.add')
    )
);

if (extension_loaded('zip')) {
    $listAdminContextMenu[] = array(
        'TEXT' => Loc::getMessage('list.actions.import'),
        'ICON' => 'btn',
        'LINK' => $arUrlTemplates['blocks.categories.import'],
        'TITLE' => Loc::getMessage('list.actions.import')
    );
    $listAdminContextMenu[] = array(
        'TEXT' => Loc::getMessage('list.actions.import.all'),
        'ICON' => 'btn',
        'LINK' => $arUrlTemplates['blocks.categories.import.all'],
        'TITLE' => Loc::getMessage('list.actions.import.all')
    );
    $listAdminContextMenu[] = array(
        'TEXT' => Loc::getMessage('list.actions.export.all'),
        'ICON' => 'btn',
        'LINK' => $arUrlTemplates['blocks.categories.export.all'],
        'TITLE' => Loc::getMessage('list.actions.export.all')
    );
}

$list->AddAdminContextMenu($listAdminContextMenu, true, true);
$list->CheckListMode();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>
