<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php
global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\block\Category;
use intec\constructor\models\block\Template;

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

/**
 * @var CMain $APPLICATION
 */

$APPLICATION->SetTitle(Loc::getMessage('title'));

if ($action == 'delete') {
    $template = $request->get('template');

    if ($template) {
        $template = Template::findOne($template);

        if ($template)
            $template->delete();
    }

    unset($template);
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

$filterId = $listId.'_filter';
$filterValues = $request->get('filter');

if (!Type::isArray($filterValues))
    $filterValues = [];

if ($request->get('del_filter') != null)
    $filterValues = [];

$filterValues = ArrayHelper::merge([
    'category' => null
], $filterValues);
$filter = new CAdminFilter($filterId, array(
    Loc::getMessage('list.header.id')
));

$categories = Category::find()->all();
$categoriesList = [];

/** @var Category $category */
foreach ($categories as $category)
    $categoriesList[$category->code] = $category->name;

$images = [];
$templates = Template::find()
    ->with(['category'])
    ->indexBy('code');

if (!empty($filterValues['category']))
    $templates->andWhere(['=', 'categoryCode', $filterValues['category']]);

if (!empty($listSortValue))
    $templates->orderBy([$listSortValue => $listOrderValue == 'asc' ? SORT_ASC : SORT_DESC]);

$templates = $templates->all();
/** @var Template[] $templates */

foreach ($templates as $template) {
    if (!empty($template->image))
        $images[] = $template->image;
}

if (!empty($images)) {
    $result = CFile::GetList(array(), array(
        '@ID' => implode(',', $images)
    ));

    $images = [];

    while ($image = $result->GetNext()) {
        $image['SRC'] = CFile::GetFileSRC($image);
        $images[$image['ID']] = $image;
    }
}

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
        'id' => 'category',
        'content' => Loc::getMessage('list.header.category'),
        'sort' => 'categoryCode',
        'default' => true
    ),
    array(
        'id' => 'name',
        'content' => Loc::getMessage('list.header.name'),
        'sort' => 'name',
        'default' => true
    ),
    array(
        'id' => 'image',
        'content' => Loc::getMessage('list.header.image'),
        'default' => true
    )
));

foreach ($templates as $template) {
    $category = $template->getCategory(true);
    $image = null;

    if (!empty($template->image))
        $image = ArrayHelper::getValue($images, $template->image);

    $actions = array();
    $actions[] = array(
        'ICON' => 'edit',
        'TEXT' => Loc::getMessage('list.rows.actions.edit'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['blocks.templates.edit'],
                array(
                    'template' => $template->code
                )
            )
        )
    );
    $actions[] = array(
        'TEXT' => Loc::getMessage('list.rows.actions.editor'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['blocks.templates.editor'],
                array(
                    'template' => $template->code
                )
            )
        )
    );
    $actions[] = array(
        'ICON' => 'copy',
        'TEXT' => Loc::getMessage('list.rows.actions.copy'),
        'ACTION' => $list->ActionRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['blocks.templates.copy'],
                array(
                    'template' => $template->code
                )
            )
        )
    );

    $actions[] = array(
        'SEPARATOR' => 'Y'
    );

    if (extension_loaded('zip')) {
        $actions[] = array(
            'TEXT' => Loc::getMessage('list.rows.actions.export'),
            'ACTION' => $list->ActionRedirect(
                StringHelper::replaceMacros(
                    $arUrlTemplates['blocks.templates.export'],
                    array(
                        'template' => $template->code
                    )
                )
            )
        );
    }

    $actions[] = array(
        'ICON' => 'delete',
        'TEXT' => Loc::getMessage('list.rows.actions.delete'),
        'ACTION' => "if(confirm('".Loc::getMessage("list.rows.actions.delete.confirm")."'))".$list->ActionAjaxReload(
            $arUrlTemplates['blocks.templates'].'&action=delete&template='.$template->code
        )
    );

    $row = $list->AddRow(
        $template->code,
        array(
            'code' => $template->code,
            'category' => !empty($category) ? $category->name : Loc::getMessage('list.header.category.unset'),
            'sort' => $template->sort,
            'name' => $template->name,
            'image' => null
        )
    );

    if (!empty($image))
        $row->AddViewField('image',  Html::img($image['SRC'], array(
            'style' => array(
                'max-width' => '140px',
                'max-height' => '80px'
            )
        )));

    $row->AddActions($actions);
}

$listAdminContextMenu = array(
    array(
        'TEXT' => Loc::getMessage('list.actions.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['blocks.templates.add'],
        'TITLE' => Loc::getMessage('list.actions.add')
    )
);

if (extension_loaded('zip')) {
    $listAdminContextMenu[] = array(
        'TEXT' => Loc::getMessage('list.actions.import'),
        'ICON' => 'btn',
        'LINK' => $arUrlTemplates['blocks.templates.import'],
        'TITLE' => Loc::getMessage('list.actions.import')
    );
    $listAdminContextMenu[] = array(
        'TEXT' => Loc::getMessage('list.actions.import.all'),
        'ICON' => 'btn',
        'LINK' => $arUrlTemplates['blocks.templates.import.all'],
        'TITLE' => Loc::getMessage('list.actions.import.all')
    );
    $listAdminContextMenu[] = array(
        'TEXT' => Loc::getMessage('list.actions.export.all'),
        'ICON' => 'btn',
        'LINK' => $arUrlTemplates['blocks.templates.export.all'],
        'TITLE' => Loc::getMessage('list.actions.export.all')
    );
}

$list->AddAdminContextMenu($listAdminContextMenu, true, true);
$list->CheckListMode();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<form method="GET">
    <?php $filter->Begin() ?>
        <tr>
            <td><?= Loc::getMessage('list.header.category')?>:</td>
            <td>
                <?= Html::dropDownList('filter[category]', $filterValues['category'], ArrayHelper::merge([
                    '' => Loc::getMessage('list.header.category.unselected')
                ], $categoriesList)) ?>
            </td>
        </tr>
    <?php
        $filter->Buttons(array(
            'table_id' => $listId,
            'url' => $APPLICATION->GetCurPage(),
            'form' => 'form'
        ));
        $filter->End();
    ?>
</form>
<?php $list->DisplayList() ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>
