<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arVisual['FAQ']['SHOW'] && !empty($arResult['DATA']['FAQ'])) {

    $arData = $arResult['DATA']['FAQ'];

?>
    <div class="catalog-element-faq">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-title" data-align="center">
                    <?= $arData['NAME'] ?>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.faq',
            'template.3', [
                'IBLOCK_ID' => $arData['IBLOCK_ID'],
                'FILTER' => [
                    'ID' => $arData['ID']
                ],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'HIDE' => 'Y',
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
    <?php unset($arData) ?>
<?php } ?>