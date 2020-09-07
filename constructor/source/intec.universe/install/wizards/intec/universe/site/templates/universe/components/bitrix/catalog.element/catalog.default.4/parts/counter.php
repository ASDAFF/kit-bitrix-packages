<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

?>
<?php $vMeasure = function ($arItem, $bOffer = false) { ?>
    <?php if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
    <?= Html::tag('span', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_QUANTITY_RATIO', [
        '#QUANTITY_RATIO#' => !empty($arItem['CATALOG_MEASURE_RATIO']) ? $arItem['CATALOG_MEASURE_RATIO'] : '1',
        '#MEASURE#' => $arItem['CATALOG_MEASURE_NAME']
    ]), [
        'data-offer' => $bOffer ? $arItem['ID'] : 'false'
    ]) ?>
<?php } ?>
<div class="catalog-element-panel-block">
    <div class="intec-grid intec-grid-a-v-center intec-grid-i-8">
        <div class="intec-grid-item-auto">
            <div class="catalog-element-ratio">
                <?php $vMeasure($arResult) ?>
                <?php foreach ($arResult['OFFERS'] as &$arOffer) $vMeasure($arOffer, true) ?>
                <?php unset($arOffer) ?>
            </div>
        </div>
        <div class="intec-grid-item-auto">
            <div class="catalog-element-counter">
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-counter-controls',
                        'intec-ui' => [
                            '',
                            'control-numeric',
                            'view-5',
                            'size-4'
                        ]
                    ],
                    'data' => [
                        'role' => 'counter',
                        'disable' => ''
                    ]
                ]) ?>
                <?= Html::tag('a', '-', [
                    'class' => 'intec-ui-part-decrement',
                    'href' => 'javascript:void(0)',
                    'data-type' => 'button',
                    'data-action' => 'decrement'
                ]) ?>
                <?= Html::input('text', null, 0, [
                    'data-type' => 'input',
                    'class' => 'intec-ui-part-input'
                ]) ?>
                <?= Html::tag('a', '+', [
                    'class' => 'intec-ui-part-increment',
                    'href' => 'javascript:void(0)',
                    'data-type' => 'button',
                    'data-action' => 'increment'
                ]) ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>