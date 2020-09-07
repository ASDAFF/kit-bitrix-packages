<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<div class="catalog-element-block-video">
    <?php foreach ($arTab['VALUE'] as $arProperty) { ?>
        <div class="catalog-element-block-video-item">
            <div class="intec-content">
                <div class="intec-content-wrapper">
                    <div class="catalog-element-title" data-align="center">
                        <?= $arProperty['NAME'] ?>
                    </div>
                </div>
            </div>
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:main.videos',
                'template.1', [
                    'IBLOCK_ID' => $arResult['VIDEO']['ID'],
                    'FILTER' => [
                        'ID' => $arProperty['ID']
                    ],
                    'HEADER_SHOW' => 'N',
                    'DESCRIPTION_SHOW' => 'N',
                    'COLUMNS' => $arVisual['TAB']['VIDEO']['COLUMNS'],
                    'PROPERTY_URL' => $arResult['VIDEO']['SETTINGS']['PROPERTY_URL'],
                    'PICTURE_SOURCES' => $arResult['VIDEO']['SETTINGS']['PICTURE_SOURCES'],
                    'PICTURE_SERVICE_QUALITY' => $arResult['VIDEO']['SETTINGS']['PICTURE_SERVICE_QUALITY'],
                    'SLIDER_USE' => 'N',
                    'CACHE_TYPE' => 'N',
                    'SORT_BY' => 'SORT',
                    'ORDER_BY' => 'ASC'
                ],
                $component
            ) ?>
        </div>
    <?php } ?>
</div>
<?php unset($arProperty) ?>