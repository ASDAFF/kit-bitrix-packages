<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

if (
	!Loader::includeModule('iblock') ||
	!Loader::includeModule('intec.core')
) return;

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$sIBlockType = ArrayHelper::getValue($arCurrentValues, 'IBLOCK_TYPE');
$sSiteId = $_REQUEST['site'];
$arFilter = [];

if (!empty($sIBlockType))
	$arFilter['TYPE'] = $sIBlockType;

if (!empty($sSiteId))
    $arFilter['SITE_ID'] = $sSiteId;

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([
	'SORT' => 'ASC',
], $arFilter));

$arParameters = [];
$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks->asArray(function ($sKey, $arIBlock) {
        return [
            'key' => $arIBlock['ID'],
            'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arParameters['USUAL'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_USUAL'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['ELEMENTS_ROOT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_ELEMENTS_ROOT'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['ELEMENTS_SECTIONS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_ELEMENTS_SECTIONS'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_ELEMENTS_COUNT'),
    'TYPE' => 'CHECKBOX'
];

$arParameters['DEPTH_LEVEL'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_DEPTH_LEVEL'),
    'TYPE' => 'STRING',
    'DEFAULT' => '1'
];

$arParameters['IS_SEF'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('MENU_SECTIONS_IS_SEF'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if($arCurrentValues['IS_SEF'] === 'Y') {
    $arParameters['SEF_BASE_URL'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('MENU_SECTIONS_SEF_BASE_URL'),
        'TYPE' => 'STRING'
    ];

    $arParameters['SECTION_PAGE_URL'] = CIBlockParameters::GetPathTemplateParam(
        'SECTION',
        'SECTION_PAGE_URL',
        Loc::getMessage('MENU_SECTIONS_SECTION_PAGE_URL'),
        '#SECTION_ID#/',
        'BASE'
    );

    $arParameters['DETAIL_PAGE_URL'] = CIBlockParameters::GetPathTemplateParam(
        'DETAIL',
        'DETAIL_PAGE_URL',
        Loc::getMessage('MENU_SECTIONS_DETAIL_PAGE_URL'),
        '#SECTION_ID#/#ELEMENT_ID#/',
        'BASE'
    );
} else {
    $arParameters['ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('MENU_SECTIONS_ID'),
        'TYPE' => 'STRING',
        'DEFAULT' => '={$_REQUEST["ID"]}',
    ];

    $arParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
        'SECTION',
        'SECTION_URL',
        Loc::getMessage('MENU_SECTIONS_SECTION_URL'),
        '',
        'BASE'
    );

    $arParameters['DETAIL_URL'] = CIBlockParameters::GetPathTemplateParam(
        'DETAIL',
        'DETAIL_URL',
        Loc::getMessage('MENU_SECTIONS_DETAIL_URL'),
        '',
        'BASE'
    );
}

$arParameters['CACHE_TIME'] = [
    'DEFAULT' => 36000000
];

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => $arParameters
];