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

$sComponent = 'bitrix:catalog.section.list';
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
    $sPrefix = 'SECTIONS_'.$sLevel.'_';
    $sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
    $sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

    if (!empty($sTemplate))
        $sTemplate = 'services.'.$sTemplate;

    $arTemplateParameters[$sPrefix.'SECTION_DESCRIPTION_SHOW'] = [
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'SECTION_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues[$sPrefix.'SECTION_DESCRIPTION_SHOW'] === 'Y') {
        $arTemplateParameters[$sPrefix.'SECTION_DESCRIPTION_POSITION'] = [
            'PARENT' => 'SECTIONS_SETTINGS',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'SECTION_DESCRIPTION_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'top' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'SECTION_DESCRIPTION_POSITION_TOP'),
                'bottom' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'SECTION_DESCRIPTION_POSITION_BOTTOM')
            ]
        ];
    }

    $arTemplateParameters[$sPrefix.'CANONICAL_URL_USE'] = [
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'CANONICAL_URL_USE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues[$sPrefix.'CANONICAL_URL_USE'] === 'Y') {
        if ($sLevel === 'ROOT') {
            $arTemplateParameters[$sPrefix.'CANONICAL_URL_TEMPLATE'] = [
                'PARENT' => 'SECTIONS_SETTINGS',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'CANONICAL_URL_TEMPLATE'),
                'TYPE' => 'STRING'
            ];
        } else {
            $arTemplateParameters[$sPrefix.'CANONICAL_URL_TEMPLATE'] = CIBlockParameters::GetPathTemplateParam(
                'SECTION',
                $sPrefix.'CANONICAL_URL_TEMPLATE',
                Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'CANONICAL_URL_TEMPLATE'),
                '',
                'SECTIONS_SETTINGS'
            );
        }
    }

    $arTemplateParameters[$sPrefix.'TEMPLATE'] = [
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters[$sPrefix.'MENU_SHOW'] = [
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'MENU_SHOW'),
        'TYPE' => 'CHECKBOX'
    ];

    $arTemplateParameters[$sPrefix.'CONTENT_BEGIN_SHOW'] = [
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'CONTENT_BEGIN_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues[$sPrefix.'CONTENT_BEGIN_SHOW'] === 'Y') {
        $arTemplateParameters[$sPrefix.'CONTENT_BEGIN_PATH'] = [
            'PARENT' => 'SECTIONS_SETTINGS',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'CONTENT_BEGIN_PATH'),
            'TYPE' => 'STRING'
        ];
    }

    $arTemplateParameters[$sPrefix.'CONTENT_END_SHOW'] = [
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'CONTENT_END_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues[$sPrefix.'CONTENT_END_SHOW'] === 'Y') {
        $arTemplateParameters[$sPrefix.'CONTENT_END_PATH'] = [
            'PARENT' => 'SECTIONS_SETTINGS',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_1_'.$sPrefix.'CONTENT_END_PATH'),
            'TYPE' => 'STRING'
        ];
    }

    if (!empty($sTemplate)) {
        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            $sComponent,
            $sTemplate,
            $siteTemplate,
            $arCurrentValues,
            $sPrefix,
            function ($sKey, &$arParameter) use (&$sLevel) {
                $arParameter['PARENT'] = 'SECTIONS_SETTINGS';
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_1_SECTIONS_'.$sLevel).'. '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }
}