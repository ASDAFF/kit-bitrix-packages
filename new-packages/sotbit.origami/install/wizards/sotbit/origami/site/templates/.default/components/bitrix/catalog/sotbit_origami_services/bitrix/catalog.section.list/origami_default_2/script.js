// window.addEventListener('DOMContentLoaded', () => {
//     setItemsHeight();
// });
window.addEventListener('load', () => {
    setDataset();
    // setItemsHeight();
    showLinks();
});
window.addEventListener('resize', () => {
    setDataset();
    // setItemsHeight();
});

// function setItemsHeight() {
    // let wrapper = document.querySelector('.services-wrapper');
    // let items = wrapper.querySelectorAll('.service-item');
    //
    // for (let i = 0; i < items.length; i++) {
    //     items[i].style.height = items[i].clientWidth < 428
    //         ? items[i].clientWidth + 'px'
    //         : 428 + 'px';
    // }
// }

function setDataset() {
    let wrapper = document.querySelector('.services-wrapper');
    let contentsWrapper = wrapper.querySelectorAll('.service-item__content-wrapper');
    let contents = wrapper.querySelectorAll('.service-item__content');

    for (let i = 0; i < contents.length; i++) {
        contents[i].dataset.height = contents[i].clientHeight;
        contentsWrapper[i].style.height = contents[i].dataset.height + 'px';
    }
}

function showLinks() {
    let wrapper = document.querySelector('.services-wrapper');
    let items = wrapper.querySelectorAll('.service-item');

    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener('mouseenter', function () {

            if (!isServicesTablet()) {
                items[i].querySelector('.service-item__links-resizer').style.height =
                    items[i]
                        .querySelector('.service-item__links-resizer > div')
                        .clientHeight
                    + 'px';

                items[i].querySelector('.service-item__content-wrapper').style.height =
                    items[i].clientHeight
                    + 'px';
            }

            function isServicesTablet() {
                return window.innerWidth < 920;
            }
        });

        items[i].addEventListener('mouseleave', function () {

            if (!isServicesTablet()) {

                items[i].querySelector('.service-item__links-resizer').style.height = 0 + 'px';

                items[i].querySelector('.service-item__content-wrapper').style.height =
                    items[i].querySelector('.service-item__content')
                        .dataset
                        .height
                    + 'px';

            }

            function isServicesTablet() {
                return window.innerWidth < 920;
            }
        })
    }
}
