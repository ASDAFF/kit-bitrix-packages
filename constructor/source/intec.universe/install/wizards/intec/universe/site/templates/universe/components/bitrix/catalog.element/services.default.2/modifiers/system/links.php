<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arParams
 */

$arLinks = [
    'USE' => false,
    'IBLOCK' => [
        'TYPE' => $arParams['LINKS_IBLOCK_TYPE'],
        'ID' => $arParams['LINKS_IBLOCK_ID']
    ],
    'SETTINGS' => [
        'PROPERTY_LINK' => $arParams['LINKS_PROPERTY_LINK'],
        'PROPERTY_NAME' => $arParams['LINKS_PROPERTY_NAME']
    ]
];

if (!empty($arLinks['IBLOCK']['ID']))
    $arLinks['USE'] = true;

$arResult['LINKS'] = $arLinks;