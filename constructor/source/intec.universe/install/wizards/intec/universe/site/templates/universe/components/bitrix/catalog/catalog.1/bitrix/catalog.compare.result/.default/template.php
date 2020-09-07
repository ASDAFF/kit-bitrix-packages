<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(false);

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];
$vLabel = include(__DIR__.'/parts/label.php');
$vProperty = include(__DIR__.'/parts/property.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => 'catalog-compare'
]) ?>
    <?php if ($arResult['AJAX']) $APPLICATION->RestartBuffer() ?>
    <?php if (!empty($arResult['ITEMS'])) { ?>
        <div class="catalog-compare-panel intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-10">
            <div class="intec-grid-item">
                <?= Html::beginTag('label', [
                    'class' => [
                        'intec-ui' => [
                            '',
                            'control-switch',
                            'scheme-current',
                            'size-2'
                        ]
                    ],
                    'data' => [
                        'action' => $arResult['DIFFERENT'] ? $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N' : $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y',
                        'role' => 'difference'
                    ]
                ]) ?>
                    <?= Html::checkbox(null, $arResult['DIFFERENT'], [
                        'onchange' => 'this.checked = !this.checked'
                    ]) ?>
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">
                        <?= Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_DIFFERENCE') ?>
                    </span>
                <?= Html::endTag('label') ?>
            </div>
            <div class="intec-grid-item-auto">
                <?= Html::tag('div', Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_CLEAR'), [
                    'class' => [
                        'intec-ui' => [
                            '',
                            'control-button',
                            'mod-round-5',
                            'mod-transparent',
                            'scheme-gray',
                            'size-2'
                        ]
                    ],
                    'data' => [
                        'role' => 'clear'
                    ]
                ]) ?>
            </div>
        </div>
        <div class="catalog-compare-items">
            <div class="catalog-compare-items-slider owl-carousel" data-role="slider">
                <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                <?php
                    $sPicture = $arItem['PREVIEW_PICTURE'];

                    if (empty($sPicture))
                        $sPicture = $arItem['DETAIL_PICTURE'];

                    if (!empty($sPicture)) {
                        $sPicture = $sPicture['SRC'];
                    }

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                ?>
                    <div class="catalog-compare-item" data-role="slide">
                        <div class="catalog-compare-item-content">
                            <a class="catalog-compare-item-picture" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                <?= Html::tag('div', null, [
                                    'class' => [
                                        'catalog-compare-item-picture-wrapper',
                                        'intec-image-effect'
                                    ],
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                    ]
                                ]) ?>
                            </a>
                            <div class="catalog-compare-item-name">
                                <a class="intec-cl-text-hover" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                    <?= $arItem['NAME'] ?>
                                </a>
                            </div>
                            <div class="catalog-compare-item-information intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                <div class="catalog-compare-item-price intec-grid-item">
                                    <?php if (!empty($arItem['MIN_PRICE'])) { ?>
                                        <?= $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] ?>
                                    <?php } ?>
                                </div>
                                <?php if ($arItem['ACTION'] === 'buy' && !empty($arItem['MIN_PRICE']) && $arItem['CAN_BUY']) { ?>
                                    <div class="catalog-compare-item-purchase intec-grid-item-auto">
                                        <?= Html::beginTag('div', [
                                            'class' => [
                                                'intec-ui',
                                                'intec-ui-control-basket-button',
                                                'catalog-compare-item-purchase-button',
                                                'catalog-compare-item-purchase-button-add',
                                                'intec-cl-text',
                                                'intec-cl-text-light-hover'
                                            ],
                                            'data' => [
                                                'basket-id' => $arItem['ID'],
                                                'basket-action' => 'add',
                                                'basket-state' => 'none',
                                                'basket-price' => $arItem['MIN_PRICE']['TYPE']
                                            ]
                                        ]) ?>
                                            <span class="intec-ui-part-content">
                                                <i class="glyph-icon-cart"></i>
                                            </span>
                                            <span class="intec-ui-part-effect intec-ui-part-effect-folding">
                                                <span class="intec-ui-part-effect-wrapper">
                                                    <i></i><i></i><i></i><i></i>
                                                </span>
                                            </span>
                                        <?= Html::endTag('div') ?>
                                        <?= Html::beginTag('a', [
                                            'href' => $arResult['URL']['BASKET'],
                                            'class' => [
                                                'catalog-compare-item-purchase-button',
                                                'catalog-compare-item-purchase-button-added',
                                                'intec-cl-background-light'
                                            ],
                                            'data' => [
                                                'basket-id' => $arItem['ID'],
                                                'basket-state' => 'none',
                                            ]
                                        ]) ?>
                                            <i class="intec-basket glyph-icon-cart"></i>
                                        <?= Html::endTag('a') ?>
                                    </div>
                                <?php } else if ($arItem['ACTION'] === 'detail') { ?>
                                    <?= Html::beginTag('a', [
                                        'href' => $arItem['DETAIL_PAGE_URL'],
                                        'class' => [
                                            'catalog-compare-item-purchase-button',
                                            'intec-cl-text',
                                            'intec-cl-text-light-hover'
                                        ]
                                    ]) ?>
                                        <i class="intec-basket glyph-icon-cart"></i>
                                    <?= Html::endTag('a') ?>
                                <?php } ?>
                            </div>
                        </div>
                        <?= Html::beginTag('div', [
                            'class' => 'catalog-compare-item-remove',
                            'data' => [
                                'action' => $arItem['~DELETE_URL'],
                                'role' => 'item.remove'
                            ]
                        ]) ?>
                            <i class="fal fa-times"></i>
                        <?= Html::endTag('div') ?>
                    </div>
                <?php } ?>
            </div>
            <div class="catalog-compare-items-navigation" data-role="navigation">
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-compare-items-navigation-button',
                        'intec-cl-border-hover',
                        'intec-cl-background-hover'
                    ],
                    'data' => [
                        'action' => 'next',
                        'role' => 'navigation.button'
                    ]
                ]) ?>
                    <i class="fal fa-angle-right"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-compare-items-navigation-button',
                        'intec-cl-border-hover',
                        'intec-cl-background-hover'
                    ],
                    'data' => [
                        'action' => 'previous',
                        'role' => 'navigation.button'
                    ]
                ]) ?>
                    <i class="fal fa-angle-left"></i>
                <?= Html::endTag('div') ?>
            </div>
        </div>
        <?php if ($arVisual['LABELS']['SHOW']) { ?>
            <div class="catalog-compare-labels intec-grid intec-grid-wrap intec-grid-i-5 intec-grid-a-h-start intec-grid-a-v-start" data-role="labels">
            <?php
                foreach ($arResult['PROPERTIES'] as $arProperty)
                    $vLabel($arProperty, $arItem);

                unset($arProperty)
            ?>
            </div>
        <?php } ?>
        <?php if ($arVisual['PROPERTIES']['SHOW']) { ?>
            <div class="catalog-compare-properties owl-carousel" data-role="properties">
                <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                    <div class="catalog-compare-properties-column">
                    <?php
                        foreach ($arResult['PROPERTIES'] as $arProperty)
                            $vProperty($arProperty, $arItem);

                        unset($arProperty);
                    ?>
                    </div>
                <?php } ?>
                <?php ?>
            </div>
        <?php } ?>
        <?php include(__DIR__.'/parts/script.php') ?>
    <?php } else { ?>
        <p><font class="notetext"><?= Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_EMPTY') ?>.</font></p>
    <?php } ?>
    <?php if ($arResult['AJAX']) exit() ?>
<?= Html::endTag('div') ?>