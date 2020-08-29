document.addEventListener('DOMContentLoaded', function () {

    const filter = document.querySelector('.bx_filter_section');
    new PerfectScrollbar(filter, {
        wheelSpeed: 0.5,
        wheelPropagation: true,
        minScrollbarLength: 20
    });

    const hideFilter = function () {
        document.querySelector('.block_main_left_menu__container').classList.remove('show-filter');
        document.body.style.overflow = "";
    };

    $(document).on('click', '.mobile_filter_btn', function (evt) {
        document.querySelector('.block_main_left_menu__container').classList.toggle('show-filter');
        document.body.style.overflow = "hidden";
    });

    $(document).on('click', '.bx_filter__close-btn', hideFilter);
    $(document).on('click', '.filter-overlay', hideFilter);

});
