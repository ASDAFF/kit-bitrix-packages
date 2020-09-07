<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 * @var array $arParts
 * @var InnerTemplate $desktopTemplate
 * @var InnerTemplate $fixedTemplate
 * @var InnerTemplate $mobileTemplate
 */

$sSite = false;

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$arMenuTypes = GetMenuTypes($sSite);
$arParts['MENU_MAIN'] = null;

if (!empty($desktopTemplate)) {
    $arTemplateParameters['MENU_MAIN_SHOW'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_MENU_MAIN_SHOW'),
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($fixedTemplate)) {
    $arTemplateParameters['MENU_MAIN_SHOW_FIXED'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_MENU_MAIN_SHOW_FIXED'),
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['MENU_MAIN_SHOW_MOBILE'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_MENU_MAIN_SHOW_MOBILE'),
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['MENU_MAIN_ROOT'] = [
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MENU_MAIN_ROOT'),
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'DEFAULT ' => 'left',
    'VALUES' => $arMenuTypes,
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['MENU_MAIN_CHILD'] = [
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MENU_MAIN_CHILD'),
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'DEFAULT ' => 'left',
    'VALUES' => $arMenuTypes,
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['MENU_MAIN_LEVEL'] = [
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MENU_MAIN_LEVEL'),
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'DEFAULT ' => 1,
    'VALUES' => [
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
    ],
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'bitrix:menu',
    [
        'horizontal.1'
    ],
    $siteTemplate,
    $arCurrentValues,
    'MENU_MAIN_',
    function ($sKey, &$arParameter) {
        $arParameter['NAME'] = Loc::getMessage('C_HEADER_TEMP1_MENU_MAIN').' '.$arParameter['NAME'];

        if ($sKey === 'TRANSPARENT')
            return false;

        return true;
    },
    Component::PARAMETERS_MODE_TEMPLATE
));