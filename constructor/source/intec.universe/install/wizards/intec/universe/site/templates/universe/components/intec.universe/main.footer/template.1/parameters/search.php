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
 */

$arTemplateParameters['SEARCH_SHOW'] = [
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SEARCH_SHOW'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SEARCH_SHOW'] === 'Y') {
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
            $arParameter['NAME'] = Loc::getMessage('C_FOOTER_TEMPLATE_1_SEARCH').'. '.$arParameter['NAME'];

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
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SEARCH_MODE'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'VALUES' => [
            'site' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SEARCH_MODE_SITE'),
            'catalog' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SEARCH_MODE_CATALOG')
        ]
    ];

    $arTemplateParameters['CATALOG_URL'] = [
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CATALOG_URL'),
        'PARENT' => 'URL_TEMPLATES',
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SEARCH_URL'] = [
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_SEARCH_URL'),
        'PARENT' => 'URL_TEMPLATES',
        'TYPE' => 'STRING'
    ];
}