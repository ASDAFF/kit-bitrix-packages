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
use intec\constructor\models\block\Category;

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
$category = new Category();

$APPLICATION->SetTitle(Loc::getMessage('title'));

if ($request->getIsPost()) {
    $post = $request->post();
    $file = UploadedFile::getInstanceByName('file');
    $category->load($post);

    if (!empty($file) && $category->validate(['code'])) {
        $directory = Path::from('@intec/constructor/upload/exchange');
        $path = $directory->add('category.json');

        FileHelper::removeDirectory($directory->value);
        FileHelper::createDirectory($directory->value);

        if (rename($file->tempName, $path->value)) {
            $result = $category->importFromFile($path);

            FileHelper::removeDirectory($directory->value);

            if (!$result) {
                $category->delete();
            } else {
                LocalRedirect(
                    StringHelper::replaceMacros(
                        $arUrlTemplates['blocks.categories.edit'],
                        array(
                            'category' => $category->code
                        )
                    )
                );
            }
        }

        FileHelper::removeDirectory($directory->value);
    } else {
        if ($category->hasErrors()) {
            $error = ArrayHelper::getFirstValue($category->getFirstErrors());
        } else {
            $error = Loc::getMessage('errors.file');
        }
    }
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => Loc::getMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => $arUrlTemplates['blocks.categories']
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
        <td width="40%"><b><?= $category->getAttributeLabel('code') ?>:</b></td>
        <td width="60%">
            <?= Html::textInput($category->formName().'[code]', $category->code) ?>
        </td>
    </tr>
    <tr>
        <td width="40%"><b><?= Loc::getMessage('fields.file') ?>:</b></td>
        <td width="60%">
            <?= Html::fileInput('file') ?>
        </td>
    </tr>
<?php $tabs->Buttons(array('back_url' => $arUrlTemplates['blocks.categories'])) ?>
<?php $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>