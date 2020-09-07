<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arVisual['ADVANTAGES']['SHOW'] && !empty($arResult['DATA']['ADVANTAGES'])) {

    $arData = $arResult['DATA']['ADVANTAGES'];

?>
    <div class="catalog-element-advantages">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-title" data-align="center">
                    <?= $arData['NAME'] ?>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.advantages',
            'template.2', [
                'IBLOCK_ID' => $arData['IBLOCK'],
                'FILTER' => [
                    'ID' => $arData['ID']
                ],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'LINE_COUNT' => $arVisual['ADVANTAGES']['COLUMNS'],
                'COLUMNS' => $arVisual['ADVANTAGES']['COLUMNS'],
                'VIEW' => 'number',
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
    <?php unset($arData) ?>
<?php } ?>