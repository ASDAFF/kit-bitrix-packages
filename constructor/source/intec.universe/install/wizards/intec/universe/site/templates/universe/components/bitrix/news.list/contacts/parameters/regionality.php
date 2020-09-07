<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\regionality\platform\iblock\properties\RegionProperty;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 * @var Arrays $arProperties
 */

if (Loader::includeModule('intec.regionality')) {
    $arTemplateParameters['REGIONALITY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_REGIONALITY_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['REGIONALITY_USE'] === 'Y' && !empty($arIBlock)) {
        $arTemplateParameters['REGIONALITY_PROPERTY'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_REGIONALITY_PROPERTY'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
                if (empty($arProperty['CODE']))
                    return ['skip' => true];

                if (
                    $arProperty['PROPERTY_TYPE'] !== RegionProperty::PROPERTY_TYPE ||
                    $arProperty['USER_TYPE'] !== RegionProperty::USER_TYPE
                ) return ['skip' => true];

                return [
                    'key' => $arProperty['CODE'],
                    'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }
}