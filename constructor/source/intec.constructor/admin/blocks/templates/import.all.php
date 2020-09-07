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

if (!extension_loaded('zip'))
    LocalRedirect($arUrlTemplates['blocks.templates']);

$request = Core::$app->request;
$error = null;
$categories = Category::find()
    ->indexBy('code')
    ->orderBy('sort, name')
    ->all();

$APPLICATION->SetTitle(Loc::getMessage('title'));

if ($request->getIsPost()) {
    $action = $request->post('action');
    $prefix = $request->post('prefix');
    $file = UploadedFile::getInstanceByName('file');
    $categoryDefault = $categories->get($request->post('category'));

    if (!empty($file)) {
        $directory = Path::from('@intec/constructor/upload/exchange');
        $dataPath = $directory->add('import.zip');
        $dataDirectory = $directory->add($dataPath->getName(false));

        FileHelper::removeDirectory($directory->value);
        FileHelper::createDirectory($directory->value);

        if (rename($file->tempName, $dataPath->value)) {
            $result = true;
            $templates = Template::find()
                ->indexBy('code')
                ->all();

            $archive = new ZipArchive();

            if ($archive->open($dataPath->getValue()) === true) {
                FileHelper::createDirectory($dataDirectory->value);

                $archive->extractTo($dataDirectory->value);
                $archive->close();

                /**
                 * @param Path $directory
                 * @param Category|null $category
                 */
                $import = function ($directory, $category = null) use (&$templates, &$action, &$prefix) {
                    $entries = FileHelper::getDirectoryEntries($directory->value, false);

                    foreach ($entries as $entry) {
                        $path = $directory->add($entry);

                        if (!FileHelper::isFile($path->value))
                            continue;

                        if ($path->getExtension() != 'zip')
                            continue;

                        $template = new Template();
                        $template->code = $path->getName(false);

                        if ($templates->exists($template->code)) {
                            if ($action == 'replace') {
                                $template = $templates->get($template->code);
                            } else if ($action == 'prefix') {
                                if (empty($prefix))
                                    continue;

                                while ($templates->exists($template->code))
                                    $template->code = $prefix . $template->code;
                            }
                        }

                        if (!empty($category))
                            $template->categoryCode = $category->code;

                        $template->importFromFile(
                            $path->value,
                            $path->getParent()->add($template->code)
                        );
                    }
                };

                $import($dataDirectory);
                $entries = FileHelper::getDirectoryEntries($dataDirectory->value, false);

                foreach ($entries as $entry) {
                    $category = $categories->get($entry);
                    $path = $dataDirectory->add($entry);

                    if (!FileHelper::isDirectory($path->value))
                        continue;

                    if (empty($category))
                        $category = $categoryDefault;

                    $import($path, $category);
                }

                FileHelper::removeDirectory($directory->value);
                LocalRedirect($arUrlTemplates['blocks.templates']);
            } else {
                $error = Loc::getMessage('errors.file.invalid');
            }
        }

        FileHelper::removeDirectory($directory->value);
    } else {
        $error = Loc::getMessage('errors.file');
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
        <td width="40%"><?= Loc::getMessage('fields.action') ?>:</td>
        <td width="60%">
            <?= Html::dropDownList('action', $request->post('action'), [
                'none' => Loc::getMessage('fields.action.none'),
                'replace' => Loc::getMessage('fields.action.replace'),
                'prefix' => Loc::getMessage('fields.action.prefix')
            ]) ?>
        </td>
    </tr>
    <tr>
        <td width="40%"><?= Loc::getMessage('fields.prefix') ?>:</td>
        <td width="60%">
            <?= Html::textInput('prefix', $request->post('prefix', 'new.')) ?>
        </td>
    </tr>
    <tr>
        <td width="40%"><?= Loc::getMessage('fields.category') ?>:</td>
        <td width="60%">
            <?= Html::dropDownList('category', $request->post('category'), ArrayHelper::merge(
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