window.addEventListener('resize', function () {
    activeCollapse();
});

setCollapseButtonsEvents();
activeCollapse();

function activeCollapse() {
    let block = document.querySelector('.brand-detail-info__description');
    let textWrapper = block.querySelector('.brand-detail-info__description-text-wrapper');
    let collapseButtonsWrapper = document.querySelector('.brand-detail-info__collapse-btns');
    let text = document.querySelector('.brand-detail-info__description-text');
    let height;

    if (window.innerWidth <= 768) {
        height = 200;
    } else {
        height = 210;
    }

    if (text.offsetHeight > height) {
        collapseButtonsWrapper.style.display = 'block';
        textWrapper.style.height = height + 'px';
    } else {
        collapseButtonsWrapper.style.display = 'none';
    }
}

function showText() {
    let textWrapper = document.querySelector('.brand-detail-info__description-text-wrapper');
    let text = document.querySelector('.brand-detail-info__description-text');

    textWrapper.style.height = text.offsetHeight + 'px';
}

function hideText() {
    let textWrapper = document.querySelector('.brand-detail-info__description-text-wrapper');
    let height;

    if (window.innerWidth <= 768) {
        height = 200;
    } else {
        height = 210;
    }

    textWrapper.style.height = height + 'px';
}

function setCollapseButtonsEvents() {
    let collapseButtonsWrapper = document.querySelector('.brand-detail-info__collapse-btns');
    let show = collapseButtonsWrapper.querySelector('.brand-detail-info__collapse-btns-show');
    let hide = collapseButtonsWrapper.querySelector('.brand-detail-info__collapse-btns-hide');

    show.addEventListener('click', function () {
        collapseButtonsWrapper.dataset.active = 'true';
        showText();
    });

    hide.addEventListener('click', function () {
        delete collapseButtonsWrapper.dataset.active;
        hideText();
    })
}
