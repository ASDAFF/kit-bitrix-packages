<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
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
                        'value' => $arValue['id'],
                        'state' => 'hidden'
                    ]
                ]) ?>
                    <?php if ($arProperty['type'] === 'picture' && !empty($arValue['picture'])) { ?>
                        <div class="catalog-element-offers-property-value-text">
                            <?= $arValue['name'] ?>
                        </div>
                        <div class="catalog-element-offers-property-value-image-wrap">
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
                        </div>
                    <?php } else { ?>
                        <div class="catalog-element-offers-property-value-text">
                            <?= $arValue['name'] ?>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>
