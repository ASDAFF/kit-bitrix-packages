<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arCurrentValues
 * @var array $arParametersCommon
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$sComponent = 'bitrix:catalog.element';
$sTemplate = 'catalog.';

$arTemplates = Arrays::from(CComponentUtil::GetTemplatesList(
    $sComponent,
    $siteTemplate
))->asArray(function ($iIndex, $arTemplate) use (&$sTemplate) {
    if (!StringHelper::startsWith($arTemplate['NAME'], $sTemplate))
        return ['skip' => true];

    $sName = StringHelper::cut(
        $arTemplate['NAME'],
        StringHelper::length($sTemplate)
    );

    return [
        'key' => $sName,
        'value' => $sName
    ];
});

$sPrefix = 'DETAIL_';
$sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
$sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

if (!empty($sTemplate))
    $sTemplate = 'catalog.'.$sTemplate;

$arTemplateParameters[$sPrefix.'TEMPLATE'] = [
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters[$sPrefix.'MENU_SHOW'] = [
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'MENU_SHOW'),
    'TYPE' => 'CHECKBOX'
];

if (!empty($sTemplate)) {
    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        $sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($sKey, &$arParameter) use (&$sLevel, &$arParametersCommon) {
            if (StringHelper::startsWith($sKey, 'ORDER_FAST_'))
                return false;

            if (ArrayHelper::isIn($sKey, $arParametersCommon))
                return false;

            $arParameter['PARENT'] = 'DETAIL_SETTINGS';
            $arParameter['NAME'] = Loc::getMessage('C_CATALOG_CATALOG_1_DETAIL').'. '.$arParameter['NAME'];

            return true;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    ));
}