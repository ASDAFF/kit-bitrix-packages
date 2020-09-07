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
 * @var InnerTemplate $template
 */

$arTemplateParameters['SOCIAL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SOCIAL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SOCIAL_SHOW'] === 'Y') {
    $arTemplateParameters['SOCIAL_VK_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SOCIAL_VK_LINK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_FACEBOOK_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SOCIAL_FACEBOOK_LINK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_INSTAGRAM_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SOCIAL_INSTAGRAM_LINK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_TWITTER_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SOCIAL_TWITTER_LINK'),
        'TYPE' => 'STRING'
    ];
}