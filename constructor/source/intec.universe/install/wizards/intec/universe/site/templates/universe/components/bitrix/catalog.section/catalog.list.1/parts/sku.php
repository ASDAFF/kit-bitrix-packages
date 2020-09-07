<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php $vSku = function (&$arProperties) use (&$arVisual) { ?>
    <div class="catalog-section-item-offers-properties">
        <?php foreach ($arProperties as $arProperty) { ?>
            <?= Html::beginTag('div', [
                'class' => 'catalog-section-item-offers-property',
                'data' => [
                    'role' => 'item.property',
                    'property' => $arProperty['code'],
                    'type' => $arProperty['type'],
                    'visible' => 'false'
                ]
            ]) ?>
            <div class="catalog-section-item-offers-property-title">
                <?= $arProperty['name'] ?>
            </div>
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
                        <div class="catalog-section-item-offers-property-value-image">
                            <div class="catalog-section-item-offers-property-value-image-name">
                                <?= $arValue['name'] ?>
                            </div>
                            <div class="catalog-section-item-offers-property-value-image-wrapper">
                                <?= Html::tag('div', '', [
                                    'class' => 'catalog-section-item-offers-property-value-image-picture',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $arValue['picture'] : null
                                    ],
                                    'style' => [
                                        'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arValue['picture'].'\')'
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="catalog-section-item-offers-property-value-text">
                            <?= $arValue['name'] ?>
                        </div>
                    <?php } ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php } ?>