<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\GroupTable;
use Bitrix\Catalog\GroupLangTable;
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

$sComponent = 'bitrix:catalog.section';
$sTemplate = 'services.';

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

foreach (['ROOT', 'CHILDREN'] as $sLevel) {
    $sPrefix = 'LIST_'.$sLevel.'_';
    $sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
    $sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

    if (!empty($sTemplate))
        $sTemplate = 'services.'.$sTemplate;

    if ($sLevel === 'ROOT') {
        $arTemplateParameters[$sPrefix.'SHOW'] = [
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'SHOW'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues[$sPrefix.'SHOW'] !== 'Y')
            continue;
    }

    $arTemplateParameters[$sPrefix.'TEMPLATE'] = [
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters[$sPrefix.'POSITION'] = [
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'top' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'POSITION_TOP'),
            'bottom' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'POSITION_BOTTOM')
        ],
        'REFRESH' => 'Y'
    ];

    if (!empty($sTemplate)) {
        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            $sComponent,
            $sTemplate,
            $siteTemplate,
            $arCurrentValues,
            $sPrefix,
            function ($sKey, &$arParameter) use (&$sLevel) {
                $arParameter['PARENT'] = 'LIST_SETTINGS';
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_1_LIST_'.$sLevel).'. '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }
}