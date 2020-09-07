;(function () {
    document.addEventListener('DOMContentLoaded', function() {

        let header = {};
        header.block = document.getElementById('header-two');
        header.heigth = header.block.offsetHeight;
        header.btn = header.block.querySelector('.header-two__btn-fixed-menu');
        header.subMenu = header.block.querySelector('.header-two__nav');
        header.headerCard = header.block.querySelector('.window_basket');
        header.linkMenu = header.block.querySelector('.header-two__main-nav-catalog');

        // --menu touch---
        header.linkMenu.addEventListener('touchstart', function (evt) {
            evt.preventDefault();
            if (header.linkMenu.classList.contains('hover')) {
                    header.linkMenu.classList.remove('hover');
                } else {
                    header.linkMenu.classList.add('hover');
                }
        });

        header.linkMenu.addEventListener('mouseenter', function (evt) {
            if (header.linkMenu.classList.contains('hover')) {
                    header.linkMenu.classList.remove('hover');
                    header.linkMenu.classList.add('hover');
                } else {
                    header.linkMenu.classList.add('hover');
                }
        });
        header.linkMenu.addEventListener('mouseleave', function (evt) {
            if (header.linkMenu.classList.contains('hover')) {
                    header.linkMenu.classList.remove('hover');
                }
        });
        window.addEventListener('resize', function (evt) {
            if (header.linkMenu.classList.contains('hover')) {
                header.linkMenu.classList.remove('hover');fix
            }
        });
        // ---end menu touch---

        let BXpanel = document.querySelector('.bx-panel-fixed');

        let searchBlock = null;
        let searchForm = null;

        document.addEventListener('DOMContentLoaded', function() {
            searchBlock = document.querySelector('.title-search-result');
            searchBlock.addEventListener('wheel', function (evt) {
                evt.preventDefault();
            });
            searchForm = document.querySelector('#search');
        });

        if(header.headerCard) {
            header.headerCard.addEventListener('wheel', function (evt) {
                evt.preventDefault();
            });
        }

        function handlerScroll() {
            if (window.pageYOffset > header.heigth) {
                header.block.classList.add('fix-header-two');
                header.block.nextElementSibling.style.paddingTop = header.heigth + "px";

                if(BXpanel) {
                    header.block.style.top = BXpanel.offsetHeight + 'px';
                } else {
                    header.block.style.top = "";
                }

                if (searchBlock && searchForm) {
                    searchForm.appendChild(searchBlock);
                    searchBlock.classList.add('fixed');
                }


            } else {
                header.block.classList.remove('fix-header-two');
                header.block.style.top = "";
                header.subMenu.classList.remove('active');
                header.block.nextElementSibling.style.paddingTop = "";

                if (searchBlock) {
                    searchBlock.classList.remove('fixed');
                    header.block.parentNode.appendChild(searchBlock);
                    searchBlock.style.display = 'none';
                }
            }
        };

        header.btn.addEventListener('click', function () {
            header.subMenu.classList.toggle('active');
        });

        function addFixedHeader () {
            window.addEventListener('scroll', handlerScroll);
            if (window.pageYOffset > header.heigth) {
                header.block.classList.add('fix-header-two');
            } else {
                header.block.classList.remove('fix-header-two');
            }
        }

        function removeFixedHeader () {
            window.removeEventListener('scroll', handlerScroll);
            header.block.classList.remove('fix-header-two');
            header.block.nextElementSibling.style.paddingTop = "";
        }


        function addMobileFixedHeader () {
            if (window.innerWidth < 768) {
                addFixedHeader();

            } else {
                removeFixedHeader();
            }
        }

        function addDesktopFixedHeader () {
            if (window.innerWidth >= 768) {
                addFixedHeader();

            } else {
                removeFixedHeader();
            }
        }

        window.fixedHeader = function (params) {
            if(params === 'mobile') {
                addMobileFixedHeader ();
                window.addEventListener('resize', addMobileFixedHeader);
            }

            if(params === 'desktop') {
                addDesktopFixedHeader ();
                window.addEventListener('resize', addDesktopFixedHeader);
            }

            if(params === 'all') {
                window.removeEventListener('resize', addDesktopFixedHeader);
                window.removeEventListener('resize', addMobileFixedHeader);
                addFixedHeader();
            }

            if(params === 'del') {
                window.removeEventListener('resize', addDesktopFixedHeader);
                window.removeEventListener('resize', addMobileFixedHeader);
                removeFixedHeader();
            }
        }
    });
})();


