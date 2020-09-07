<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;

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

$arParts['ADDRESS'] = null;

if (!empty($desktopTemplate)) {
    $arTemplateParameters['ADDRESS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_ADDRESS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($fixedTemplate)) {
    $arTemplateParameters['ADDRESS_SHOW_FIXED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_ADDRESS_SHOW_FIXED'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['ADDRESS_SHOW_MOBILE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_ADDRESS_SHOW_MOBILE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['ADDRESS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_ADDRESS'),
    'TYPE' => 'STRING'
];