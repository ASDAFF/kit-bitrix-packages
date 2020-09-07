<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arTemplateParameters
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$arCommonParameters = [
    'HEADER_SHOW',
    'HEADER_TEXT',
    'IBLOCK_TYPE',
    'IBLOCK_ID',
    'ELEMENTS_COUNT',
    'PROPERTY_TAGS',
    'HEADER_BLOCK_SHOW',
    'DESCRIPTION_BLOCK_SHOW',
    'DATE_FORMAT',
    'TAGS_VARIABLE',
    'LIST_PAGE_URL',
    'SECTION_URL',
    'DETAIL_URL',
    'CACHE_TYPE',
    'CACHE_TIME',
    'CACHE_NOTES',
    'FOOTER_SHOW',
    'SEE_ALL_SHOW'
];

$arCurrentValues['TOP_IBLOCK_ID'] = $arCurrentValues['IBLOCK_ID'];
$arCurrentValues['TOP_PROPERTY_TAGS'] = $arCurrentValues['PROPERTY_TAGS'];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'intec.universe:main.news',
    'template.7',
    $siteTemplate,
    $arCurrentValues,
    'TOP_',
    function ($key, &$parameter) use (&$arCommonParameters) {
        if (ArrayHelper::isIn($key, $arCommonParameters))
            return false;

        if ($key === 'SECTION_ID')
            return false;

        $parameter['PARENT'] = 'VISUAL';
        $parameter['NAME'] = Loc::getMessage('C_NEWS_NEWS_1_TOP').'. '.$parameter['NAME'];

        return true;
    },
    Component::PARAMETERS_MODE_BOTH
));

unset(
    $arCommonParameters,
    $arCurrentValues['TOP_IBLOCK_ID'],
    $arCurrentValues['TOP_PROPERTY_TAGS'],
    $arCurrentValues['TOP_TAGS_SHOW']
);