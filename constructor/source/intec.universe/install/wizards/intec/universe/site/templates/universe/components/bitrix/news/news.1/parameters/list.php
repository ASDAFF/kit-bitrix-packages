<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$sComponent = 'bitrix:news.list';
$sPrefix = 'LIST_';
$sTemplate = 'news.';
$sLength = StringHelper::length($sTemplate);

$arTemplates = Arrays::from(CComponentUtil::GetTemplatesList(
    $sComponent,
    $siteTemplate
))->asArray(function ($key, $arValue) use (&$sTemplate, &$sLength) {
    if (!StringHelper::startsWith($arValue['NAME'], $sTemplate))
        return ['skip' => true];

    $sName = StringHelper::cut($arValue['NAME'], $sLength);

    return [
        'key' => $sName,
        'value' => $sName
    ];
});

$sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
$sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

$arTemplateParameters[$sPrefix.'TEMPLATE'] = [
    'PARENT' => 'LIST_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_NEWS_1_'.$sPrefix.'TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($sTemplate)) {
    $arCommonParameters = [
        'PROPERTY_TAGS',
        'DATE_FORMAT',
        'TAGS_VARIABLE'
    ];

    if ($arCurrentValues['TAGS_USE'] === 'Y')
        $arCurrentValues['LIST_PROPERTY_TAGS'] = $arCurrentValues['PROPERTY_TAGS'];
    else {
        $arCurrentValues['LIST_PROPERTY_TAGS'] = null;
        $arCurrentValues['LIST_TAGS_SHOW'] = 'N';
    }

    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        'news.'.$sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($key, &$parameter) use (&$arCommonParameters) {
            if (ArrayHelper::isIn($key, $arCommonParameters))
                return false;

            $parameter['PARENT'] = 'LIST_SETTINGS';
            $parameter['NAME'] = Loc::getMessage('C_NEWS_NEWS_1_LIST').'. '.$parameter['NAME'];

            return true;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    ));

    unset($arCommonParameters, $arCurrentValues['LIST_PROPERTY_TAGS']);
}

unset($sComponent, $sPrefix, $sTemplate, $sLength, $arTemplates);