$(document).ready(function () {

    // ---hide nav items------
    var sizeChange = [];
    function arrow (quantity) {
        var navigation = document.querySelectorAll('.header-two__main-navigation .header-two__main-nav-item');

        for(var n = 0; navigation.length > n; n++) {
            if( navigation[n].querySelector('.header-two__nav-submenu')) {
                navigation[n].querySelector('.header-two__nav-submenu').classList.remove('header-two__nav-submenu--right');
                navigation[n].querySelector('.header-two__nav-submenu').classList.add('header-two__nav-submenu--left');
            }
        }

        for (var i = quantity; i > 0; i--) {
            var item = navigation[navigation.length - i];

            if (item.querySelector('.header-two__nav-submenu')) {
                item.querySelector('.header-two__nav-submenu').classList.remove('header-two__nav-submenu--left');
                item.querySelector('.header-two__nav-submenu').classList.add('header-two__nav-submenu--right');
            }
        }
    }
    arrow(3);

    function delItem () {
        var widthScreen = document.documentElement.clientWidth;
        var nav = document.querySelectorAll('.header-two__main-navigation > div');
        var widthNav = document.querySelector('.header-two__main-nav-catalog').clientWidth;
        for (var i = 0; nav.length > i; i++) {
            widthNav += nav[i].offsetWidth;
        }

        if (widthScreen < widthNav) {

            if (!document.querySelector('.header-two__main-nav-item-more')) {
                var itemMore = document.createElement('div');
                itemMore.classList.add('header-two__main-nav-item-more');
                var submenuItemMore = document.createElement('div');
                submenuItemMore.classList.add('header-two__nav-submenu');
                submenuItemMore.classList.add('header-two__nav-submenu--right');
                itemMore.innerHTML =
                    '<svg class="site-navigation__item-icon-more" width="24" height="18">' +
                        '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_more"></use>' +
                    '</svg>';
                itemMore.appendChild(submenuItemMore);
                document.querySelector('.header-two__main-navigation').appendChild(itemMore);
            }

            var itemsNav = document.querySelectorAll('.header-two__main-navigation > .header-two__main-nav-item');
            var lastItem = itemsNav[itemsNav.length - 1].parentNode.removeChild(itemsNav[itemsNav.length - 1]);
            sizeChange.push(widthNav);

            document.querySelector('.header-two__main-nav-item-more .header-two__nav-submenu').insertBefore(lastItem, document.querySelector('.header-two__main-nav-item-more .header-two__nav-submenu .header-two__main-nav-item'));
            delItem ();

        }
    }
    delItem ();


    function addItem() {
        sizeChange.pop();
        var item = document.querySelector('.header-two__main-nav-item-more .header-two__main-nav-item').parentNode.removeChild(document.querySelector('.header-two__main-nav-item-more .header-two__main-nav-item'));
        document.querySelector('.header-two__main-navigation').insertBefore(item, document.querySelector('.header-two__main-nav-item-more'));
    }


    window.addEventListener('resize', function(evt) {

        var widthScreen = document.documentElement.clientWidth;
        var nav = document.querySelectorAll('.header-two__main-navigation > div');
        var widthNav = document.querySelector('.header-two__main-nav-catalog').clientWidth;
        for (var i = 0; nav.length > i; i++) {
            widthNav += nav[i].offsetWidth;
        }

        if (widthScreen < widthNav) {
            delItem();
        }

        var size = sizeChange[sizeChange.length - 1];
        if ((widthScreen > size) && size) {
            addItem();
        }

        if (document.querySelector('.header-two__main-nav-item-more')) {
            if(!document.querySelector('.header-two__main-nav-item-more .header-two__nav-submenu').childNodes[0]) {
                document.querySelector('.header-two__main-nav-item-more').parentNode.removeChild(document.querySelector('.header-two__main-nav-item-more'));
            }
        }

        arrow(3);
    })

    // ---end hide nav items------
});
