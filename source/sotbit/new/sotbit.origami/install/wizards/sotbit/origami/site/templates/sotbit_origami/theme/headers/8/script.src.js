    document.addEventListener('DOMContentLoaded', function () {

    const header = document.getElementById('header-three');
    const bxPanel = document.getElementById('bx-panel');
    const search_wrap = document.querySelector(".header-three__search-wrap");
    const search_icon_open = document.querySelector(".header-three__search-icon-open");
    const search_icon_close = document.querySelector(".header-three__search-icon-close");
    const contact_block = document.querySelector(".header-three__contact");
    const search = document.getElementById('search');
    const slider_header = document.querySelector(".slider-header-three");
    const input_search = document.getElementById('title-search-input');
    const search_result = search.querySelector(".title-search-result");
    const btn_search = search.querySelector(".header-search__btn-search");

    handlerScroll();
    setPosition();

    window.addEventListener('scroll', ()=>{
        handlerScroll();
        setPosition();
    });
    window.addEventListener('resize', function(){
        setPosition();

        if(slider_header){
            slider_header.style.height = "100vh";
        }
       
    });


    if (bxPanel) {
        const observerBxPanel = new MutationObserver(setPosition);
        observerBxPanel.observe(bxPanel, {
            attributes: true
        });
    }


    search_icon_open.addEventListener("click",function(){

        search.classList.add("transitionOn");
        search_wrap.classList.remove("search-wrap--close");
        search_wrap.classList.remove("search-wrap--hide-input");
        search_wrap.classList.add("search-wrap--open");
        btn_search.classList.remove("hidden");

        setTimeout(function(){
            search_icon_close.classList.add("show");
            contact_block.classList.add("hidden");
        },1000);

    })

    search_icon_close.addEventListener("click",function(){
        
        input_search.value = "";
        search_result.style.display = "none";
        search_wrap.classList.add("search-wrap--close");
        search_wrap.classList.remove("search-wrap--open");
        contact_block.classList.remove("hidden");

        setTimeout(()=>search_icon_close.classList.remove("show"),600);

        setTimeout(function(){
            search.classList.remove("transitionOn");
            btn_search.classList.add("hidden");
            search.classList.remove("transitionOn");
            search_wrap.classList.add("search-wrap--hide-input");
        },1000)

       
    });

  

    function handlerScroll(){
        if (window.pageYOffset > header.offsetHeight) {
                header.classList.remove('header-three--unfixed');
                search_wrap.classList.remove('search-wrap--unfixed'); 
        } else {
            header.classList.add('header-three--unfixed');
            search_wrap.classList.add('search-wrap--unfixed'); 
            header.style.top = "";
        }
    }   
    function setPosition() {
        
            if(!header.classList.contains("header-three--unfixed")){
                if (bxPanel && bxPanel.classList.contains('bx-panel-fixed')) {
                    header.style.top = getHeightElement(bxPanel) + 'px';
                   
                } else {
                    if (window.pageYOffset >= getHeightElement(bxPanel)) {
                        header.style.top = '0px';
                       
                    } else {
                        header.style.top = (getHeightElement(bxPanel) - window.pageYOffset) + 'px';
                       
                    }
                }
            }       
    }

    function getHeightElement(item) {
        return item ? item.offsetHeight : 0;
    }
});

;document.addEventListener('DOMContentLoaded', function () {
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

/*script from header 2*/

$(document).ready(function () {

    /* ---hide nav items------*/
    var sizeChange = [];

    function getMinWidth (widthA, widthB) {
        if(widthA > widthB ) {
            return widthB;
        }
        return widthA;
    }

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
        var navPanel = document.querySelector('.header-two__main-nav');
        var widthNavPanel = navPanel.clientWidth;
        var nav = document.querySelectorAll('.header-two__main-navigation > div');
        var widthNav = document.querySelector('.header-two__main-nav-catalog').clientWidth;
        for (var i = 0; nav.length > i; i++) {
            widthNav += nav[i].offsetWidth;
        }

        if (getMinWidth(widthScreen, widthNavPanel) < widthNav) {

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

            document.querySelector('.header-two__main-nav-item-more .header-two__nav-submenu')
                .insertBefore(lastItem, document.querySelector('.header-two__main-nav-item-more .header-two__nav-submenu .header-two__main-nav-item'));
            delItem ();
        }

        navPanel.classList.remove('load');
    }
    delItem ();

    function addItem() {
        sizeChange.pop();
        var item = document.querySelector('.header-two__main-nav-item-more .header-two__main-nav-item').parentNode.removeChild(document.querySelector('.header-two__main-nav-item-more .header-two__main-nav-item'));
        document.querySelector('.header-two__main-navigation').insertBefore(item, document.querySelector('.header-two__main-nav-item-more'));
    }

    window.addEventListener('resize', function(evt) {

        var widthScreen = document.documentElement.clientWidth;
        var widthNavPanel = document.querySelector('.header-two__main-nav').clientWidth;
        var nav = document.querySelectorAll('.header-two__main-navigation > div');
        var widthNav = document.querySelector('.header-two__main-nav-catalog').clientWidth;
        for (var i = 0; nav.length > i; i++) {
            widthNav += nav[i].offsetWidth;
        }
        if (getMinWidth(widthScreen, widthNavPanel) < widthNav) {
            delItem();
        }

        var size = sizeChange[sizeChange.length - 1];

        if ((widthNavPanel > size) && size) {
            addItem();
        }

        if (document.querySelector('.header-two__main-nav-item-more')) {
            if(!document.querySelector('.header-two__main-nav-item-more .header-two__nav-submenu').childNodes[0]) {
                document.querySelector('.header-two__main-nav-item-more').parentNode.removeChild(document.querySelector('.header-two__main-nav-item-more'));
            }
        }

        arrow(3);
    });

    window.refreshMenu();
    
    /* ---end hide nav items------*/
    let catalog_link = document.querySelector('.header-two__main-nav-catalog');
       /* --menu touch---*/
       catalog_link.addEventListener('touchstart', function (evt) {
        evt.preventDefault();
        if (catalog_link .classList.contains('hover')) {
            catalog_link .classList.remove('hover');
            } else {
                catalog_link .classList.add('hover');
            }
    });

    catalog_link.addEventListener('mouseenter', function (evt) {
        if (catalog_link .classList.contains('hover')) {
            catalog_link .classList.remove('hover');
            catalog_link .classList.add('hover');
            } else {
                catalog_link .classList.add('hover');
            }
    });
    catalog_link.addEventListener('mouseleave', function (evt) {
        if (catalog_link .classList.contains('hover')) {
            catalog_link .classList.remove('hover');
            }
    });
    window.addEventListener('resize', function (evt) {
        if (catalog_link .classList.contains('hover')) {
            catalog_link .classList.remove('hover');
        }
    });
    /* ---end menu touch---*/

});
