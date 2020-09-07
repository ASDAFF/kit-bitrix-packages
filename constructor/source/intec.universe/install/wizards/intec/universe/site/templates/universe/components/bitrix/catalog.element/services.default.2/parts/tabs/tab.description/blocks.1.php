<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arResult['BLOCKS']['USE'] && !empty($arTab['VALUE']['BLOCKS_1'])) { ?>
    <div class="catalog-element-block-description-item">
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.advantages',
            'template.26',[
                'IBLOCK_TYPE' => $arResult['BLOCKS']['IBLOCK']['TYPE'],
                'IBLOCK_ID' => $arResult['BLOCKS']['IBLOCK']['ID'],
                'FILTER' => [
                    'ID' => $arTab['VALUE']['BLOCKS_1']['ID']
                ],
                'PROPERTY_NAME' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_NAME'],
                'PROPERTY_IMAGES' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_IMAGES'],
                'PROPERTY_TEXT_ADDITIONAL' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_TEXT_ADDITIONAL'],
                'PROPERTY_THEME' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_THEME'],
                'PROPERTY_VIEW' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_VIEW'],
                'PROPERTY_DETAIL_NARROW' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_DETAIL_NARROW'],
                'PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW'],
                'PROPERTY_COMPACT_POSITION' => $arResult['BLOCKS']['SETTINGS']['PROPERTY_COMPACT_POSITION'],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'IMAGES_SHOW' => $arResult['BLOCKS']['SETTINGS']['IMAGES_SHOW'],
                'BUTTON_TEXT' => $arResult['BLOCKS']['SETTINGS']['BUTTON_TEXT'],
                'CACHE_TYPE' => 'N',
            ],
            $component
        ) ?>
    </div>
<?php } ?>