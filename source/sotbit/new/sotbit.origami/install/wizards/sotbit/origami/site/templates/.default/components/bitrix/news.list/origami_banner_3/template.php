<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin();

use Sotbit\Origami\Helper\Config;

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
$idItem = \Bitrix\Main\Security\Random::getString(5);
$sliderButtons = "";
if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'square') {
    $sliderButtons = "btn-slider-main--one";
} else if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'circle') {
    $sliderButtons = "btn-slider-main--two";
}
?>
<div id="main_page-catalog_banner_<?= $idItem ?>" class="puzzle_block about__puzzle_block main-container">
    <div class="main_page-catalog_banner">
            <div class="slider_block swiper-container">
                <div class="block_main_canvas__big_canvas  swiper-wrapper">

                    <? if ($arResult['MAIN']): ?>
                        <? foreach ($arResult['MAIN'] as $item):
                            $blockID = $this->randString();
                            $img = $item['PREVIEW_PICTURE']['SRC'] ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'];
                            if ($lazyLoad) {
                                $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $img["SRC"] . '" class="lazy"';
                            } else {
                                $strLazyLoad = 'src="' . $img["SRC"] . '"';
                            }
                            ?>
                            <div class="block_main_canvas__big_canvas__content swiper-slide <?= $hoverClass ?>" data-timer="timerID_<?= $blockID ?>">
                                <img <?= $strLazyLoad ?>
                                     width="<?= $img['WIDTH'] ?>"
                                     height="<?= $img['HEIGHT'] ?>"
                                     title="<?= $img['TITLE'] ?>"
                                     alt="<?= $img['ALT'] ?>"
                                >
                                <? if ($lazyLoad): ?>
                                    <span class="loader-lazy loader-lazy--big"></span>
                                <? endif; ?>

                                <div class="banner-info__wrapper">
                                    <div class="block_main_canvas__big_canvas__info banner-info">

                                        <div class="banner-info__name"><span><?=$item['PREVIEW_TEXT']?></span></div>
                                        <div
                                            class="block_main_canvas__big_canvas__title fonts__middle_title banner-info__title">
                                            <span><?=$item['NAME']?></span>
                                        </div>
                                        <div
                                            class="block_main_canvas__big_canvas__comment fonts__small_text banner-info__text">
                                            <span><?=$item['DETAIL_TEXT']?></span>
                                        </div>
                                        <div class="banner-info__button">
                                            <a href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>"
                                               <?= ($item['PROPERTIES']['NEW_TAB']['VALUE'] == 'Y') ? 'target="_blank"' : '' ?>
                                               class="main_btn-big"
                                               title="<?= $item['~PREVIEW_TEXT'] ?>">
                                                <?= $item['PROPERTIES']['BUTTON_TEXT']['VALUE'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <? if (isset($item[ACTIVE_TO]) && !empty($item[ACTIVE_TO])): ?>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            let timerID_<?= $item[ID] ?> = new TimerAction({
                                                itemParent: '[data-timer="timerID_<?=$blockID?>"]',
                                                endTime: (<?=MakeTimeStamp($item[ACTIVE_TO], "DD.MM.YYYY HH:MI:SS")?>) * 1000,
                                                day: 'дней',
                                                hours: 'час.',
                                                minutes: 'мин.',
                                                seconds: 'сек.',
                                                size: 'lg',
                                            });
                                        });
                                    </script>
                                <? endif; ?>
                            </div>
                        <?endforeach;
                    endif; ?>
                </div>
                <div class="slider-pagination"></div>
                <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--prev btn-slider-main--disabled"></div>
                <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--next btn-slider-main--disabled"></div>
            </div>
            <?
            if ($arResult['SIDE']):?>
                <div class="catalog_links-block">
                    <div class="links-section_wrapper">
                        <div class="links-section_left">
                            <? if (isset($arResult['SIDE'][0])):
                                $item = $arResult['SIDE'][0];
                                $img = $item['PREVIEW_PICTURE']['SRC'] ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'];
                                $name = $item['~PREVIEW_TEXT'] ? $item['~PREVIEW_TEXT'] : $item['NAME'];
                                if ($lazyLoad) {
                                    $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $img['SRC'] . '" class="lazy"';
                                } else {
                                    $strLazyLoad = 'src="' . $img['SRC'] . '"';
                                }

                                ?>
                                <a class="links-section__link <?= $hoverClass ?>"
                                   href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>" title="<?= $name ?>">
                                    <img <?= $strLazyLoad ?>
                                         width="<?= $img['WIDTH'] ?>"
                                         height="<?= $img['HEIGHT'] ?>"
                                         title="<?= $img['TITLE'] ?>"
                                         alt="<?= $img['ALT'] ?>"
                                    >
                                    <? if ($lazyLoad): ?>
                                        <span class="loader-lazy"></span>
                                    <? endif; ?>
                                    <div class="links-section__overlay"></div>
                                    <div class="link-section_text"><?= $name ?></div>
                                </a>
                            <? endif; ?>
                            <? if (isset($arResult['SIDE'][1])):
                                $item = $arResult['SIDE'][1];
                                $img = $item['PREVIEW_PICTURE']['SRC'] ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'];
                                $name = $item['~PREVIEW_TEXT'] ? $item['~PREVIEW_TEXT'] : $item['NAME'];
                                if ($lazyLoad) {
                                    $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $img['SRC'] . '" class="lazy"';
                                } else {
                                    $strLazyLoad = 'src="' . $img['SRC'] . '"';
                                }
                                ?>
                                <a class="links-section__link <?= $hoverClass ?>"
                                   href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>" title="<?= $name ?>">
                                    <img <?= $strLazyLoad ?>
                                         width="<?= $img['WIDTH'] ?>"
                                         height="<?= $img['HEIGHT'] ?>"
                                         title="<?= $img['TITLE'] ?>"
                                         alt="<?= $img['ALT'] ?>"
                                    >
                                    <? if ($lazyLoad): ?>
                                        <span class="loader-lazy"></span>
                                    <? endif; ?>
                                    <div class="links-section__overlay"></div>
                                    <div class="link-section_text"><?= $name ?></div>
                                </a>
                            <? endif; ?>
                        </div>
                    </div>
                    <div class="links-section_wrapper">
                        <div class="links-section_right">
                            <? if (isset($arResult['SIDE'][2])):
                                $item = $arResult['SIDE'][2];
                                $img = $item['PREVIEW_PICTURE']['SRC'] ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'];
                                $name = $item['~PREVIEW_TEXT'] ? $item['~PREVIEW_TEXT'] : $item['NAME'];

                                if ($lazyLoad) {
                                    $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $img['SRC'] . '" class="lazy"';
                                } else {
                                    $strLazyLoad = 'src="' . $img['SRC'] . '"';
                                }
                                ?>
                                <a class="links-section__link <?= $hoverClass ?>"
                                   href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>" title="<?= $name ?>">
                                    <img <?= $strLazyLoad ?>
                                         width="<?= $img['WIDTH'] ?>"
                                         height="<?= $img['HEIGHT'] ?>"
                                         title="<?= $img['TITLE'] ?>"
                                         alt="<?= $img['ALT'] ?>"
                                    >
                                    <? if ($lazyLoad): ?>
                                        <span class="loader-lazy"></span>
                                    <? endif; ?>
                                    <div class="links-section__overlay"></div>
                                    <div class="link-section_text"><?= $name ?></div>
                                </a>
                            <? endif; ?>
                            <? if (isset($arResult['SIDE'][3])):
                                $item = $arResult['SIDE'][3];
                                $img = $item['PREVIEW_PICTURE']['SRC'] ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'];
                                $name = $item['~PREVIEW_TEXT'] ? $item['~PREVIEW_TEXT'] : $item['NAME'];

                                if ($lazyLoad) {
                                    $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $img['SRC'] . '" class="lazy"';
                                } else {
                                    $strLazyLoad = 'src="' . $img['SRC'] . '"';
                                }
                                ?>
                                <a class="links-section__link <?= $hoverClass ?>"
                                   href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>" title="<?= $name ?>">
                                    <img <?= $strLazyLoad ?>
                                         width="<?= $img['WIDTH'] ?>"
                                         height="<?= $img['HEIGHT'] ?>"
                                         title="<?= $img['TITLE'] ?>"
                                         alt="<?= $img['ALT'] ?>"
                                    >
                                    <? if ($lazyLoad): ?>
                                        <span class="loader-lazy"></span>
                                    <? endif; ?>
                                    <div class="links-section__overlay"></div>
                                    <div class="link-section_text"><?= $name ?></div>
                                </a>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        </div>
</div>

<script>
    const main_page_catalog_banner_<?=$idItem?> = new CreateSlider({
        sliderContainer: '#main_page-catalog_banner_<?=$idItem?> .slider_block',
        sizeSliderInit: 'all',
        slidesPerView: 1,
        freeMode: false
    });

    document.addEventListener('DOMContentLoaded', function () {
        let items = document.querySelectorAll('.main_page-catalog_banner .links-section__link');
        let itemsList = Array.prototype.slice.call(items);

        function setHeight(item) {
            let elemWidth = Math.round(item.offsetWidth);
            item.style.height = elemWidth + 'px';
        }

        itemsList.forEach(function (elem) {
            setHeight(elem);
        });

        window.addEventListener('resize', function () {
            itemsList.forEach(function (elem) {
                setHeight(elem);
            });
        });
    });
</script>
