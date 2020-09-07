<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 * @var array $arParametersCommon
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 * @var Arrays $arProperties
 */

$sPrefix = 'TAGS_';
$arTemplateParameters[$sPrefix.'USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_TAGS_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues[$sPrefix.'USE'] === 'Y') {
    $sComponent = 'intec.universe:tags.list';
    $sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');

    $arTemplates = Arrays::from(CComponentUtil::GetTemplatesList(
        $sComponent,
        $siteTemplate
    ))->asArray(function ($iIndex, $arTemplate) use (&$sTemplate) {
        return [
            'key' => $arTemplate['NAME'],
            'value' => $arTemplate['NAME']
        ];
    });

    $arTemplateParameters[$sPrefix.'TEMPLATE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_'.$sPrefix.'TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters[$sPrefix.'PROPERTY'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_TAGS_PROPERTY'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']) || $arProperty['PROPERTY_TYPE'] != 'L' || $arProperty['USER_TYPE'] !== null)
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters[$sPrefix.'COUNT'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_TAGS_COUNT'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ];

    $arTemplateParameters[$sPrefix.'VARIABLE_TAGS'] = [
        'PARENT' => 'ACTION_SETTINGS',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_TAGS_VARIABLE_TAGS'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'tags'
    ];

    if (!empty($sTemplate)) {
        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            $sComponent,
            $sTemplate,
            $siteTemplate,
            $arCurrentValues,
            $sPrefix,
            function ($sKey, &$arParameter) use (&$arParametersCommon) {
                if (ArrayHelper::isIn($sKey, $arParametersCommon))
                    return false;

                $arParameter['PARENT'] = 'BASE';
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_CATALOG_1_TAGS').'. '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }
}