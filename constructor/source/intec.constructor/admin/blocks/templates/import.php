<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\io\Path;
use intec\core\web\UploadedFile;
use \intec\core\helpers\FileHelper;
use intec\constructor\models\block\Template;
use \intec\constructor\models\block\Category;

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
$template = new Template();
$categories = Category::find()
    ->orderBy('sort, name')
    ->indexBy('code')
    ->all();

$APPLICATION->SetTitle(Loc::getMessage('title'));

if ($request->getIsPost()) {
    $post = $request->post();
    $file = UploadedFile::getInstanceByName('file');
    $template->load($post);

    if (!$categories->exists($template->categoryCode))
        $template->categoryCode = null;

    if (!empty($file) && $template->validate(['code'])) {
        $directory = Path::from('@intec/constructor/upload/exchange');
        $dataPath = $directory->add($file->name);
        $dataDirectory = $directory->add($dataPath->getName(false));

        FileHelper::removeDirectory($directory->value);
        FileHelper::createDirectory($directory->value);

        if (rename($file->tempName, $dataPath->value)) {
            $result = $template->importFromFile(
                $dataPath,
                $dataDirectory
            );

            FileHelper::removeDirectory($directory->value);

            if (!$result) {
                $template->delete();
                $error = Loc::getMessage('errors.file.invalid');
            } else {
                LocalRedirect(
                    StringHelper::replaceMacros(
                        $arUrlTemplates['blocks.templates.edit'],
                        array(
                            'template' => $template->code
                        )
                    )
                );
            }
        }

        FileHelper::removeDirectory($directory->value);
    } else {
        if ($template->hasErrors()) {
            $error = ArrayHelper::getFirstValue($template->getFirstErrors());
        } else {
            $error = Loc::getMessage('errors.file');
        }
    }
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => Loc::getMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => $arUrlTemplates['blocks.templates']
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
<?= Html::beginForm('', 'post', array('id' => 'page', 'enctype' => 'multipart/form-data')) ?>
<?php $tabs->Begin() ?>
<?php $tabs->BeginNextTab() ?>
    <tr>
        <td width="40%"><b><?= $template->getAttributeLabel('code') ?>:</b></td>
        <td width="60%">
            <?= Html::textInput($template->formName().'[code]', $template->code) ?>
        </td>
    </tr>
    <tr>
        <td width="40%"><?= $template->getAttributeLabel('categoryCode') ?>:</td>
        <td width="60%">
            <?= Html::dropDownList($template->formName().'[categoryCode]', $template->code, ArrayHelper::merge(
                ['' => Loc::getMessage('fields.category.unselected')],
                $categories->asArray(function ($key, $category) {
                    /**
                     * @var Category $category
                     */

                    return [
                        'key' => $category->code,
                        'value' => '['.$category->code.'] '.$category->name
                    ];
                })
            )) ?>
        </td>
    </tr>
    <tr>
        <td width="40%"><b><?= Loc::getMessage('fields.file') ?>:</b></td>
        <td width="60%">
            <?= Html::fileInput('file') ?>
        </td>
    </tr>
<?php $tabs->Buttons(array('back_url' => $arUrlTemplates['blocks.templates'])) ?>
<?php $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>