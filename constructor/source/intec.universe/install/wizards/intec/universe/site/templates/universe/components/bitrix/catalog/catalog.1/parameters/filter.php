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

if ($arCurrentValues['USE_FILTER'] === 'Y') {
    $sType = ArrayHelper::fromRange([
        'horizontal',
        'vertical'
    ], $arCurrentValues['FILTER_TYPE']);

    $sPrefix = 'FILTER_';
    $sComponent = 'bitrix:catalog.smart.filter';
    $sTemplate = $sType.'.';

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
        $sTemplate = $sType.'.'.$sTemplate;

    $arTemplateParameters[$sPrefix.'AJAX'] = [
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'AJAX'),
        'TYPE' => 'CHECKBOX'
    ];

    $arTemplateParameters[$sPrefix.'TYPE'] = [
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'horizontal' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'TYPE_HORIZONTAL'),
            'vertical' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'TYPE_VERTICAL')
        ],
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters[$sPrefix.'TEMPLATE'] = [
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'REFRESH' => 'Y'
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

                $arParameter['PARENT'] = 'FILTER_SETTINGS';
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_CATALOG_1_FILTER').'. '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }
}