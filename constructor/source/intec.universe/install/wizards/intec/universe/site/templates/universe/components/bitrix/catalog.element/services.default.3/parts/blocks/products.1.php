<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

$GLOBALS['arServicesProductsFilter'] = [
    'ID' => $arBlock['IBLOCK']['ELEMENTS']
];

?>
<div class="catalog-element-products widget">
    <div class="catalog-element-products-wrapper intec-content intec-content-visible">
        <div class="catalog-element-products-wrapper-2 intec-content-wrapper">
            <?php if (!empty($arBlock['HEADER']['VALUE'])) { ?>
                <div class="catalog-element-products-header widget-header">
                    <?= Html::tag('div', $arBlock['HEADER']['VALUE'], [
                        'class' => [
                            'widget-title',
                            'align-'.$arBlock['HEADER']['POSITION']
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <div class="catalog-element-products-content widget-content">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.section',
                    'products.small.1',
                    array(
                        'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
                        'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
                        'SECTION_USER_FIELDS' => array(),
                        'SHOW_ALL_WO_SECTION' => 'Y',
                        'FILTER_NAME' => 'arServicesProductsFilter',
                        'PRICE_CODE' => $arBlock['PRICE_CODE'],
                        'CONVERT_CURRENCY' => 'N',
                        'COLUMNS' => 4,
                        'BORDERS' => 'Y',
                        'POSITION' => 'left',
                        'SIZE' => 'small',
                        'SLIDER_USE' => 'N',
                        'WIDE' => 'N',
                        'SETTINGS_USE' => 'N',
                        'LAZYLOAD_USE' => $arResult['LAZYLOAD']['USE'] ? 'Y' : 'N'
                    ),
                    $component
                ) ?>
            </div>
        </div>
    </div>
</div>