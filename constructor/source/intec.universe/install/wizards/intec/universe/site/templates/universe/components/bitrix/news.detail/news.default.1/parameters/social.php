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
    'PAGE_URL',
    'PAGE_TITLE'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'bitrix:main.share',
    'flat',
    $siteTemplate,
    $arCurrentValues,
    'BUTTON_SOCIAL_',
    function ($key, &$parameter) use (&$arCommonParameters) {
        if (ArrayHelper::isIn($key, $arCommonParameters))
            return false;

        $parameter['PARENT'] = 'VISUAL';
        $parameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_BUTTON_SOCIAL').'. '.$parameter['NAME'];

        return true;
    },
    Component::PARAMETERS_MODE_BOTH
));

unset($arCommonParameters);