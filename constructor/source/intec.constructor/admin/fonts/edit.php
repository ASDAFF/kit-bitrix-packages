<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use intec\constructor\models\font\File;
use intec\constructor\models\font\Link;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Font;
use intec\core\helpers\Type;
use intec\core\io\Path;
use intec\core\net\Url;
use intec\core\web\UploadedFile;

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
$error = false;
$font = $request->get('font');
$formats = Font::getFormats();
$weights = Font::getWeights();
$styles = Font::getStyles();

/** @var Font $font */
$font = Font::findOne($font);

if (empty($font)) {
    $font = new Font();
    $font->loadDefaultValues();
}

$menuNavigation = [];
$tabs = [];

$menuNavigation[] = [
    'TEXT' => Loc::getMessage('menu.back'),
    'ICON' => 'btn_list',
    'LINK' => $arUrlTemplates['fonts']
];

if ($font->getIsNewRecord()) {
    $APPLICATION->SetTitle(Loc::getMessage('title.add'));

    $step = 1;
    $data = [];

    if ($request->getIsPost()) {
        $step = $request->post('step');
        $step = Type::toInteger($step);

        if ($step < 1)
            $step = 1;

        $data = $request->post('data');

        if (!Type::isArray($data))
            $data = [];

        $type = null;

        if ($step >= 1) {
            $type = ArrayHelper::getValue($data, 'type');

            if (!ArrayHelper::isIn($type, Font::getTypesValues()))
                $error = Loc::getMessage(''); // Incorrect type
        }

        if ($type == Font::TYPE_LOCAL) {
            if ($step >= 3) {
                $font->load($data, '');

                if ($font->save()) {
                    LocalRedirect(
                        StringHelper::replaceMacros(
                            $arUrlTemplates['fonts.edit'],
                            [
                                'font' => $font->code
                            ]
                        )
                    );
                } else {
                    $error = $font->getFirstErrors();
                    $error = ArrayHelper::getFirstValue($error);
                }
            }
        } else {
            if ($step >= 3) {
                $link = ArrayHelper::getValue($data, 'link');
                $link = new Url($link);
                $host = $link->getHost();

                if (!empty($host) && ($link->getScheme() === 'http' || $link->getScheme() === 'https')) {
                    $link = new Link([
                        'value' => $link->build()
                    ]);

                    $font->populateRelation('link', $link);
                    $font->code = $font->getStyleCode();

                    if ($font->getIsValid()) {
                        $font->name = $font->code;
                        $font->type = $type;

                        if ($font->save()) {
                            $link->fontCode = $font->code;
                            $link->save();

                            LocalRedirect(
                                StringHelper::replaceMacros(
                                    $arUrlTemplates['fonts.edit'],
                                    [
                                        'font' => $font->code
                                    ]
                                )
                            );
                        } else {
                            $error = Loc::getMessage('errors.fontExists', [
                                '#code#' => $font->code
                            ]);
                        }
                    } else {
                        $error = Loc::getMessage('errors.invalidFont');
                    }
                } else {
                    $error = Loc::getMessage('errors.invalidLink');
                }
            }
        }

        if ($error !== false)
            $step--;
    }

    $tabs = [[
        'DIV' => 'common',
        'TAB' => Loc::getMessage('tabs.common'),
        'TITLE' => Loc::getMessage('tabs.common')
    ]];
} else {
    $APPLICATION->SetTitle(Loc::getMessage('title.edit', array(
        '#name#' => $font->name
    )));

    $menuNavigation[] = [
        'TEXT' => Loc::getMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => $arUrlTemplates['fonts.add']
    ];

    if ($request->getIsPost()) {
        $return = $request->post('apply');
        $return = !empty($return) ? false : true;
        $post = $request->post();
        $font->load($post);

        if ($font->save()) {
            /** @var File[] $files */
            $files = $font->getFiles(true);
            $directory = Path::from('@intec/constructor/upload')
                ->getRelativeFrom('@upload')
                ->add('fonts')
                ->getValue('/');

            if ($font->type === Font::TYPE_LOCAL) {
                foreach ($weights as $weight => $weightName) {
                    foreach ($styles as $style => $styleName) {
                        foreach ($formats as $format => $formatName) {
                            $file = null;
                            $uploadedFile = UploadedFile::getInstanceByName('file['.$weight.']['.$style.']['.$format.']');
                            $remove = ArrayHelper::getValue($post, ['file', $weight, $style, $format, 'remove']) == 1;

                            foreach ($files as $file) {
                                if ($file->weight == $weight && $file->style == $style && $file->format == $format)
                                    break;

                                $file = null;
                            }

                            if ($remove) {
                                if (!empty($file))
                                    $file->delete();

                                continue;
                            }

                            if (empty($uploadedFile))
                                continue;

                            if (empty($file)) {
                                $file = new File();
                                $file->fontCode = $font->code;
                                $file->weight = $weight;
                                $file->style = $style;
                                $file->format = $format;
                            }

                            $uploadedFileName = $uploadedFile->name;
                            $uploadedFile = CFile::MakeFileArray($uploadedFile->tempName);

                            if (!empty($uploadedFile)) {
                                $uploadedFile['name'] = $uploadedFileName;
                                $uploadedFile = CFile::SaveFile($uploadedFile, $directory);

                                if (!empty($uploadedFile)) {
                                    if (!empty($file->fileId))
                                        CFile::Delete($file->fileId);

                                    $file->fileId = $uploadedFile;
                                    $file->save();
                                }
                            }

                            unset($uploadedFileName);
                        }
                    }
                }
            }

            if ($return)
                LocalRedirect($arUrlTemplates['fonts']);

            LocalRedirect(
                StringHelper::replaceMacros(
                    $arUrlTemplates['fonts.edit'],
                    [
                        'font' => $font->code
                    ]
                )
            );
        } else {
            $error = ArrayHelper::getFirstValue($font->getFirstErrors());
        }
    }

    $tabs = [[
        'DIV' => 'common',
        'TAB' => Loc::getMessage('tabs.common'),
        'TITLE' => Loc::getMessage('tabs.common')
    ]];

    if ($font->type === Font::TYPE_LOCAL) {
        $tabs[] = [
            'DIV' => 'files',
            'TAB' => Loc::getMessage('tabs.files'),
            'TITLE' => Loc::getMessage('tabs.files')
        ];
    }
}

