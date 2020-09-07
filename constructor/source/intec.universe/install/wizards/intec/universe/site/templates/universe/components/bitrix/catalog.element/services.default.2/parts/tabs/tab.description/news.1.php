<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if (!empty($arTab['VALUE']['NEWS_1'])) { ?>
    <div class="catalog-element-block-description-item">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-title" data-align="center">
                    <?= $arTab['VALUE']['NEWS_1']['NAME'] ?>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.news',
            'template.6',[
                'IBLOCK_ID' => $arTab['VALUE']['NEWS_1']['IBLOCK']['ID'],
                'FILTER' => [
                    'ID' => $arTab['VALUE']['NEWS_1']['ID']
                ],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'DATE_SHOW' => $arVisual['TAB']['DESCRIPTION']['NEWS_1']['DATE']['SHOW'],
                'DATE_FORMAT' => $arVisual['TAB']['DESCRIPTION']['NEWS_1']['DATE']['FORMAT'],
                'COLUMNS' => $arVisual['TAB']['DESCRIPTION']['NEWS_1']['COLUMNS'],
                'LINK_USE' => $arVisual['TAB']['DESCRIPTION']['NEWS_1']['LINK']['USE'],
                'LINK_BLANK' => $arVisual['TAB']['DESCRIPTION']['NEWS_1']['LINK']['BLANK'],
                'PREVIEW_SHOW' => 'Y',
                'CACHE_TYPE' => 'N',
            ],
            $component
        ) ?>
    </div>
<?php } ?>
