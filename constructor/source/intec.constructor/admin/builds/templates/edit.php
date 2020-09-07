<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php') ?>
<?php
IncludeModuleLangFile(__FILE__);

global $APPLICATION;

use intec\Core;
use intec\core\db\ActiveRecords;
use intec\core\collections\Arrays;
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

$sites = Arrays::fromDBResult(CSite::GetList($by = 'sort', $order = 'asc', [
    'ACTIVE' => 'Y'
]))->indexBy('ID');

$properties = Property::find()
    ->with(['enums'])
    ->where(['buildId' => $build->id])
    ->orderBy(['sort' => SORT_ASC])
    ->all();
/** @var ActiveRecords $properties */
$properties->indexBy('code');

$themes = $build->getThemes(true);
/** @var Theme[] $themes */

$template = $request->get('template');
$template = Template::find()
    ->where([
        'id' => $template,
        'buildId' => $build->id
    ])
    ->one();
/** @var Template $template */

if (empty($template)) {
    $template = new Template();
    $template->buildId = $build->id;
    $template->populateRelation('values', []);
}

/** @var ActiveRecords $values */
$values = $template->getValues(true);
$values->indexBy('propertyCode');

if ($template->getIsNewRecord()) {
    $APPLICATION->SetTitle(GetMessage('title.add', array(
        '#build#' => $build->name
    )));
} else {
    $APPLICATION->SetTitle(GetMessage('title.edit', array(
        '#name#' => $template->name,
        '#build#' => $build->name
    )));
}

if ($request->getIsPost()) {
    $return = $request->post('apply');
    $return = !empty($return) ? false : true;
    $post = $request->post();
    $data = ArrayHelper::getValue($post, $template->formName());
    $template->load($post);
    $template->condition = ArrayHelper::getValue($data, 'condition');

    if ($template->save()) {
        $dataProperties = ArrayHelper::getValue($data, 'properties');

        /** @var Property $property */
        foreach ($properties as $property) {
            /** @var Value $value */
            $value = $values->get($property->code);

            if (empty($value)) {
                $value = new Value();
                $value->buildId = $build->id;
                $value->propertyCode = $property->code;
                $value->templateId = $template->id;
            }

            $value->populateRelation('property', $property);
            $value->value = ArrayHelper::getValue($dataProperties, $property->code);
            $value->save();
        }

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
                    'template' => $template->id
                )
            )
        );
    } else {
        $error = ArrayHelper::getFirstValue($template->getFirstErrors());
    }
}

$data = array();
$data['code'] = $template->code;
$data['active'] = $template->active;
$data['default'] = $template->default;
$data['themeCode'] = $template->themeCode;
$data['name'] = $template->name;
$data['sort'] = $template->sort;
$data['properties'] = array();
$data['condition'] = $template->condition;

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

$condition = [];
$condition['operators'] = [];
$condition['logics'] = [];
$condition['types'] = [];

$operators = Template::getConditionOperators();
$logics = Template::getConditionLogics();
$types = Template::getConditionTypes();

foreach ($operators as $key => $operator)
    $condition['operators'][] = [
        'code' => $key,
        'name' => $operator
    ];

foreach ($logics as $key => $logic)
    $condition['logics'][] = [
        'code' => $key,
        'name' => $logic
    ];

foreach ($types as $key => $type) {
    if ($key === Template::CONDITION_TYPE_PARAMETER_TEMPLATE)
        continue;

    $condition['types'][] = [
        'code' => $key,
        'name' => $type
    ];
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
        ),
        array(
            'DIV' => 'properties',
            'TAB' => GetMessage('tabs.properties'),
            'TITLE' => GetMessage('tabs.properties')
        ),
        array(
            'DIV' => 'conditions',
            'TAB' => GetMessage('tabs.conditions'),
            'TITLE' => GetMessage('tabs.conditions')
        )
    )
);

