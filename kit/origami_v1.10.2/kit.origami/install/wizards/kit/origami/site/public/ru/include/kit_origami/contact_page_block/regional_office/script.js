window.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector(".regions-select-buttons")) {
        let dotsBtn = document.querySelector(".show-more-dots");
        let topPositions = getTopPositions();

        moveToRegion();
        window.addEventListener("scroll", function () {
            fixContactsRegionsPanel();
            setActiveButton(topPositions);
        });
        window.addEventListener("resize", function () {
            fixReadySizes();
            fixContactsRegionsPanel();
        });
        window.addEventListener("load", function () {
            topPositions = getTopPositions();
            fixReadySizes();
            fixContactsRegionsPanel();
            dotsBtn.addEventListener("click", showHideFixedRegions);
        });
    }
});

function isContactsMobile() {
    let windowWidth = window.innerWidth || document.documentElement.clientWidth;
    return windowWidth < 768;
}

function moveToRegion() {
    let regionsButtons = document.querySelectorAll(".regions-select-buttons .select-region");
    let regions = document.querySelectorAll(".contacts-content-regional-wrapper");

    if (regionsButtons.length !== regions.length) {
        console.warn("Regions buttons number != Regions number");
    }

    for (let i = 0; i < regionsButtons.length; i++) {
        regionsButtons[i].addEventListener("click", function () {
            let fixedButtonsHeight = 0;
            let selectButtons = document.querySelector(".full-size-wrapper");

            selectButtons.dataset.fixed = true;

            if (document.querySelector(".full-size-wrapper[data-fixed=true]")) {
                fixedButtonsHeight = document.querySelector(".full-size-wrapper[data-fixed=true]").clientHeight; //+padding
            }

            let coordY = getPosition(regions[i]) - getHeadersHeight() - fixedButtonsHeight;

            if (coordY > window.pageYOffset) {

                let scroller = setInterval(function () {
                    let scrollBy = 8;
                    if ((scrollBy > window.pageYOffset - coordY)
                        && (window.innerHeight + window.pageYOffset < document.body.offsetHeight)
                        && (window.pageYOffset !== 0)
                        && (coordY !== window.pageYOffset)) {

                        window.scrollBy(0, scrollBy);

                    } else {
                        window.scrollTo(0, coordY);
                        clearInterval(scroller);
                    }
                }, 0.5);

            } else {

                let scroller = setInterval(function () {
                    let scrollBy = 8;
                    if ((scrollBy > coordY - window.pageYOffset)
                        && (window.innerHeight + window.pageYOffset < document.body.offsetHeight)
                        && (window.pageYOffset !== 0)
                        && (coordY !== window.pageYOffset)) {

                        window.scrollBy(0, -scrollBy);

                    } else {
                        window.scrollTo(0, coordY);
                        clearInterval(scroller);
                    }
                }, 0.3);
            }
        })
    }
}

function getTopPositions() {
    let regions = document.querySelectorAll(".contacts-content-regional-wrapper");
    let topPositions = [];

    for (let i = 0; i < regions.length; i++) {
        topPositions[i] = getPosition(regions[i]);
    }

    return topPositions;
}

function getFixButtonsHeight() {
    if (document.querySelector(".full-size-wrapper[data-fixed=true]")) {
        return document.querySelector(".full-size-wrapper[data-fixed=true]").clientHeight + 25; //+padding
    }

    return 0;
}

function setActiveButton(topPositions) {
    let regionsButtons = document.querySelectorAll(".regions-select-buttons .select-region");

    for (let button of regionsButtons) {
        delete button.dataset.active;
    }

    for (let i = 0; i < topPositions.length; i++) {

        if (document.documentElement.scrollTop + (getHeadersHeight() + getFixButtonsHeight()) > topPositions[i]) {

            for (let a = 0; a < i; a++) {
                delete regionsButtons[a].dataset.active;
            }

            regionsButtons[i].dataset.active = true;
        }
    }
}

function getPosition(element) {
    let yPosition = 0;

    while (element) {
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }

    return yPosition;
}

function getHeadersHeight() {
    let headersHeight = 0;

    if (document.querySelector(".fix-header-one")) {
        headersHeight += document.querySelector(".fix-header-one").clientHeight;
    }

    if (document.querySelector(".fix-header-two")) {
        headersHeight += document.querySelector(".fix-header-two").clientHeight;
    }

    if (document.querySelector(".bx-panel-fixed")) {
        headersHeight += document.querySelector(".bx-panel-fixed").clientHeight;
    }

    if (document.querySelector(".header-two__nav.active")) {
        headersHeight += document.querySelector(".header-two__nav.active").clientHeight;
    }

    if (document.querySelector('.header-three')) {
        headersHeight += document.querySelector(".header-three").clientHeight;
    }

    return headersHeight;
}

function fixReadySizes() {
    let selectButtonsWrapper = document.querySelector(".regions-select-buttons-empty-wrapper");
    let buttons = document.querySelector(".regions-select-buttons");
    let hideWrapper = document.querySelector(".hide-buttons-wrapper");

    hideWrapper.dataset.closed = true;
    hideWrapper.dataset.height = hideWrapper.querySelector(".regions-select-buttons").clientHeight;
    hideWrapper.style.height = "34px";
    selectButtonsWrapper.style.height = buttons.clientHeight + "px";
}

function fixContactsRegionsPanel() {
    let scrollTop = document.documentElement.scrollTop;
    let selectButtons = document.querySelector(".full-size-wrapper");
    let anchor = document.querySelector(".contacts_anchor");
    let dots = document.querySelector(".show-more-dots");
    let buttonsWrapperHeight = document.querySelector(".hide-buttons-wrapper").clientHeight + 35;

    if (scrollTop + getHeadersHeight() + buttonsWrapperHeight > getPosition(anchor) && (!isContactsMobile())) {
        selectButtons.dataset.fixed = true;
        selectButtons.style.top = getHeadersHeight() + "px";
        showDots();
    } else {
        delete selectButtons.dataset.fixed;
        dots.style.display = "none";
    }

}

function showDots() {
    let hideBtnsWrapper = document.querySelector(".hide-buttons-wrapper");
    let regionsSelectButtons = document.querySelector(".regions-select-buttons");
    let dots = document.querySelector(".show-more-dots");

    if (regionsSelectButtons.clientHeight > hideBtnsWrapper.clientHeight || hideBtnsWrapper.dataset.opened) {
        dots.style.display = "inline-block";
    } else {
        if (!document.querySelector(".hide-buttons-wrapper").dataset.opened) {
            dots.style.display = "none";
        }
    }
}

function showHideFixedRegions() {
    let hideWrapper = document.querySelector(".hide-buttons-wrapper");

    if (hideWrapper.dataset.closed) {
        delete hideWrapper.dataset.closed;
        hideWrapper.dataset.opened = true;

        hideWrapper.style.height = hideWrapper.querySelector(".regions-select-buttons").clientHeight + "px";

    } else {
        delete hideWrapper.dataset.opened;
        hideWrapper.dataset.closed = true;

        hideWrapper.style.height = "34px"
    }
}
