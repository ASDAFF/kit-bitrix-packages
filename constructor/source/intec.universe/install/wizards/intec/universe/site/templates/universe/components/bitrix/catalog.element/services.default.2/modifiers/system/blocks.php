<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arBlocks = [
    'USE' => false,
    'IBLOCK' => [
        'TYPE' => $arParams['BLOCKS_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_IBLOCK_ID']
    ],
    'SETTINGS' => [
        'PROPERTY_NAME' => $arParams['BLOCKS_PROPERTY_NAME'],
        'PROPERTY_IMAGES' => $arParams['BLOCKS_PROPERTY_IMAGES'],
        'PROPERTY_TEXT_ADDITIONAL' => $arParams['BLOCKS_PROPERTY_TEXT_ADDITIONAL'],
        'PROPERTY_THEME' => $arParams['BLOCKS_PROPERTY_THEME'],
        'PROPERTY_VIEW' => $arParams['BLOCKS_PROPERTY_VIEW'],
        'PROPERTY_DETAIL_NARROW' => $arParams['BLOCKS_PROPERTY_DETAIL_NARROW'],
        'PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW' => $arParams['BLOCKS_PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW'],
        'PROPERTY_COMPACT_POSITION' => $arParams['BLOCKS_PROPERTY_COMPACT_POSITION'],
        'IMAGES_SHOW' => $arParams['BLOCKS_IMAGES_SHOW'],
        'BUTTON_TEXT' => $arParams['BLOCKS_BUTTON_TEXT']
    ]
];

if (!empty($arBlocks['IBLOCK']['ID']) && Type::isNumeric($arBlocks['IBLOCK']['ID']))
    $arBlocks['USE'] = true;

$arResult['BLOCKS'] = $arBlocks;

unset($arBlocks);