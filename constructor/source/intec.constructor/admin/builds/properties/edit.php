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

$property = $request->get('property');
$property = Property::find()
    ->with(['enums'])
    ->where([
        'buildId' => $build->id,
        'code' => $property
    ])
    ->one();
/** @var Property $property */

if (empty($property)) {
    $property = new Property();
    $property->buildId = $build->id;
}

if ($property->getIsNewRecord()) {
    $APPLICATION->SetTitle(GetMessage('title.add', array(
        '#build#' => $build->name
    )));
} else {
    $APPLICATION->SetTitle(GetMessage('title.edit', array(
        '#name#' => $property->name,
        '#build#' => $build->name
    )));
}

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $data = ArrayHelper::getValue($post, $property->formName());
    $property->load($post);

    if ($property->validate()) {
        /** @var ActiveRecords $enums */
        $enum = null;
        /** @var Enum[] $enumsNew */
        $enumsNew = null;

        if ($property->type == Property::TYPE_ENUM) {
            $enums = $property->getEnums(true);
            $enums->indexBy('code');
            $enumsNew = new ActiveRecords();
            $items = ArrayHelper::getValue($data, 'enums');

            if (Type::isArray($items))
                foreach ($items as $item) {
                    $code = ArrayHelper::getValue($item, 'code');
                    $name = ArrayHelper::getValue($item, 'name');
                    $sort = ArrayHelper::getValue($item, 'sort');

                    if (empty($code) || empty($name))
                        continue;

                    $enum = $enums->get($code);

                    if (empty($enum)) {
                        $enum = new Enum();
                        $enum->buildId = $build->id;
                        $enum->propertyCode = $property->code;
                        $enum->code = $code;
                    }

                    $isNew = $enum->getIsNewRecord();
                    $enum->name = $name;
                    $enum->sort = $sort;

                    if ($enum->validate())
                        $enumsNew->set($enum->code, $enum);
                }

            $property->populateRelation('enums', $enumsNew->asArray());
        }

        $property->save(false);

        if ($property->type == Property::TYPE_ENUM) {
            foreach ($enumsNew as $enum)
                $enum->save(false);

            /** @var Enum $enum */
            foreach ($enums as $enum)
                if (!$enumsNew->exists($enum->code))
                    $enum->delete();
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
                $arUrlTemplates['builds.properties.edit'],
                array(
                    'build' => $build->id,
                    'property' => $property->code
                )
            )
        );
    } else {
        $error = ArrayHelper::getFirstValue($property->getFirstErrors());
    }
}

$data = array();
$data['code'] = $property->code;
$data['name'] = $property->name;
$data['sort'] = $property->sort;
$data['type'] = $property->type;
$data['value'] = $property->default;
$data['values'] = array();

