<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin();
$sliderButtons = "";
if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'square') {
    $sliderButtons = "btn-slider-main--one";
} else if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'circle') {
    $sliderButtons = "btn-slider-main--two";
}
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs(SITE_DIR . "local/templates/kit_origami/assets/plugin/swiper5.2.0/js/swiper.js");
Asset::getInstance()->addJs(SITE_DIR . "local/templates/kit_origami/assets/js/custom-slider.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/kit_origami/assets/plugin/swiper5.2.0/css/swiper.min.css");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/kit_origami/assets/css/style-swiper-custom.css");
?>

<?if($arResult["ITEMS"]):?>
    <div class="slider-header-three swiper-container">

        <div class="slider-header-three__wrapper swiper-wrapper">
            <?foreach ($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="slider-header-three__slide slider-header-three-item swiper-slide slider-header-three-item--<?=$arItem["PROPERTIES"]["TEXT_COLOR"]["VALUE_XML_ID"]?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <div class="slider-header-three-item__inner">
                        <div class="slider-header-three-item__content">
                            <h2 class="slider-header-three-item__title"><?=$arItem["NAME"]?></h2>
                            <?if($arItem["DETAIL_TEXT"]):?>
                                <p class="slider-header-three-item__text"><?=$arItem["DETAIL_TEXT"]?></p>
                            <?endif;?>
                            <?if($arItem["PROPERTIES"]["BUTTON_TEXT"]["VALUE"]):?>
                                <a
                                    <?= ($arItem['PROPERTIES']['NEW_TAB']['VALUE'] == '��') ? 'target="_blank"' : '' ?>
                                        href="<?=$arItem['PROPERTIES']['URL']['VALUE']?>" class="slider-header-three-item__link"><?=$arItem["PROPERTIES"]["BUTTON_TEXT"]["VALUE"]?>
                                </a>
                            <?endif;?>
                        </div>
                    </div>
                    <picture class="slider-header-three-item__background-wrap">
                        <?
                        if($arItem["PROPERTIES"]["IMAGES_WEBP"]["WEBP_ORIGINAL_NAME"]){
                            if(is_numeric($numMobile = array_search('mobile.webp', $arItem["PROPERTIES"]["IMAGES_WEBP"]["WEBP_ORIGINAL_NAME"]))){
                                ?>
                                    <source  type="image/webp" srcset="<?=$arItem["PROPERTIES"]["IMAGES_WEBP"]["VALUE"][$numMobile]["SRC"]?>" media="screen and (orientation: portrait)">
                                <?
                            }
                            if(is_numeric($numDesktop = array_search('desktop.webp', $arItem["PROPERTIES"]["IMAGES_WEBP"]["WEBP_ORIGINAL_NAME"]))){
                                ?>
                                <source  type="image/webp" srcset="<?=$arItem["PROPERTIES"]["IMAGES_WEBP"]["VALUE"][$numDesktop]["SRC"]?>">
                                <?
                            }
                        }
                        ?>
                        <?if($arItem["PREVIEW_PICTURE"]):?>
                            <source  srcset="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" media="screen and (orientation: portrait)">
                        <?endif;?>
                        <?if($arItem["DETAIL_PICTURE"]):?>
                            <source  srcset="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>">
                        <?endif;?>
                        <img  class="slider-header-three-item__background swiper-lazy" data-srcset="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" title="<?=$arItem["DETAIL_PICTURE"]["TITLE"]?>">
                    </picture>
                </div>
            <?endforeach;?>
        </div>

        <div class="slider-header-three__pagination swiper-pagination"></div>

        <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--prev btn-slider-main--disabled slider-header-three__button-prev"></div>
        <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--next btn-slider-main--disabled slider-header-three__button-next"></div>

    </div>

<script>

    const header = document.getElementById('header-three');

    header.classList.add("header-three--not-full-width");

    var mySwiper = new Swiper ('.slider-header-three', {

        preloadImages: false,
        lazy: true,
        watchOverflow: true,
        speed:600,

        pagination: {
            el: '.slider-header-three__pagination',
            clickable:true,
        },


        navigation: {
            nextEl: '.slider-header-three__button-next',
            prevEl: '.slider-header-three__button-prev',
        },

        on:{
            transitionEnd:function(){
                changeColorHeader();
            },
        },
    })

    changeColorHeader();

    function changeColorHeader(){

        const slider_item = document.querySelector(".slider-header-three-item.swiper-slide-active");

        if(slider_item.closest(".slider-header-three-item--black")){
            header.classList.remove("header-three--black");
            header.classList.add("header-three--white");
        }
        else if(slider_item.closest(".slider-header-three-item--white")) {
            header.classList.remove("header-three--white");
            header.classList.add("header-three--black");
        }

    }
</script>
<?endif;?>
