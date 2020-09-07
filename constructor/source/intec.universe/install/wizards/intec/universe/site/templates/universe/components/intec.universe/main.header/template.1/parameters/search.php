<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

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

$arParts['SEARCH'] = null;

if (!empty($desktopTemplate)) {
    $arTemplateParameters['SEARCH_SHOW'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_SEARCH_SHOW'),
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX'
    ];
}

if (!empty($fixedTemplate)) {
    $arTemplateParameters['SEARCH_SHOW_FIXED'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_SEARCH_SHOW_FIXED'),
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['SEARCH_SHOW_MOBILE'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_SEARCH_SHOW_MOBILE'),
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX'
    ];
}

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'bitrix:search.title',
    [
        'input.1',
        'popup.1'
    ],
    $siteTemplate,
    $arCurrentValues,
    'SEARCH_',
    function ($sKey, &$arParameter) {
        $arParameter['NAME'] = Loc::getMessage('C_HEADER_TEMP1_SEARCH').' '.$arParameter['NAME'];

        if (StringHelper::startsWith($sKey, 'CACHE'))
            return false;

        if (StringHelper::startsWith($sKey, 'COMPOSITE'))
            return false;

        if ($sKey == 'PAGE')
            return false;

        return true;
    }
));

$arTemplateParameters['SEARCH_MODE'] = [
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_SEARCH_MODE'),
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'VALUES' => [
        'site' => Loc::getMessage('C_HEADER_TEMP1_SEARCH_MODE_SITE'),
        'catalog' => Loc::getMessage('C_HEADER_TEMP1_SEARCH_MODE_CATALOG')
    ]
];

$arTemplateParameters['SEARCH_URL'] = [
    'NAME'  => Loc::getMessage('C_HEADER_TEMP1_SEARCH_URL'),
    'PARENT' => 'URL_TEMPLATES',
    'TYPE' => 'STRING'
];