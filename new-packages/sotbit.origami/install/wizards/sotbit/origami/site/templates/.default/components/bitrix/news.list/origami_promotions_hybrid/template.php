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
use Sotbit\Origami\Helper\Config;

Loc::loadMessages(__FILE__);
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
$idItem = \Bitrix\Main\Security\Random::getString(5);
?>

<div class="promotion-block__wrapper puzzle_block main-container size">
    <p class="puzzle_block__title fonts__middle_title">
        <?= $arParams["BLOCK_NAME"] ?>
        <a href="<?= ($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"] ?>"
           title="<?= Loc::getMessage("SOTBIT_PROMOTIONS_HYBRID_LINK_TEXT"); ?>"
           class="puzzle_block__link fonts__small_text">
            <?= Loc::getMessage("SOTBIT_PROMOTIONS_HYBRID_LINK_TEXT"); ?>
            <i class="icon-nav_1"></i>
        </a>
    </p>

    <div id="promotion_block_two_<?= $idItem ?>" class="promotion_block_two swiper-container">
        <div class="promotion_block_two__wrapper swiper-wrapper">
            <?
            $i = 0;
            $count = count($arResult["ITEMS"]);
            ?>
            <? foreach (array_slice($arResult["ITEMS"], 0, 4) as $arItem):
            $blockID = $this->randString();
            ?>
            <?
            $i++;
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            ?>
            <? if ($i == 1):
            $aClass = "promotion_block_two__link";
            $imgClass = "promotion_block_two__img";
            $orientation = "PREVIEW_PICTURE"; // VERTICAL_IMAGE
            ?>
            <div class="promotion_block_two__item swiper-slide">
                <? elseif ($i == 2):
                $aClass = "promotion_block_two__block_img_small_link";
                $imgClass = "promotion_block_two__block_img_small__img";
                $orientation = "DETAIL_PICTURE"; // HORIZONTAL_IMAGE
                ?>
                <div class="promotion_block_two__item promotion_block_two__item--vertical swiper-slide">
                    <? elseif ($i == 4):
                    $aClass = "promotion_block_two__link";
                    $imgClass = "promotion_block_two__img";
                    $orientation = "PREVIEW_PICTURE"; // VERTICAL_IMAGE
                    ?>
                    <div class="promotion_block_two__item swiper-slide">
                        <? endif;
                        ?>
                        <?
                        if ($lazyLoad) {
                            $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $arItem["$orientation"]["SRC"] . '"';
                            $imgClass .= " lazy";
                        } else {
                            $strLazyLoad = 'src="' . $arItem["$orientation"]["SRC"] . '"';
                        }
                        ?>

                        <? if ($arParams["DISPLAY_PICTURE"] != "N"):?>
                            <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
                                id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                                class="<?= $aClass . " " . $hoverClass ?>"
                                title="<? if (isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"] ?>"
                                data-timer="timerID_<?= $blockID ?>">
                            <? endif; ?>

                            <? if ($arItem["$orientation"]):?>
                                <img class="<?= $imgClass ?>"
                                    <?= $strLazyLoad ?>
                                     width="<?= $arItem["$orientation"]["WIDTH"] ?>"
                                     height="<?= $arItem["$orientation"]["HEIGHT"] ?>"
                                     alt="<?= $arItem["$orientation"]["ALT"] ?>"
                                     title="<?= $arItem["$orientation"]["TITLE"] ?>"
                                >
                                <? if ($lazyLoad && ($i == 1 || $i == 4)):?>
                                    <span class="loader-lazy loader-lazy--big"></span>
                                <? elseif ($lazyLoad):?>
                                    <span class="loader-lazy"></span>
                                <? endif; ?>
                            <? else:?>
                                <img class=<?= $imgClass ?>
                                     src="<?= $templateFolder ?>/images/empty_<?= ($orientation == "PREVIEW_PICTURE") ? "v" : "h" ?>.jpg"
                                     alt="<?= $arItem["NAME"] ?>"
                                     title="<?= $arItem["NAME"] ?>"
                                >
                            <? endif ?>

                            <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                </a>
                            <? endif; ?>
                        <? endif ?>

                        <? if ($i == 2 && $count == 2 || $i == 3): ?>
                    </div>
                    <? elseif ($i == 1 || $i == 4): ?>
                </div>
            <? endif ?>
                <? if (isset($arItem["DATE_ACTIVE_TO"]) && !empty($arItem["DATE_ACTIVE_TO"])): ?>
                    <?
                    if (Config::get('TIMER_PROMOTIONS') == 'Y') {
                        $APPLICATION->IncludeComponent(
                            "sotbit:origami.timer",
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
        const promotion_block_two_<?= $idItem ?> = new CreateSlider({
            sliderContainer: '#promotion_block_two_<?=$idItem?>',
            sizeSliderInit: 768
        });
    </script>
