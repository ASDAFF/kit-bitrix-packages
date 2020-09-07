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
    'AJAX_MODE',
    'AJAX_OPTION_STYLE',
    'AJAX_OPTION_HISTORY',
    'AJAX_OPTION_JUMP',
    'SET_TITLE',
    'CACHE_TYPE',
    'CACHE_TIME',
    'CACHE_NOTES'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'bitrix:subscribe.edit',
    'small.1',
    $siteTemplate,
    $arCurrentValues,
    'SUBSCRIBE_',
    function ($key, &$parameter) use (&$arCommonParameters) {
        if (ArrayHelper::isIn($key, $arCommonParameters))
            return false;

        $parameter['PARENT'] = 'VISUAL';
        $parameter['NAME'] = Loc::getMessage('C_NEWS_NEWS_1_SUBSCRIBE').'. '.$parameter['NAME'];

        return true;
    },
    Component::PARAMETERS_MODE_BOTH
));

unset($arCommonParameters);