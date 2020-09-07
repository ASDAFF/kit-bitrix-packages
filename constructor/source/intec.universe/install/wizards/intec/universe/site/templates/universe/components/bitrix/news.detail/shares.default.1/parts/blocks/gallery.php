<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="news-detail-gallery">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.gallery',
        'template.2',
        Array(
            'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
            'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
            'FILTER' => [
                'ID' => $arBlock['IBLOCK']['ELEMENTS']
            ],
            'SETTINGS_USE' => 'N',
            'LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N',
            'HEADER_SHOW' => 'Y',
            'HEADER_TEXT' => $arBlock['HEADER']['VALUE'],
            'HEADER_POSITION' => $arBlock['HEADER']['POSITION'],
            'LINE_COUNT' => $arBlock['COLUMNS'],
            'WIDE' => $arBlock['WIDE'],
            'DELIMITERS' => 'N',
            'DESCRIPTION_SHOW' => 'N',
            'FOOTER_SHOW' => 'N'
        ),
        $component
    ) ?>
</div>