<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-offers-properties">
    <?php foreach ($arResult['SKU_PROPS'] as $arProperty) { ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-offers-property',
            'data' => [
                'role' => 'property',
                'type' => $arProperty['type'],
                'property' => $arProperty['code']
            ]
        ]) ?>
            <div class="catalog-element-offers-property-title">
                <?= $arProperty['name'] ?>
            </div>
            <div class="catalog-element-offers-property-values">
                <?php foreach ($arProperty['values'] as $arValue) { ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-offers-property-value',
                            'intec-cl-border-hover'
                        ],
                        'data' => [
                            'role' => 'property.value',
                            'value' => $arValue['id'],
                            'state' => 'hidden'
                        ]
                    ]) ?>
                        <div class="catalog-element-offers-property-value-text">
                            <?= $arValue['name'] ?>
                        </div>
                        <?php if ($arProperty['type'] === 'picture' && !empty($arValue['picture'])) { ?>
                            <?= Html::tag('div', null, [
                                'class' => 'catalog-element-offers-property-value-image',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arValue['picture'] : null
                                ],
                                'style' => [
                                    'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arValue['picture'].'\')'
                                ]
                            ]) ?>
                        <?php } ?>
                        <div class="catalog-element-offers-property-value-overlay"></div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
</div>