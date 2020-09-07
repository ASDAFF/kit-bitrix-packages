<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\models\block\Category;
use intec\constructor\models\block\Template;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructorlite'))
    return;

Loc::loadMessages(__FILE__);

Core::$app->web->js->loadExtensions(['intec']);
include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$error = null;
$template = $request->get('template');

/** @var ActiveRecords $categories */
$categories = Category::find()->indexBy('code')->all();
$categoriesList = [];

/** @var Category $category */
foreach ($categories as $category)
    $categoriesList[$category->code] = $category->name;

/** @var Template $template */
$template = Template::findOne($template);

if (empty($template))
    LocalRedirect($arUrlTemplates['blocks.templates']);

$APPLICATION->SetTitle(Loc::getMessage('title'));

$code = null;
$category = null;
$name = null;

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $data = ArrayHelper::getValue($post, $template->formName());
    $name = ArrayHelper::getValue($data, 'name');
    $category = ArrayHelper::getValue($data, 'categoryCode');
    $code = ArrayHelper::getValue($data, 'code');

    if (!empty($code) && !empty($name)) {
        if (!Template::findOne([
            'code' => $code
        ])) {
            $copy = new Template();
            $copy->code = $code;
            $copy->categoryCode = $categories->exists($category) ? $category : null;

            $structure = $template->export();
            $structure['name'] = $name;

            if ($copy->import($structure)) {
                $copyResources = $copy->getResources();
                $templateResources = $template->getResources();

                FileHelper::removeDirectory($copyResources->getDirectory()->value);
                FileHelper::createDirectory($copyResources->getDirectory()->getParent()->value);

                if (FileHelper::isDirectory($templateResources->getDirectory()->value))
                    FileHelper::copyDirectory(
                        $templateResources->getDirectory()->value,
                        $copyResources->getDirectory()->value
                    );

                if (!empty($template->image)) {
                    $file = CFile::CopyFile($template->image);

                    if (!empty($file)) {
                        $copy->image = $file;
                        $copy->save(true, ['image']);
                    }
                }

                if ($return)
                    LocalRedirect($arUrlTemplates['blocks.templates']);

                LocalRedirect(
                    StringHelper::replaceMacros(
                        $arUrlTemplates['blocks.templates.edit'],
                        array(
                            'template' => $copy->code
                        )
                    )
                );
            } else {
                $error = GetMessage('error.copy');
            }
        } else {
            $error = GetMessage('error.exists');
        }
    } else {
        if (empty($code))
            $error = GetMessage('error.field.empty', [
                '#name#' => $template->getAttributeLabel('code')
            ]);

        if (empty($name))
            $error = GetMessage('error.field.empty', [
                '#name#' => $template->getAttributeLabel('name')
            ]);
    }

    /*$return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $template->load($post);

    if ($template->save()) {
        $file = UploadedFile::getInstance($template, 'image');

        if (!empty($file)) {
            $fileName = $file->name;
            $file = CFile::MakeFileArray($file->tempName);
            $file['name'] = $fileName;

            if (!empty($file)) {
                $file = CFile::SaveFile($file,
                    Path::from('@intec/constructor/upload')
                        ->getRelativeFrom('@upload')
                        ->add('blocks/previews')
                        ->getValue('/')
                );

                if (!empty($file)) {
                    if (!empty($image))
                        CFile::Delete($image['ID']);

                    $template->image = $file;
                    $template->save(true, ['image']);
                }
            }
        }

        if ($return)
            LocalRedirect($arUrlTemplates['blocks.templates']);

        LocalRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['blocks.templates.edit'],
                array(
                    'template' => $template->code
                )
            )
        );
    } else {
        $error = ArrayHelper::getFirstValue($template->getFirstErrors());
    }*/
} else {
    $code = $template->code;
    $category = $template->categoryCode;
    $name = $template->name;
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => Loc::getMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => $arUrlTemplates['blocks.templates']
    ),
    array(
        'TEXT' => Loc::getMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['blocks.templates.add'],
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
<?= Html::beginForm('', 'post', array(
    'id' => 'page',
    'enctype' => 'multipart/form-data'
)) ?>
    <? $tabs->Begin() ?>
    <? $tabs->BeginNextTab() ?>
        <tr>
            <td width="40%"><b><?= $template->getAttributeLabel('code') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($template->formName().'[code]', $code) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('categoryCode') ?>:</td>
            <td width="60%">
                <?= Html::dropDownList($template->formName().'[categoryCode]', $category, ArrayHelper::merge([
                    '' => Loc::getMessage('fields.category.unselected')
                ], $categoriesList)) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $template->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($template->formName().'[name]', $name) ?>
            </td>
        </tr>
    <? $tabs->Buttons(array('back_url' => $arUrlTemplates['blocks.templates'])) ?>
    <? $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
