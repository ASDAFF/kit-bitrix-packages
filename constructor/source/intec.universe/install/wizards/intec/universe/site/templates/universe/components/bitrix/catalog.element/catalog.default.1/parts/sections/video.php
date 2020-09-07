<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-section-videos">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.videos',
        'template.1',
        [
            'IBLOCK_TYPE' => $arParams['VIDEO_IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['VIDEO_IBLOCK_ID'],
            'PROPERTY_URL' => $arParams['VIDEO_PROPERTY_URL'],
            'FILTER' => [
                'ID' => $arResult['VIDEO']
            ],
            'PICTURE_SERVICE_QUALITY' => 'sddefault',
            'SLIDER_USE' => 'N',
            'HEADER_SHOW' => 'N',
            'DESCRIPTION_SHOW' => 'N',
            'FOOTER_SHOW' => 'N',
            'CONTENT_POSITION' => 'left',
            'COLUMNS' => !(
                $arVisual['VIEW']['VALUE'] === 'tabs' &&
                $arVisual['VIEW']['POSITION'] === 'right' &&
                $arVisual['GALLERY']['SHOW']
            ) ? 3 : 2
        ],
        $component
    ) ?>
</div>