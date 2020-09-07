<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

$arBasketParams = !empty($arBasketParams) ? $arBasketParams : [];

?>
<?php if ($arResult['BASKET']['POPUP'] && $arData['type'] !== 'MOBILE') { ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:sale.basket.small",
        "icons.1",
        ArrayHelper::merge([
            "BASKET_SHOW" => $arResult['BASKET']['SHOW'][$arData['type']] ? 'Y' : 'N',
            "DELAYED_SHOW" => $arResult['DELAY']['SHOW'][$arData['type']] ? 'Y' : 'N',
            "COMPARE_SHOW" => $arResult['COMPARE']['SHOW'][$arData['type']] ? 'Y' : 'N',
            "BASKET_URL" => $arResult['URL']['BASKET'],
            "COMPARE_URL" => $arResult['URL']['COMPARE'],
            "COMPARE_CODE" => $arResult['COMPARE']['CODE'],
            "COMPARE_IBLOCK_TYPE" => $arResult['COMPARE']['IBLOCK']['TYPE'],
            "COMPARE_IBLOCK_ID" => $arResult['COMPARE']['IBLOCK']['ID'],
            "ORDER_URL" => $arResult['URL']['ORDER']
        ], $arBasketParams),
        $this->getComponent()
    ) ?>
<?php } else { ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:sale.basket.icons",
        ".default",
        ArrayHelper::merge([
            "BASKET_SHOW" => $arResult['BASKET']['SHOW'][$arData['type']] ? 'Y' : 'N',
            "DELAY_SHOW" => $arResult['DELAY']['SHOW'][$arData['type']] ? 'Y' : 'N',
            "COMPARE_SHOW" => $arResult['COMPARE']['SHOW'][$arData['type']] ? 'Y' : 'N',
            "BASKET_URL" => $arResult['URL']['BASKET'],
            "COMPARE_URL" => $arResult['URL']['COMPARE'],
            "COMPARE_CODE" => $arResult['COMPARE']['CODE'],
            "COMPARE_IBLOCK_TYPE" => $arResult['COMPARE']['IBLOCK']['TYPE'],
            "COMPARE_IBLOCK_ID" => $arResult['COMPARE']['IBLOCK']['ID'],
            "ORDER_URL" => $arResult['URL']['ORDER']
        ], $arBasketParams),
        $this->getComponent()
    ) ?>
<?php } ?>
<?php unset($arBasketParams) ?>