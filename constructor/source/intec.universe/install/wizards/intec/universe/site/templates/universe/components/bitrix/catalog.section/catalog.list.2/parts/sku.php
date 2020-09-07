<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @param array $arProperties
 */
$vSku = function ($arProperties) use ($arVisual) { ?>
    <div class="catalog-section-item-offers intec-grid intec-grid-wrap intec-grid-i-h-16 intec-grid-i-v-5">
        <?php foreach ($arProperties as $arProperty) { ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-section-item-offers-property',
                    'intec-grid-item-auto',
                    'intec-grid-item-shrink-1'
                ],
                'data' => [
                    'role' => 'item.property',
                    'property' => $arProperty['code'],
                    'type' => $arProperty['type']
                ]
            ]) ?>
                <?= Html::tag('div', $arProperty['name'], [
                    'class' => 'catalog-section-item-offers-property-name'
                ]) ?>
                <div class="catalog-section-item-offers-property-values">
                    <?php foreach ($arProperty['values'] as $arValue) { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-section-item-offers-property-value',
                                'intec-cl-border-hover'
                            ],
                            'data' => [
                                'role' => 'item.property.value',
                                'state' => 'hidden',
                                'value' => $arValue['id']
                            ]
                        ]) ?>
                        <?php if ($arProperty['type'] === 'picture' && !empty($arValue['picture'])) { ?>
                            <?= Html::beginTag('div', [
                                'title' => $arValue['name'],
                                'data-type' => 'picture',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arValue['picture'] : null
                                ],
                                'style' => [
                                    'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arValue['picture'].'\')'
                                ]
                            ]) ?>
                            <?= $arValue['name'] ?>
                            <?= Html::endTag('div') ?>
                        <?php } else { ?>
                            <?= Html::tag('div', $arValue['name'], [
                                'data-type' => 'text'
                            ]) ?>
                        <?php } ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php };