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
<div class="sale-personal-order-detail-block" data-block="information">
    <h2 class="sale-personal-order-detail-block-header intec-ui-markup-header">
        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_INFORMATION_TITLE') ?>
    </h2>
    <div class="sale-personal-order-detail-block-content">
        <div class="sale-personal-order-detail-block-fields intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-i-10">
            <?php if (!empty($arResult['ORDER_PROPS'])) { ?>
                <?php foreach ($arResult['ORDER_PROPS'] as $arOrderProperty) { ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'sale-personal-order-detail-block-field',
                            'intec-grid-item' => [
                                '4',
                                '1100-3',
                                '1024-2',
                                '850-1'
                            ]
                        ],
                        'data' => [
                            'field' => !empty($arOrderProperty['CODE']) ? $arOrderProperty['CODE'] : null
                        ]
                    ]) ?>
                        <div class="sale-personal-order-detail-block-field-name">
                            <?= Html::encode($arOrderProperty['NAME']) ?>:
                        </div>
                        <div class="sale-personal-order-detail-block-field-value">
                        <?php
                            if ($arOrderProperty['TYPE'] === 'Y/N') {
                                echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_INFORMATION_FIELDS_LOGIC_VALUE_'.($arOrderProperty['VALUE'] === 'Y' ? 'YES' : 'NO'));
                            } else if (
                                $arOrderProperty['MULTIPLE'] == 'Y' &&
                                $arOrderProperty['TYPE'] !== 'FILE' &&
                                $arOrderProperty['TYPE'] !== 'LOCATION'
                            ) {
                                $arOrderPropertyValues = unserialize($arOrderProperty['VALUE']);

                                if (!empty($arOrderPropertyValues))
                                    echo implode('<br />', $arOrderPropertyValues);

                                unset($arOrderPropertyValues);
                            } else if ($arOrderProperty['TYPE'] === 'FILE') {
                                echo $arOrderProperty['VALUE'];
                            } else {
                                echo Html::encode($arOrderProperty['VALUE']);
                            }
                        ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?php } ?>
            <?php if (!empty($arResult['USER_DESCRIPTION'])) { ?>
                <div class="sale-personal-order-detail-block-field intec-grid-item-1" data-field="comment">
                    <div class="sale-personal-order-detail-block-field-name">
                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_INFORMATION_FIELDS_COMMENT_NAME') ?>:
                    </div>
                    <div class="sale-personal-order-detail-block-field-value">
                        <?= nl2br(Html::encode($arResult['USER_DESCRIPTION'])) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>