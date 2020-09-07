<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arResult['LINKS']['USE'] && !empty($arTab['VALUE']['LINKS_1'])) { ?>
    <div class="catalog-element-block-description-item">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-title" data-align="center">
                    <?= $arTab['VALUE']['LINKS_1']['NAME'] ?>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.categories',
            'template.12',[
                'IBLOCK_ID' => $arResult['LINKS']['IBLOCK']['ID'],
                'FILTER' => [
                    'ID' => $arTab['VALUE']['LINKS_1']['ID']
                ],
                'PROPERTY_LINK' => $arResult['LINKS']['SETTINGS']['PROPERTY_LINK'],
                'PROPERTY_NAME' => $arResult['LINKS']['SETTINGS']['PROPERTY_NAME'],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'COLUMNS' => $arVisual['TAB']['DESCRIPTION']['LINKS_1']['COLUMNS'],
                'LINK_USE' => 'Y',
                'LINK_BLANK' => $arVisual['TAB']['DESCRIPTION']['LINKS_1']['BLANK'],
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
<?php } ?>