<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php
IncludeModuleLangFile(__FILE__);

global $APPLICATION;

use intec\Core;
use intec\core\base\InvalidParamException;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\web\UploadedFile;
use intec\constructor\models\Build;
use intec\constructor\models\build\Property;
use intec\constructor\models\build\property\Enum;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

Core::$app->web->js->loadExtensions(['intec']);
include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$error = null;
$build = $request->get('build');
$build = Build::findOne($build);

if (empty($build))
    LocalRedirect($arUrlTemplates['builds']);

$APPLICATION->SetTitle(GetMessage('title', array(
    '#build#' => $build->name
)));

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $action = $request->post('action');
    $action = ArrayHelper::fromRange(['skip', 'replace'], $action);
    $file = UploadedFile::getInstanceByName('file');

    if (!empty($file)) {
        /** @var ActiveRecords $properties */
        $properties = $build
            ->getProperties(false)
            ->with(['enums'])
            ->all();

        $properties->indexBy('code');
        $data = FileHelper::getFileData($file->tempName);

        try {
            $data = Json::decode($data);
        } catch(InvalidParamException $exception) {
            $error = GetMessage('errors.structure');
        }

        $data = ArrayHelper::convertEncoding($data, null, Encoding::UTF8);

        if ($error === null) {
            foreach ($data as $item) {
                $code = ArrayHelper::getValue($item, 'code');

                if (empty($code))
                    continue;

                $property = $properties->get($code);

                if (!empty($property)) {
                    if ($action == 'skip')
                        continue;
                } else {
                    $property = new Property();
                    $property->buildId = $build->id;
                }

                $property->import($item);
            }

            if ($return)
                LocalRedirect(
                    StringHelper::replaceMacros(
                        $arUrlTemplates['builds.properties'],
                        array(
                            'build' => $build->id
                        )
                    )
                );

            LocalRedirect(
                StringHelper::replaceMacros(
                    $arUrlTemplates['builds.properties.import'],
                    array(
                        'build' => $build->id
                    )
                )
            );
        }
    } else {
        $error = GetMessage('errors.file');
    }
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => GetMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.properties'],
            array(
                'build' => $build->id
            )
        )
    ),
    array(
        'TEXT' => GetMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.properties.add'],
            array(
                'build' => $build->id
            )
        )
    )
));

$tabs = new CAdminTabControl(
    'tabs',
    array(
        array(
            'DIV' => 'common',
            'TAB' => GetMessage('tabs.common'),
            'TITLE' => GetMessage('tabs.common')
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
    <? $tabs->Begin() ?>
    <? $tabs->BeginNextTab() ?>
        <tr>
            <td width="40%"><b><?= GetMessage('fields.action') ?>:</b></td>
            <td width="60%">
                <?= Html::dropDownList('action', $request->post('action'), array(
                    'skip' => GetMessage('fields.action.skip'),
                    'replace' => GetMessage('fields.action.replace')
                ), array(
                    'data-bind' => '{ value: code }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= GetMessage('fields.file') ?>:</b></td>
            <td width="60%">
                <?= Html::fileInput('file') ?>
            </td>
        </tr>
    <? $tabs->Buttons(['back_url' => StringHelper::replaceMacros($arUrlTemplates['builds.properties'], array('build' => $build->id))]) ?>
    <? $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
