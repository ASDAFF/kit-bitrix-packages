<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplates;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;

if (!Loader::includeModule('intec.core'))
    return;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 */

Loc::loadMessages(__FILE__);

$arParts = [];

$component = new CBitrixComponent();
$template = new CBitrixComponentTemplate();
$component->initComponent($componentName);
$component->setTemplateName($templateName);
$template->Init($component, $siteTemplate);

$desktopTemplates = InnerTemplates::find($template, 'templates/desktop');
$desktopTemplate = InnerTemplates::findOne($template, 'templates/desktop', $arCurrentValues['DESKTOP']);
$fixedTemplates = InnerTemplates::find($template, 'templates/fixed');
$fixedTemplate = InnerTemplates::findOne($template, 'templates/fixed', $arCurrentValues['FIXED']);
$mobileTemplates = InnerTemplates::find($template, 'templates/mobile');
$mobileTemplate = InnerTemplates::findOne($template, 'templates/mobile', $arCurrentValues['MOBILE']);
$bannerTemplates = InnerTemplates::find($template, 'templates/banners');
$bannerTemplate = InnerTemplates::findOne($template, 'templates/banners', $arCurrentValues['BANNER']);

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];
$arTemplateParameters['TRANSPARENCY'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_TRANSPARENCY'),
    'TYPE' => 'CHECKBOX'
];

include(__DIR__.'/parameters/logotype.php');
include(__DIR__.'/parameters/phones.php');
include(__DIR__.'/parameters/address.php');
include(__DIR__.'/parameters/social.php');
include(__DIR__.'/parameters/email.php');
include(__DIR__.'/parameters/authorization.php');
include(__DIR__.'/parameters/tagline.php');
include(__DIR__.'/parameters/search.php');
include(__DIR__.'/parameters/basket.php');
include(__DIR__.'/parameters/menu.php');
include(__DIR__.'/parameters/forms.call.php');
include(__DIR__.'/parameters/regioinality.php');
include(__DIR__.'/parameters/menu.popup.php');

$arTemplateParameters['CATALOG_URL'] = [
    'NAME'  => Loc::getMessage('C_HEADER_TEMP1_CATALOG_URL'),
    'PARENT' => 'URL_TEMPLATES',
    'TYPE' => 'STRING'
];

$arTemplateParameters['DESKTOP'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP'),
    'TYPE' => 'LIST',
    'VALUES' => $desktopTemplates->asArray(function ($key, $template) {
        return [
            'key' => $key,
            'value' => '['.$key.'] '.$template->name
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($desktopTemplate)) {
    $arTemplateParameters = ArrayHelper::merge(
        $arTemplateParameters,
        $desktopTemplate->getParameters(
            $componentName,
            $templateName,
            $siteTemplate,
            $arCurrentValues
        )
    );
}

$arTemplateParameters['MOBILE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE'),
    'TYPE' => 'LIST',
    'VALUES' => $mobileTemplates->asArray(function ($key, $template) {
        return [
            'key' => $key,
            'value' => '['.$key.'] '.$template->name
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($mobileTemplate)) {
    $arTemplateParameters['MOBILE_FIXED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_FIXED'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters = ArrayHelper::merge(
        $arTemplateParameters,
        $mobileTemplate->getParameters(
            $componentName,
            $templateName,
            $siteTemplate,
            $arCurrentValues
        )
    );

    $arTemplateParameters['MOBILE_HIDDEN'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_HIDDEN'),
        'TYPE' => 'CHECKBOX'
    ];
}

$arTemplateParameters['FIXED'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_FIXED'),
    'TYPE' => 'LIST',
    'VALUES' => $fixedTemplates->asArray(function ($key, $template) {
        return [
            'key' => $key,
            'value' => '['.$key.'] '.$template->name
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($fixedTemplate)) {
    $arTemplateParameters = ArrayHelper::merge(
        $arTemplateParameters,
        $fixedTemplate->getParameters(
            $componentName,
            $templateName,
            $siteTemplate,
            $arCurrentValues
        )
    );
}

$arTemplateParameters['BANNER'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_BANNER'),
    'TYPE' => 'LIST',
    'VALUES' => $bannerTemplates->asArray(function ($key, $template) {
        return [
            'key' => $key,
            'value' => '['.$key.'] '.$template->name
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['BANNER_DISPLAY'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_BANNER_DISPLAY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'all' => Loc::getMessage('C_HEADER_TEMP1_BANNER_DISPLAY_ALL'),
        'main' => Loc::getMessage('C_HEADER_TEMP1_BANNER_DISPLAY_MAIN')
    ],
    'DEFAULT' => 'main'
];

if (!empty($bannerTemplate)) {
    $arTemplateParameters = ArrayHelper::merge(
        $arTemplateParameters,
        $bannerTemplate->getParameters(
            $componentName,
            $templateName,
            $siteTemplate,
            $arCurrentValues
        )
    );
}

foreach ($arParts as $sPart => $fHandler) {
    $oExpression = new RegExp('^'.RegExp::escape($sPart).'.*');
    $bHide = !(
        $arCurrentValues[$sPart.'_SHOW'] == 'Y' ||
        $arCurrentValues[$sPart.'_SHOW_FIXED'] == 'Y' ||
        $arCurrentValues[$sPart.'_SHOW_MOBILE'] == 'Y'
    );

    $bHide = $bHide || !(
        ArrayHelper::keyExists($sPart.'_SHOW', $arTemplateParameters) ||
        ArrayHelper::keyExists($sPart.'_SHOW_FIXED', $arTemplateParameters) ||
        ArrayHelper::keyExists($sPart.'_SHOW_MOBILE', $arTemplateParameters)
    );

    if ($bHide) {
        foreach ($arTemplateParameters as $sKey => $arTemplateParameter) {
            if (ArrayHelper::isIn($sKey, [
                $sPart.'_SHOW',
                $sPart.'_SHOW_FIXED',
                $sPart.'_SHOW_MOBILE'
            ])) continue;

            if ($oExpression->isMatch($sKey))
                unset($arTemplateParameters[$sKey]);
        }

        if ($fHandler instanceof Closure)
            $fHandler();
    }
}