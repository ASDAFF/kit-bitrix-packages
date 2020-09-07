<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arVisual['RECOMMEND']['SHOW'] && !empty($arResult['DATA']['RECOMMEND'])) {

$arData = $arResult['DATA']['RECOMMEND'];

?>
    <div class="catalog-element-recommend">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-recommend-title intec-grid intec-grid-i-h-15 intec-grid-768-wrap">
                    <div class="intec-grid-item intec-grid-item-768-1">
                        <div class="catalog-element-title" data-align="left">
                            <?= $arData['NAME'] ?>
                        </div>
                    </div>
                    <div class="intec-grid-item intec-grid-item-768-1">
                        <div class="catalog-element-recommend-description" data-align="left">
                            <?= $arData['DESCRIPTION'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.categories',
            'template.10', [
                'IBLOCK_ID' => $arData['IBLOCK'],
                'FILTER' => [
                    'ID' => $arData['ID']
                ],
                'LINK_MODE' => 'component',
                'PROPERTY_PRICE' => $arData['PROPERTY']['PRICE'],
                'PROPERTY_OLD_PRICE' => $arData['PROPERTY']['PRICE_OLD'],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'COLUMNS' => 4,
                'LINK_USE' => 'Y',
                'PRICE_USE' => !empty($arData['PROPERTY']['PRICE']) ? 'Y' : 'N',
                'PROPERTY_MARK_HIT' => $arResult['MARKS']['HIT'],
                'PROPERTY_MARK_NEW' => $arResult['MARKS']['NEW'],
                'PROPERTY_MARK_RECOMMEND' => $arResult['MARKS']['RECOMMEND'],
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
    <?php unset($arData) ?>
<?php } ?>