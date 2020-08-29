<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if($arResult["SECTIONS"] && !empty($arResult["SECTIONS"]))
{
    foreach($arResult["SECTIONS"] as &$arSection)
    {
        if(!$arSection["PICTURE"])
        {
            $arSection["PICTURE"]["ALT"] = "";
            $arSection["PICTURE"]["TITLE"] = "";
            $image = new Sotbit\Origami\Image\Item();
            $arSection["PICTURE"]["SRC"] = $image->getNoImageMedium();
        }
    }
}

if($arResult["SECTION"]["DETAIL_PICTURE"])
{
    \Bitrix\Iblock\Component\Tools::getFieldImageData(
        $arResult["SECTION"],
        array('DETAIL_PICTURE'),
        \Bitrix\Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION,
        'IPROPERTY_VALUES'
    );
}

if($arParams["SECTION_ROOT_TEMPLATE"] == "products")
    $arResult['SECTIONS'] = array();

if($arResult["SECTIONS"]) {
    foreach ($arResult["SECTIONS"] as &$SECTION) {
        $SECTION['CHILDREN'] = array();
        $rsParentSection = CIBlockSection::GetByID($SECTION['ID']);
        if ($arParentSection = $rsParentSection->GetNext())
        {
            $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
            $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
            while ($arSect = $rsSect->GetNext())
            {
                $SECTION['CHILDREN'][] = $arSect;
            }
        }
    }
}
