<?php
/**
 * Copyright (c) 25/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Kit\Origami\Helper\Config;
use Kit\Origami\Config\Option, \Bitrix\Main\Localization\Loc;

global $analogProducts;

$selectSectionMain = unserialize(\Kit\Origami\Config\Option::get('SECTIONS_'));
$selectSectionNoTabs = unserialize(\Kit\Origami\Config\Option::get('SECTIONS_NO_TABS'));
$catalogId = Option::get('IBLOCK_ID', $site);
$ar = array();
$rsSection = \CIBlockSection::GetTreeList(Array("IBLOCK_ID" => $catalogId ), array("ID", "NAME", "DEPTH_LEVEL"));
$i = 0;
while($ar_l = $rsSection->GetNext()){
    $ar[$i] = array(
        'ID' => $ar_l["ID"],
        'DEPTH_LEVEL' => $ar_l["DEPTH_LEVEL"],
    );
    $i++;
}

function getSectionByCode($id, $code) {
    return $result = CIBlockElement::GetList(
        array(),
        array("IBLOCK_ID" => $id, "CODE" => $code)
    )->Fetch()
    ['IBLOCK_SECTION_ID'];
}

function getChildrenSection($arrayTree, $selectsSection) {
    $arSections = $selectsSection;
    foreach ($selectsSection as $sectionId) { // 1
        foreach ($arrayTree as $key => $value) { // id: 1 del: 2
            if ($sectionId == $value['ID']) { // 1 == 1
                $depthLv = $value['DEPTH_LEVEL'];
                $sort = array();
                $j = $key+1;
                while ($arrayTree[$j]['DEPTH_LEVEL'] > $depthLv) {
                    $sort[] = $arrayTree[$j]['ID'];
                    $j++;
                }
                $arSections = array_merge($sort, $arSections);
                break;
            }
        }
    }
    return $arSections;
}

$arSectionsMain = getChildrenSection($ar, $selectSectionMain);
$arSectionsNoTabs = getChildrenSection($ar, $selectSectionNoTabs);
$sectionIdByCode = getSectionByCode($catalogId, $componentElementParams['ELEMENT_CODE']);

$APPLICATION->IncludeComponent('bitrix:breadcrumb', 'origami_default',
    [
        "START_FROM" => "0",
        "PATH"       => "",
        "SITE_ID"    => SITE_ID,
    ], false, [
        'HIDE_ICONS' => 'N',
]);

$templateName = "origami_service_v1";

$elementId = $APPLICATION->IncludeComponent(
    'bitrix:catalog.element',
    $templateName,
    $componentElementParams,
    $component
);

\KitOrigami::setSeoOffer();

$GLOBALS['CATALOG_CURRENT_ELEMENT_ID'] = $elementId;


?>
