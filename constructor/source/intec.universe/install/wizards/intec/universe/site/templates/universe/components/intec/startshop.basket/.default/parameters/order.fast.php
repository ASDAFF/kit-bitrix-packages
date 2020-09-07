<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$arTemplateParameters['FAST_ORDER_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('SB_FAST_ORDER_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FAST_ORDER_USE'] == 'Y') {
    $sComponent = 'intec.universe:sale.order.fast';
    $sPrefix = 'FAST_ORDER_';

    $arTemplates = Arrays::from(CComponentUtil::GetTemplatesList(
        $sComponent,
        $siteTemplate
    ))->asArray(function ($iIndex, $arTemplate) use (&$sTemplate) {
        return [
            'key' => $arTemplate['NAME'],
            'value' => $arTemplate['NAME']
        ];
    });

    $sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
    $sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

    $arTemplateParameters['FAST_ORDER_TEMPLATE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('SB_FAST_ORDER_TEMPLATE'),
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
            function ($sKey, &$arParameter) use (&$sLevel, &$arParametersCommon) {
                $arParameter['NAME'] = Loc::getMessage('SB_FAST_ORDER').'. '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_BOTH
        ));
    }
}