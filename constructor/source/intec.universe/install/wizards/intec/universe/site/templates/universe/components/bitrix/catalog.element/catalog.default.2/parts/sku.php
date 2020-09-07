<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

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
                'property' => $arProperty['code'],
                'type' => $arProperty['type']
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
                            'state' => 'hidden',
                            'value' => $arValue['id']
                        ]
                    ]) ?>
                        <?php if ($arProperty['type'] === 'picture' && !empty($arValue['picture'])) { ?>
                            <?= Html::beginTag('div', [
                                'class' => 'catalog-element-offers-property-value-content',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arValue['picture'] : null
                                ],
                                'style' => [
                                    'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arValue['picture'].'\')'
                                ]
                            ]) ?>
                                <div class="intec-aligner"></div>
                                <i class="far fa-check"></i>
                            <?= Html::endTag('div') ?>
                        <?php } else { ?>
                            <div class="catalog-element-offers-property-value-content">
                                <?= $arValue['name'] ?>
                            </div>
                        <?php } ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
</div>
