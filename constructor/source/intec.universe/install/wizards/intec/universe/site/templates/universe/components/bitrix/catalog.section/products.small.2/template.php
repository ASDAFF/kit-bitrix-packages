<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

/**
 * @var Closure $vCounter
 * @var Closure $dData
 * @var Closure $vImage
 * @var Closure $vPrice
 * @var Closure $vPurchase
 */
include(__DIR__.'/parts/counter.php');
include(__DIR__.'/parts/data.php');
include(__DIR__.'/parts/image.php');
include(__DIR__.'/parts/price.php');
include(__DIR__.'/parts/purchase.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-products-small-2'
    ]
]) ?>
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-section-items',
                    'intec-grid' => [
                        '',
                        'wrap',
                        'a-h-start',
                        'a-v-stretch'
                    ]
                ]
            ]) ?>
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $bOffer = false;


                    $sData = Json::encode($dData($arItem), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true);
                    $arPrice = null;

                    if (empty($arItem['OFFERS'])) {
                        $arPrice = $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);
                    } else {
                        $bOffer = true;
                        $arPrice = $arItem['MIN_PRICE'];
                    }

                ?>
                    <?= Html::beginTag('div', [
                        'id' => $sAreaId,
                        'class' => Html::cssClassFromArray([
                            'catalog-section-item' => true,
                            'intec-grid-item' => [
                                '' => true,
                                $arVisual['COLUMNS'] => true,
                                '950-3' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 4,
                                '950-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                                '720-2' => $arVisual['COLUMNS'] >= 3,
                                '450-1' => $arVisual['COLUMNS'] >= 2
                            ]
                        ], true),
                        'data' => [
                            'role' => 'item',
                            'data' => $sData,
                            'available' => $arItem['CAN_BUY'] ? 'true' : 'false',
                            'expanded' => 'false',
                            'action' => $arItem['ACTION'] !== 'none' ? 'true' : 'false',
                            'border' => $arVisual['BORDERS'] ? 'true' : 'false'
                        ]
                    ]) ?>
                        <div class="catalog-section-item-wrapper">
                            <div class="catalog-section-item-base">
                                <div class="catalog-section-item-picture-block">
                                    <?php $vImage($arItem) ?>
                                </div>
                                <?= Html::tag('a', $arItem['NAME'], [
                                    'href' => Html::decode($arItem['DETAIL_PAGE_URL']),
                                    'class' => [
                                        'catalog-section-item-name',
                                        'intec-cl-text-hover'
                                    ],
                                    'title' => $arItem['NAME'],
                                    'data' => [
                                        'align' => $arVisual['NAME']['ALIGN']
                                    ]
                                ]) ?>
                                <div class="catalog-section-item-price">
                                    <?php $vPrice($arPrice, $bOffer) ?>
                                </div>
                            </div>
                            <?php if ($arItem['ACTION'] !== 'none') { ?>
                                <div class="catalog-section-item-advanced">
                                    <div class="intec-grid intec-grid-a-v-center">
                                        <?php if ($arVisual['COUNTER']['SHOW']) { ?>
                                            <div class="catalog-section-item-counter intec-grid-item">
                                                <?php $vCounter() ?>
                                            </div>
                                        <?php } ?>
                                        <div class="catalog-section-item-purchase intec-grid-item">
                                            <?php $vPurchase($arItem) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
<?= Html::endTag('div') ?>
<?php include(__DIR__.'/parts/script.php') ?>
<?php unset($vCounter, $dData, $vImage, $vPrice, $vPurchase) ?>
