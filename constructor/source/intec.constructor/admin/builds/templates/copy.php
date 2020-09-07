<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php
IncludeModuleLangFile(__FILE__);

global $APPLICATION;

use intec\Core;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\Build;
use intec\constructor\models\build\Property;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Value;
use intec\constructor\models\build\Theme;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

Core::$app->web->js->loadExtensions(['intec_constructor']);

include(Core::getAlias('@intec/constructor/module/admin/url.php'));

$request = Core::$app->request;
$error = null;
$build = $request->get('build');
$build = Build::find()
    ->where(['id' => $build])
    ->one();
/** @var Build $build */

if (empty($build))
    LocalRedirect($arUrlTemplates['builds']);

$template = $request->get('template');
$template = Template::find()
    ->where([
        'id' => $template,
        'buildId' => $build->id
    ])
    ->one();
/** @var Template $template */

if (empty($template))
    LocalRedirect(StringHelper::replaceMacros(
        $arUrlTemplates['builds.templates'],
        array(
            'build' => $build->id
        )
    ));

$APPLICATION->SetTitle(GetMessage('title', array(
    '#name#' => $template->name,
    '#build#' => $build->name
)));

$code = null;
$name = null;

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $data = ArrayHelper::getValue($post, $template->formName());
    $name = ArrayHelper::getValue($data, 'name');
    $code = ArrayHelper::getValue($data, 'code');

    if (!empty($code) && !empty($name)) {
        if (!Template::findOne([
            'buildId' => $build->id,
            'code' => $code
        ])) {
            $copy = new Template();
            $copy->buildId = $build->id;

            $structure = $template->export();
            $structure['code'] = $code;
            $structure['name'] = $name;

            if ($copy->import($structure)) {
                if ($return)
                    LocalRedirect(
                        StringHelper::replaceMacros(
                            $arUrlTemplates['builds.templates'],
                            array(
                                'build' => $build->id
                            )
                        )
                    );

                LocalRedirect(
                    StringHelper::replaceMacros(
                        $arUrlTemplates['builds.templates.edit'],
                        array(
                            'build' => $build->id,
                            'template' => $copy->id
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
} else {
    $code = $template->code;
    $name = $template->name;
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => GetMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.templates'],
            array(
                'build' => $build->id
            )
        )
    ),
    array(
        'TEXT' => GetMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.templates.add'],
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
<?= Html::beginForm('', 'post', array('id' => 'page')) ?>
<?php $tabs->Begin() ?>
<?php $tabs->BeginNextTab() ?>
    <tr>
        <td width="40%"><b><?= $template->getAttributeLabel('code') ?>:</b></td>
        <td width="60%">
            <?= Html::textInput($template->formName().'[code]', $code) ?>
        </td>
    </tr>
    <tr>
        <td width="40%"><b><?= $template->getAttributeLabel('name') ?>:</b></td>
        <td width="60%">
            <?= Html::textInput($template->formName().'[name]', $name) ?>
        </td>
    </tr>
<?php $tabs->Buttons(['back_url' => StringHelper::replaceMacros($arUrlTemplates['builds.templates'], array('build' => $build->id))]) ?>
<?php $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
