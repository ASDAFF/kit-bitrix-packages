<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$sPrefix = 'QUICK_VIEW_';
$arTemplateParameters[$sPrefix.'USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUICK_VIEW_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'DEFAULT' => 'N'
];
$arTemplateParameters[$sPrefix.'DETAIL'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUICK_VIEW_DETAIL'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues[$sPrefix.'USE'] === 'Y') {
    $sComponent = 'bitrix:catalog.element';
    $sTemplate = 'quick.view.';

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
        $sTemplate = 'quick.view.'.$sTemplate;

    $arTemplateParameters[$sPrefix.'TEMPLATE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUICK_VIEW_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['IBLOCK_ID'])) {
        $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['sort' => 'asc'], [
            'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
            'ACTIVE' => 'Y'
        ]));

        $arTemplateParameters[$sPrefix . 'PROPERTY_CODE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUICK_VIEW_PROPERTY_CODE'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'ADDITIONAL_VALUES' => 'Y',
            'VALUES' => $arProperties->asArray(function ($iIndex, $arProperty) {
                $sCode = $arProperty['CODE'];

                if (empty($sCode))
                    $sCode = $arProperty['ID'];

                return [
                    'key' => $sCode,
                    'value' => '['.$sCode.'] '.$arProperty['NAME']
                ];
            })
        ];
    }

    if (!empty($sTemplate)) {
        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            $sComponent,
            $sTemplate,
            $siteTemplate,
            $arCurrentValues,
            $sPrefix,
            function ($sKey, &$arParameter) {
                $arParameter['PARENT'] = 'LIST_SETTINGS';
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUICK_VIEW').'. '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }
}