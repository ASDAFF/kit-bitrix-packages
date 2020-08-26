<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arSectionName = array();
$rsSectionName = CIBlockSection::GetList(
    array(),
    array('ID' => $arResult['ITEMS'][0]['IBLOCK_SECTION_ID'])
);
while ($it = $rsSectionName->Fetch()) {
    $arSectionName[] = $it;
}

$arResult['SECTION_NAME'] = $arSectionName[0]['NAME'];
