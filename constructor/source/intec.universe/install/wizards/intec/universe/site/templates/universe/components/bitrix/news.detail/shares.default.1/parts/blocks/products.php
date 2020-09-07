<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

$GLOBALS['arrFilter'] = array(
    'ID' => $arBlock['IBLOCK']['ELEMENTS']
);

$sPrefix = 'PRODUCTS_';
$arProducts['PARAMETERS'] = [];

foreach ($arParams as $sKey => $mValue) {
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arProducts['PARAMETERS'][$sKey] = $mValue;
    }
}

$arProducts['PARAMETERS'] = ArrayHelper::merge($arProducts['PARAMETERS'], [
    'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
    'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
    'SHOW_ALL_WO_SECTION' => 'Y',
    'FILTER_NAME' => 'arrFilter',
    'PRODUCT_DISPLAY_MODE' => 'Y',
    'OFFER_TREE_PROPS' => ArrayHelper::getValue($arProducts, ['PARAMETERS', 'OFFERS_PROPERTY_CODE']),
    'LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N',
    'QUICK_VIEW_LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N'
]);

?>
<div class="news-detail-products widget">
    <div class="news-detail-products-wrapper intec-content intec-content-visible">
        <div class="news-detail-products-wrapper-2 intec-content-wrapper">
            <?php if (!empty($arBlock['HEADER'])) { ?>
                <div class="news-detail-products-header widget-header">
                    <?= Html::tag('div', $arBlock['HEADER']['VALUE'], [
                        'class' => [
                            'widget-title',
                            'align-'.$arBlock['HEADER']['POSITION']
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <div class="news-detail-products-content widget-content">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.section',
                    'catalog.tile.1',
                    $arProducts['PARAMETERS'],
                    $component
                ) ?>
            </div>
        </div>
    </div>
</div>
