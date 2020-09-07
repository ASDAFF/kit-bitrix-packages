<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

?>
<div class="sale-personal-order-detail-block" data-block="common">
    <h2 class="sale-personal-order-detail-block-header intec-ui-markup-header">
        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_COMMON_TITLE') ?>
    </h2>
    <div class="sale-personal-order-detail-block-content">
        <div class="sale-personal-order-detail-block-fields intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-i-10">
            <?php if (!empty($arResult['USER'])) { ?>
                <div class="sale-personal-order-detail-block-field intec-grid-item-3 intec-grid-item-1024-2 intec-grid-item-850-1" data-field="account">
                <?php
                    $sName = Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_COMMON_FIELDS_ACCOUNT_NAME_1');
                    $sValue = [];

                    if (!empty($arResult['USER']['LAST_NAME']))
                        $sValue[] = $arResult['USER']['LAST_NAME'];

                    if (!empty($arResult['USER']['NAME']))
                        $sValue[] = $arResult['USER']['NAME'];

                    if (!empty($arResult['USER']['SECOND_NAME']))
                        $sValue[] = $arResult['USER']['SECOND_NAME'];

                    $sValue = implode(' ', $sValue);

                    if (empty($sValue)) {
                        if (!empty($arResult['FIO'])) {
                            $sValue = $arResult['FIO'];
                        } else {
                            $sName = Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_COMMON_FIELDS_ACCOUNT_NAME_2');
                            $sValue = $arResult['USER']['LOGIN'];
                        }
                    }
                ?>
                    <div class="sale-personal-order-detail-block-field-name">
                        <?= $sName ?>:
                    </div>
                    <div class="sale-personal-order-detail-block-field-value">
                        <?= $sValue ?>
                    </div>
                    <?php unset($sName, $sValue) ?>
                </div>
            <?php } ?>
            <div class="sale-personal-order-detail-block-field intec-grid-item-3 intec-grid-item-1024-2 intec-grid-item-850-1" data-field="state">
                <div class="sale-personal-order-detail-block-field-name">
                    <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_COMMON_FIELDS_STATE_NAME', [
                        '#DATE#' => $arResult['DATE_INSERT_FORMATED']
                    ]) ?>:
                </div>
                <div class="sale-personal-order-detail-block-field-value">
                    <?= $arResult['CANCELED'] !== 'Y' && !empty($arResult['STATUS']) ? Html::encode($arResult['STATUS']['NAME']) : Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_COMMON_FIELDS_STATE_VALUE_CANCELED') ?>
                </div>
            </div>
            <?php if (!empty($arResult['PRICE_FORMATED'])) { ?>
                <div class="sale-personal-order-detail-block-field intec-grid-item-3 intec-grid-item-1024-2 intec-grid-item-850-1" data-field="sum">
                    <div class="sale-personal-order-detail-block-field-name">
                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_COMMON_FIELDS_SUM_NAME') ?>:
                    </div>
                    <div class="sale-personal-order-detail-block-field-value">
                        <?= $arResult['PRICE_FORMATED'] ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>