const CreateSlider = function (params = {}) {
    let {
        sliderContainer,
        sizeSliderInit = 768,
        btnPrev = sliderContainer + ' .btn-slider-main--prev',
        btnNext = sliderContainer + ' .btn-slider-main--next',
        pagination = sliderContainer + ' .slider-pagination',
        spaceBetween576 = 20,
        spaceBetween768 = 20,
        spaceBetween1024 = 20,
        slidesPerView = 'auto',
        freeMode = true} = params;
    this.slider = null;
    this.isSliderInit = null;
    this.sliderInit = () => {
        this.slider = new Swiper(sliderContainer, {
            slidesPerView: slidesPerView,
            spaceBetween: 15,
            freeMode: freeMode,
            watchOverflow: true,
            breakpoints: {
                576: {
                    spaceBetween: spaceBetween576,
                },
                768: {
                    spaceBetween: spaceBetween768,
                },
                1024: {
                    spaceBetween: spaceBetween1024,
                }
            },
            navigation: {
                nextEl: btnNext,
                prevEl: btnPrev,
            },
            pagination: {
                el: pagination,
                clickable: true,
            },
        });
        this.isSliderInit = true;
    };
    this.sliderDestroy = () => {
        this.slider.destroy();
        this.isSliderInit = false;
    };

    if (typeof sizeSliderInit === "number") {
        this.trigger = () => {
            if ((window.innerWidth <= sizeSliderInit) && !this.isSliderInit) {
                this.sliderInit();
            }

            if ((window.innerWidth > sizeSliderInit) && this.isSliderInit) {
                this.sliderDestroy();
            }
        };

        window.addEventListener('resize', this.trigger);

        this.trigger();
    }

    if (sizeSliderInit === 'all') {
        this.sliderInit();
    }


};
