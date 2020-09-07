let desktopScroll;
let desktopInit = false;
let tabletScroll;
let tabletInit = false;

window.addEventListener('DOMContentLoaded', function () {
    let wrapper = document.querySelector('.vlog-detail__videos-list-wrapper');
    let wrapperTablet = document.querySelector('.vlog-detail__videos-list-content-scroll');

    wrapper.addEventListener('scroll', hideShadow);
    wrapperTablet.addEventListener('scroll', hideShadow);

    if (isVlogTablet()) {

        initTabletVideoScroll();
        deleteDesktopScroll();

    } else {

        initDesktopVideoScroll();
        deleteTabletScroll();

    }

    window.addEventListener('resize', function () {
        if (isVlogTablet()) {
            deleteDesktopScroll();

            if (!tabletInit) {
                initTabletVideoScroll();
            } else {
                tabletScroll.update();
            }

        } else {
            if (desktopInit) {
                desktopScroll.update();
            } else {
                initDesktopVideoScroll();
            }

            deleteTabletScroll();
        }
    });
});

window.addEventListener('load', function () {
    let videosWrapper = document.querySelector('.vlog-detail__videos-list-size');
    let wrapper = document.querySelector('.vlog-detail__videos-list-wrapper');

    if (wrapper.clientHeight - 40 >= videosWrapper.clientHeight) {
        document
            .querySelector('.vlog-detail__videos-list-wrapper-shadow')
            .style
            .display =
            'none';
    }
});

function deleteDesktopScroll() {
    if (desktopInit) {
        desktopScroll.destroy();
    }
    desktopInit = false;
}

function deleteTabletScroll() {
    if (tabletInit) {
        tabletScroll.destroy();
    }
    tabletInit = false;
}

function initTabletVideoScroll() {
    if (!tabletInit) {
        tabletScroll = new PerfectScrollbar('.vlog-detail__videos-list-content-scroll', {
            wheelSpeed: 0.3,
            wheelPropagation: true,
            minScrollbarLength: 10
        });
    }

    tabletInit = true;
}

function initDesktopVideoScroll() {
    if (!desktopInit) {
        desktopScroll = new PerfectScrollbar('.vlog-detail__videos-list-wrapper', {
            wheelSpeed: 0.3,
            wheelPropagation: true,
            minScrollbarLength: 10
        });
    }

    desktopInit = true;
}

function isVlogTablet() {
    return window.innerWidth >= 550 && window.innerWidth < 851;
}

function isVlogMobile() {
    return window.innerWidth < 550;
}

function hideShadow() {
    let scrollElement = document.querySelector('.vlog-detail__videos-list-wrapper');
    let wrapper = document.querySelector('.vlog-detail__videos-list-wrapper');
    let shadowElement = document.querySelector('.vlog-detail__videos-list-wrapper-shadow');
    let wrapperTablet = document.querySelector('.vlog-detail__videos-list-content-scroll');

    if (!isVlogTablet() || isVlogMobile()) {
        if (scrollElement.scrollHeight - scrollElement.scrollTop - wrapper.clientHeight < 20) {
            shadowElement.style.opacity = '0';
            shadowElement.style.visibility = 'hidden';
        } else {
            shadowElement.style.opacity = '1';
            shadowElement.style.visibility = 'visible';
        }
    } else {
        if (wrapperTablet.scrollWidth - wrapperTablet.scrollLeft - wrapper.clientWidth < 20) {
            shadowElement.style.opacity = '0';
            shadowElement.style.visibility = 'hidden';
        } else {
            shadowElement.style.opacity = '1';
            shadowElement.style.visibility = 'visible';
        }
    }
}
