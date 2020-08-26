;(function () {
    window.resizeScaleBrands = function () {
        let items = document.querySelectorAll('.brand_block_variant_two__item');
        if (!items) {
            return;
        }
        for (let i = 0; items.length > i; i++) {
            items[i].style.height = Math.round(items[0].offsetWidth) + 'px';
        }
    }
})();
