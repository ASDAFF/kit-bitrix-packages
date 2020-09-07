$(document).ready(function () {


    // ---scroll-----
    var cont = document.querySelector('.header-two__menu-catalog');
    if(cont) {
        new PerfectScrollbar(cont,{
            wheelSpeed: 0.5,
            wheelPropagation: true,
            minScrollbarLength: 20
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
