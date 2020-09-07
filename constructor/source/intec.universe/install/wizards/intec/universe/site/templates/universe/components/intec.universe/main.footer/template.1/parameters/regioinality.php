<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\regionality\platform\iblock\properties\RegionProperty;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

if (Loader::includeModule('intec.regionality')) {
    $arTemplateParameters['REGIONALITY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_REGIONALITY_USE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['REGIONALITY_USE'] === 'Y') {
        $arTemplateParameters['REGIONALITY_PRICES_TYPES_USE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_REGIONALITY_PRICES_TYPES_USE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['REGIONALITY_PRICES_TYPES_USE'] === 'Y') {
            $arTemplateParameters['PRICE_CODE'] = ['HIDDEN' => 'Y'];
        }
    }
}