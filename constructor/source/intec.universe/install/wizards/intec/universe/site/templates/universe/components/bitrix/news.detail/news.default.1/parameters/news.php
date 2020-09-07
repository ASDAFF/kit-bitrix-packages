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
    'IBLOCK_TYPE',
    'IBLOCK_ID',
    'ELEMENTS_COUNT',
    'SETTINGS_USE',
    'HEADER_BLOCK_SHOW',
    'DESCRIPTION_BLOCK_SHOW',
    'DATE_FORMAT',
    'DATE_TYPE',
    'LIST_PAGE_URL',
    'SECTION_URL',
    'DETAIL_URL',
    'SORT_BY',
    'ORDER_BY',
    'FOOTER_SHOW',
    'CACHE_TYPE',
    'CACHE_TIME',
    'CACHE_NOTES'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'intec.universe:main.news',
    'template.2',
    $siteTemplate,
    $arCurrentValues,
    'ADDITIONAL_NEWS_',
    function ($key, &$parameter) use (&$arCommonParameters) {
        if (ArrayHelper::isIn($key, $arCommonParameters))
            return false;

        $parameter['PARENT'] = 'VISUAL';
        $parameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_ADDITIONAL_NEWS').'. '.$parameter['NAME'];

        return true;
    },
    Component::PARAMETERS_MODE_BOTH
));

unset($arCommonParameters);