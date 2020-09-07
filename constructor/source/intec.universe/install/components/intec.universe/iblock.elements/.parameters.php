<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arCurrentValues
 */

if (!CModule::IncludeModule('iblock'))
    return;

$arParameters = array();

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = array();
$arIBlocksFilter = array();
$arIBlocksFilter['ACTIVE'] = 'Y';

if (!empty($sIBlockType))
    $arIBlocksFilter['TYPE'] = $sIBlockType;

$rsIBlocks = CIBlock::GetList(array('SORT' => 'ASC'), $arIBlocksFilter);

while ($arIBlock = $rsIBlocks->Fetch())
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

$iIBlockId = (int)$arCurrentValues['IBLOCK_ID'];

$arParameters['IBLOCK_TYPE'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('IBLOCK_ELEMENTS_PARAMETERS_IBLOCK_TYPE'),
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);
$arParameters['IBLOCK_ID'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('IBLOCK_ELEMENTS_PARAMETERS_IBLOCK_ID'),
    'VALUES' => $arIBlocks,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);

$arSectionsById = array();
$iSectionsId = $arCurrentValues['SECTIONS_ID'];
$arSectionsByCode = array();
$sSectionsCode = $arCurrentValues['SECTIONS_CODE'];
$arElements = array();

if (!empty($iIBlockId)) {
    $rsSections = CIBlockSection::GetList(array('SORT' => 'ASC'), array(
        'IBLOCK_ID' => $iIBlockId
    ));

    while ($arSection = $rsSections->GetNext()) {
        $arSectionsById[$arSection['ID']] = '['.$arSection['ID'].'] '.$arSection['NAME'];

        if (empty($arSection['CODE']))
            continue;

        $arSectionsByCode[$arSection['CODE']] = '['.$arSection['CODE'].'] '.$arSection['NAME'];
    }

    if (!empty($iSectionsId) || !empty($sSectionsCode)) {
        $rsElements = CIBlockElement::GetList(array('SORT' => 'ASC'), array(
            'IBLOCK_ID' => $iIBlockId,
            'SECTION_ID' => !empty($iSectionsId) ? $iSectionsId : null,
            'SECTION_CODE' => !empty($sSectionsCode) ? $sSectionsCode : null
        ));

        while ($arElement = $rsElements->GetNext())
            $arElements[$arElement['ID']] = '['.$arElement['ID'].'] '.$arElement['NAME'];
    }
}

$arParameters['SECTIONS_ID'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('IBLOCK_ELEMENTS_PARAMETERS_SECTIONS_ID'),
    'VALUES' => $arSectionsById,
    'ADDITIONAL_VALUES' => 'Y',
    'MULTIPLE' => 'Y',
    'REFRESH' => 'Y'
);
$arParameters['SECTIONS_CODE'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('IBLOCK_ELEMENTS_PARAMETERS_SECTIONS_CODE'),
    'VALUES' => $arSectionsByCode,
    'ADDITIONAL_VALUES' => 'Y',
    'MULTIPLE' => 'Y',
    'REFRESH' => 'Y'
);
$arParameters['ELEMENTS_ID'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('IBLOCK_ELEMENTS_PARAMETERS_ELEMENTS_ID'),
    'VALUES' => $arElements,
    'ADDITIONAL_VALUES' => 'Y',
    'MULTIPLE' => 'Y',
    'REFRESH' => 'Y'
);
$arParameters['ELEMENTS_COUNT'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'STRING',
    'NAME' => GetMessage('IBLOCK_ELEMENTS_PARAMETERS_ELEMENTS_COUNT')
);

$arParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
    'SECTION',
    'SECTION_URL',
    GetMessage('IBLOCK_ELEMENTS_PARAMETERS_SECTION_URL'),
    '',
    'URL_TEMPLATES'
);
$arParameters['DETAIL_URL'] = CIBlockParameters::GetPathTemplateParam(
    'DETAIL',
    'DETAIL_URL',
    GetMessage('IBLOCK_ELEMENTS_PARAMETERS_DETAIL_URL'),
    '',
    'URL_TEMPLATES'
);

$arComponentParameters = array(
    'PARAMETERS' => $arParameters
);