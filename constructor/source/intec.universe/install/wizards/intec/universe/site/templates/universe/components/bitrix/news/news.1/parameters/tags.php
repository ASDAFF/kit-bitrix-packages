<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$sComponent = 'intec.universe:tags.list';
$sPrefix = 'TAGS_';

$arTemplates = Arrays::from(CComponentUtil::GetTemplatesList(
    $sComponent,
    $siteTemplate
))->asArray(function ($key, $arValue) {
    return [
        'key' => $arValue['NAME'],
        'value' => $arValue['NAME']
    ];
});

$sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
$sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

$arTemplateParameters[$sPrefix.'TEMPLATE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_NEWS_1_'.$sPrefix.'TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($sTemplate)) {
    $arCommonParameters = [
        'IBLOCK_TYPE',
        'IBLOCK_ID',
        'SECTION_ID',
        'PROPERTY',
        'FILTER_NAME',
        'VARIABLE_TAGS',
        'CACHE_TYPE',
        'CACHE_TIME',
        'CACHE_NOTES'
    ];

    $arCurrentValues['PROPERTY'] = $arCurrentValues['PROPERTY_TAGS'];

    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        $sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($key, &$parameter) use (&$arCommonParameters) {
            if (ArrayHelper::isIn($key, $arCommonParameters))
                return false;

            $parameter['PARENT'] = 'VISUAL';
            $parameter['NAME'] = Loc::getMessage('C_NEWS_NEWS_1_TAGS').'. '.$parameter['NAME'];

            return true;
        },
        Component::PARAMETERS_MODE_BOTH
    ));

    unset($arCommonParameters, $arCurrentValues['PROPERTY']);
}

unset($sComponent, $sPrefix, $arTemplates, $sTemplate);