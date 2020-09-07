<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-offers catalog-element-block">
    <?php foreach ($arResult['SKU_PROPS'] as $arProperty) { ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-offers-property',
            'data' => [
                'role' => 'property',
                'property' => $arProperty['code'],
                'type' => $arProperty['type']
            ]
        ]) ?>
            <div class="intec-grid intec-grid-nowrap intec-grid-a-v-start">
                <div class="intec-grid-item-auto">
                    <?= Html::tag('div', $arProperty['name'], [
                        'class' => 'catalog-element-offers-property-name'
                    ]) ?>
                </div>
                <div class="intec-grid-item">
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
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
</div>