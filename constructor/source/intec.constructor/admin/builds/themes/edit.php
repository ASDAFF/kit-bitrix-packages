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
use intec\constructor\models\Build;
use intec\constructor\models\build\Property;
use intec\constructor\models\build\Theme;
use intec\constructor\models\build\theme\Value;

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
$build = Build::find()
    ->where(['id' => $build])
    ->one();
/** @var Build $build */

if (empty($build))
    LocalRedirect($arUrlTemplates['builds']);

$properties = Property::find()
    ->with(['enums'])
    ->where(['buildId' => $build->id])
    ->orderBy(['sort' => SORT_ASC])
    ->all();
/** @var ActiveRecords $properties */
$properties->indexBy('code');

$theme = $request->get('theme');
$theme = Theme::find()
    ->with(['values'])
    ->where([
        'buildId' => $build->id,
        'code' => $theme
    ])
    ->one();
/** @var Theme $theme */

if (empty($theme)) {
    $theme = new Theme();
    $theme->buildId = $build->id;
    $theme->populateRelation('values', []);
}

/** @var ActiveRecords $values */
$values = $theme->getValues(true);
$values->indexBy('propertyCode');

if ($theme->getIsNewRecord()) {
    $APPLICATION->SetTitle(GetMessage('title.add', array(
        '#build#' => $build->name
    )));
} else {
    $APPLICATION->SetTitle(GetMessage('title.edit', array(
        '#name#' => $theme->name,
        '#build#' => $build->name
    )));
}

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $data = ArrayHelper::getValue($post, $theme->formName());
    $theme->load($post);

    if ($theme->save()) {
        $dataProperties = ArrayHelper::getValue($data, 'properties');

        /** @var Property $property */
        foreach ($properties as $property) {
            /** @var Value $value */
            $value = $values->get($property->code);

            if (empty($value)) {
                $value = new Value();
                $value->buildId = $build->id;
                $value->propertyCode = $property->code;
                $value->themeCode = $theme->code;
            }

            $value->populateRelation('property', $property);
            $value->value = ArrayHelper::getValue($dataProperties, $property->code);
            $value->save();
        }

        if ($return)
            LocalRedirect(
                StringHelper::replaceMacros(
                    $arUrlTemplates['builds.themes'],
                    array(
                        'build' => $build->id
                    )
                )
            );

        LocalRedirect(
            StringHelper::replaceMacros(
                $arUrlTemplates['builds.themes.edit'],
                array(
                    'build' => $build->id,
                    'theme' => $theme->code
                )
            )
        );
    } else {
        $error = ArrayHelper::getFirstValue($theme->getFirstErrors());
    }
}

$data = array();
$data['code'] = $theme->code;
$data['name'] = $theme->name;
$data['sort'] = $theme->sort;
$data['properties'] = array();

/** @var Property $property */
foreach ($properties as $property) {
    /** @var Value $value */
    $value = $values->get($property->code);
    $dataValues = array();

    if ($property->type == Property::TYPE_ENUM) {
        $enums = $property->getEnums(true);
        /** @var ActiveRecords $enums */
        $enums->sortBy('sort');

        foreach ($enums as $enum)
            $dataValues[] = [
                'code' => $enum->code,
                'name' => $enum->name
            ];
    }

    $data['properties'][] = array(
        'code' => $property->code,
        'name' => $property->name,
        'type' => $property->type,
        'value' => $value !== null ? $value->value : $property->default,
        'values' => $dataValues
    );
}

