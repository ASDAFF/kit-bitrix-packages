<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$page = $APPLICATION->GetCurPage(false);
?>

<?if(!empty($arResult)):?>
    <div class="sidebar" id="header-sidebar">
        <ul class="sidebar__wrapper swiper-wrapper">

            <?foreach($arResult as $item):?>
                <li class="sidebar__item swiper-slide">
                    <a href="<?=$item['LINK']?>" class="sidebar__item-link main-txt-hover main-svg-fill-hover <?if($item["SELECTED"]):?>current<?endif;?>" title="<?=$item['TEXT']?>">
                        <?if(isset($item["PARAMS"]["ICON"])):?>
                            <div class="sidebar__item-logo">
                                <svg class="sidebar__item-logo-icon" width="24" height="24">
                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#<?=$item["PARAMS"]["ICON"];?>"></use>
                                </svg>
                            </div>
                        <?endif;?>
                        <p class="sidebar__item-name"><?=$item['TEXT']?></p>
                    </a>
                </li>
            <?endforeach;?>


        </ul>
        <div class="sidebar__slider-button sidebar__slider-button--next">
            <svg class="sidebar__slider-button-icon" width="8" height="12">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_right_small"></use>
            </svg>
        </div>
        <div class="sidebar__slider-button sidebar__slider-button--prev">
            <svg class="sidebar__slider-button-icon" width="8" height="12">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_left_small"></use>
            </svg>
        </div>
    </div>
    <script>
        const sidebarSlider = new Swiper('#header-sidebar', {
            direction: 'horizontal',
            freeMode: true,
            slidesPerView: 'auto',
            observer: true,
            observeParents: true,
            spaceBetween: 12,
            navigation: {
                nextEl: '.sidebar__slider-button--next',
                prevEl: '.sidebar__slider-button--prev',
            },

            breakpoints: {
                // when window width is >= 1024px
                1024: {
                    direction: 'vertical',
                    spaceBetween: 0,
                },
            }
        });
    </script>
<?endif;?>


