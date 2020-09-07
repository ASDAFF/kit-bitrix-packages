<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php
global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\Font;

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

$APPLICATION->SetTitle(Loc::getMessage('title'));

if ($action == 'delete') {
    $font = $request->get('font');

    if ($font) {
        $font = Font::findOne($font);

        if ($font)
            $font->delete();
    }

    unset($font);
}

$listId = 'constructor_fonts';
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

$fonts = Font::find()
    ->indexBy('code');

if (!empty($listSortValue))
    $fonts->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$fonts = $fonts->all();
/** @var Font[] $fonts */

$list = new CAdminList($listId, $listSort);
$list->AddHeaders(array(
    array(
        'id' => 'code',
        'content' => Loc::getMessage('list.header.code'),
        'sort' => 'code',
        'default' => true
    ),
    array(
        'id' => 'active',
        'content' => Loc::getMessage('list.header.active'),
        'sort' => 'active',
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
    ),
    array(
        'id' => 'type',
        'content' => Loc::getMessage('list.header.type'),
        'sort' => 'type',
        'default' => true
    )
));

$types = Font::getTypes();

foreach ($fonts as $font) {
    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => Loc::getMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['fonts.edit'],
                array(
                    'font' => $font->code
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
        'ACTION' => "if(confirm('".Loc::getMessage("list.rows.actions.delete.confirm")."'))".$list->ActionAjaxReload(
                $arUrlTemplates['fonts'].'&action=delete&font='.$font->code
            )
    );

    $row = $list->AddRow(
        $font->code,
        array(
            'code' => $font->code,
            'active' => $font->active ?
                Loc::getMessage('list.rows.yes') :
                Loc::getMessage('list.rows.no'),
            'name' => $font->name,
            'sort' => $font->sort,
            'type' => ArrayHelper::getValue($types, $font->type)
        )
    );

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => Loc::getMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['fonts.add'],
        'TITLE' => Loc::getMessage('list.actions.add')
    )
);

$list->AddAdminContextMenu($listAdminContextMenu, true, true);
$list->CheckListMode();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>