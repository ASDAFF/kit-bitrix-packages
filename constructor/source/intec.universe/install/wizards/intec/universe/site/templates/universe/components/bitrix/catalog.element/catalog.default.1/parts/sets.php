<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */
?>
<?php if (!empty($arResult['OFFERS'])) { ?>
    <?php if ($arResult['OFFER_GROUP']) { ?>
        <?php foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId) { ?>
            <div data-offer="<?= $offerId ?>">
                <div class="catalog-element-sections catalog-element-sections-wide" data-role="sections">
                    <div class="catalog-element-section" data-role="section">
                        <div class="catalog-element-section-name" data-role="section.toggle">
                            <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SETS') ?>
                        </div>
                        <div class="catalog-element-section-content catalog-element-section-content-sets" data-role="section.content">
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:catalog.set.constructor',
                                '',
                                array(
                                    'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
                                    'ELEMENT_ID' => $offerId,
                                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                                    'BASKET_URL' => $arResult['URL']['BASKET'],
                                    'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
                                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                    'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                    'CURRENCY_ID' => $arParams['CURRENCY_ID']
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <?php if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP']) { ?>
        <div data-offer="false">
            <div class="catalog-element-sections catalog-element-sections-wide" data-role="sections">
                <div class="catalog-element-section" data-role="section">
                    <div class="catalog-element-section-name" data-role="section.toggle">
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SETS') ?>
                    </div>
                    <div class="catalog-element-section-content catalog-element-section-content-sets" data-role="section.content">
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:catalog.set.constructor',
                            '',
                            array(
                                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                'ELEMENT_ID' => $arResult['ID'],
                                'PRICE_CODE' => $arParams['PRICE_CODE'],
                                'BASKET_URL' => $arResult['URL']['BASKET'],
                                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                'CACHE_TIME' => $arParams['CACHE_TIME'],
                                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                'CURRENCY_ID' => $arParams['CURRENCY_ID']
                            ),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>