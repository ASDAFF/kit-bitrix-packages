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
$this->createFrame()->begin();

use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;

Loc::loadMessages(__FILE__);

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>
<? $idItem = \Bitrix\Main\Security\Random::getString(5); ?>

<div class="puzzle_block news-block__wrapper main-container stock-wrapper__right-navigation size">
    <p class="puzzle_block__title fonts__middle_title">
        <?= $arParams["BLOCK_NAME"] ?>
        <a href="<?= ($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"] ?>"
           class="puzzle_block__link fonts__small_text"
           title="<?= Loc::getMessage("KIT_PROMOTIONS_IMAGES_AND_LIST_LINK_TEXT"); ?>">
            <?= Loc::getMessage("KIT_PROMOTIONS_IMAGES_AND_LIST_LINK_TEXT"); ?>
            <i class="icon-nav_1"></i>
        </a>
    </p>

    <div id="news_block_eight_<?= $idItem ?>" class="news_block_eight swiper-container">
        <div class="news_block_eight__slider swiper-wrapper">
            <div class="news_block_eight__others swiper-slide">
                <p class="news_block_eight__others_title"><?= Loc::getMessage("KIT_PROMOTIONS_ADDITIONAL_TEXT"); ?></p>
                <? foreach (array_slice($arResult["ITEMS"], 2, 7) as $arItem): ?>

                    <div class="news_block_eight__others__content" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">

                        <svg class="news_block_eight__clock-icon" width="13" height="20">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>
                        </svg>
                        <p class="news_block_eight__content_date fonts__middle_comment"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></p>
                        <p class="news_block_eight__others__content_comment fonts__middle_text"
                           title="<? if (isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"] ?>">
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a>
                        </p>
                    </div>
                <? endforeach; ?>
            </div>

            <!-- <div class="news_block_eight__wrapper"> -->
            <?
            $i = 0;
            $count = count($arResult["ITEMS"]);
            ?>
            <? foreach (array_slice($arResult["ITEMS"], 0, 7) as $arItem): ?>

                <?
                $blockID = $this->randString();
                $i++;
                $seenId = array();
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                if ($lazyLoad) {
                    $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $arItem["PREVIEW_PICTURE"]["SRC"] . '" class="lazy"';
                } else {
                    $strLazyLoad = 'src="' . $arItem["PREVIEW_PICTURE"]["SRC"] . '"';
                }
                ?>
                <? if ($i <= 2): ?>
                    <div class="news_block_eight__item swiper-slide" id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                         data-timer="timerID_<?= $blockID ?>">
                        <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
                            <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
                                   class="news_block_eight__img_link <?= $hoverClass ?>"
                                   title="<? if (isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"] ?>">
                                    <? if ($arItem["PREVIEW_PICTURE"]): ?>
                                        <img <?= $strLazyLoad ?>
                                            width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                            height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                            alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                            title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                        >
                                        <? if ($lazyLoad): ?>
                                            <span class="loader-lazy"></span>
                                        <? endif; ?>
                                    <? else: ?>
                                        <img src="<?= $templateFolder ?>/images/empty_h.jpg"
                                             alt="<?= $arItem["NAME"] ?>"
                                             title="<?= $arItem["NAME"] ?>"
                                        >
                                    <? endif ?>
                                </a>
                            <? else: ?>
                                <? if ($arItem["PREVIEW_PICTURE"]): ?>
                                    <img <?= $strLazyLoad ?>
                                        width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                        height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                        alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                        title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                    >
                                    <? if ($lazyLoad): ?>
                                        <span class="loader-lazy"></span>
                                    <? endif; ?>
                                <? else: ?>
                                    <img src="<?= $templateFolder ?>/images/empty_h.jpg"
                                         alt="<?= $arItem["NAME"] ?>"
                                         title="<?= $arItem["NAME"] ?>"
                                    >
                                <? endif ?>
                            <? endif; ?>
                        <? endif ?>
                        <div class="news_block_eight__content">
                            <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
                                <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                                    <p class="news_block_eight__content_comment fonts__main_text">
                                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
                                           title="<? if (isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"] ?>">
                                            <?= $arItem["NAME"] ?>
                                        </a>
                                    </p>
                                <? else: ?>
                                    <p class="news_block_eight__content_comment fonts__main_text"><?= $arItem["NAME"] ?></p>
                                <? endif; ?>
                            <? endif; ?>
                            <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                                <p class="news_block_eight__content_comment fonts__small_text"><?= $arItem["PREVIEW_TEXT"]; ?></p>
                            <? endif; ?>
                            <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
                                <p class="news_block_eight__content_date fonts__middle_comment"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></p>
                            <? endif ?>
                        </div>
                    </div>
                <? endif; ?>
                <? if (isset($arItem["DATE_ACTIVE_TO"]) && !empty($arItem["DATE_ACTIVE_TO"])): ?>
                    <?
                    if (Config::get('TIMER_PROMOTIONS') == 'Y') {
                        $APPLICATION->IncludeComponent(
                            "kit:origami.timer",
                            "origami_default",
                            array(
                                "COMPONENT_TEMPLATE" => "origami_default",
                                "ID" => $arItem["ID"],
                                "BLOCK_ID" => $blockID,
                                "ACTIVATE" => "Y",
                                "TIMER_SIZE" => "md",
                                "TIMER_DATE_END" => $arItem["DATE_ACTIVE_TO"]
                            ),
                            $component
                        );
                    }
                    ?>
                <? endif; ?>
            <? endforeach; ?>
        </div>
    </div>
</div>
<script>
    const news_block_eight_<?= $idItem ?> = new CreateSlider({
        sliderContainer: '#news_block_eight_<?=$idItem?>',
        sizeSliderInit: 768
    });
</script>
