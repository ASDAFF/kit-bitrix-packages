<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$sliderButtons = "";
if (\Kit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'square') {
    $sliderButtons = "btn-slider-main--one";
} else if (\Kit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'circle') {
    $sliderButtons = "btn-slider-main--two";
}
?>
<? $idItem = \Bitrix\Main\Security\Random::getString(5);?>

<div id="brand_block_variant_<?=$idItem?>" class="brand_block_variant__wrapper-square puzzle_block main-container ">
    <div class="swiper-container">
        <div class="brand_block_variant_square swiper-wrapper">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="brand_block__item_square swiper-slide" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
                        <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="brand_block__item_link_square">
                                <? if ($arItem["PREVIEW_PICTURE"]): ?>
                                    <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                         width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                         height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                         alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                         title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                    >
                                <? else: ?>
                                    <img src="<?= $templateFolder ?>/images/empty_h.jpg"
                                         alt="<?= $arItem["NAME"] ?>"
                                         title="<?= $arItem["NAME"] ?>"
                                    >
                                <? endif ?>
                            </a>
                        <? else: ?>
                            <? if ($arItem["PREVIEW_PICTURE"]): ?>
                                <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                     width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                     height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                     alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                     title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                >
                            <? else: ?>
                                <img src="<?= $templateFolder ?>/images/empty_h.jpg"
                                     alt="<?= $arItem["NAME"] ?>"
                                     title="<?= $arItem["NAME"] ?>"
                                >
                            <? endif ?>
                        <? endif; ?>
                    <? endif ?>
                </div>
            <? endforeach; ?>
        </div>
        <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--prev btn-slider-main--disabled"></div>
        <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--next btn-slider-main--disabled"></div>
    </div>
</div>
<script>
    const brand_block_variant_<?=$idItem?> = new CreateSlider ({
        sliderContainer: '#brand_block_variant_<?=$idItem?> .swiper-container',
        sizeSliderInit: 'all'
    });
</script>
