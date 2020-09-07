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

$sSite = false;

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$sPrefix = 'MENU_';
$sComponent = 'bitrix:menu';
$sTemplate = 'vertical.';

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

$sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
$sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

if (!empty($sTemplate))
    $sTemplate = 'vertical.'.$sTemplate;

$arMenuTypes = GetMenuTypes($sSite);
$arTemplateParameters[$sPrefix.'TEMPLATE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters[$sPrefix.'ROOT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'ROOT'),
    'TYPE' => 'LIST',
    'VALUES' => $arMenuTypes,
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters[$sPrefix.'CHILD'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'CHILD'),
    'TYPE' => 'LIST',
    'VALUES' => $arMenuTypes,
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters[$sPrefix.'LEVEL'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'LEVEL'),
    'TYPE' => 'LIST',
    'VALUES' => [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4'
    ],
    'ADDITIONAL_VALUES' => 'Y'
];

if (!empty($sTemplate)) {
    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        $sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($sKey, &$arParameter) use (&$sLevel, &$arParametersCommon) {
            if (ArrayHelper::isIn($sKey, $arParametersCommon))
                return false;

            $arParameter['NAME'] = Loc::getMessage('C_CATALOG_CATALOG_1_MENU').'. '.$arParameter['NAME'];

            return true;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    ));
}