<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 */

Loc::loadMessages(__FILE__);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

/**
 * @var array $arData
 */
include(__DIR__.'/parts/data.php');

$arVisual = $arResult['VISUAL'];
$arPrice = null;

if (!empty($arResult['ITEM_PRICES']))
    $arPrice = ArrayHelper::getFirstValue($arResult['ITEM_PRICES']);

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-element',
        'c-catalog-element-quick-view-1',
        'catalog-element-scroll'
    ],
    'data' => [
        'data' => Json::encode($arData, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'properties' => Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'available' => $arData['available'] ? 'true' : 'false',
        'wide' => $arVisual['WIDE'] ? 'true' : 'false',
        'scroll' => 'true'
    ]
]) ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="catalog-element intec-grid">
                <div class="catalog-element-left intec-grid-item-2">
                    <div class="itec-grid-item-2 catalog-element-name">
                        <?= $arResult['NAME'] ?>
                    </div>
                    <?php if ($arVisual['WEIGHT']['SHOW']) { ?>
                        <div class="catalog-element-weight" data-role="weight"></div>
                    <?php } ?>
                    <?php if ($arVisual['MARKS']['SHOW']) { ?>
                        <div class="catalog-element-marks">
                            <?php $APPLICATION->IncludeComponent(
                                'intec.universe:main.markers',
                                'template.1', [
                                    'RECOMMEND' => $arResult['MARKS']['RECOMMEND'] ? 'Y' : 'N',
                                    'NEW' => $arResult['MARKS']['NEW'] ? 'Y' : 'N',
                                    'HIT' => $arResult['MARKS']['HIT'] ? 'Y' : 'N',
                                    'ORIENTATION' => 'horizontal'
                                ],
                                $component,
                                ['HIDE_ICONS' => 'Y']
                            ) ?>
                        </div>
                    <?php } ?>
                    <div class="catalog-element-gallery-block">
                        <?php include(__DIR__.'/parts/gallery.php') ?>
                    </div>
                </div>
                <div class="catalog-element-right intec-grid-item-2">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-purchase-block',
                            'intec-grid' => [
                                '',
                                'wrap',
                                'a-v-center'
                            ]
                        ],
                        'data' => [
                            'role' => 'price',
                            'show' => !empty($arPrice) ? 'true' : 'false',
                            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
                        ]
                    ])?>
                        <?php if ($arVisual['QUANTITY']['SHOW']) { ?>
                            <div class="catalog-element-quantity intec-grid-item-1">
                                <?php include(__DIR__.'/parts/quantity.php') ?>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['ACTION'] !== 'none') { ?>
                            <div class="catalog-element-purchase intec-grid-item-auto">
                                <?php include(__DIR__.'/parts/purchase.php') ?>
                            </div>
                            <div class="catalog-element-counter intec-grid-item-auto">
                                <?php include(__DIR__.'/parts/counter.php') ?>
                            </div>
                        <?php } ?>
                        <div class="catalog-element-discount intec-grid-item-1">
                            <div class="catalog-element-discount-percent" data-role="price.percent">
                                <?= !empty($arPrice) ? '-'.$arPrice['PERCENT'].'%' : null ?>
                            </div>
                            <div class="catalog-element-discount-price" data-role="price.base">
                                <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                            </div>
                        </div>
                        <?php if ($arVisual['DESCRIPTION']['SHOW']) { ?>
                            <div class="catalog-element-text intec-grid-item-1">
                                <?= strip_tags($arResult[$arVisual['DESCRIPTION']['MODE'] === 'preview' ? 'PREVIEW_TEXT' : 'DETAIL_TEXT'], '<br>') ?>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arResult['OFFERS'])) { ?>
                            <div class="catalog-element-offers intec-grid-item-1">
                                <?php include(__DIR__.'/parts/sku.php') ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['TEXT']['SHOW']) { ?>
                            <div class="catalog-element-description intec-grid-item-1">
                                <?= $arResult['TEXT'] ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['INFORMATION']['PAYMENT'] || $arVisual['INFORMATION']['SHIPMENT']) { ?>
                            <div class="catalog-element-information">
                                <?php include(__DIR__.'/parts/information.php') ?>
                            </div>
                        <?php } ?>
                    <?= Html::endTag('div') ?>
                </div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>
<?php include(__DIR__.'/parts/script.php') ?>