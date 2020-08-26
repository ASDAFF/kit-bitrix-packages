let isMove = false,
    startX,
    startY;

window.addEventListener("DOMContentLoaded", function () {
    const RESOLUTION = 900;
    let isShow = true,
        topPositions = getTopPositions(),
        fixedBlock = new FixedBlock('.regions-select-buttons', {}),
        wheel = new WheelOfFortune('.wheel-buttons', {
            classBtnOpen: 'detail-menu-open'
        }),
        buttons = document.querySelectorAll(".regions-select-buttons .select-region");

    window.addEventListener("load", function () {
        topPositions = getTopPositions();
    });

    if (document.querySelector(".regions-select-buttons")) {
        window.addEventListener("scroll", function () {
            setActiveButton(topPositions);
        });
    }

    if (window.innerWidth <= RESOLUTION) {
        initWheelButtons(buttons, topPositions);
    }

    if (window.innerWidth > RESOLUTION && isShow) {
        wheel.destroy();
        fixedBlock.init();
        isShow = false;

        if (document.querySelector(".regions-select-buttons")) {
            let regionsButtons = document.querySelectorAll(".regions-select-buttons .select-region"),
                regions = document.querySelectorAll(".contacts-content-regional-wrapper");

            moveToRegion(regionsButtons, regions, "click");
        }
    }

    if (document.querySelector('.btn-round-menu-open.detail-menu-open')) {
        document.querySelector('.btn-round-menu-open.detail-menu-open').addEventListener('click', hideGoTopButton);
    }
    if (document.querySelector('.btn-round-menu-close')) {
        document.querySelector('.btn-round-menu-close').addEventListener('click', hideGoTopButton);
    }

    // hideErrorButtons();
    hideGoTopButton();

    window.addEventListener('resize', function () {
        topPositions = getTopPositions();

        if (window.innerWidth < RESOLUTION && !isShow) {
            fixedBlock.destroy();
            wheel.init();

            if (document.querySelector('.btn-round-menu-open.detail-menu-open')) {
                document.querySelector('.btn-round-menu-open.detail-menu-open').addEventListener('click', hideGoTopButton);
            }
            if (document.querySelector('.btn-round-menu-close')) {
                document.querySelector('.btn-round-menu-close').addEventListener('click', hideGoTopButton);
            }

            let buttons = document.querySelectorAll(".regions-select-buttons .select-region");
            let regions = document.querySelectorAll(".contacts-content-regional-wrapper");

            moveToRegion(buttons, regions, "click");
            isShow = true;
        }

        if (window.innerWidth >= RESOLUTION && isShow) {
            wheel.destroy();
            fixedBlock.init();
            isShow = false;
        }

        // hideErrorButtons();
    });

});

window.addEventListener('scroll', hideGoTopButton);

function hideGoTopButton() {
    if (document.querySelector('.btn_go-top.visible')
        && (document.querySelector('.wheel-buttons.nav-round-menu-box'))) {
        let goTopBtn = document.querySelector('.btn_go-top.visible'),
            wheel = document.querySelector('.wheel-buttons.nav-round-menu-box');

        if (wheel.classList.contains('hide')) {
            goTopBtn.style.display = 'inline-block';
        } else {
            goTopBtn.style.display = 'none';
        }
    }
}

function initWheelButtons(btns, positions) {
    for (let i = 0; i < btns.length; i++) {

        let a = i;

        if (i >= positions.length) {
            while (a >= positions.length) {
                a -= positions.length;
            }
        }

        btns[i].addEventListener('click', function () {
            scrollToCoordY(positions[a]);
        });

        btns[i].addEventListener('touchstart', function (evt) {
            handlerTouchStart(evt);
        });

        btns[i].addEventListener('touchend', function (evt) {
            handlerTouchEnd(evt, positions[a]);
        });

    }
}

function handlerTouchStart(evt) {
    window.addEventListener('touchmove', isTouchMove);
    isMove = false;
    startX = evt.changedTouches[0].clientX;
    startY = evt.changedTouches[0].clientY;
}

function handlerTouchEnd(evt, coordY) {
    window.removeEventListener('touchmove', isTouchMove);

    let endX = evt.changedTouches[0].clientX,
        endY = evt.changedTouches[0].clientY,
        diffX = Math.abs(endX - startX),
        diffY = Math.abs(endY - startY);

    isMove = !(diffX < 5 || diffY < 5);

    if (!isMove) {
        scrollToCoordY(coordY);
    }
}

function isTouchMove() {
    isMove = true;
}

function scrollToCoordY(coordY) {
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
}

function moveToRegion(regionsButtons, regions, event) {

    for (let i = 0; i < regionsButtons.length; i++) {
        regionsButtons[i].addEventListener(event, function () {

            let fixedButtonsHeight = 0;
            let a;

            if (i > regions.length) {
                let j = i;
                while (j > regions.length) {
                    j -= regionsButtons.length;
                }
                a = j;
            } else {
                a = i;
            }

            let coordY = getPosition(regions[a]) - getHeadersHeight() - fixedButtonsHeight;

            scrollToCoordY(coordY);
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

function setActiveButton(topPositions) {
    let regionsButtons = document.querySelectorAll(".regions-select-buttons .select-region");

    for (let button of regionsButtons) {
        delete button.dataset.active;
    }

    for (let i = 0; i < topPositions.length; i++) {

        if (document.documentElement.scrollTop + (getHeadersHeight()) + 2 >= topPositions[i]) {

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

    if (document.querySelector(".header-three")) {
        headersHeight += document.querySelector(".header-three").clientHeight;
    }

    return headersHeight;
}
