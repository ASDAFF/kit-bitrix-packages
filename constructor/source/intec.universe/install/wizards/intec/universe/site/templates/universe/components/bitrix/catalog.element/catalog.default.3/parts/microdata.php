<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$sPicture = null;
$fRatingValue = 3;
$iReviewCount = 1;
$iOffersCount = null;
$arMinPrice = [];
$arMaxPrice = [];
$sAvailability = 'OutOfStock';

if (!empty($arResult['PICTURES']))
    $sPicture = $arResult['PICTURES'][0]['SRC'];
else
    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

if (!empty($arResult['PROPERTIES']['rating']['VALUE']))
    $fRatingValue = $arResult['PROPERTIES']['rating']['VALUE'];

if (!empty($arResult['PROPERTIES']['vote_count']['VALUE']))
    $iReviewCount = $arResult['PROPERTIES']['vote_count']['VALUE'];

if (!empty($arResult['OFFERS'])) {
    $iOffersCount = count($arResult['OFFERS']);

    foreach ($arResult['OFFERS'] as &$arOffer) {
        $arCurrentPrice = $arOffer['ITEM_PRICES'][0];

        if (empty($arMinPrice) || $arMinPrice['PRICE'] > $arCurrentPrice['PRICE'])
            $arMinPrice = $arCurrentPrice;

        if (empty($arMaxPrice) || $arMaxPrice['PRICE'] < $arCurrentPrice['PRICE'])
            $arMaxPrice = $arCurrentPrice;
    }

    unset($arOffer);
}

if ($arResult['CAN_BUY'])
    $sAvailability = 'InStock';

$sDescription = '';

$sDescription = $arResult['DETAIL_TEXT'];

if (empty($sDescription))
    $sDescription = $arResult['PREVIEW_TEXT'];

?>
<div itemscope itemtype="http://schema.org/Product" style="display: none">
    <meta itemprop="name" content="<?= $arResult['NAME'] ?>" />
    <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>" />
    <img loading="lazy" itemprop="image" src="<?= $sPicture ?>" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
    <?php if (!empty($sDescription)) { ?>
        <meta itemprop="description" content="<?= Html::encode($sDescription) ?>" />
    <?php } ?>
    <?php if (!empty($arResult['BRAND'])) { ?>
        <meta itemprop="brand" content="<?= $arResult['BRAND']['NAME'] ?>" />
    <?php } ?>
    <div itemscope itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?= $fRatingValue ?>" />
        <meta itemprop="reviewCount" content="<?= $iReviewCount ?>" />
        <meta itemprop="bestRating" content="5" />
        <meta itemprop="worstRating" content="0" />
    </div>
    <?php if (!empty($arResult['OFFERS'])) { ?>
        <div itemscope itemprop="offers" itemtype="http://schema.org/AggregateOffer">
            <meta itemprop="lowPrice" content="<?= $arMinPrice['PRICE'] ?>" />
            <meta itemprop="highPrice" content="<?= $arMaxPrice['PRICE'] ?>" />
            <meta itemprop="offerCount" content="<?= $iOffersCount ?>" />
            <meta itemprop="priceCurrency" content="<?= $arResult['ITEM_PRICES'][0]['CURRENCY'] ?>" />
            <?php foreach ($arResult['OFFERS'] as &$arOffer) { ?>
                <div itemscope itemprop="offers" itemtype="http://schema.org/Offer">
                    <meta itemprop="price" content="<?= $arOffer['ITEM_PRICES'][0]['PRICE'] ?>" />
                </div>
            <?php }
                unset($arOffer)
            ?>
        </div>
    <?php } else { ?>
        <div itemscope itemprop="offers" itemtype="http://schema.org/Offer">
            <meta itemprop="price" content="<?= $arResult['ITEM_PRICES'][0]['PRICE'] ?>" />
            <meta itemprop="priceCurrency" content="<?= $arResult['ITEM_PRICES'][0]['CURRENCY'] ?>" />
            <link itemprop="availability" href="<?= 'http://schema.org/'.$sAvailability ?>" />
            <link itemprop="url" href="<?= $arResult['DETAIL_PAGE_URL'] ?>" />
        </div>
    <?php } ?>
    <?php if (!empty($arResult['ARTICLE'])) { ?>
        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
            <meta itemprop="name" content="<?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ARTICLE') ?>" />
            <meta itemprop="value" content="<?= $arResult['ARTICLE'] ?>" />
        </div>
    <?php } ?>
    <?php if (!empty($arResult['DISPLAY_PROPERTIES'])) { ?>
        <?php foreach ($arResult['DISPLAY_PROPERTIES'] as &$arProperty) { ?>
            <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
                <meta itemprop="name" content="<?= $arProperty['NAME'] ?>" />
                <meta itemprop="value" content="<?= $arProperty['VALUE'] ?>" />
            </div>
        <?php }
            unset($arProperty)
        ?>
    <?php } ?>
</div>