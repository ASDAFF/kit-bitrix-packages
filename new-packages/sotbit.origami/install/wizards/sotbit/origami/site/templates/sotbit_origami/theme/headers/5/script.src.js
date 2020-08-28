document.addEventListener('DOMContentLoaded', function () {

    const header = document.getElementById('header-three');
    const sidebar = document.getElementById('header-sidebar');
    const bxPanel = document.getElementById('bx-panel');

    setPosition();
    window.addEventListener('scroll', setPosition);
    window.addEventListener('resize', setPosition);


    if (bxPanel) {
        const observerBxPanel = new MutationObserver(setPosition);
        observerBxPanel.observe(bxPanel, {
            attributes: true
        });
    }

    function setPosition() {

        if (window.innerWidth > 1023) {
            if (bxPanel && bxPanel.classList.contains('bx-panel-fixed')) {
                header.style.top = getHeightElement(bxPanel) + 'px';
                sidebar.style.top = getHeightElement(bxPanel) + getHeightElement(header) + 'px';
                sidebar.style.height = window.innerHeight - (getHeightElement(bxPanel) - window.pageYOffset + getHeightElement(header)) + 'px';
            } else {
                if (window.pageYOffset >= getHeightElement(bxPanel)) {
                    header.style.top = '0px';
                    sidebar.style.top = getHeightElement(header) + 'px';
                    sidebar.style.height = window.innerHeight - getHeightElement(header) + 'px';
                } else {
                    header.style.top = (getHeightElement(bxPanel) - window.pageYOffset) + 'px';
                    sidebar.style.top = (getHeightElement(bxPanel) + getHeightElement(header) - window.pageYOffset) + 'px';
                    sidebar.style.height = window.innerHeight - (getHeightElement(bxPanel) - window.pageYOffset + getHeightElement(header)) + 'px';
                }
            }
            return;
        } else {
            if (bxPanel && bxPanel.classList.contains('bx-panel-fixed')) {
                header.style.top = getHeightElement(bxPanel) + 'px';
                sidebar.style.height = '';
                sidebar.style.top = '';
            } else {
                if (window.pageYOffset >= getHeightElement(bxPanel)) {
                    header.style.top = '0px';
                    sidebar.style.height = '';
                    sidebar.style.top = '';
                } else {
                    header.style.top = (getHeightElement(bxPanel) - window.pageYOffset) + 'px';
                    sidebar.style.top = '';
                    sidebar.style.height = '';
                }

            }
            return;
        }
        showMobileView();
    }

    function showMobileView() {
        header.style.top = '';
        sidebar.style.bottom = '0px';
        sidebar.style.top = '';
        sidebar.style.height = '';
    }

    function getHeightElement(item) {
        return item ? item.offsetHeight : 0;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const cityMenu = document.getElementById('menu-city');
    let cityHeader = document.querySelector('[data-entity="open_region"]');

    const compareMenuCount = document.getElementById('menu-compare-count');
    const favoritesMenuCount = document.getElementById('menu-favorites-count');
    const basketMenuCount = document.getElementById('menu-basket-count');
    let compareHeaderCount = document.getElementById('compare-count');
    let favoritesHeaderCount = document.getElementById('favorites-count');
    let basketHeaderCount = document.getElementById('basket-count');
    const city = document.querySelector('.header-three__city');
    changeCity();
    changeCount();
    const observerCity = new MutationObserver(changeCity);
    observerCity.observe(city, {
        childList: true,
        subtree: true,
    });
    const basket = document.querySelector('.header-three__basket');
    const observerCompare = new MutationObserver(changeCount);
    observerCompare.observe(basket, {
        childList: true,
        subtree: true,
    });

    function changeCity() {
        if (cityHeader && cityMenu) {
            createCopyTextContent(cityHeader, cityMenu);
        }
    }

    function changeCount() {
        compareHeaderCount = document.getElementById('compare-count');
        favoritesHeaderCount = document.getElementById('favorites-count');
        basketHeaderCount = document.getElementById('basket-count');
        if (compareHeaderCount) {
            createCopyTextContent(compareHeaderCount, compareMenuCount);
            toggleActive(compareMenuCount);
        }
        if (favoritesHeaderCount) {
            createCopyTextContent(favoritesHeaderCount, favoritesMenuCount);
            toggleActive(favoritesMenuCount);
        }
        if (basketHeaderCount) {
            createCopyTextContent(basketHeaderCount, basketMenuCount);
            toggleActive(basketMenuCount);
        }
    }

    function toggleActive(observerItem) {
        if ((parseInt(observerItem.innerText) !== 0) && !observerItem.classList.contains('active')) {
            observerItem.classList.add('active');
        }

        if (parseInt(observerItem.innerText) === 0) {
            observerItem.classList.remove('active');
        }
    }

    function createCopyTextContent(inEl, outEl) {
        outEl.innerText = inEl.innerText.replace(/\r?\n/g, "");
    };

    const arrSubMenu = document.querySelectorAll('.section__item-submenu');
    for (const item of arrSubMenu) {
        const titleItem = item.previousElementSibling;
        const heightItem = item.offsetHeight;
        titleItem.dataset.submenu = true;
        item.style.height = '0px';
        titleItem.addEventListener('click', (evt) => {
            evt.currentTarget.classList.toggle('open');
            if (evt.currentTarget.classList.contains('open')) {
                item.style.height = heightItem + 'px';
            } else {
                item.style.height = '0px';
            }
        });
    }

    const menu = document.querySelector('#menu-header-three');
    const overlay = menu.querySelector('.menu__overlay');
    const btnClose = document.querySelector('[data-entity="close_menu"]');
    const btnOpen = document.querySelector('[data-entity="open_menu"]');
    if (btnOpen) {
        btnOpen.addEventListener('click', (evt) => {
            menu.classList.add('show');
        });
    }
    btnClose.addEventListener('click', (evt) => {
        menu.classList.remove('show');
    });

    overlay.addEventListener('click', (evt) => {
        menu.classList.remove('show');
    });

    const menuWrapper = menu.querySelector('.menu__wrap-scroll');
    new PerfectScrollbar(menuWrapper, {
        wheelSpeed: 0.5,
        wheelPropagation: true,
        minScrollbarLength: 20,
    });
});