$menuNavigation = new CAdminContextMenu(array(
    array(
        'TEXT' => GetMessage('menu.back'),
        'ICON' => 'btn_list',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.themes'],
            array(
                'build' => $build->id
            )
        )
    ),
    array(
        'TEXT' => GetMessage('menu.add'),
        'ICON' => 'btn_new',
        'LINK' => StringHelper::replaceMacros(
            $arUrlTemplates['builds.themes.add'],
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
            'DIV' => 'properties',
            'TAB' => GetMessage('tabs.properties'),
            'TITLE' => GetMessage('tabs.properties')
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
            <td width="40%"><b><?= $theme->getAttributeLabel('code') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($theme->formName().'[code]', $theme->code, array(
                    'data-bind' => '{ value: code }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $theme->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($theme->formName().'[name]', $theme->name, array(
                    'data-bind' => '{ value: name }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $theme->getAttributeLabel('sort') ?>:</td>
            <td width="60%">
                <?= Html::textInput($theme->formName().'[sort]', $theme->sort, array(
                    'data-bind' => '{ value: sort }'
                )) ?>
            </td>
        </tr>
    <?php $tabs->BeginNextTab() ?>
        <!-- ko foreach: properties -->
            <tr>
                <td width="40%" data-bind="{ text: name() + ':' }"></td>
                <td width="60%">
                    <!-- ko if: type() == <?= JavaScript::toObject(Property::TYPE_BOOLEAN) ?> -->
                        <?= Html::hiddenInput(null, 0, array(
                            'data-bind' => '{
                               attr: {
                                   name: attribute
                               }
                            }',
                        )) ?>
                        <?= Html::checkbox(null, false, array(
                            'data-bind' => '{
                               checked: value,
                               attr: {
                                   name: attribute
                               }
                            }',
                            'value' => 1
                        )) ?>
                    <!-- /ko -->
                    <!-- ko if:
                        type() == <?= JavaScript::toObject(Property::TYPE_STRING) ?> ||
                        type() == <?= JavaScript::toObject(Property::TYPE_INTEGER) ?> ||
                        type() == <?= JavaScript::toObject(Property::TYPE_FLOAT) ?>
                    -->
                        <?= Html::textInput(null, null, array(
                            'data-bind' => '{ 
                                value: value,
                                attr: {
                                    name: attribute
                                }
                            }'
                        )) ?>
                    <!-- /ko -->
                    <!-- ko if: type() == <?= JavaScript::toObject(Property::TYPE_ENUM) ?> -->
                        <?= Html::dropDownList(null, null, array(), array(
                            'data-bind' => '{ 
                                value: value,
                                attr: {
                                    name: attribute
                                },
                                options: values,
                                optionsText: \'name\',
                                optionsValue: \'code\',
                                optionsCaption: '.JavaScript::toObject(GetMessage('theme.properties.values.unset')).',
                            }'
                        )) ?>
                    <!-- /ko -->
                    <!-- ko if: type() == <?= JavaScript::toObject(Property::TYPE_COLOR) ?> -->
                        <?= Html::textInput(null, $property->default, array(
                            'data-bind' => '{
                                value: value,
                                attr: {
                                    name: attribute
                                }
                            }'
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
        <!-- /ko -->
    <?php $tabs->Buttons(['back_url' => StringHelper::replaceMacros($arUrlTemplates['builds.themes'], array('build' => $build->id))]) ?>
    <?php $tabs->End() ?>
    <script type="text/javascript">
        (function ($, api) {
            var root = $('#page');
            var data = <?= JavaScript::toObject($data); ?>;
            var models = {};
            var model = {};

            models.property = function (data) {
                var self = this;

                self.code = ko.computed(function () { return data.code; });
                self.name = ko.computed(function () { return data.name; });
                self.type = ko.computed(function () { return data.type; });
                self.value = ko.observable(data.value);
                self.values = ko.computed(function () {
                    var values = [];

                    api.each(data.values, function (index, data) {
                        values.push(new models.property.value(data));
                    });

                    return values;
                });

                self.attribute = ko.computed(function () {
                    return <?= JavaScript::toObject($theme->formName().'[properties][') ?> + self.code() + ']';
                });
            };

            models.property.value = function (data, property) {
                var self = this;

                self.code = ko.computed(function () { return data.code; });
                self.name = ko.computed(function () { return data.name; });
            };

            model.code = ko.observable(data.code);
            model.name = ko.observable(data.name);
            model.sort = ko.observable(data.sort);
            model.properties = ko.computed(function () {
                var properties = [];

                api.each(data.properties, function (index, data) {
                    properties.push(new models.property(data));
                });

                return properties;
            });

            ko.applyBindings(model, root.get(0));
        })(jQuery, intec)
    </script>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
