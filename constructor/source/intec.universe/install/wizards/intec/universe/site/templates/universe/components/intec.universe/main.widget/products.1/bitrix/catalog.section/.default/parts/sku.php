<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

/**
 * @param array $arSku
 */
$vSku = function ($arSku) use (&$arVisual) { ?>
    <div class="widget-item-offers-properties">
        <?php foreach ($arSku as $arProperty) { ?>
            <?= Html::beginTag('div', [
                'class' => 'widget-item-offers-property',
                'data' => [
                    'role' => 'item.property',
                    'property' => $arProperty['code'],
                    'type' => $arProperty['type'],
                    'visible' => 'false'
                ]
            ]) ?>
                <div class="widget-item-offers-property-title" data-align="<?= $arVisual['OFFERS']['ALIGN'] ?>">
                    <?= $arProperty['name'] ?>
                </div>
                <div class="widget-item-offers-property-values" data-align="<?= $arVisual['OFFERS']['ALIGN'] ?>">
                    <?php foreach ($arProperty['values'] as $arValue) { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'widget-item-offers-property-value',
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
                                    'class' => 'widget-item-offers-property-value-image',
                                    'style' => [
                                        'background-image' => 'url(\''.$arValue['picture'].'\')'
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
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php };