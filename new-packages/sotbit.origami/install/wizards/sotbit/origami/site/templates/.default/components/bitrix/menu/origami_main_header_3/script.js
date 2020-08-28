// "use strict";
(function () {
    document.addEventListener("DOMContentLoaded", function () {

        let widthHeaderNav = document.querySelector('.header-two__main-nav').offsetWidth;
        let menuWrapper = document.querySelector('.header-two__menu-catalog.menu-catalog-one');
        let menuBtn = document.querySelector('.header-two__main-nav-catalog.header-two__main-nav-catalog--one');
        let widthBorder = 1;
        if(document.querySelector(".header-three")){
            widthBorder = 0;
        }
        let widthBtn = document.querySelector('.header-two__main-nav-catalog > a').offsetWidth + widthBorder * 2;
        let menu = document.querySelector('.main-menu-wrapper');
        let mainMenu = menu.querySelector('.main-menu-wrapper__submenu-main');
        let containerTwo = menu.querySelector('.main-menu-wrapper__submenu-two-wrapper');
        let containerThree = menu.querySelector('.main-menu-wrapper__submenu-three-wrapper');
        let containerBanner = menu.querySelector('.main-menu-wrapper__submenu-banner-wrapper');

        const menuScrollbar = new PerfectScrollbar(mainMenu,{
            wheelSpeed: 0.5,
            wheelPropagation: true,
            minScrollbarLength: 20,
            typeContainer: 'li'
        });

        mainMenu.style.width = widthBtn + 'px';
        mainMenu.style.minWidth = widthBtn + 'px';
        menu.style.minWidth = widthBtn + 'px';
        menu.style.width = widthBtn +'px';
        widthSubmenu = (widthHeaderNav - widthBtn) / 3;

        window.refreshMenu = function () {
            widthBtn = document.querySelector('.header-two__main-nav-catalog > a').offsetWidth + widthBorder * 2;
            mainMenu.style.width = widthBtn + 'px';
            mainMenu.style.minWidth = widthBtn + 'px';
            menu.style.minWidth = widthBtn + 'px';
            menu.style.width = widthBtn + 'px';
            widthHeaderNav = document.querySelector('.header-two__main-nav').offsetWidth;
            widthSubmenu = (widthHeaderNav - widthBtn) / 3;
            if(containerTwo.firstElementChild) {
                containerTwo.firstElementChild.style.width = widthSubmenu + 'px';
            }
            if (containerThree.firstElementChild) {
                containerThree.firstElementChild.style.width = widthSubmenu + 'px';
            }
            if (containerBanner.firstElementChild) {
                containerBanner.firstElementChild.style.width = widthSubmenu + 'px';
            }
        };

        window.addEventListener('resize', window.refreshMenu);

        let tempItem = null;
        menuWrapper.addEventListener('mouseover', function (evt) {
            let btnEvt = evt.target;
            while (btnEvt !== this) {
                if(btnEvt.getAttribute('data-role') == 'item-menu' || btnEvt.getAttribute('data-role') == 'item-submenu' || btnEvt.getAttribute('data-role') == 'item-submenu-two') {
                    switch (btnEvt.getAttribute('data-role')) {
                        case 'item-menu':
                            if(btnEvt !== tempItem) {
                                hover(btnEvt, mainMenu);
                                delSubmenu(containerThree);
                                copySubmenu(btnEvt, containerTwo, containerThree);
                                delBanner(containerBanner);
                                copyBanner(btnEvt, containerBanner);
                                tempItem = btnEvt;
                                calcWidth();
                            }
                            break;
                        case 'item-submenu':
                            if(btnEvt !== tempItem) {
                                hover(btnEvt, btnEvt.parentElement);
                                copySubmenu(btnEvt, containerThree);
                                tempItem = btnEvt;
                                calcWidth();
                            }
                            break;
                        case 'item-submenu-two':
                            if(btnEvt !== tempItem) {
                                hover(btnEvt, btnEvt.parentElement);
                                tempItem = btnEvt;
                                calcWidth();
                            }
                            break;

                        default:
                            break;
                    }
                    return;
                }
                btnEvt = btnEvt.parentNode;
            }
        });

        menuWrapper.addEventListener('mouseleave', function (evt) {
            delSubmenu(containerTwo);
            delSubmenu(containerThree);
            delHover(this);
            delBanner(containerBanner);
            tempItem = null;
            menu.style.width = widthBtn + 'px';
        });

        menuBtn.addEventListener('mouseover', function (evt) {
            menuScrollbar.update();
        });

        function copySubmenu(item, container, containerNext) {
            if(item.querySelector('.js-submenu')) {
                let submenu = item.querySelector('.js-submenu');
                if(container.firstElementChild) {
                    container.removeChild(container.firstElementChild);
                }
                container.appendChild(submenu.cloneNode(true));
                container.firstElementChild.style.width = widthSubmenu + 'px';
                // calcWidth();

                new PerfectScrollbar(container.firstElementChild,{
                    wheelSpeed: 0.5,
                    wheelPropagation: true,
                    minScrollbarLength: 20
                });
                if(containerNext) {
                    if (submenu.firstElementChild.querySelector('ul')) {
                        containerNext.appendChild(submenu.firstElementChild.querySelector('ul').cloneNode(true));
                        containerNext.firstElementChild.style.width = widthSubmenu + 'px';
                        // calcWidth();
                    }
                }
            } else {
                if(container.firstElementChild) {
                    container.removeChild(container.firstElementChild);
                }
            }
        }

        function delSubmenu (container) {
            if(container.firstElementChild) {
                container.removeChild(container.firstElementChild);
            }
        }

        function copyBanner (item, container) {
            if (item.querySelector('.main-menu-wrapper__submenu-banner')){
                container.appendChild(item.querySelector('.main-menu-wrapper__submenu-banner').cloneNode(true));
                container.firstElementChild.style.width = widthSubmenu + 'px';
                // calcWidth();
            };
        }

        function delBanner (container) {
            if(container.firstElementChild) {
                container.removeChild(container.firstElementChild);
            }
        }

        function hover (item, container) {
            let items = container.children;
            for(let i = 0; items.length > i; i++) {
                items[i].classList.remove('hover');
            }
            item.classList.add('hover');
        }

        function delHover (container) {
            let allItemsHover = container.querySelectorAll('.hover');
            for (let i = 0; i < allItemsHover.length; i++) {
                allItemsHover[i].classList.remove('hover');

            }
        }

        function calcWidth() {
            let summWidth = widthBtn + containerTwo.offsetWidth + containerThree.offsetWidth + containerBanner.offsetWidth - widthBorder;
            menu.style.width = summWidth + 'px';
        }

        menuWrapper.addEventListener('wheel', function (evt) {
            evt.preventDefault();
        });
    });
})();

$(document).ready(function () {


    // ---scroll-----
    var cont = document.querySelector('.header-two__menu-catalog');
    if(cont) {
        new PerfectScrollbar(cont,{
            wheelSpeed: 0.5,
            wheelPropagation: true,
            minScrollbarLength: 20,
            // typeContainer: 'li'
        });
    }


    let navSubmenu = document.querySelectorAll('.header-two__nav-submenu');
    if(navSubmenu) {
        for (let i = 0; navSubmenu.length > i; i++) {
            new PerfectScrollbar(navSubmenu[i],{
                wheelSpeed: 0.5,
                wheelPropagation: true,
                minScrollbarLength: 20,
                typeContainer: 'li'
            });
        }
    }
});
