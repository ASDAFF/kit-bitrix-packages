<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="news-detail-conditions">
    <?$APPLICATION->IncludeComponent(
        "intec.universe:main.advantages",
        "template.10",
        Array(
            'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
            'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
            'FILTER' => [
                'ID' => $arBlock['IBLOCK']['ELEMENTS']
            ],
            'SETTINGS_USE' => 'N',
            'LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N',
            'HEADER_SHOW' => 'Y',
            'HEADER' => $arBlock['HEADER']['VALUE'],
            'HEADER_POSITION' => $arBlock['HEADER']['POSITION'],
            'DESCRIPTION_SHOW' => 'N',
            'COLUMNS' => $arBlock['COLUMNS'],
            'PICTURE_SHOW' => 'N',
            'NAME_SHOW' => 'Y',
            'PREVIEW_SHOW' => 'Y',
            'SECTIONS' => null,
            'CACHE_TYPE' => 'N'
        ),
        $component
    );?>
</div>