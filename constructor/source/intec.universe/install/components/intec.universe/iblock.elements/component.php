<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponent $this
 */

if (!CModule::IncludeModule('intec.core'))
	return;

if (!CModule::IncludeModule('iblock'))
	return;

$arResult = array(
	'ITEMS' => array()
);

$arParams = ArrayHelper::merge(array(
	'SORT' => array(),
    'FILTER' => array(),
	'IBLOCK_ID' => null,
	'SECTIONS_ID' => null,
	'SECTIONS_CODE' => null,
	'ELEMENTS_ID' => null,
	'ELEMENTS_COUNT' => 0
), $arParams);

$arSort = ArrayHelper::getValue($arParams, 'SORT', array());
$arSort = ArrayHelper::merge(array(
	'SORT' => 'ASC'
), Type::isArray($arSort) ? $arSort : array());

$arFilter = ArrayHelper::getValue($arParams, 'FILTER', array());
$arFilter = Type::isArray($arFilter) ? $arFilter : array();

$arNavigation = false;

$iIBlockId = ArrayHelper::getValue($arParams, 'IBLOCK_ID');

if (Type::isNumeric($iIBlockId)) {
	$iIBlockId = Type::toInteger($iIBlockId);
} else {
	$iIBlockId = null;
}

$iSectionsId = ArrayHelper::getValue($arParams, 'SECTIONS_ID');

if (Type::isNumeric($iSectionsId)) {
    $iSectionsId = Type::toInteger($iSectionsId);
} else if (Type::isArray($iSectionsId)) {
    if (ArrayHelper::isEmpty($iSectionsId, true))
        $iSectionsId = null;
} else if ($iSectionsId !== false) {
    $iSectionsId = null;
}

$sSectionsCode = ArrayHelper::getValue($arParams, 'SECTIONS_CODE');

if (Type::isNumeric($sSectionsCode)) {
    $sSectionsCode = Type::toString($sSectionsCode);
} else if (Type::isArray($sSectionsCode)) {
    if (ArrayHelper::isEmpty($sSectionsCode, true))
        $sSectionsCode = null;
} else if (!Type::isString($sSectionsCode) && $sSectionsCode !== false) {
    $sSectionsCode = null;
}

$iElementsId = ArrayHelper::getValue($arParams, 'ELEMENTS_ID');

if (Type::isNumeric($iElementsId)) {
    $iElementsId = Type::toInteger($iElementsId);
} else if (Type::isArray($iElementsId)) {
    if (ArrayHelper::isEmpty($iElementsId, true))
        $iElementsId = null;
} else {
    $iElementsId = null;
}

$iElementsCount = ArrayHelper::getValue($arParams, 'ELEMENTS_COUNT');
$iElementsCount = Type::toInteger($iElementsCount);
$iElementsCount = $iElementsCount < 0 ? 0 : $iElementsCount;

$sSectionUrl = ArrayHelper::getValue($arParams, 'SECTION_URL');
$sSectionUrl = Type::isString($sSectionUrl) ? $sSectionUrl : '';
$sDetailUrl = ArrayHelper::getValue($arParams, 'DETAIL_URL');
$sDetailUrl = Type::isString($sDetailUrl) ? $sDetailUrl : '';

if ($iIBlockId !== null)
	$arFilter['IBLOCK_ID'] = $iIBlockId;

if ($iSectionsId !== null)
    $arFilter['SECTION_ID'] = $iSectionsId;

if ($sSectionsCode !== null)
    $arFilter['SECTION_CODE'] = $sSectionsCode;

if ($iElementsId !== null)
    $arFilter['ID'] = $iElementsId;

if ($iElementsCount > 0)
	$arNavigation = array('nPageSize' => $iElementsCount);

if ($iIBlockId !== null && $APPLICATION->GetShowIncludeAreas()) {
	$arButtons = CIBlock::GetPanelButtons($iIBlockId, 0,
		Type::isNumeric($iSectionsId) ? $iSectionsId : 0,
		array(
			'SECTION_BUTTONS' => false
		)
	);

	$this->AddIncludeAreaIcons(
		CIBlock::GetComponentMenu(
			$APPLICATION->GetPublicShowMode(),
			$arButtons
		)
	);
}

$arElements = array();
$arFiles = array();
$rsElements = CIBlockElement::GetList($arSort, $arFilter, false, $arNavigation);
$rsElements->SetUrlTemplates($sDetailUrl, $sSectionUrl, $sSectionUrl);

while ($rsElement = $rsElements->GetNextElement()) {
	$arElement = $rsElement->GetFields();
	$arElement['PROPERTIES'] = $rsElement->GetProperties();

	if (!empty($arElement['PREVIEW_PICTURE']))
		$arFiles[] = $arElement['PREVIEW_PICTURE'];

    if (!empty($arElement['DETAIL_PICTURE']))
        $arFiles[] = $arElement['DETAIL_PICTURE'];

    $arButtons = CIBlock::GetPanelButtons(
        $arElement['IBLOCK_ID'],
        $arElement['ID'],
        $arElement['SECTION_ID'],
        array(
        	'SECTION_BUTTONS' => false,
			'SESSID' => false,
			'CATALOG' => true
		)
    );

    $arElement["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
    $arElement["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
	$arElements[$arElement['ID']] = $arElement;
}

if (!empty($arFiles)) {
    $rsFiles = CFile::GetList(array(), array(
    	'@ID' => implode(',', $arFiles)
	));
	$arFiles = array();

	while ($arFile = $rsFiles->GetNext()) {
		$arFile['SRC'] = CFile::GetFileSRC($arFile);
		$arFiles[$arFile['ID']] = $arFile;
	}

	foreach ($arElements as &$arElement) {
		if (!empty($arElement['PREVIEW_PICTURE']))
			$arElement['PREVIEW_PICTURE'] = ArrayHelper::getValue($arFiles, $arElement['PREVIEW_PICTURE']);

        if (!empty($arElement['DETAIL_PICTURE']))
            $arElement['DETAIL_PICTURE'] = ArrayHelper::getValue($arFiles, $arElement['PREVIEW_PICTURE']);
	}
}

$arResult['ITEMS'] = $arElements;
$this->IncludeComponentTemplate();