$menuNavigation = new CAdminContextMenu($menuNavigation);
$tabs = new CAdminTabControl(
    'tabs',
    $tabs
);

?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php') ?>
<?php $menuNavigation->Show() ?>
<?php if (!empty($error)) { ?>
    <?php CAdminMessage::ShowMessage($error) ?>
<?php } ?>
<?php if ($font->getIsNewRecord()) { ?>
    <?= Html::beginForm('', 'post', array('id' => 'page')) ?>
        <?= Html::hiddenInput('step', $step + 1) ?>
        <?= Html::hiddenInput('data', $data) ?>
        <? $tabs->Begin() ?>
            <? $tabs->BeginNextTab() ?>
                <?php if ($step == 1) { ?>
                    <tr>
                        <td width="40%"><b><?= $font->getAttributeLabel('type') ?>:</b></td>
                        <td width="60%">
                            <?= Html::dropDownList('data[type]', null, Font::getTypes()) ?>
                        </td>
                    </tr>
                <?php } else if ($step == 2) { ?>
                    <?php if ($type == Font::TYPE_LOCAL) { ?>
                        <tr>
                            <td width="40%"><b><?= $font->getAttributeLabel('code') ?>:</b></td>
                            <td width="60%">
                                <?= Html::textInput('data[code]', $font->code) ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%"><b><?= $font->getAttributeLabel('name') ?>:</b></td>
                            <td width="60%">
                                <?= Html::textInput('data[name]', $font->name) ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%"><?= $font->getAttributeLabel('sort') ?>:</td>
                            <td width="60%">
                                <?= Html::textInput('data[sort]', $font->sort) ?>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td width="40%"><b><?= Loc::getMessage('fields.link') ?>:</b></td>
                            <td width="60%">
                                <?= Html::textInput('data[link]', null) ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
        <? $tabs->Buttons() ?>
            <a class="adm-btn" href="<?= $arUrlTemplates['fonts'] ?>"><?= Loc::getMessage('buttons.back') ?></a>
            <input class="adm-btn-save" type="submit" value="<?= Loc::getMessage('buttons.next') ?>" />
        <? $tabs->End() ?>
    <?= Html::endForm() ?>
<?php } else { ?>
    <?= Html::beginForm('', 'post', array('id' => 'page', 'enctype' => 'multipart/form-data')) ?>
        <? $tabs->Begin() ?>
            <? $tabs->BeginNextTab() ?>
                <tr>
                    <td width="40%"><b><?= $font->getAttributeLabel('type') ?>:</b></td>
                    <td width="60%">
                        <?= ArrayHelper::getValue(Font::getTypes(), $font->type) ?>
                    </td>
                </tr>
                <tr>
                    <td width="40%"><b><?= $font->getAttributeLabel('code') ?>:</b></td>
                    <td width="60%">
                        <?= $font->code ?>
                    </td>
                </tr>
                <tr>
                    <td width="40%"><?= $font->getAttributeLabel('active') ?>:</td>
                    <td width="60%">
                        <?= Html::hiddenInput($font->formName().'[active]', 0) ?>
                        <?= Html::checkbox($font->formName().'[active]', $font->active, [
                            'value' => 1
                        ]) ?>
                    </td>
                </tr>
                <tr>
                    <td width="40%"><b><?= $font->getAttributeLabel('name') ?>:</b></td>
                    <td width="60%">
                        <?= Html::textInput($font->formName().'[name]', $font->name) ?>
                    </td>
                </tr>
                <tr>
                    <td width="40%"><?= $font->getAttributeLabel('sort') ?>:</td>
                    <td width="60%">
                        <?= Html::textInput($font->formName().'[sort]', $font->sort) ?>
                    </td>
                </tr>
                <?php if ($font->type === Font::TYPE_EXTERNAL) { ?>
                <?php
                    $link = $font->getLink(true);
                ?>
                    <?php if (!empty($link)) { ?>
                        <tr>
                            <td width="40%"><?= Loc::getMessage('fields.link') ?>:</td>
                            <td width="60%">
                                <?= $link->value ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
        <?php if ($font->type === Font::TYPE_LOCAL) { ?>
            <? $tabs->BeginNextTab() ?>
                <?php
                    /** @var File[] $files */
                    $files = $font->getFiles(true);
                ?>
                <?php foreach ($weights as $weight => $weightName) { ?>
                    <tr>
                        <td class="heading"><?= $weightName ?></td>
                    </tr>
                    <tr>
                        <td>
                            <table class="internal" style="width: 100%">
                                <tr>
                                    <td></td>
                                    <?php foreach ($formats as $format => $formatName) { ?>
                                        <td class="heading"><?= $formatName ?></td>
                                    <?php } ?>
                                </tr>
                                <?php foreach ($styles as $style => $styleName) { ?>
                                    <tr>
                                        <td class="heading" style="text-align: right !important;"><?= $styleName ?></td>
                                        <?php foreach ($formats as $format => $formatName) { ?>
                                        <?php
                                            $file = null;

                                            foreach ($files as $file) {
                                                if ($file->weight == $weight && $file->style == $style && $file->format == $format)
                                                    break;

                                                $file = null;
                                            }
                                        ?>
                                            <td style="text-align: center">
                                                <div>
                                                    <input name="file[<?= $weight ?>][<?= $style ?>][<?= $format ?>]" type="file" />
                                                </div>
                                                <?php if (!empty($file) && !empty($file->fileId)) { ?>
                                                    <div style="padding-top: 10px;">
                                                        <input name="file[<?= $weight ?>][<?= $style ?>][<?= $format ?>][remove]" type="hidden" value="0" />
                                                        <input name="file[<?= $weight ?>][<?= $style ?>][<?= $format ?>][remove]" type="checkbox" value="1" />
                                                        <span><?= Loc::getMessage('buttons.remove') ?></span>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
        <?php } ?>
        <? $tabs->Buttons(['back_url' => $arUrlTemplates['fonts']]) ?>
        <? $tabs->End() ?>
    <?= Html::endForm() ?>
<?php } ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
