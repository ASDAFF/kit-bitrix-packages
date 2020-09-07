<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if (!empty($arTab['VALUE']['ADVANTAGES_1'])) { ?>
    <div class="catalog-element-block-description-item">
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.advantages',
            'template.25', [
                'IBLOCK_ID' => $arTab['VALUE']['ADVANTAGES_1']['IBLOCK']['ID'],
                'FILTER' => [
                    'ID' => $arTab['VALUE']['ADVANTAGES_1']['ID']
                ],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'COLUMNS' => $arVisual['TAB']['DESCRIPTION']['ADVANTAGES_1']['COLUMNS'],
                'PICTURE_SHOW' => 'Y',
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
<?php } ?>