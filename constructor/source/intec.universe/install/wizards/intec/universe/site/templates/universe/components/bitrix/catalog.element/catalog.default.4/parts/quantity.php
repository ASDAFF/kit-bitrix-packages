<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
/**
 * @var array $arResult
 * @var array $arVisual
 */



?>
<?php $vQuantity = function (&$arItem, $bOffer = false) use (&$arVisual) { ?>
    <?php if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
    <?= Html::beginTag('div', [
        'class' => '',
        'data-offer' => $bOffer ? $arItem['ID'] : 'false'
    ]) ?>
        <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-4">
            <?php if ($arItem['CAN_BUY']) { ?>
                <?php if ($arVisual['QUANTITY']['MODE'] !== 'text') { ?>
                    <div class="intec-grid-item-auto">
                        <div class="catalog-element-quantity-indicator" data-quantity-state="many"></div>
                    </div>
                    <div class="intec-grid-item">
                        <div class="catalog-element-quantity-value">
                            <?php $iOffset = StringHelper::position('.', $arItem['CATALOG_QUANTITY']);

                                $iPrecision = 0;

                                if ($iOffset)
                                    $iPrecision = StringHelper::length(
                                        StringHelper::cut($arItem['CATALOG_QUANTITY'], $iOffset + 1)
                                    );

                                $arItem['CATALOG_QUANTITY'] = number_format(
                                    $arItem['CATALOG_QUANTITY'],
                                    $iPrecision,
                                    '.',
                                    ' '
                                );

                                unset($iOffset, $iPrecision);

                            ?>
                            <?php if ($arVisual['QUANTITY']['MODE'] === 'number' && $arItem['CATALOG_QUANTITY'] > 0) { ?>
                                <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_4_TEMPLATE_QUANTITY_NAME_VALUE_MEASURE', [
                                    '#VALUE#' => $arItem['CATALOG_QUANTITY'],
                                    '#MEASURE#' => !empty($arItem['CATALOG_MEASURE_NAME']) ? ' '.$arItem['CATALOG_MEASURE_NAME']: null
                                ]) ?>
                            <?php } else { ?>
                                <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_4_TEMPLATE_QUANTITY_AVAILABLE') ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else {

                    $sState = 'empty';

                    if ($arItem['CATALOG_QUANTITY'] > $arVisual['QUANTITY']['BOUNDS']['MANY'])
                        $sState = 'many';
                    else if ($arItem['CATALOG_QUANTITY'] < $arVisual['QUANTITY']['BOUNDS']['MANY'] && $arItem['CATALOG_QUANTITY'] > $arVisual['QUANTITY']['BOUNDS']['FEW'])
                        $sState = 'enough';
                    else if ($arItem['CATALOG_QUANTITY'] < $arVisual['QUANTITY']['BOUNDS']['FEW'] && $arItem['CATALOG_QUANTITY'] > 0)
                        $sState = 'few';
                    else if ($arItem['CATALOG_QUANTITY_TRACE'] === 'N' || $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y')
                        $sState = 'many'

                ?>
                    <div class="intec-grid-item-auto">
                        <div class="catalog-element-quantity-indicator" data-quantity-state="<?= $sState ?>"></div>
                    </div>
                    <div class="intec-grid-item">
                        <div class="catalog-element-quantity-value">
                            <?php if ($arVisual['QUANTITY']['MODE'] === 'text') { ?>
                                <?php if ($sState === 'many') { ?>
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_4_TEMPLATE_QUANTITY_BOUNDS_MANY') ?>
                                <?php } else if ($sState === 'enough') { ?>
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_4_TEMPLATE_QUANTITY_BOUNDS_ENOUGH') ?>
                                <?php } else if ($sState === 'few') { ?>
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_4_TEMPLATE_QUANTITY_BOUNDS_FEW') ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="intec-grid-item-auto">
                    <div class="catalog-element-quantity-indicator" data-quantity-state="empty"></div>
                </div>
                <div class="intec-grid-item">
                    <div class="catalog-element-quantity-value">
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_4_TEMPLATE_QUANTITY_UNAVAILABLE') ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>
<div class="intec-grid-item-auto intec-grid-item-768-1">
    <div class="catalog-element-quantity">
        <?php $vQuantity($arResult) ?>
        <?php if (!empty($arResult['OFFERS'])) {
            foreach ($arResult['OFFERS'] as &$arOffer)
                $vQuantity($arOffer, true);

            unset($arOffer);
        } ?>
    </div>
</div>
<?php unset($vQuantity) ?>