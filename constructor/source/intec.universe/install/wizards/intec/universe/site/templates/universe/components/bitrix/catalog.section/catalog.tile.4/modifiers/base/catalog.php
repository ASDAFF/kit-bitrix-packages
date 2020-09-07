<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arCodes
 * @var array $arVisual
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$component->applyTemplateModifications();

$arResult['SKU_PROPS'] = ArrayHelper::getValue($arResult, ['SKU_PROPS', $arResult['IBLOCK_ID']], []);
$arSKUProps = [];

foreach ($arResult['SKU_PROPS'] as $arSKUProperty) {
    $arOffersProperty = [
        'id' => $arSKUProperty['ID'],
        'code' => 'P_'.$arSKUProperty['CODE'],
        'name' => $arSKUProperty['NAME'],
        'type' => $arSKUProperty['SHOW_MODE'] === 'TEXT' ? 'text' : 'picture',
        'values' => []
    ];

    foreach ($arSKUProperty['VALUES'] as $arValue) {
        $arOffersProperty['values'][] = [
            'id' => !empty($arValue['XML_ID']) ? $arValue['XML_ID'] : $arValue['ID'],
            'name' => $arValue['NAME'],
            'stub' => $arValue['NA'] == 1,
            'picture' => !empty($arValue['PICT']) ? $arValue['PICT']['SRC'] : null
        ];
    }

    $arSKUProps[] = $arOffersProperty;
}

$arResult['SKU_PROPS'] = $arSKUProps;

unset($arSKUProps);