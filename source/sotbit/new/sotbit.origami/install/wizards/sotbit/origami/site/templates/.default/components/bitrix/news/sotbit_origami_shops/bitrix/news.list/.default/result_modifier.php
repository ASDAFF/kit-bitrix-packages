<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}
$arResult['SECTIONS'] = [];
$idSections = [];
if($arResult['ITEMS'])
{
    foreach($arResult['ITEMS'] as $item)
    {
        $idSections[] = $item['IBLOCK_SECTION_ID'];
    }
}
$rs = CIBlockSection::GetList([],['ID' => $idSections, 'IBLOCK_ID' => $arParams['IBLOCK_ID']]);
while($section = $rs->Fetch())
{
    $arResult['SECTIONS'][$section['ID']] = $section;
    if($arParams['ONE_OFFICE'] == 'Y')
    {
        break;
    }
}