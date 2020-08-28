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
}

sliderInit();

window.sliderInitWindow = function () {
    sliderInit();
};
