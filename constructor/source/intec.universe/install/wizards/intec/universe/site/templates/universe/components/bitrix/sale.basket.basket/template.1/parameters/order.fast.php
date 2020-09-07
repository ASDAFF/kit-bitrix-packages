<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arTemplateParameters
 * @var array $arCurrentValues
 */

$sComponent = 'intec.universe:sale.order.fast';
$sPrefix = 'ORDER_FAST_';

$arTemplates = Arrays::from(CComponentUtil::GetTemplatesList(
    $sComponent,
    $siteTemplate
))->asArray(function ($key, $arTemplate) use (&$sTemplate) {
    return [
        'key' => $arTemplate['NAME'],
        'value' => $arTemplate['NAME']
    ];
});

$sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
$sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

$arTemplateParameters['ORDER_FAST_TEMPLATE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_BASKET_TEMPLATE_DEFAULT_1_ORDER_FAST_TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($sTemplate)) {
    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        $sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($key, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_BASKET_TEMPLATE_DEFAULT_1_ORDER_FAST').'. '.$arParameter['NAME'];
            $arParameter['PARENT'] = 'BASE';

            return true;
        },
        Component::PARAMETERS_MODE_BOTH
    ));
}

unset($sComponent, $sPrefix, $arTemplates, $sTemplate);