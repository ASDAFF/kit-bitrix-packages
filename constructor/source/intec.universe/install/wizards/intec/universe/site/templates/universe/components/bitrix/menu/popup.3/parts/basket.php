<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php $APPLICATION->IncludeComponent(
    "intec.universe:sale.basket.small",
    "template.1",
    [
        'BASKET_SHOW' => $arResult['VISUAL']['BASKET']['SHOW'] ? 'Y' : 'N',
        'DELAY_SHOW' => $arResult['VISUAL']['DELAY']['SHOW'] ? 'Y' : 'N',
        'COMPARE_SHOW' => $arResult['VISUAL']['COMPARE']['SHOW'] ? 'Y' : 'N',
        'BASKET_URL' => $arResult['URL']['BASKET'],
        'COMPARE_URL' => $arResult['URL']['COMPARE'],
        'COMPARE_CODE' => $arParams['COMPARE_CODE'],
        'COMPARE_IBLOCK_TYPE' => $arParams['COMPARE_IBLOCK_TYPE'],
        'COMPARE_IBLOCK_ID' => $arParams['COMPARE_IBLOCK_ID'],
        'ORDER_URL' => $arResult['URL']['ORDER'],
        'THEME' => $arResult['VISUAL']['THEME']
    ],
    $this->getComponent()
) ?>