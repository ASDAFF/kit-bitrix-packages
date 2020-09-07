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

$arParts['SOCIAL'] = null;

if (!empty($desktopTemplate)) {
    $arTemplateParameters['SOCIAL_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_SOCIAL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['SOCIAL_SHOW_MOBILE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_SOCIAL_SHOW_MOBILE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['SOCIAL_VK'] = [
    'PARENT' => 'DATA_SOURCE',
    'TYPE' => 'STRING',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_SOCIAL_VK')
];

$arTemplateParameters['SOCIAL_INSTAGRAM'] = [
    'PARENT' => 'DATA_SOURCE',
    'TYPE' => 'STRING',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_SOCIAL_INSTAGRAM')
];

$arTemplateParameters['SOCIAL_FACEBOOK'] = [
    'PARENT' => 'DATA_SOURCE',
    'TYPE' => 'STRING',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_SOCIAL_FACEBOOK')
];

$arTemplateParameters['SOCIAL_TWITTER'] = [
    'PARENT' => 'DATA_SOURCE',
    'TYPE' => 'STRING',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_SOCIAL_TWITTER')
];