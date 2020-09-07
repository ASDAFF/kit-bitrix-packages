window.addEventListener('resize', function () {
    activeCollapse();
});

window.addEventListener('DOMContentLoaded', function () {
    setCollapseButtonsEvents();
    activeCollapse();
});

window.addEventListener('load', function () {
    activeCollapse();
});

function activeCollapse() {
    if (document.querySelector('.stocks__description')) {
        let block = document.querySelector('.stocks__description');
        let textWrapper = block.querySelector('.stocks__description-text-wrapper');
        let collapseButtonsWrapper = document.querySelector('.stocks__collapse-btns');
        let text = document.querySelector('.stocks__description-text');
        let height;

        if (window.innerWidth > 768) {
            height = 100;
        } else if (window.innerWidth > 520) {
            height = 90;
        } else {
            height = 180;
        }

        if (text.offsetHeight > height) {
            collapseButtonsWrapper.style.display = 'block';
            textWrapper.style.height = height + 'px';
        } else {
            collapseButtonsWrapper.style.display = 'none';
        }
    }
}

function showText() {
    let textWrapper = document.querySelector('.stocks__description-text-wrapper');
    let text = document.querySelector('.stocks__description-text');

    textWrapper.style.height = text.offsetHeight + 5 + 'px';
}

function hideText() {
    let textWrapper = document.querySelector('.stocks__description-text-wrapper');
    let height;

    if (window.innerWidth > 768) {
        height = 100;
    } else if (window.innerWidth > 520) {
        height = 90;
    } else {
        height = 180;
    }

    textWrapper.style.height = height + 'px';
}

function setCollapseButtonsEvents() {
    if (document.querySelector('.stocks__collapse-btns')) {
        let collapseButtonsWrapper = document.querySelector('.stocks__collapse-btns');
        let show = collapseButtonsWrapper.querySelector('.stocks__collapse-btns-show');
        let hide = collapseButtonsWrapper.querySelector('.stocks__collapse-btns-hide');

        show.addEventListener('click', function () {
            collapseButtonsWrapper.dataset.active = 'true';
            showText();
        });

        hide.addEventListener('click', function () {
            delete collapseButtonsWrapper.dataset.active;
            hideText();
        })
    }
}
