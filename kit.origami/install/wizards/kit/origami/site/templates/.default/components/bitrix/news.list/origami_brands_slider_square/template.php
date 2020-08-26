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
?>

<div class="brand_block_variant__wrapper-square size main-container">
    <div class="brand_block_variant_square">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="brand_block__item_square" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
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
</div>
<script>

    let brandsSquareSlider = $('.brand_block_variant_square').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        centerMode: false,
        letiableWidth: false,
        focusOnSelect: true,
        edgeFriction: 1,
        infinite: true,
        arrows: true,
        prevArrow: '<button type="button" class="btn-slick-custom btn-slick-custom--prev">Prev</button>',
        nextArrow: '<button type="button" class="btn-slick-custom btn-slick-custom--next">Prev</button>',
        lazyLoad: 'ondemand',
        pauseOnHover: true,
        responsive: [
            {
                breakpoint: 1180,
                settings: {
                    slidesToShow: 5,
                }
            },
            {
                breakpoint: 940,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 730,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 540,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 360,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    });
</script>
