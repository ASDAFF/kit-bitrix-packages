<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$maxCategories = $arParams["COUNT_SECTIONS"];
$childCategories = array();
$arID = array();


$arResult["SECTIONS"] = array_slice($arResult["SECTIONS"], 0, $maxCategories);

foreach($arResult["SECTIONS"] as $key => &$arSection)
{
    if($arSection["UF_PHOTO_DETAIL"] && !$arSection["PICTURE"])
    {
        $arSection["PICTURE"] = $arSection["UF_PHOTO_DETAIL"];

        \Bitrix\Iblock\Component\Tools::getFieldImageData(
            $arSection,
            array('PICTURE'),
            \Bitrix\Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION,
            'IPROPERTY_VALUES'
        );
    }

}

foreach($arResult["SECTIONS"] as $key => &$arSection)
{
    $arID[$arSection["ID"]] = $arSection["ID"];
}

if($arParams["SHOW_SUBSECTIONS"] == "Y" && !empty($arID))
{
    $arSort = array(
        "left_margin"=>"asc",
    );

    $arFilter = array(
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "CNT_ACTIVE" => "Y",
        "SECTION_ID" => $arID
    );

    $arSelect = array("NAME", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID");

    $rsSections = CIBlockSection::GetList($arSort, $arFilter, true, $arSelect);

    while($arSect = $rsSections->GetNext())
    {
        $childCategories[$arSect["IBLOCK_SECTION_ID"]][$arSect["ID"]] = $arSect;
    }
}

foreach($arResult["SECTIONS"] as $key => &$arSection)
{
    if(isset($childCategories[$arSection["ID"]]))
    {
        $arSection["CHILD_CATEGORIES"] = $childCategories[$arSection["ID"]];
    }
}

?>