<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 */

if (empty($arParams['PRODUCTS_ASSOCIATED_TEMPLATE']))
    return;

$GLOBALS['arCatalogElementFilter'] = [
    'ID' => $arResult['ASSOCIATED']
];

$sPrefix = 'PRODUCTS_ASSOCIATED_';

$sTemplate = 'products.small.' . $arParams[$sPrefix.'TEMPLATE'];

foreach ($arParams as $sKey => $sValue) {
    if (!StringHelper::startsWith($sKey, $sPrefix))
        continue;

    $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));

    if ($sKey === 'TEMPLATE')
        continue;

    $arProperties[$sKey] = $sValue;
}

unset($sPrefix, $sKey, $sValue);

$arProperties = ArrayHelper::merge($arProperties, [
    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'SECTION_USER_FIELDS' => array(),
    'SHOW_ALL_WO_SECTION' => 'Y',
    'FILTER_NAME' => 'arCatalogElementFilter',
    'PRICE_CODE' => $arParams['PRICE_CODE'],
    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
    'BASKET_URL' => $arParams['BASKET_URL'],
    'CONSENT_URL' => $arParams['CONSENT_URL'],
    'ACTION' => $arResult['ACTION'],
    'WIDE' => $arVisual['WIDE'] ? 'Y' : 'N'
]);
?>
    <div class="catalog-element-section-associated">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.section',
            $sTemplate,
            $arProperties,
            $component
        ) ?>
    </div>

<?php unset($sTemplate, $arProperties) ?>