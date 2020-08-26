<?

use Bitrix\Main\Page\Asset;
use Sotbit\Origami\Helper\Config;

$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
Asset::getInstance()->addcss(SITE_DIR . "include/sotbit_origami/contact_page_block/contacts_description_slider/style.css");
Asset::getInstance()->addjs(SITE_DIR . "include/sotbit_origami/contact_page_block/contacts_description_slider/script.js");
//$useRegion = false;
?>
<div class="contact-slider-description">
    <div class="contact-description-text">
        <div class="show-description-wrapper">
            <div class="text-wrapper">
                <span>
                    <?
                    if ($useRegion && $_SESSION["SOTBIT_REGIONS"]["UF_REGIONS_DESCRIPT"]) {
                        echo $_SESSION["SOTBIT_REGIONS"]["UF_REGIONS_DESCRIPT"];
                    } else {
                        $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/sotbit_origami/contact_areas/contact_page_description.php")
                        );
                    }
                    ?>
                </span>
            </div>
        </div>
        <div class="contacts-description-show_more_btn">
            <span class="show_btn">Подробнее о компании</span>
            <span class="collapse_btn">Свернуть</span>
        </div>
    </div>
    <div class="contact-description-slider-wrapper">
        <div class="contact-description-slider">
            <? if (!$useRegion && $_SESSION["SOTBIT_REGIONS"]["UF_REGIONS_PHOTOS"]): ?>
                <!-- REGIONS -->
            <? else:
                $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/sotbit_origami/contact_areas/contact_page_slider.php")
                );
            endif; ?>
        </div>
    </div>
</div>

<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

<script>

    let images = [];

    $('.contacts-slider-image-wrapper').each(function (i) {
        let proportion = this.querySelector("img").clientWidth / this.querySelector("img").clientHeight;
        let height = 600;

        let width = proportion ? proportion * height : 600;

        images.push(
            {
                SRC: $(this).children().attr('src'),
                WIDTH: width,
                HEIGHT: height,
            }
        );
    });

    let slidesToShow;
    let centerMode = true;

    let navSlider2 = $('.contact-description-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        centerMode: centerMode,
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
                breakpoint: 1050,
                settings: {
                    slidesToShow: 6,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 2,
                    centerMode: false,
                }
            }
        ]
    });

    let pswpContactsElement = document.querySelector(".pswp");
    let items = getItemsForPhotoSwipe(images);

    function initPhotoSwipe(index) {
        let options = {
            index: index,
            history: false,
            focus: false,
            showAnimationDuration: 0,
            hideAnimationDuration: 0
        };

        let contactsOfficeGallery = new PhotoSwipe(pswpContactsElement, PhotoSwipeUI_Default, items, options);
        contactsOfficeGallery.init();
    }

    function getItemsForPhotoSwipe(images) {
        let items = [];

        for (let i = 0; i < images.length; i++) {

            let img = {
                src: images[i]['SRC'],
                w: images[i]['WIDTH'],
                h: images[i]['HEIGHT']
            };

            items.push(img);

        }

        return items;
    }
</script>
