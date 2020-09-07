<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\io\Path;
use intec\core\web\UploadedFile;
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
$image = null;

/** @var Category[] $categories */
$categories = Category::find()->all();
$categoriesList = [];

foreach ($categories as $category)
    $categoriesList[$category->code] = $category->name;

/** @var Template $template */
$template = Template::findOne($template);

if (empty($template)) {
    $template = new Template();
    $template->loadDefaultValues();
}

if (!empty($template->image)) {
    $image = CFile::GetFileArray($template->image);

    if (empty($image))
        $image = null;
}

if ($template->getIsNewRecord()) {
    $APPLICATION->SetTitle(Loc::getMessage('title.add'));
} else {
    $APPLICATION->SetTitle(Loc::getMessage('title.edit', array(
        '#name#' => $template->name
    )));
}

if ($request->getIsPost()) {
    $return = $request->post('apply');
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
    }
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
                <?= Html::textInput($template->formName().'[code]', $template->code) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('categoryCode') ?>:</td>
            <td width="60%">
                <?= Html::dropDownList($template->formName().'[categoryCode]', $template->categoryCode, ArrayHelper::merge([
                    '' => Loc::getMessage('fields.category.unselected')
                ], $categoriesList)) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('sort') ?>:</td>
            <td width="60%">
                <?= Html::textInput($template->formName().'[sort]', $template->sort) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $template->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($template->formName().'[name]', $template->name) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('image') ?>:</td>
            <td width="60%">
                <?php if (!empty($image)) { ?>
                    <div style="position: relative; width: 300px; height: 180px; border: 2px dashed #929ba1; border-radius: 3px; background: #fff; margin-bottom: 10px;">
                        <div style="position: relative; width: 280px; height: 160px; margin: 10px; font-size: 0; text-align: center;">
                            <div style="display: inline-block; vertical-align: middle; width: 0; height: 100%; overflow: hidden;"></div>
                            <img src="<?= $image['SRC'] ?>" style="display: inline-block; vertical-align: middle; max-width: 100%; max-height: 100%;" />
                        </div>
                    </div>
                <?php } ?>
                <?= Html::fileInput($template->formName().'[image]') ?>
            </td>
        </tr>
    <? $tabs->Buttons(array('back_url' => $arUrlTemplates['blocks.templates'])) ?>
    <? $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
