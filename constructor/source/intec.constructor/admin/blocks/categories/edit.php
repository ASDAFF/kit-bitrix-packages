<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\models\block\Category;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

Loc::loadMessages(__FILE__);

Core::$app->web->js->loadExtensions(['intec']);
include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$error = null;
$category = $request->get('category');

/** @var Category $category */
$category = Category::findOne($category);

if (empty($category)) {
    $category = new Category();
    $category->loadDefaultValues();
}

if ($category->getIsNewRecord()) {
    $APPLICATION->SetTitle(Loc::getMessage('title.add'));
} else {
    $APPLICATION->SetTitle(Loc::getMessage('title.edit', array(
        '#name#' => $category->name
    )));
}

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $category->load($post);

    if ($category->save()) {
        if ($return)
            LocalRedirect($arUrlTemplates['blocks.categories']);

        LocalRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['blocks.categories.edit'],
                array(
                    'category' => $category->code
                )
            )
        );
    } else {
        $error = ArrayHelper::getFirstValue($category->getFirstErrors());
    }
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => Loc::getMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => $arUrlTemplates['blocks.categories']
    ),
    array(
        'TEXT' => Loc::getMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['blocks.categories.add'],
    )
));

$tabs = new CAdminTabControl(
    'tabs',
    array(
        array(
            'DIV' => 'common',
            'TAB' => Loc::getMessage('tabs.common'),
            'TITLE' => Loc::getMessage('tabs.common')
        )
    )
);
?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php') ?>
<?php $menuNavigation->Show() ?>
<?php if (!empty($error)) { ?>
    <? CAdminMessage::ShowMessage($error) ?>
<?php } ?>
<?= Html::beginForm('', 'post', array('id' => 'page')) ?>
    <? $tabs->Begin() ?>
    <? $tabs->BeginNextTab() ?>
        <tr>
            <td width="40%"><b><?= $category->getAttributeLabel('code') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($category->formName().'[code]', $category->code) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $category->getAttributeLabel('sort') ?>:</td>
            <td width="60%">
                <?= Html::textInput($category->formName().'[sort]', $category->sort) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $category->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($category->formName().'[name]', $category->name) ?>
            </td>
        </tr>
    <? $tabs->Buttons(array('back_url' => $arUrlTemplates['blocks.categories'])) ?>
    <? $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
