<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([
    'SORT' => 'ASC'
], [
    'ACTIVE' => 'Y'
]))->indexBy('ID');

$arGroups = [];
$arParameters = [];
$arParameters['COMPARE_SHOW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_COMPARE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['COMPARE_SHOW'] === 'Y') {
    $arParameters['COMPARE_CODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_COMPARE_CODE'),
        'TYPE' => 'STRING'
    ];

    $arParameters['COMPARE_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_COMPARE_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocksTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arParameters['COMPARE_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_COMPARE_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
            if (!empty($arCurrentValues['COMPARE_IBLOCK_TYPE']))
                if ($arIBlock['IBLOCK_TYPE_ID'] !== $arCurrentValues['COMPARE_IBLOCK_TYPE'])
                    return ['skip' => true];

            return [
                'key' => $iId,
                'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arComponentParameters = [
    'PARAMETERS' => $arParameters,
    'GROUPS' => $arGroups
];