if ($property->type == Property::TYPE_ENUM) {
    $enums = $property->getEnums(true);

    foreach ($enums as $enum)
        $data['values'][] = array(
            'code' => $enum->code,
            'name' => $enum->name,
            'sort' => $enum->sort
        );
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
        ),
        array(
            'DIV' => 'values',
            'TAB' => GetMessage('tabs.values'),
            'TITLE' => GetMessage('tabs.values')
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
        <tr>
            <td width="40%"><b><?= $property->getAttributeLabel('code') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($property->formName().'[code]', $property->code, array(
                    'data-bind' => '{ value: code }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $property->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($property->formName().'[name]', $property->name, array(
                    'data-bind' => '{ value: name }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $property->getAttributeLabel('sort') ?>:</td>
            <td width="60%">
                <?= Html::textInput($property->formName().'[sort]', $property->sort, array(
                    'data-bind' => '{ value: sort }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $property->getAttributeLabel('type') ?>:</b></td>
            <td width="60%">
                <?= Html::dropDownList($property->formName().'[type]', $property->type, Property::getTypes(), array(
                    'data-bind' => '{ value: type }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $property->getAttributeLabel('default') ?>:</td>
            <td width="60%">
                <!-- ko if: type() == <?= JavaScript::toObject(Property::TYPE_BOOLEAN) ?> -->
                    <?= Html::hiddenInput($property->formName().'[default]', 0) ?>
                    <?= Html::checkbox($property->formName().'[default]', $property->default === 1, array(
                        'data-bind' => '{ checked: value }',
                        'value' => 1
                    )) ?>
                <!-- /ko -->
                <!-- ko if:
                    type() == <?= JavaScript::toObject(Property::TYPE_STRING) ?> ||
                    type() == <?= JavaScript::toObject(Property::TYPE_INTEGER) ?> ||
                    type() == <?= JavaScript::toObject(Property::TYPE_FLOAT) ?>
                -->
                    <?= Html::textInput($property->formName().'[default]', $property->default, array(
                        'data-bind' => '{ value: value }'
                    )) ?>
                <!-- /ko -->
                <!-- ko if: type() == <?= JavaScript::toObject(Property::TYPE_ENUM) ?> -->
                    <?= Html::dropDownList($property->formName().'[default]', null, array(), array(
                        'data-bind' => '{ 
                            value: value,
                            options: values,
                            optionsText: \'name\',
                            optionsValue: \'code\',
                            optionsCaption: \''.GetMessage('property.value.unset').'\',
                        }'
                    )) ?>
                <!-- /ko -->
                <!-- ko if: type() == <?= JavaScript::toObject(Property::TYPE_COLOR) ?> -->
                    <?= Html::textInput($property->formName().'[default]', $property->default, array(
                        'data-bind' => '{ value: value }'
                    )) ?>
                    <div data-bind="{
                        bind: ko.models.colorpicker({}, value),
                        style: {
                            backgroundColor: value
                        }
                    }" style="
                        display: inline-block;
                        height: 27px;
                        width: 27px;
                        border: 1px solid #e1e1e1;
                        border-radius: 5px;
                        vertical-align: middle;
                        overflow: hidden;
                        -webkit-box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        box-sizing: border-box;
                    "></div>
                <!-- /ko -->
            </td>
        </tr>
    <? $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2">
                <div class="adm-list-table-wrap adm-list-table-without-header adm-list-table-without-footer">
                    <table class="adm-list-table">
                        <thead>
                        <tr class="adm-list-table-header">
                            <td class="adm-list-table-cell">
                                <div class="adm-list-table-cell-inner">
                                    <?= GetMessage('property.values.fields.code') ?>
                                </div>
                            </td>
                            <td class="adm-list-table-cell">
                                <div class="adm-list-table-cell-inner">
                                    <?= GetMessage('property.values.fields.name') ?>
                                </div>
                            </td>
                            <td class="adm-list-table-cell">
                                <div class="adm-list-table-cell-inner">
                                    <?= GetMessage('property.values.fields.sort') ?>
                                </div>
                            </td>
                            <td class="adm-list-table-cell">
                                <div class="adm-list-table-cell-inner">
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                            <!-- ko if: values().length > 0 -->
                                <!-- ko foreach: values -->
                                    <tr class="adm-list-table-row">
                                        <td class="adm-list-table-cell">
                                            <div class="adm-list-table-cell-inner">
                                                <input type="text" data-bind="{
                                                    value: code,
                                                    attr: {
                                                        name: attribute('code')
                                                    }
                                                }" />
                                            </div>
                                        </td>
                                        <td class="adm-list-table-cell">
                                            <div class="adm-list-table-cell-inner">
                                                <input type="text" data-bind="{
                                                    value: name,
                                                    attr: {
                                                        name: attribute('name')
                                                    }
                                                }" />
                                            </div>
                                        </td>
                                        <td class="adm-list-table-cell">
                                            <div class="adm-list-table-cell-inner">
                                                <input type="text" data-bind="{
                                                    value: sort,
                                                    attr: {
                                                        name: attribute('sort')
                                                    }
                                                }" />
                                            </div>
                                        </td>
                                        <td class="adm-list-table-cell">
                                            <div class="adm-list-table-cell-inner">
                                                <a class="adm-btn" data-bind="click: remove"><?= GetMessage('property.values.delete') ?></a>
                                            </div>
                                        </td>
                                    </tr>
                                <!-- /ko -->
                            <!-- /ko -->
                            <!-- ko if: values().length == 0 -->
                                <tr class="adm-list-table-row">
                                    <td class="adm-list-table-cell" colspan="5">
                                        <div class="adm-list-table-cell-inner" style="text-align: center;">
                                            <?= GetMessage('property.values.empty') ?>
                                        </div>
                                    </td>
                                </tr>
                            <!-- /ko -->
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 10px;">
                    <a class="adm-btn adm-btn-save" data-bind="click: function () { values.add(); return true; }"><?= GetMessage('property.values.add') ?></a>
                </div>
            </td>
        </tr>
    <? $tabs->Buttons(['back_url' => StringHelper::replaceMacros($arUrlTemplates['builds.properties'], array('build' => $build->id))]) ?>
    <? $tabs->End() ?>
    <script type="text/javascript">
        (function ($, api) {
            var root = $('#page');
            var data = <?= JavaScript::toObject($data); ?>;
            var models = {};
            var model = {};

            models.value = function (data) {
                var self = this;

                self.code = ko.observable(data.code);
                self.name = ko.observable(data.name);
                self.sort = ko.observable(data.sort);
                self.sort.subscribe(function () {
                    model.values.resort();
                });

                self.remove = function () {
                    model.values.remove(self);
                };

                self.attribute = function (name) {
                    return <?= JavaScript::toObject($property->formName().'[enums][') ?> + model.values.indexOf(self) + '][' + name + ']';
                }
            };

            model.code = ko.observable(data.code);
            model.name = ko.observable(data.name);
            model.sort = ko.observable(data.sort);
            model.type = ko.observable();
            model.type.subscribe(function (value) {
                 if (value == <?= JavaScript::toObject(Property::TYPE_ENUM) ?>) {
                     tabs.EnableTab('values');
                 } else {
                     tabs.DisableTab('values');
                     tabs.SelectTab('common');
                 }
            });
            model.type(data.type);
            model.value = ko.observable(data.value);
            model.values = ko.observableArray([]);
            model.values.resort = function () {
                model.values.sort(function (value1, value2) {
                    if (value1.sort() > value2.sort()) return 1;
                    if (value1.sort() < value2.sort()) return -1;
                    return 0;
                });
            };
            model.values.add = function (data) {
                var value;

                data = api.extend({}, data);
                value = new models.value(data);
                model.values.push(value);
                model.values.resort();

                return value;
            };

            api.each(data.values, function (index, data) {
                model.values.add(data);
            });

            ko.applyBindings(model, root.get(0));
        })(jQuery, intec)
    </script>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
