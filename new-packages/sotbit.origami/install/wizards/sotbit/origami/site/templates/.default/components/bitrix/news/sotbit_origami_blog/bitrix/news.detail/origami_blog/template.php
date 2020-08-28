<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
$idItem = \Bitrix\Main\Security\Random::getString(5);
$sliderButtons = "";
if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'square') {
    $sliderButtons = "btn-slider-main--one";
} else if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'circle') {
    $sliderButtons = "btn-slider-main--two";
}
?>
<div id="blog-detail_<?= $idItem ?>" class="blog-detail">
    <div class="blog-detail__top">
        <?php if ($arParams["DISPLAY_PICTURE"] != "N"
            && is_array($arResult["DETAIL_PICTURE"])
        ) : ?>
            <img
                    class="detail_picture"
                    border="0"
                    src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"
                    width="<?= $arResult["DETAIL_PICTURE"]["WIDTH"] ?>"
                    height="<?= $arResult["DETAIL_PICTURE"]["HEIGHT"] ?>"
                    alt="<?= $arResult["DETAIL_PICTURE"]["ALT"] ?>"
                    title="<?= $arResult["DETAIL_PICTURE"]["TITLE"] ?>"
            />
        <?php endif ?>
        <?php
        if (strlen($arResult["PREVIEW_TEXT"]) > 0) {
            ?>
            <div class="blog-detail__preview-text">
                <?= $arResult["PREVIEW_TEXT"] ?>
            </div>
            <?php
        }
        ?>

        <?php if ($arParams["DISPLAY_DATE"] != "N"
            && $arResult["DISPLAY_ACTIVE_FROM"]
        ) : ?>
            <div class="blog-date-time"><?= $arResult["DISPLAY_ACTIVE_FROM"] ?></div>
        <?php endif;
        if ($arResult['TAGS']) {
            $APPLICATION->IncludeComponent(
                "bitrix:search.tags.cloud",
                "origami_tags_blog",
                Array(
                    "CHECK_DATES" => "Y",
                    "arrWHERE" => Array("iblock_".$arParams["IBLOCK_TYPE"]),
                    "arrFILTER" => Array("iblock_".$arParams["IBLOCK_TYPE"]),
                    "arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => Array($arParams["IBLOCK_ID"]),
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "URL_SEARCH" => $arParams["SECTION_URL"].$arParams["SEF_URL_TEMPLATES"]["search"],

                    "PAGE_ELEMENTS" => $arParams["TAGS_CLOUD_ELEMENTS"],
                    "PERIOD_NEW_TAGS" => $arParams["PERIOD_NEW_TAGS"],
                    "FONT_MAX" => $arParams["FONT_MAX"],
                    "FONT_MIN" => $arParams["FONT_MIN"],
                    "COLOR_NEW" => $arParams["COLOR_NEW"],
                    "COLOR_OLD" => $arParams["COLOR_OLD"],
                    "WIDTH" => $arParams["TAGS_CLOUD_WIDTH"],
                    "ITEM_TAGS" => $arResult["TAGS"],
                ),
                $component
            );
        }
        ?>
    </div>
    <?php if (strlen($arResult["DETAIL_TEXT"]) > 0) : ?>
        <div class="blog-detail__middle">
            <?php echo $arResult["DETAIL_TEXT"]; ?>
        </div>
    <?php endif;
    if ($arResult['PHOTOS']) {
        ?>
        <div class="blog-detail__gallery">
            <div class="blog-detail__gallery-title">
                <?= Loc::getMessage('GALLERY_TITLE') ?>
            </div>
            <div class="blog-detail__gallery-items swiper-container">
                <div class="swiper-wrapper">
                <?
                foreach ($arResult['PHOTOS'] as $photo) {
                    ?>
                    <div class="blog-detail__gallery-item swiper-slide">
                        <img src="<?= $photo['src'] ?>">
                    </div>
                    <?
                }
                ?>
                </div>
                <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--prev btn-slider-main--disabled"></div>
                <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--next btn-slider-main--disabled"></div>
            </div>
        </div>
        <?
    }
    ?>
</div>
<script>
    const blog_detail_<?=$idItem?> = new CreateSlider({
        sliderContainer: '#blog-detail_<?=$idItem?> .blog-detail__gallery-items',
        sizeSliderInit: 'all'
    });
</script>
