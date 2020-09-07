<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

?>
<div class="sale-personal-order-detail-block" data-block="user">
    <h2 class="sale-personal-order-detail-block-header intec-ui-markup-header">
        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_USER_TITLE') ?>
    </h2>
    <div class="sale-personal-order-detail-block-content">
        <div class="sale-personal-order-detail-block-fields intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-i-10">
            <?php if (!empty($arResult['USER']['LOGIN']) && !ArrayHelper::isIn('LOGIN', $arParams['HIDE_USER_INFO'])) { ?>
                <div class="sale-personal-order-detail-block-field intec-grid-item-4 intec-grid-item-1100-3 intec-grid-item-1024-2 intec-grid-item-850-1" data-field="login">
                    <div class="sale-personal-order-detail-block-field-name">
                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_USER_FIELDS_LOGIN_NAME') ?>:
                    </div>
                    <div class="sale-personal-order-detail-block-field-value">
                        <?= $arResult['USER']['LOGIN'] ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (!empty($arResult['USER']['EMAIL']) && !ArrayHelper::isIn('EMAIL', $arParams['HIDE_USER_INFO'])) { ?>
                <div class="sale-personal-order-detail-block-field intec-grid-item-4 intec-grid-item-1100-3 intec-grid-item-1024-2 intec-grid-item-850-1" data-field="email">
                    <div class="sale-personal-order-detail-block-field-name">
                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_USER_FIELDS_EMAIL_NAME') ?>:
                    </div>
                    <div class="sale-personal-order-detail-block-field-value">
                        <?= $arResult['USER']['EMAIL'] ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (!empty($arResult['USER']['PERSON_TYPE_NAME']) && !ArrayHelper::isIn('PERSON_TYPE_NAME', $arParams['HIDE_USER_INFO'])) { ?>
                <div class="sale-personal-order-detail-block-field intec-grid-item-4 intec-grid-item-1100-3 intec-grid-item-1024-2 intec-grid-item-850-1" data-field="person">
                    <div class="sale-personal-order-detail-block-field-name">
                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_USER_FIELDS_PERSON_NAME') ?>:
                    </div>
                    <div class="sale-personal-order-detail-block-field-value">
                        <?= $arResult['USER']['PERSON_TYPE_NAME'] ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>