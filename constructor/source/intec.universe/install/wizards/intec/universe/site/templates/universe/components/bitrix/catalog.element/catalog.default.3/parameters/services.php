<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arTemplateParameters
 * @var array $arCurrentValues
 */

$sComponent = 'intec.universe:main.services';
$sTemplate = 'template.17';
$sPrefix = 'SERVICES_';

$arExcluded = [
    'SETTINGS_USE',
    'LAZYLOAD_USE'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    $sComponent,
    $sTemplate,
    $siteTemplate,
    $arCurrentValues,
    $sPrefix,
    function ($key, &$arParameter) use (&$arExcluded) {
        if (ArrayHelper::isIn($key, $arExcluded))
            return false;

        $arParameter['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SERVICES').' '.$arParameter['NAME'];

        return true;
    },
    Component::PARAMETERS_MODE_TEMPLATE
));

unset($sComponent, $sTemplate, $sPrefix, $arExcluded);