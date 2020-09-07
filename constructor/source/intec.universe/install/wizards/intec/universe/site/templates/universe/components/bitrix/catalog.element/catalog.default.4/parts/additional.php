<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

/**
 * @var array $arParams
 * @var array $arData
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$GLOBALS['arCatalogElementFilter'] = [
    'ID' => $arData['ADDITIONAL']
];

?>
<div class="catalog-element-block">
    <?php $APPLICATION->IncludeComponent(
        'bitrix:catalog.section',
        'products.additional.1',
        array(
            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'SECTION_USER_FIELDS' => array(),
            'SHOW_ALL_WO_SECTION' => 'Y',
            'FILTER_NAME' => 'arCatalogElementFilter',
            'PRICE_CODE' => $arParams['PRICE_CODE'],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID']
        ),
        $component
    ) ?>
</div>
