window.addEventListener("load", showHideDescriptionText);
window.addEventListener("resize", setDataHeight);

function showHideDescriptionText() {
    let wrapper = document.querySelector(".contact-slider-description");
    let text = document.querySelector(".text-wrapper");
    let MAX_HEIGHT = 86;
    let showMoreBtn = wrapper.querySelector(".contacts-description-show_more_btn");
    let descriptionWrapper = wrapper.querySelector(".show-description-wrapper");

    if (text.clientHeight <= MAX_HEIGHT) {
        showMoreBtn.style.display = "none";
        descriptionWrapper.style.height = "auto";
    } else {
        descriptionWrapper.dataset.height = text.clientHeight;
        descriptionWrapper.dataset.closed = true;
        descriptionWrapper.style.height = 84 + "px";
        showMoreBtn.style.display = "inline-block";

        showMoreBtn.addEventListener("click", function () {
            showHideText(descriptionWrapper);
        });

        showMoreBtn.addEventListener("touch", function () {
            showHideText(descriptionWrapper);
        })
    }
}

function setDataHeight() {
    let wrapper = document.querySelector(".contact-slider-description");
    let descriptionWrapper = wrapper.querySelector(".show-description-wrapper");
    let text = document.querySelector(".text-wrapper");
    let MAX_HEIGHT = 86;

    if (text.clientHeight > MAX_HEIGHT) {
        descriptionWrapper.dataset.height = text.clientHeight;
    }
}

function showHideText(descriptionWrapper) {
    if (descriptionWrapper.dataset.closed) {
        delete descriptionWrapper.dataset.closed;
        descriptionWrapper.style.height = descriptionWrapper.dataset.height + "px";
    } else {
        descriptionWrapper.dataset.closed = true;
        descriptionWrapper.style.height = 84 + "px";
    }
}
