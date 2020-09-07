<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arResult['REVIEWS']['USE'] && $arVisual['REVIEWS']['SHOW'] && !empty($arResult['DATA']['REVIEWS'])) {

    $arData = $arResult['DATA']['REVIEWS'];

?>
    <!--noindex-->
    <div class="catalog-element-reviews">
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-element-title" data-align="center">
                    <?= $arData['NAME'] ?>
                </div>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.reviews',
            'template.9', [
                'IBLOCK_TYPE' => $arResult['REVIEWS']['IBLOCK']['TYPE'],
                'IBLOCK_ID' => $arResult['REVIEWS']['IBLOCK']['ID'],
                'FILTER' => [
                    'ID' => $arData['ID']
                ],
                'PROPERTY_POSITION' => $arResult['REVIEWS']['SETTINGS']['PROPERTY_POSITION'],
                'HEADER_SHOW' => 'N',
                'DESCRIPTION_SHOW' => 'N',
                'POSITION_SHOW' => $arResult['REVIEWS']['SETTINGS']['POSITION_SHOW'],
                'FOOTER_SHOW' => $arResult['REVIEWS']['SETTINGS']['FOOTER_BUTTON_SHOW'],
                'FOOTER_POSITION' => 'center',
                'FOOTER_BUTTON_SHOW' => $arResult['REVIEWS']['SETTINGS']['FOOTER_BUTTON_SHOW'],
                'FOOTER_BUTTON_TEXT' => $arResult['REVIEWS']['SETTINGS']['FOOTER_BUTTON_TEXT'],
                'LIST_PAGE_URL' => $arResult['REVIEWS']['SETTINGS']['LIST_PAGE_URL'],
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'ORDER_BY' => 'ASC'
            ],
            $component
        ) ?>
    </div>
    <!--/noindex-->
    <?php unset($arData) ?>
<?php } ?>