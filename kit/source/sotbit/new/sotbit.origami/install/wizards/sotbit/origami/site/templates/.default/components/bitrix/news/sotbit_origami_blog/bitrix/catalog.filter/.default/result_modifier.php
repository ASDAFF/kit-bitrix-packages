<?php
use Sotbit\Origami\Helper\Config;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$arResult['TAGS'] = [];

$filter = [
    'ACTIVE' => 'Y',
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
];

$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    $filter['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID']
    ];
}

$rs = CIBlockElement::GetList(
    [],
    $filter,
    false,
    false,
    [
        'TAGS'
    ]
);
while($el = $rs->fetch())
{
    if($el['TAGS'])
    {
        $tags = explode(',',$el['TAGS']);
        $arResult['TAGS'] = array_merge($arResult['TAGS'],$tags);
    }
}
$arResult['TAGS'] = array_unique($arResult['TAGS']);
?>