<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$maxCategories = $arParams["COUNT_SECTIONS"];

if(count($arResult["SECTIONS"]) > $maxCategories)
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


?>