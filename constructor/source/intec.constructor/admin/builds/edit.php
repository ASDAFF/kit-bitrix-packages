<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php
IncludeModuleLangFile(__FILE__);

global $APPLICATION;

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;

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

/** @var Build $build */
$build = Build::findOne($build);

if (empty($build))
    $build = new Build();

if ($build->getIsNewRecord()) {
    $APPLICATION->SetTitle(GetMessage('title.add'));
} else {
    $APPLICATION->SetTitle(GetMessage('title.edit', array(
        '#name#' => $build->name
    )));
}

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $build->load($post);

    if ($build->save()) {
        $build->checkout();

        if ($return)
            LocalRedirect($arUrlTemplates['builds']);

        LocalRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.edit'],
                array(
                    'build' => $build->id
                )
            )
        );
    } else {
        $error = ArrayHelper::getFirstValue($build->getFirstErrors());
    }
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => GetMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => $arUrlTemplates['builds']
    ),
    array(
        'TEXT' => GetMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['builds.add'],
    )
));

$tabs = new CAdminTabControl(
    'tabs',
    array(
        array(
            'DIV' => 'common',
            'TAB' => GetMessage('tabs.common'),
            'TITLE' => GetMessage('tabs.common')
        ),
        array(
            'DIV' => 'css',
            'TAB' => GetMessage('tabs.css'),
            'ONSELECT' => 'window.page.refresh();',
            'TITLE' => GetMessage('tabs.css')
        ),
        array(
            'DIV' => 'less',
            'TAB' => GetMessage('tabs.less'),
            'ONSELECT' => 'window.page.refresh();',
            'TITLE' => GetMessage('tabs.less')
        ),
        array(
            'DIV' => 'js',
            'TAB' => GetMessage('tabs.js'),
            'ONSELECT' => 'window.page.refresh();',
            'TITLE' => GetMessage('tabs.js')
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
        <? if (!$build->getIsNewRecord()) { ?>
            <tr>
                <td width="40%"><b><?= $build->getAttributeLabel('id') ?>:</b></td>
                <td width="60%"><?= $build->id ?></td>
            </tr>
        <? } ?>
        <tr>
            <td width="40%"><b><?= $build->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($build->formName().'[name]', $build->name) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $build->getAttributeLabel('code') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($build->formName().'[code]', $build->code) ?>
            </td>
        </tr>
    <? $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2" width="100%">
                <?= Html::textarea($build->formName().'[css]', $build->css, array(
                    'data-bind' => 'bind: editors.css'
                )) ?>
            </td>
        </tr>
    <? $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2">
                <?= Html::textarea($build->formName().'[less]', $build->less, array(
                    'data-bind' => 'bind: editors.less'
                )) ?>
                <?/*<!-- ko if: properties().length > 0 -->
                    <div style="margin-top: 10px;">
                        <div><?= GetMessage('template.properties.available') ?>:</div>
                        <div style="font-weight: bold;">
                            <!-- ko foreach: properties -->
                            <a data-bind="text: name, click: addToEditor" style="cursor: pointer;"></a>
                            <!-- /ko -->
                        </div>
                    </div>
                <!-- /ko -->*/?>
            </td>
        </tr>
    <? $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2" width="100%">
                <?= Html::textarea($build->formName().'[js]', $build->js, array(
                    'data-bind' => 'bind: editors.js'
                )) ?>
            </td>
        </tr>
    <? $tabs->Buttons(array('back_url' => $arUrlTemplates['builds'])) ?>
    <? $tabs->End() ?>
    <script type="text/javascript">
        (function ($, api) {
            window.page = {};
            window.page.refresh = function () {};

            var root = $('#page');
            var data = <?= JavaScript::toObject([]); ?>;
            var model = {};

            model.editors = {};
            model.editors.css = ko.models.codeMirror({
                'mode': 'text/css'
            });
            model.editors.less = ko.models.codeMirror({
                'mode': 'text/x-less'
            });
            model.editors.js = ko.models.codeMirror({
                'mode': 'text/javascript'
            });

            window.page.refresh = function () {
                if (model.editors.css.getEditor())
                    model.editors.css.getEditor().refresh();

                if (model.editors.less.getEditor())
                    model.editors.less.getEditor().refresh();

                if (model.editors.js.getEditor())
                    model.editors.js.getEditor().refresh();
            };

            ko.applyBindings(model, root.get(0));
        })(jQuery, intec);
    </script>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
