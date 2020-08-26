window.addEventListener('DOMContentLoaded', function () {
    showOnHover();
    setItemsWrappersHeights();

    setShowMoreEvent();
    collapseTextOnResize();
});

window.addEventListener('load', setItemsWrappersHeights);
window.addEventListener('resize', function () {
    setItemsWrappersHeights();
    collapseTextOnResize();
});

function isServicesMobile() {
    return window.innerWidth <= 470;
}

function setShowMoreEvent() {
    if (document.querySelector('.services-detail-description__show-more')) {
        let showMore = document.querySelector('.services-detail-description__show-more');

        showMore.addEventListener('click', function () {
            document
                .querySelector('.services-detail-description__wrapper')
                .style
                .height =
                document
                    .querySelector('.services-detail-description__text')
                    .clientHeight + 15 + 'px';

            this.style.display = 'none';

        })
    }
}

function collapseTextOnResize() {
    if (document.querySelector('.services-detail-description__wrapper')) {
        let wrapper = document.querySelector('.services-detail-description__wrapper');
        let text = wrapper.querySelector('.services-detail-description__text');
        let showMore = document.querySelector('.services-detail-description__show-more');

        if (isServicesMobile()) {
            if (wrapper.clientHeight > 200) {
                wrapper.style.height = 200 + 'px';
            }

            if (wrapper.clientHeight < text.clientHeight) {
                showMore.style.display = 'block';
            } else {
                showMore.style.display = 'none';
            }
        } else {
            wrapper.style.height = 'auto';
            showMore.style.display = 'none';
        }
    }
}

function setItemsWrappersHeights() {
    let itemsWrappers = document.querySelectorAll('.service-detail-item-wrapper');

    for (let i = 0; i < itemsWrappers.length; i++) {
        itemsWrappers[i].style.height = itemsWrappers[i]
            .querySelector('.service-detail-item')
            .offsetHeight + 'px';

        itemsWrappers[i]
            .querySelector('.service-detail-item')
            .style
            .zIndex = itemsWrappers.length + 2 - i;
    }
}

function showOnHover() {
    let items = document.querySelectorAll('.service-detail-item');

    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener('mouseenter', function () {

            if (items[i].querySelector('.service-detail-item__content-hidden-wrapper')) {
                let hiddenWrapper = items[i].querySelector('.service-detail-item__content-hidden-wrapper');

                hiddenWrapper.style.height = hiddenWrapper
                        .querySelector('.service-detail-item__content-hidden')
                        .clientHeight
                    + 'px';
            }

        });

        items[i].addEventListener('mouseleave', function () {

            if (items[i].querySelector('.service-detail-item__content-hidden-wrapper')) {
                items[i]
                    .querySelector('.service-detail-item__content-hidden-wrapper')
                    .style
                    .height = 0 + 'px';
            }
        })
    }
}
