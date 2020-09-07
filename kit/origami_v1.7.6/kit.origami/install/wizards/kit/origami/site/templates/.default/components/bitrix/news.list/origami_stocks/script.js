function sliderStockInit() {
    window.addEventListener("DOMContentLoaded", setSliderHeight);
    window.addEventListener("load", setSliderHeight);
    window.addEventListener("resize", setSliderHeight);

    function setSliderHeight() {
        let windowWidth = window.innerWidth;

        let sliderWrapper = document.querySelector(".stocks-slider__wrapper");

        if (windowWidth > 1024) {
            let newHeight = sliderWrapper.clientWidth / (1344 / 441) + "px";
            sliderWrapper.style.height =
                (newHeight < 323)
                    ? 323 + "px"
                    : newHeight;
        } else if (windowWidth > 800) {
            let newHeight = sliderWrapper.clientWidth / (1024 / 371) + "px";
            sliderWrapper.style.height =
                (newHeight < 323)
                    ? 323 + "px"
                    : newHeight;
        } else {
            let newHeight = sliderWrapper.clientWidth / (1024 / 261) + "px";
            sliderWrapper.style.height =
                (newHeight < 261)
                    ? 261 + "px"
                    : newHeight;
        }
    }
}

sliderStockInit();

window.sliderStockInitGlobal = function () {
    sliderStockInit();
};
