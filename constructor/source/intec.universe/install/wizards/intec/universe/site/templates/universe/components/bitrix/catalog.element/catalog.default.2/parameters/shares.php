<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$arTemplateParameters['SHARES_SHOW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SHARES_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SHARES_SHOW'] == 'Y') {
    $sComponent = 'bitrix:catalog.socnets.buttons';
    $sPrefix = 'SHARES_';
    $sTemplate = 'template.1';

    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        $sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SHARES') . ' ' . $arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'FB_USE',
                'TW_USE',
                'TW_VIA',
                'TW_HASHTAGS',
                'TW_RELATED',
                'VK_USE',
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ));

    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        $sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SHARES') . ' ' . $arParameter['NAME'];

            return true;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    ));

}