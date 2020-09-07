<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var array $arSkuExtended
 */

?>
<?php $vSku = function ($arSku) use (&$arVisual, &$arSkuExtended) { ?>
    <div class="catalog-section-item-offers-properties">
        <?php foreach ($arSku as $arProperty) {

            $bExtended = false;

            if ($arVisual['OFFERS']['VIEW'] === 'extended' && !empty($arSkuExtended))
                foreach ($arSkuExtended as $arPropertyExtended)
                    if ($arProperty['code'] === $arPropertyExtended['code'])
                        $bExtended = true;

        ?>
            <?= Html::beginTag('div', [
                'class' => 'catalog-section-item-offers-property',
                'data' => [
                    'role' => 'item.property',
                    'property' => $arProperty['code'],
                    'type' => $arProperty['type'],
                    'extended' => $bExtended ? 'true' : 'false',
                    'visible' => 'false'
                ]
            ]) ?>
                <div class="catalog-section-item-offers-property-title" data-align="<?= $arVisual['OFFERS']['ALIGN'] ?>">
                    <?= $arProperty['name'] ?>
                </div>
                <div class="catalog-section-item-offers-property-values" data-align="<?= $arVisual['OFFERS']['ALIGN'] ?>">
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
                                    'class' => 'catalog-section-item-offers-property-value-image',
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
<?php $vSkuExtended = function (&$arSkuExtended) use (&$arVisual) { ?>
    <?php foreach ($arSkuExtended as $key => $arProperty) { ?>
        <?php if (empty($arProperty)) continue ?>
        <?= Html::beginTag('div', [
            'class' => [
                'catalog-section-item-offers-property-extended',
                'intec-grid' => [
                    '',
                    'a-v-center'
                ]
            ],
            'data' => [
                'role' => 'item.property',
                'property' => $arProperty['code'],
                'type' => $arProperty['type'],
                'side' => strtolower($key),
                'visible' => 'false'
            ]
        ]) ?>
        <div class="catalog-section-item-offers-property-extended-values intec-grid-item">
            <?php foreach ($arProperty['values'] as $arValue) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-offers-property-extended-value'
                    ],
                    'data' => [
                        'role' => 'item.property.value',
                        'state' => 'hidden',
                        'value' => $arValue['id']
                    ]
                ]) ?>
                    <?php if ($arProperty['type'] === 'picture' && !empty($arValue['picture'])) { ?>
                        <?= Html::tag('div', '', [
                            'class' => [
                                'catalog-section-item-offers-property-extended-value-image',
                                'intec-cl-border-hover'
                            ],
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $arValue['picture'] : null
                            ],
                            'style' => [
                                'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arValue['picture'].'\')'
                            ]
                        ]) ?>
                        <div class="catalog-section-item-offers-property-extended-value-overlay"></div>
                    <?php } else { ?>
                        <div class="catalog-section-item-offers-property-extended-value-text">
                            <?= $arValue['name'] ?>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php } ?>