<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arParams
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (!CModule::IncludeModule('intec.core'))
    return;

$APPLICATION->IncludeComponent(
    'intec.universe:iblock.elements',
    '.default',
    array(
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'SECTIONS_ID' => $arParams['SECTIONS_ID'],
        'SECTIONS_CODE' => array(),
        'ELEMENTS_ID' => $arParams['ELEMENTS_ID'],
        'ELEMENTS_COUNT' => $arParams['ELEMENTS_COUNT'],
        'SHOW_HEADER' => $arParams['SHOW_HEADER'],
        'HEADER' => $arParams['HEADER'],
        'HEADER_POSITION' => $arParams['HEADER_POSITION'],
        'LINE_ELEMENTS_COUNT' => $arParams['LINE_ELEMENTS_COUNT'],
        'VIEW' => $arParams['VIEW'],
        'FONT_SIZE_HEADER' => $arParams['FONT_SIZE_HEADER'],
        'FONT_STYLE_HEADER_BOLD' => $arParams['FONT_STYLE_HEADER_BOLD'],
        'FONT_STYLE_HEADER_ITALIC' => $arParams['FONT_STYLE_HEADER_ITALIC'],
        'FONT_STYLE_HEADER_UNDERLINE' => $arParams['FONT_STYLE_HEADER_UNDERLINE'],
        'HEADER_TEXT_POSITION' => $arParams['HEADER_TEXT_POSITION'],
        'HEADER_TEXT_COLOR' => $arParams['HEADER_TEXT_COLOR'],
        'FONT_SIZE_DESCRIPTION' => $arParams['FONT_SIZE_DESCRIPTION'],
        'FONT_STYLE_DESCRIPTION_BOLD' => $arParams['FONT_STYLE_DESCRIPTION_BOLD'],
        'FONT_STYLE_DESCRIPTION_ITALIC' => $arParams['FONT_STYLE_DESCRIPTION_ITALIC'],
        'FONT_STYLE_DESCRIPTION_UNDERLINE' => $arParams['FONT_STYLE_DESCRIPTION_UNDERLINE'],
        'DESCRIPTION_TEXT_POSITION' => $arParams['DESCRIPTION_TEXT_POSITION'],
        'DESCRIPTION_TEXT_COLOR' => $arParams['DESCRIPTION_TEXT_COLOR'],
        'TARGET_BLANK' => $arParams['TARGET_BLANK'],
        'BACKGROUND_COLOR_ICON' => $arParams['BACKGROUND_COLOR_ICON'],
        'BACKGROUND_OPACITY_ICON' => $arParams['BACKGROUND_OPACITY_ICON'],
        'BACKGROUND_BORDER_RADIUS' => $arParams['BACKGROUND_BORDER_RADIUS'],
        'PROPERTY_USE_LINK' => $arParams['PROPERTY_USE_LINK'],
        'PROPERTY_LINK' => $arParams['PROPERTY_LINK']
    ),
    $component
);