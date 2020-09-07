<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arResult['VIDEO']['USE'] && $arVisual['VIDEO']['SHOW'] && !empty($arResult['DATA']['VIDEO'])) {

    $arData = $arResult['DATA']['VIDEO'];

?>
    <div class="catalog-element-videos">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-title" data-align="center">
                    <?= $arData['NAME'] ?>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.videos',
            'template.2', [
                'IBLOCK_TYPE' => $arResult['VIDEO']['TYPE'],
                'IBLOCK_ID' => $arResult['VIDEO']['ID'],
                'FILTER' => [
                    'ID' => $arData['ID']
                ],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'PROPERTY_URL' => $arResult['VIDEO']['SETTINGS']['PROPERTY_URL'],
                'PICTURE_SOURCES' => $arResult['VIDEO']['SETTINGS']['PICTURE_SOURCES'],
                'PICTURE_SERVICE_QUALITY' => $arResult['VIDEO']['SETTINGS']['PICTURE_SERVICE_QUALITY'],
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
    <?php unset($arData) ?>
<?php } ?>