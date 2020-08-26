<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$sections = [];
foreach($arResult['ITEMS'] as $item)
{
    $sections[$item['ID']] = $item['IBLOCK_SECTION_ID'];
}
if($sections)
{
    $rs = \CIBlockSection::GetList([],['ID' => $sections],false,['ID','ACTIVE','NAME','IBLOCK_ID','SECTION_PAGE_URL'],false);

    while($section = $rs->GetNext())
    {
        foreach($arResult['ITEMS'] as &$item)
        {
            if($sections[$item['ID']] == $section['ID'])
            {
                $item['SECTION'] = $section;
            }
        }
    }
}