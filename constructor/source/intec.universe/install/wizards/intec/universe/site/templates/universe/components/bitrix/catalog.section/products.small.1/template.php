<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-products-small-1'
    ],
    'data' => [
        'borders' => $arVisual['BORDERS'] ? 'true' : 'false',
        'columns' => $arVisual['COLUMNS'],
        'position' => $arVisual['POSITION'],
        'size' => $arVisual['SIZE'],
        'wide' => $arVisual['WIDE'] ? 'true' : 'false',
        'slider' => $arVisual['SLIDER']['USE'] ? 'true' : 'false',
        'slider-dots' => $arVisual['SLIDER']['DOTS'] ? 'true' : 'false',
        'slider-navigation' => $arVisual['SLIDER']['NAVIGATION'] ? 'true' : 'false'
    ]
]) ?>
    <div class="catalog-section-wrapper intec-content intec-content-visible">
        <div class="catalog-section-wrapper-2 intec-content-wrapper">
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-section-items' => true,
                    'owl-carousel' => $arVisual['SLIDER']['USE'],
                    'intec-grid' => !$arVisual['SLIDER']['USE'] ? [
                        '' => true,
                        'wrap' => true,
                        'a-h-start' => $arVisual['POSITION'] === 'left',
                        'a-h-center' => $arVisual['POSITION'] === 'center',
                        'a-h-end' => $arVisual['POSITION'] === 'right',
                        'a-v-stretch' => true,
                        'i-5' => true
                    ] : false
                ], true),
                'data-role' => $arVisual['SLIDER']['USE'] ? 'slider' : null
            ]) ?>
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $arPrice = null;

                    $sPicture = $arItem['PICTURE'];

                    if (!empty($sPicture)) {
                        $sPicture = CFile::ResizeImageGet($sPicture, [
                            'width' => 115,
                            'height' => 115
                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                        if (!empty($sPicture))
                            $sPicture = $sPicture['src'];
                    }

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                    if (!empty($arItem['ITEM_PRICES']))
                        $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);

                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'catalog-section-item' => true,
                            'intec-grid-item' => $arVisual['WIDE'] ? [
                                $arVisual['COLUMNS'] => !$arVisual['SLIDER']['USE'],
                                '1000-3' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] > 3,
                                '720-2' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] > 2,
                                '450-1' => !$arVisual['SLIDER']['USE']
                            ] : [
                                $arVisual['COLUMNS'] => !$arVisual['SLIDER']['USE'],
                                '1100-3' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] > 3,
                                '800-2' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] > 2,
                                '500-1' => !$arVisual['SLIDER']['USE']
                            ]
                        ], true)
                    ]) ?>
                        <div id="<?= $sAreaId ?>" class="catalog-section-item-wrapper">
                            <?= Html::beginTag('a', [
                                'class' => [
                                    'catalog-section-item-image',
                                    'intec-image',
                                    'intec-image-effect'
                                ],
                                'href' => $arItem['DETAIL_PAGE_URL']
                            ]) ?>
                                <div class="intec-aligner"></div>
                                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                                    'alt' => $arItem['NAME'],
                                    'title' => $arItem['NAME'],
                                    'loading' => 'lazy',
                                    'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : null,
                                    'data-original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ]) ?>
                            <?= Html::endTag('a') ?>
                            <div class="catalog-section-item-information">
                                <div class="catalog-section-item-name intec-cl-text-hover">
                                    <?= Html::tag('a', $arItem['NAME'], [
                                        'class' => 'catalog-section-item-name-wrapper',
                                        'href' => $arItem['DETAIL_PAGE_URL']
                                    ]) ?>
                                </div>
                                <div class="catalog-section-item-price">
                                    <?php if (!empty($arPrice)) { ?>
                                        <div class="catalog-section-item-price-discount">
                                            <?php if (!empty($arItem['OFFERS'])) { ?>
                                                <?= Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_PRICE_FORM') ?>
                                            <?php } ?>
                                            <?= $arPrice['PRINT_PRICE'] ?>
                                        </div>
                                        <?php if ($arPrice['PERCENT'] > 0) { ?>
                                            <div class="catalog-section-item-price-base">
                                                <?php if (!empty($arItem['OFFERS'])) { ?>
                                                    <?= Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_PRICE_FORM') ?>
                                                <?php } ?>
                                                <?= $arPrice['PRINT_BASE_PRICE'] ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="intec-clearfix"></div>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>
