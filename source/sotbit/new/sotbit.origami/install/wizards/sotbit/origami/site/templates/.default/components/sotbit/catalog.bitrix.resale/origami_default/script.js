// import Swiper from "../../../../../sotbit_origami/assets/plugin/swiper5.2.0";

let buildTabsProductBlock = (function () {
    function buildTabsProductBlock () {
        document.addEventListener("DOMContentLoaded", function () {
            const blockWrapper = document.querySelector('.small-product-blocks');
            if (!blockWrapper) {
                return;
            }
            const productsBlock = Array.prototype.slice.call(blockWrapper.querySelectorAll('.small-product'));
            if (!productsBlock.length > 0) {
                return;
            }
            const tabs = document.createElement('div');
            tabs.classList.add('small-product__tabs-title');
            tabs.classList.add('swiper-container');
            tabs.innerHTML = '<div class="swiper-wrapper">' +
                '<div class="swiper-slide">' +
                '</div>' +
                '</div>';
            const listTitlesBlock = [];


            productsBlock.forEach(function (item, i) {
                const titleBlock = item.querySelector('.small-product__title');
                listTitlesBlock.push(titleBlock);
                tabs.querySelector('.swiper-slide').appendChild(titleBlock.cloneNode(true));
                titleBlock.style.display = 'none';
                item.style.display = 'none';
                if (i === 0) {
                    item.classList.add('active');
                    item.style.display = 'block';
                }
            });

            blockWrapper.insertBefore(tabs, blockWrapper.firstChild);
            new Swiper('.small-product__tabs-title', {
                slidesPerView: 'auto',
                freeMode: true,
            });

            const cloneTitleTabs = Array.prototype.slice.call(tabs.querySelectorAll('.small-product__title'));
            cloneTitleTabs.forEach(function (item, i) {
                if (i === 0) {
                    item.classList.add('active');
                }

                item.addEventListener('click', function () {
                    productsBlock.forEach(function (item) {
                        item.style.display = 'none';
                        item.classList.remove('active');
                    });
                    cloneTitleTabs.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    productsBlock[i].classList.add('active');
                    productsBlock[i].style.display = 'block';
                    item.classList.add('active');
                });
            });
        });
    }
    function clipText(quantity) {
        document.addEventListener("DOMContentLoaded", function () {
            let itemName = document.getElementsByClassName('small-product-block__name-product');
            if (itemName.length > 0) {
                for (let i = 0; itemName.length > i; i++) {
                    let text = null;
                    if (itemName[i].innerText.length >= quantity) {
                        text = itemName[i].innerText.slice(0, quantity) + '...';
                    } else {
                        text = itemName[i].innerText;
                    }
                    itemName[i].textContent = text;
                }
            }
        });
    }
    clipText(34);
    return buildTabsProductBlock;
}) ();
