<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if (!empty($arTab['VALUE']['ADVANTAGES_2'])) { ?>
    <div class="catalog-element-block-description-item">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-title" data-align="center">
                    <?= $arTab['VALUE']['ADVANTAGES_2']['NAME'] ?>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.advantages',
            'template.28', [
                'IBLOCK_ID' => $arTab['VALUE']['ADVANTAGES_2']['IBLOCK']['ID'],
                'FILTER' => [
                    'ID' => $arTab['VALUE']['ADVANTAGES_2']['ID']
                ],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'PICTURE_SHOW' => 'Y',
                'HIDE' => 'Y',
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
<?php } ?>