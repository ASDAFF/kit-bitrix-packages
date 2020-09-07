<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Area;

/**
 * @var array $arUrlTemplates
 * @var CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.constructor'))
    return;

Loc::loadMessages(__FILE__);

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

$area = $request->get('area');
$area = Area::find()->where([
    'id' => $area,
    'buildId' => $build->id
])->one();

if (empty($area)) {
    $area = new Area();
    $area->buildId = $build->id;
    $area->loadDefaultValues();
}

if ($area->getIsNewRecord()) {
    $APPLICATION->SetTitle(Loc::getMessage('title.add', array(
        '#build#' => $build->name
    )));
} else {
    $APPLICATION->SetTitle(Loc::getMessage('title.edit', array(
        '#name#' => $area->name,
        '#build#' => $build->name
    )));
}

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $area->load($post);

    if ($area->save()) {
        if ($return)
            LocalRedirect(
                StringHelper::replaceMacros(
                    $arUrlTemplates['builds.areas'],
                    array(
                        'build' => $build->id
                    )
                )
            );

        LocalRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.areas.edit'],
                array(
                    'build' => $build->id,
                    'area' => $area->id
                )
            )
        );
    } else {
        $error = ArrayHelper::getFirstValue($area->getFirstErrors());
    }
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => Loc::getMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.areas'],
            array(
                'build' => $build->id
            )
        )
    ),
    array(
        'TEXT' => Loc::getMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.areas.add'],
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
    <?php $tabs->Begin() ?>
    <?php $tabs->BeginNextTab() ?>
        <?php if (!$area->getIsNewRecord()) { ?>
            <tr>
                <td width="40%"><?= $area->getAttributeLabel('id') ?>:</td>
                <td width="60%">
                    <?= $area->id ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td width="40%"><b><?= $area->getAttributeLabel('code') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($area->formName().'[code]', $area->code) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $area->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($area->formName().'[name]', $area->name) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $area->getAttributeLabel('sort') ?>:</td>
            <td width="60%">
                <?= Html::textInput($area->formName().'[sort]', $area->sort) ?>
            </td>
        </tr>
    <?php $tabs->Buttons(['back_url' => StringHelper::replaceMacros($arUrlTemplates['builds.areas'], array('build' => $build->id))]) ?>
    <?php $tabs->End() ?>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
