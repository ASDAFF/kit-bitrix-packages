function sliderInit() {
    $('.slider-canvas').owlCarousel({
        stopOnHover: true,
        loop: false,
        items: 1,
        nav: true,
        navText: ["", ""],
        autoplay: false,
        smartSpeed: 500,
    });

    window.addEventListener("load", function () {
        setTimeout(setBannerHeight, 1000);
        setSlidesDirection();
    });
    window.addEventListener("resize", function () {
        setSlidesDirection();
        setBannerHeight();
    });

    function setBannerHeight() {

    //     let windowWidth = window.innerWidth || document.documentElement.clientWidth,
    //         banner = document.querySelector(".main_page-catalog_banner"),
    //         bannerPrevBtn = banner.querySelector(".owl-nav .owl-prev"),
    //         bannerNextBtn = banner.querySelector(".owl-nav .owl-next"),
    //         blocksText = banner.querySelectorAll(".block_main_canvas__big_canvas__info"),
    //         owlDots = banner.querySelector(".owl-dots");

    //     if (windowWidth > 1024) {

    //         let bannerWidth = banner.clientWidth,
    //             blockHeight = bannerWidth * 430 / 1344;

    //         banner.style.height = blockHeight + "px";
    //         bannerNextBtn.style.top = blockHeight / 2 + "px";
    //         bannerPrevBtn.style.top = blockHeight / 2 + "px";
    //         owlDots.style.top = blockHeight - 34 + "px";
    //         owlDots.style.bottom = "auto";

    //         for (let i = 0; i < blocksText.length; i++) {
    //             blocksText[i].style.top = blockHeight - blocksText[i].clientHeight - 100 + "px";
    //             blocksText[i].style.bottom = "auto";
    //         }

    //     } else if (windowWidth > 420) {

    //         banner.style.height = "auto";
    //         bannerNextBtn.style.top = 50 + "%";
    //         bannerPrevBtn.style.top = 50 + "%";
    //         owlDots.style.top = "auto";
    //         owlDots.style.bottom = "2.5rem";

    //         for (let i = 0; i < blocksText.length; i++) {
    //             blocksText[i].style.top = "auto";
    //             blocksText[i].style.bottom = "30%";
    //         }

    //     } else {

    //         banner.style.height = "auto";
    //         bannerNextBtn.style.top = 50 + "%";
    //         bannerPrevBtn.style.top = 50 + "%";
    //         owlDots.style.top = "auto";
    //         owlDots.style.bottom = "20px";

    //         for (let i = 0; i < blocksText.length; i++) {
    //             blocksText[i].style.top = "auto";
    //             blocksText[i].style.bottom = "30%";
    //         }

    //     }
    }

    function setSlidesDirection() {
        let wrapper = document.querySelector(".main_page-catalog_banner .slider_block .owl-stage-outer");
        let slidesWrappers = document.querySelectorAll(".main_page-catalog_banner .slider_block .block_main_canvas__big_canvas__content");
        let wrapperProportion =  wrapper.clientWidth / wrapper.clientHeight;

        for (let i = 0; i < slidesWrappers.length; i++) {
            let image = slidesWrappers[i].querySelector("img");
            let imgWidth = parseInt(image.getAttribute("width")) ;
            let imgHeight = parseInt(image.getAttribute("height"));
            let imageProportion = imgWidth / imgHeight;
            let newSlideWidth = imgWidth * wrapper.clientHeight / imgHeight;

            if (wrapperProportion > 1 && imageProportion < 1) {
                slidesWrappers[i].style.flexDirection = "row";

                image.style.height = "auto";
                image.style.width = "100%";
            } else if (wrapperProportion < 1 && imageProportion > 1) {
                slidesWrappers[i].style.flexDirection = "column";
                image.style.height = "100%";
                image.style.width = "auto";
            } else if (newSlideWidth > wrapper.clientWidth) {
                slidesWrappers[i].style.flexDirection = "column";
                image.style.height = "100%";
                image.style.width = "auto";
            } else {
                slidesWrappers[i].style.flexDirection = "row";

                image.style.height = "auto";
                image.style.width = "100%";
            }

        }
    }
}

sliderInit();

window.sliderInitWindow = function () {
    sliderInit();
};
