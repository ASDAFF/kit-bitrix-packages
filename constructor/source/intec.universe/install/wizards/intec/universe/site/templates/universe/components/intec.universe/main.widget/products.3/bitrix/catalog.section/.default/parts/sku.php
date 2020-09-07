<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$vSku = function (&$arSku) use (&$arVisual) { ?>
    <div class="widget-item-offers">
        <?php foreach ($arSku as $arProperty) {

            if ($arVisual['OFFERS']['ALIGN'] === 'start')
                $sOfferNameAlign = 'left';
            else if ($arVisual['OFFERS']['ALIGN'] === 'end')
                $sOfferNameAlign = 'right';
            else
                $sOfferNameAlign = 'center'

        ?>
            <?= Html::beginTag('div', [
                'class' => 'widget-item-offers-property',
                'data' => [
                    'role' => 'item.property',
                    'property' => $arProperty['code'],
                    'type' => $arProperty['type'],
                    'visible' => 'false'
                ]
            ]) ?>
                <div class="widget-item-offers-property-name" data-align="<?= $sOfferNameAlign ?>">
                    <?= $arProperty['name'] ?>
                </div>
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-offers-property-values',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-h-'.$arVisual['OFFERS']['ALIGN']
                        ]
                    ]
                ]) ?>
                    <?php foreach ($arProperty['values'] as $arValue) { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'widget-item-offers-property-value',
                                'intec-cl-border-hover',
                                'intec-grid-item-auto'
                            ],
                            'data' => [
                                'role' => 'item.property.value',
                                'state' => 'hidden',
                                'value' => $arValue['id']
                            ]
                        ]) ?>
                            <?php if ($arProperty['type'] === 'picture' && !empty($arValue['picture'])) { ?>
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'widget-item-offers-property-value-image',
                                    ],
                                    'style' => [
                                        'background-image' => 'url('.$arValue['picture'].')'
                                    ]
                                ]) ?>
                                    <div class="intec-aligner"></div>
                                    <i class="far fa-check"></i>
                                <?= Html::endTag('div') ?>
                            <?php } else { ?>
                                <div class="widget-item-offers-property-value-text">
                                    <?= $arValue['name'] ?>
                                </div>
                            <?php } ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php };