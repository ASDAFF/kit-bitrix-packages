<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="news-detail-videos">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.videos',
        'template.1',
        array(
            'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
            'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
            'FILTER' => [
                'ID' => $arBlock['IBLOCK']['ELEMENTS']
            ],
            'SETTINGS_USE' => 'N',
            'LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N',
            'PROPERTY_URL' => $arBlock['LINK'],
            'HEADER_SHOW' => 'Y',
            'HEADER' => $arBlock['HEADER']['VALUE'],
            'HEADER_POSITION' => $arBlock['HEADER']['POSITION'],
            'COLUMNS' => $arBlock['COLUMNS'],
            'DESCRIPTION_SHOW' => 'N',
            'FOOTER_SHOW' => 'N',
            'SLIDER_USE' => 'N'
        ),
        $component
    ) ?>
</div>