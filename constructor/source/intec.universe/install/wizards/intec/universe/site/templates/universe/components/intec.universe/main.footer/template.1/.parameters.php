<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplates;
use intec\core\helpers\ArrayHelper;

if (!Loader::includeModule('intec.core'))
    return;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 */

Loc::loadMessages(__FILE__);

$component = new CBitrixComponent();
$template = new CBitrixComponentTemplate();
$component->initComponent($componentName);
$component->setTemplateName($templateName);
$template->Init($component, $siteTemplate);

$templates = InnerTemplates::find($template, 'templates');
$template = InnerTemplates::findOne($template, 'templates', $arCurrentValues['TEMPLATE']);

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

if (Loader::includeModule('sale')) {
    $arTemplateParameters['PRODUCTS_VIEWED_SHOW'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_PRODUCTS_VIEWED_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['PRODUCTS_VIEWED_SHOW'] === 'Y')
        include(__DIR__.'/parameters/products.viewed.php');
}

include(__DIR__.'/parameters/regioinality.php');
include(__DIR__.'/parameters/contacts.php');
include(__DIR__.'/parameters/copyright.php');
include(__DIR__.'/parameters/forms.php');
include(__DIR__.'/parameters/menu.php');
include(__DIR__.'/parameters/search.php');
include(__DIR__.'/parameters/social.php');

$arTemplateParameters['THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'light' => Loc::getMessage('C_FOOTER_TEMPLATE_1_THEME_LIGHT'),
        'dark' => Loc::getMessage('C_FOOTER_TEMPLATE_1_THEME_DARK')
    ],
    'REFRESH' => 'Y'
];

$arTemplateParameters['TEMPLATE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $templates->asArray(function ($key, $template) {
        return [
            'key' => $key,
            'value' => '['.$key.'] '.$template->name
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($template)) {
    $arTemplateParameters = ArrayHelper::merge(
        $arTemplateParameters,
        $template->getParameters(
            $componentName,
            $templateName,
            $siteTemplate,
            $arCurrentValues
        )
    );
}