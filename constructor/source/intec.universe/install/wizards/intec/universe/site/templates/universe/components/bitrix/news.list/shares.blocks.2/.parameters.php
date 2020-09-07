<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

if (!Loader::includeModule('iblock') || !Loader::includeModule('intec.core'))
    return;

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], ['ACTIVE' => 'Y']))->indexBy('ID');
$arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters = [];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_BLOCKS_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $hPropertyString = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N' && !$arProperty['USER_TYPE'])
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arTemplateParameters['PROPERTY_DURATION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_BLOCKS_2_PROPERTY_DURATION'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertyString),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_DISCOUNT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_BLOCKS_2_PROPERTY_DISCOUNT'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertyString),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['IBLOCK_DESCRIPTION_SHOW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_SHARES_BLOCKS_2_IBLOCK_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ];
}