Core::$app->web->css->addString('
    .conditions {
        display: block;
    }
    
    .conditions .condition {
        display: block;
        border: 1px solid #caced7;
        border-radius: 3px;
        background: #fdfefe;
        padding: 10px 15px 5px 15px;
        margin-bottom: 10px;
    }
    .conditions .condition.condition-0 {
        background: #f7f9fa;
    }
    .conditions .condition.condition-1 {
        background: #edf5f6;
    }
    .conditions .condition.condition-2 {
        background: #ddeaec;
    }
    
    .conditions .condition .condition-wrapper {
        display: block;
    }
    
    .conditions .condition .condition-logic {
        display: block;
        margin-bottom: 10px;
    }
    
    .conditions .condition .condition-logic .condition-operator,
    .conditions .condition .condition-logic .condition-result {
        display: inline-block;
        vertical-align: middle;
    }
    
    .conditions .condition .condition-logic .condition-operator {
        margin-right: 10px;
    }
    
    .conditions .condition .condition-logic .condition-result {
    
    }
    
    .conditions .condition .condition-content {
        display: block;
        margin-bottom: 10px;
    }
    
    .conditions .condition .condition-fields {
        display: block;
        overflow: hidden;
    }
    .conditions .condition .condition-fields .condition-fields-wrapper {
        margin: -3px -6px;
    }
    .conditions .condition .condition-field {
        display: block;
        float: left;
        width: 33.333333%;
        padding: 3px 6px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .conditions .condition .condition-field.condition-field-2 {
        width: 66.666666%;
    }
    .conditions .condition .condition-field.condition-field-3 {
        width: 100%;
    }
    .conditions .condition .condition-field .condition-field-wrapper {
        display: block;
    }
    .conditions .condition .condition-field .condition-field-title {
        display: block;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .conditions .condition .condition-field .condition-field-content {
        display: block;
    }
    
    .conditions .condition .condition-field .condition-field-content select,
    .conditions .condition .condition-field .condition-field-content input[type="text"] {
        width: 100%;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        height: 27px;
    }
    
    .conditions .condition .condition-footer {
        margin-bottom: 10px;
    }
    
    .conditions .condition .condition-settingable {
        color: #2c4b90;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
        border-bottom: 1px dashed #2c4b90;
    }
    .conditions .condition .condition-settingable:hover {
        border-color: transparent;
    }
')

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
                <?= Html::textInput($template->formName().'[code]', $template->code, array(
                    'data-bind' => '{ value: code }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('active') ?>:</td>
            <td width="60%">
                <?= Html::hiddenInput($template->formName().'[active]', 0) ?>
                <?= Html::checkbox($template->formName().'[active]', $template->active, array(
                    'value' => 1
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('default') ?>:</td>
            <td width="60%">
                <?= Html::hiddenInput($template->formName().'[default]', 0) ?>
                <?= Html::checkbox($template->formName().'[default]', $template->default, array(
                    'value' => 1
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><b><?= $template->getAttributeLabel('name') ?>:</b></td>
            <td width="60%">
                <?= Html::textInput($template->formName().'[name]', $template->name, array(
                    'data-bind' => '{ value: name }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('sort') ?>:</td>
            <td width="60%">
                <?= Html::textInput($template->formName().'[sort]', $template->sort, array(
                    'data-bind' => '{ value: sort }'
                )) ?>
            </td>
        </tr>
        <tr>
            <td width="40%"><?= $template->getAttributeLabel('themeCode') ?>:</td>
            <td width="60%">
                <?= Html::dropDownList($template->formName().'[themeCode]', $template->themeCode, ArrayHelper::merge(
                    array('' => GetMessage('template.fields.theme.unset')),
                    ArrayHelper::map($themes, 'code', 'name')
                ), array(
                    'data-bind' => '{ value: themeCode }'
                )) ?>
            </td>
        </tr>
    <?php $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2" width="100%">
                <?= Html::textarea($template->formName().'[css]', $template->css, array(
                    'data-bind' => 'bind: editors.css'
                )) ?>
            </td>
        </tr>
    <?php $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2">
                <?= Html::textarea($template->formName().'[less]', $template->less, array(
                    'data-bind' => 'bind: editors.less'
                )) ?>
                <!-- ko if: properties().length > 0 -->
                    <div style="margin-top: 10px;">
                        <div><?= GetMessage('template.fields.less.properties') ?>:</div>
                        <div style="font-weight: bold;">
                            <!-- ko foreach: properties -->
                                <a data-bind="text: name, click: addToEditor" style="cursor: pointer;"></a>
                            <!-- /ko -->
                        </div>
                    </div>
                <!-- /ko -->
            </td>
        </tr>
    <?php $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2" width="100%">
                <?= Html::textarea($template->formName().'[js]', $template->js, array(
                    'data-bind' => 'bind: editors.js'
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
    <?php $tabs->BeginNextTab() ?>
        <tr>
            <td colspan="2">
                <div class="conditions" data-bind="{
                    template: {
                        name: 'page-condition',
                        data: condition
                    }
                }"></div>
                <script id="page-condition" type="text/html">
                    <div class="condition" data-bind="{
                        css: 'condition-' + type() + ' condition-' + level() % 3
                    }">
                        <div class="condition-wrapper">
                            <input type="hidden" data-bind="{
                                attr: {
                                    name: attribute('type')
                                },
                                value: type
                            }" />
                            <!-- ko if: type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_GROUP) ?> -->
                                <div class="condition-logic">
                                    <div class="condition-operator">
                                        <select data-bind="{
                                            attr: {
                                                name: attribute('operator')
                                            },
                                            value: operator,
                                            options: $root.condition.operators,
                                            optionsText: 'name',
                                            optionsValue: 'code'
                                        }"></select>
                                    </div>
                                    <div class="condition-result">
                                        <select data-bind="{
                                            attr: {
                                                name: attribute('result')
                                            },
                                            value: result
                                        }">
                                            <option value="1"><?= GetMessage('template.condition.result.implemented') ?></option>
                                            <option value="0"><?= GetMessage('template.condition.result.unimplemented') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="conditions" data-bind="{
                                    template: {
                                        name: 'page-condition',
                                        foreach: conditions
                                    }
                                }"></div>
                                <div class="condition-footer">
                                    <!-- ko if: !adding.state() -->
                                        <a class="condition-settingable" data-bind="{
                                            click: function () { adding.state(true); }
                                        }"><?= GetMessage('template.condition.add') ?></a>
                                    <!-- /ko -->
                                    <!-- ko if: adding.state() -->
                                        <select data-bind="{
                                            options: $root.condition.types,
                                            optionsText: 'name',
                                            optionsValue: 'code',
                                            optionsCaption: <?= JavaScript::toObject(GetMessage('template.condition.type.unset')) ?>,
                                            value: adding.value
                                        }"></select>
                                        <a class="condition-settingable" style="margin-left: 7px;" data-bind="{
                                            click: function () { adding.state(false); }
                                        }"><?= GetMessage('template.condition.cancel') ?></a>
                                    <!-- /ko -->
                                    <!-- ko if: parent -->
                                        <a class="condition-settingable" style="margin-left: 7px;" data-bind="{
                                            click: remove
                                        }"><?= GetMessage('template.condition.remove') ?></a>
                                    <!-- /ko -->
                                </div>
                            <!-- /ko -->
                            <!-- ko if:
                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PATH) ?> ||
                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_MATCH) ?> ||
                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PARAMETER_GET) ?> ||
                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PARAMETER_PAGE) ?> ||
                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_EXPRESSION) ?> ||
                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_SITE) ?>
                            -->
                                <div class="condition-content">
                                    <div class="condition-fields">
                                        <div class="condition-fields-wrapper">
                                            <!-- ko if:
                                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PATH) ?> ||
                                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_EXPRESSION) ?>
                                            -->
                                                <div class="condition-field condition-field-2">
                                                    <div class="condition-field-wrapper">
                                                        <div class="condition-field-title">
                                                            <!-- ko if: type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PATH) ?> -->
                                                                <?= GetMessage('template.condition.type.path') ?>
                                                            <!-- /ko -->
                                                            <!-- ko if: type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_EXPRESSION) ?> -->
                                                                <?= GetMessage('template.condition.type.expression') ?>
                                                            <!-- /ko -->
                                                        </div>
                                                        <div class="condition-field-content">
                                                            <input type="text" data-bind="{
                                                                attr: {
                                                                    name: attribute('value')
                                                                },
                                                                value: value
                                                            }" />
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- /ko -->
                                            <!-- ko if: type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_MATCH) ?> -->
                                                <div class="condition-field">
                                                    <div class="condition-field-wrapper">
                                                        <div class="condition-field-title">
                                                            <?= GetMessage('template.condition.type.match') ?>
                                                        </div>
                                                        <div class="condition-field-content">
                                                            <input type="text" data-bind="{
                                                            attr: {
                                                                name: attribute('value')
                                                            },
                                                            value: value
                                                        }" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="condition-field">
                                                    <div class="condition-field-wrapper">
                                                        <div class="condition-field-title">
                                                            <?= GetMessage('template.condition.type.match.compare') ?>
                                                        </div>
                                                        <div class="condition-field-content">
                                                            <select data-bind="{
                                                                attr: {
                                                                    name: attribute('match')
                                                                },
                                                                value: match
                                                            }">
                                                                <?php $matches = Template::getConditionMatches() ?>
                                                                <?php foreach ($matches as $key => $match) { ?>
                                                                    <option value="<?= $key ?>"><?= $match ?></option>
                                                                <?php } ?>
                                                                <?php unset($matches) ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- /ko -->
                                            <!-- ko if:
                                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PARAMETER_GET) ?> ||
                                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PARAMETER_PAGE) ?>
                                            -->
                                                <div class="condition-field">
                                                    <div class="condition-field-wrapper">
                                                        <div class="condition-field-title">
                                                            <!-- ko if: type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PARAMETER_GET) ?> -->
                                                                <?= GetMessage('template.condition.type.parameter.get') ?>
                                                            <!-- /ko -->
                                                            <!-- ko if: type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PARAMETER_PAGE) ?> -->
                                                                <?= GetMessage('template.condition.type.parameter.page') ?>
                                                            <!-- /ko -->
                                                        </div>
                                                        <div class="condition-field-content">
                                                            <input type="text" data-bind="{
                                                                attr: {
                                                                    name: attribute('key')
                                                                },
                                                                value: key
                                                            }" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ko if: type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_PARAMETER_PAGE) ?> -->
                                                    <div class="condition-field">
                                                        <div class="condition-field-wrapper">
                                                            <div class="condition-field-title">
                                                                <?= GetMessage('template.condition.type.parameter.logic') ?>
                                                            </div>
                                                            <div class="condition-field-content">
                                                                <select data-bind="{
                                                                    attr: {
                                                                        name: attribute('logic')
                                                                    },
                                                                    value: logic,
                                                                    options: $root.condition.logics,
                                                                    optionsText: 'name',
                                                                    optionsValue: 'code'
                                                                }"></select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- /ko -->
                                                <div class="condition-field">
                                                    <div class="condition-field-wrapper">
                                                        <div class="condition-field-title">
                                                            <?= GetMessage('template.condition.type.parameter.value') ?>
                                                        </div>
                                                        <div class="condition-field-content">
                                                            <input type="text" data-bind="{
                                                                attr: {
                                                                    name: attribute('value')
                                                                },
                                                                value: value
                                                            }" />
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- /ko -->
                                            <!-- ko if:
                                                type() == <?= JavaScript::toObject(Template::CONDITION_TYPE_SITE) ?>
                                            -->
                                                <div class="condition-field">
                                                    <div class="condition-field-wrapper">
                                                        <div class="condition-field-title">
                                                            <?= GetMessage('template.condition.type.parameter.site') ?>
                                                        </div>
                                                        <div class="condition-field-content">
                                                            <select data-bind="{
                                                                attr: {
                                                                    name: attribute('value')
                                                                },
                                                                value: value
                                                            }">
                                                                <?php foreach ($sites as $site) { ?>
                                                                    <option value="<?= $site['ID'] ?>">
                                                                        <?= !empty($site['SITE_NAME']) ? '['.$site['ID'].'] '.$site['SITE_NAME'] : $site['ID'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- /ko -->
                                            <div class="condition-field condition-field-result">
                                                <div class="condition-field-wrapper">
                                                    <div class="condition-field-title"><?= GetMessage('template.condition.result') ?></div>
                                                    <div class="condition-field-content">
                                                        <select data-bind="{
                                                            attr: {
                                                                name: attribute('result')
                                                            },
                                                            value: result
                                                        }">
                                                            <option value="1"><?= GetMessage('template.condition.result.true') ?></option>
                                                            <option value="0"><?= GetMessage('template.condition.result.false') ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="clear: both"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="condition-footer">
                                    <a class="condition-settingable" data-bind="{
                                        click: remove
                                    }"><?= GetMessage('template.condition.remove') ?></a>
                                </div>
                            <!-- /ko -->
                        </div>
                    </div>
                </script>
            </td>
        </tr>
    <?php $tabs->Buttons(['back_url' => StringHelper::replaceMacros($arUrlTemplates['builds.templates'], array('build' => $build->id))]) ?>
    <?php $tabs->End() ?>
    <script type="text/javascript">
        (function ($, api) {
            window.page = {};

            var constructor = window.constructor();
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

                self.addToEditor = function () {
                    var editor = model.editors.less.getEditor();

                    if (!editor)
                        return;

                    var length = editor.doc.getSelections().length;
                    var selections = [];

                    for (var i = 0; i < length; i++)
                        selections.push('@' + self.code());

                    editor.doc.replaceSelections(selections);
                };

                self.attribute = ko.computed(function () {
                    return <?= JavaScript::toObject($template->formName().'[properties][') ?> + self.code() + ']';
                });
            };

            models.property.value = function (data, property) {
                var self = this;

                self.code = ko.computed(function () { return data.code; });
                self.name = ko.computed(function () { return data.name; });
            };

            model.code = ko.observable(data.code);
            model.themeCode = ko.observable(data.themeCode);
            model.name = ko.observable(data.name);
            model.sort = ko.observable(data.sort);
            model.properties = ko.computed(function () {
                var properties = [];

                api.each(data.properties, function (index, data) {
                    properties.push(new models.property(data));
                });

                return properties;
            });

            constructor.models.condition.on('created', function (event, self, data, manager) {
                self.adding = {};
                self.adding.state = ko.observable(false);
                self.adding.value = ko.observable(null);
                self.adding.value.subscribe(function (value) {
                    if (!api.isDeclared(value))
                        return;

                    self.conditions.push(new constructor.models.condition({
                        'parent': self,
                        'type': value
                    }));

                    self.adding.state(false);
                    self.adding.value(null);
                });

                self.attribute = function (name) {
                    if (self.parent()) {
                        return self.parent().attribute('conditions') +
                            '[' + self.parent().conditions.indexOf(self) +
                            '][' + name + ']'
                    } else {
                        return <?= JavaScript::toObject($template->formName().'[condition][') ?> + name + ']';
                    }
                };

                self.level = function () {
                    if (self.parent())
                        return self.parent().level() + 1;

                    return 0;
                }
            });

            model.condition = new constructor.models.condition(api.extend({}, data.condition, {
                'type': 'group'
            }));
            model.condition.operators = <?= JavaScript::toObject($condition['operators']) ?>;
            model.condition.logics = <?= JavaScript::toObject($condition['logics']) ?>;
            model.condition.types = <?= JavaScript::toObject($condition['types']) ?>;

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
        })(jQuery, intec)
    </script>
<?= Html::endForm() ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
