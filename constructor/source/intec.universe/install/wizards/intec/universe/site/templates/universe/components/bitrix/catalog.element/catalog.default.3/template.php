<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

Loc::loadMessages(__FILE__);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];

if ($arResult['TAB']['USE'] && !empty($arResult['TAB']['VALUE']))
    return;

/**
 * @var array $arData
 */
include(__DIR__.'/parts/data.php');
include(__DIR__.'/parts/price.range.php');

$arPrice = null;

if (!empty($arResult['ITEM_PRICES']))
    $arPrice = ArrayHelper::getFirstValue($arResult['ITEM_PRICES']);

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-element',
        'c-catalog-element-catalog-default-3'
    ],
    'data' => [
        'data' => Json::encode($arData, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'properties' => !empty($arResult['SKU_PROPS']) ? Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
        'available' => $arData['available'] ? 'true' : 'false',
        'wide' => $arVisual['WIDE'] ? 'true' : 'false',
        'panel-mobile' => $arVisual['PANEL']['MOBILE']['SHOW'] ? 'true' : 'false'
    ]
]) ?>
    <?php if ($arVisual['PANEL']['DESKTOP']['SHOW']) { ?>
        <!--noindex-->
        <? include(__DIR__.'/parts/panel.php') ?>
        <!--/noindex-->
    <?php } ?>
    <?php if ($arVisual['PANEL']['MOBILE']['SHOW']) { ?>
        <!--noindex-->
        <? include(__DIR__.'/parts/panel.mobile.php') ?>
        <!--/noindex-->
    <?php } ?>
    <div class="catalog-element-content intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <div class="intec-grid intec-grid-wrap">
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'intec-grid-item' => [
                            '2' => true,
                            '720-1' => $arVisual['WIDE'],
                            '1000-1' => !$arVisual['WIDE']
                        ]
                    ], true),
                ]) ?>
                    <div class="catalog-element-block-left">
                        <div class="catalog-element-gallery-block">
                            <?php if ($arVisual['MARKS']['SHOW']) { ?>
                                <div class="catalog-element-marks">
                                    <?php $APPLICATION->IncludeComponent(
                                        'intec.universe:main.markers',
                                        'template.1',
                                        $arResult['MARKS'],
                                        $component
                                    ) ?>
                                </div>
                            <?php } ?>
                            <?php include(__DIR__.'/parts/gallery.php') ?>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'intec-grid-item' => [
                            '2' => true,
                            '720-1' => $arVisual['WIDE'],
                            '1000-1' => !$arVisual['WIDE']
                        ]
                    ], true)
                ]) ?>
                    <div class="catalog-element-block-right">
                        <div class="intec-grid intec-grid-i-h-10">
                            <div class="intec-grid-item">
                                <?php if ($arVisual['ARTICLE']['SHOW']) { ?>
                                    <?php include(__DIR__.'/parts/article.php') ?>
                                <?php } ?>
                            </div>
                            <div class="intec-grid-item-auto">
                                <?php if ($arVisual['BRAND']['SHOW']) { ?>
                                    <?php include(__DIR__.'/parts/brand.php') ?>
                                <?php } ?>
                            </div>
                            <?php if ($arVisual['PRINT']['SHOW']) { ?>
                                <div class="catalog-element-print-wrap intec-grid-item-auto">
                                    <div class="catalog-element-print" data-role="print">
                                        <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.7427 5.12061H0.742676V14.1206H4.74268V18.1206H16.7427V14.1206H20.7427V5.12061ZM14.7427 16.1206H6.74268V11.1206H14.7427V16.1206ZM17.7427 9.12061C17.1927 9.12061 16.7427 8.67061 16.7427 8.12061C16.7427 7.57061 17.1927 7.12061 17.7427 7.12061C18.2927 7.12061 18.7427 7.57061 18.7427 8.12061C18.7427 8.67061 18.2927 9.12061 17.7427 9.12061ZM16.7427 0.120605H4.74268V4.12061H16.7427V0.120605Z" />
                                        </svg>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="intec-grid-item-auto">
                                <?php if ($arResult['SHARES']['SHOW']) { ?>
                                    <?php include(__DIR__.'/parts/shares.php') ?>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if (empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'dynamic') { ?>
                            <?php include(__DIR__.'/parts/price.php') ?>
                            <?php if ($arVisual['PRICE']['RANGE']) { ?>
                                <div class="catalog-element-price-ranges">
                                    <?php $vPriceRange($arResult);

                                    if (!empty($arResult['OFFERS']))
                                        foreach ($arResult['OFFERS'] as &$arOffer) {
                                            $vPriceRange($arOffer, true);

                                            unset($arOffer);
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['FORM']['CHEAPER']['SHOW']) { ?>
                                <?php include(__DIR__.'/parts/cheaper.php') ?>
                            <?php } ?>
                            <?php if ($arResult['ACTION'] !== 'none') { ?>
                                <div class="catalog-element-purchase-block intec-grid intec-grid-wrap intec-grid-a-v-center">
                                    <?php if ($arVisual['COUNTER']['SHOW']) { ?>
                                        <?= Html::beginTag('div', [
                                            'class' => Html::cssClassFromArray([
                                                'catalog-element-counter' => true,
                                                'intec-grid-item' => [
                                                    'auto' => true,
                                                    '500-1' => true
                                                ]
                                            ], true)
                                        ]) ?>
                                            <?php include(__DIR__.'/parts/counter.php') ?>
                                        <?= Html::endTag('div') ?>
                                    <?php } ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'catalog-element-purchase' => true,
                                            'intec-grid-item' => [
                                                'auto' => true
                                            ]
                                        ], true),
                                        'data' => [
                                            'role' => 'purchase'
                                        ]
                                    ]) ?>
                                        <?php include(__DIR__.'/parts/purchase.php') ?>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?php } ?>
                        <?php } elseif(!empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'list') { ?>
                            <div class="catalog-element-information-part intec-grid intec-grid-wrap intec-grid-i-5 intec-grid-a-h-start intec-grid-a-v-center">
                                <div class="intec-grid-item">
                                    <?php if (!empty($arPrice)) { ?>
                                        <div class="catalog-element-price-discount intec-grid-item-auto" data-role="price.discount">
                                            <?= Loc::GetMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PRICE_FROM');?>
                                            <?= $arPrice['PRINT_PRICE'];?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if ($arResult['FORM']['CHEAPER']['SHOW']) { ?>
                                    <?php include(__DIR__.'/parts/cheaper.php') ?>
                                <?php } ?>
                                <div class="intec-grid-item-auto intec-grid-item-shrink-1">
                                    <div class="intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-5 intec-ui-mod-round-half" onclick="(function () {
                                            var id = <?= JavaScript::toObject('#'.$sTemplateId.'-'.'sku-list') ?>;
                                            var content = $(id);

                                            $(document).scrollTo(content, 500);
                                            })()"
                                    >
                                        <?= Loc::GetMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SKU_MORE');?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['ADDITIONAL']['SHOW']) { ?>
                            <div class="catalog-element-additional-products">
                                <?php include(__DIR__.'/parts/additional.php') ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['DESCRIPTION']['SHOW']) { ?>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'catalog-element-description',
                                    'catalog-element-section'
                                ],
                                'data' => [
                                    'role' => 'section',
                                    'expanded' => $arVisual['DESCRIPTION']['EXPANDED'] ? 'true' : 'false'
                                ]
                            ]) ?>
                                <div class="catalog-element-section-name intec-ui-markup-header">
                                    <div class="catalog-element-section-name-wrapper">
                                        <span data-role="section.name">
                                            <?php if (!empty($arVisual['DESCRIPTION']['NAME'])) { ?>
                                                <?= $arVisual['DESCRIPTION']['NAME'] ?>
                                            <?php } else { ?>
                                                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_DESCRIPTION_NAME') ?>
                                            <?php } ?>
                                        </span>
                                        <div class="catalog-element-section-name-decoration" data-role="section.name"></div>
                                    </div>
                                </div>
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'catalog-element-section-content',
                                        'catalog-element-description-value',
                                        'intec-ui-markup-text'
                                    ],
                                    'data' => [
                                        'role' => 'section.content'
                                    ]
                                ]) ?>
                                    <div class="catalog-element-section-content-wrapper">
                                        <?= strip_tags($arResult[strtoupper($arVisual['DESCRIPTION']['MODE'].'_TEXT')], '<br>') ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                        <?php if (!empty($arResult['SKU_PROPS']) && !empty($arResult['OFFERS']) && $arResult['SKU_VIEW'] == 'dynamic') { ?>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'catalog-element-offers',
                                    'catalog-element-section'
                                ],
                                'data' => [
                                    'role' => 'section',
                                    'expanded' => $arVisual['OFFERS']['EXPANDED'] ? 'true' : 'false'
                                ]
                            ]) ?>
                                <div class="catalog-element-section-name intec-ui-markup-header">
                                    <div class="catalog-element-section-name-wrapper">
                                        <span data-role="section.name">
                                            <?php if (!empty($arVisual['OFFERS']['NAME'])) { ?>
                                                <?= $arVisual['OFFERS']['NAME'] ?>
                                            <?php } else { ?>
                                                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_OFFERS_NAME') ?>
                                            <?php } ?>
                                        </span>
                                        <div class="catalog-element-section-name-decoration" data-role="section.name"></div>
                                    </div>
                                </div>
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'catalog-element-offers-wrapper',
                                        'catalog-element-section-content'
                                    ],
                                    'data' => [
                                        'role' => 'section.content'
                                    ]
                                ]) ?>
                                    <div class="catalog-element-section-content-wrapper">
                                        <?php include(__DIR__.'/parts/sku.php') ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                        <?php if ($arVisual['PROPERTIES']['SHOW'] && !empty($arResult['DISPLAY_PROPERTIES'])) { ?>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'catalog-element-properties',
                                    'catalog-element-section'
                                ],
                                'data' => [
                                    'role' => 'section',
                                    'expanded' => $arVisual['PROPERTIES']['EXPANDED'] ? 'true' : 'false'
                                ]
                            ]) ?>
                                <div class="catalog-element-section-name intec-ui-markup-header">
                                    <div class="catalog-element-section-name-wrapper">
                                        <span data-role="section.name">
                                            <?php if (!empty($arVisual['PROPERTIES']['NAME'])) { ?>
                                                <?= $arVisual['PROPERTIES']['NAME'] ?>
                                            <?php } else { ?>
                                                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PROPERTIES_NAME') ?>
                                            <?php } ?>
                                        </span>
                                        <div class="catalog-element-section-name-decoration" data-role="section.name"></div>
                                    </div>
                                </div>
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'catalog-element-properties-wrapper',
                                        'catalog-element-section-content'
                                    ],
                                    'data' => [
                                        'role' => 'section.content'
                                    ]
                                ]) ?>
                                    <div class="catalog-element-section-content-wrapper">
                                        <?php include(__DIR__.'/parts/properties.php') ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                        <?php if ($arVisual['INFORMATION']['PAYMENT']['SHOW'] || $arVisual['INFORMATION']['SHIPMENT']['SHOW']) { ?>
                            <div class="catalog-element-information">
                                <?php include(__DIR__.'/parts/information.php') ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['ADVANTAGES']['SHOW']) { ?>
                            <div class="catalog-element-advantages">
                                <?php include(__DIR__.'/parts/advantages.php') ?>
                            </div>
                        <?php } ?>
                    </div>
                <?= Html::endTag('div') ?>
            </div>
            <?php if (!empty($arResult['OFFERS']) && $arResult['SKU_VIEW'] == 'list') {
                include(__DIR__.'/parts/sku.list.php');
            } ?>
            <?php if ($arVisual['ASSOCIATED']['SHOW']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-associated',
                        'catalog-element-section'
                    ],
                    'data' => [
                        'role' => 'section',
                        'expanded' => $arVisual['ASSOCIATED']['EXPANDED'] ? 'true' : 'false'
                    ]
                ]) ?>
                    <div class="catalog-element-section-name intec-ui-markup-header">
                        <div class="catalog-element-section-name-wrapper">
                            <span data-role="section.name">
                                <?php if (!empty($arVisual['ASSOCIATED']['NAME'])) { ?>
                                    <?= $arVisual['ASSOCIATED']['NAME'] ?>
                                <?php } else { ?>
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ASSOCIATED_NAME') ?>
                                <?php } ?>
                            </span>
                            <div class="catalog-element-section-name-decoration" data-role="section.name"></div>
                        </div>
                    </div>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-associated-wrapper',
                            'catalog-element-section-content'
                        ],
                        'data' => [
                            'role' => 'section.content'
                        ]
                    ]) ?>
                        <div class="catalog-element-section-content-wrapper">
                            <?php include(__DIR__.'/parts/associated.php') ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php if ($arVisual['RECOMMENDED']['SHOW']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-associated',
                        'catalog-element-section'
                    ],
                    'data' => [
                        'role' => 'section',
                        'expanded' => $arVisual['RECOMMENDED']['EXPANDED'] ? 'true' : 'false'
                    ]
                ]) ?>
                    <div class="catalog-element-section-name intec-ui-markup-header">
                        <div class="catalog-element-section-name-wrapper">
                                <span data-role="section.name">
                                    <?php if (!empty($arVisual['RECOMMENDED']['NAME'])) { ?>
                                        <?= $arVisual['RECOMMENDED']['NAME'] ?>
                                    <?php } else { ?>
                                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_RECOMMEND_NAME') ?>
                                    <?php } ?>
                                </span>
                            <div class="catalog-element-section-name-decoration" data-role="section.name"></div>
                        </div>
                    </div>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-associated-wrapper',
                            'catalog-element-section-content'
                        ],
                        'data' => [
                            'role' => 'section.content'
                        ]
                    ]) ?>
                        <div class="catalog-element-section-content-wrapper">
                            <?php include(__DIR__.'/parts/recommended.php') ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php if ($arVisual['SERVICES']['SHOW']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-associated',
                        'catalog-element-section'
                    ],
                    'data' => [
                        'role' => 'section',
                        'expanded' => $arVisual['SERVICES']['EXPANDED'] ? 'true' : 'false'
                    ]
                ]) ?>
                    <div class="catalog-element-section-name intec-ui-markup-header">
                        <div class="catalog-element-section-name-wrapper">
                            <span data-role="section.name">
                                <?php if (!empty($arVisual['SERVICES']['NAME'])) { ?>
                                    <?= $arVisual['SERVICES']['NAME'] ?>
                                <?php } else { ?>
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_SERVICES_NAME') ?>
                                <?php } ?>
                            </span>
                            <div class="catalog-element-section-name-decoration" data-role="section.name"></div>
                        </div>
                    </div>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-associated-wrapper',
                            'catalog-element-section-content'
                        ],
                        'data' => [
                            'role' => 'section.content'
                        ]
                    ]) ?>
                        <div class="catalog-element-section-content-wrapper">
                            <?php include(__DIR__.'/parts/services.php') ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php include(__DIR__.'/parts/microdata.php') ?>
        </div>
    </div>
<?= Html::endTag('div') ?>
<?php include(__DIR__.'/parts/script.php') ?>
