window.addEventListener('DOMContentLoaded', function () {
    let tags = document.querySelectorAll('.blog__sidebar-tag');

    for (let i = 0; i < tags.length; i++) {
        tags[i].addEventListener('click', function () {
            clickOnTag.call(this, tags);
        })
    }

    setTagsWrapperHeight();
    showAllTags();
});

window.addEventListener('resize', function () {
    if (document.querySelector(".blog__sidebar-items-tags-show-all").dataset.hide) {
        let wrapper = document.querySelector('.blog__sidebar-item-tags-wrapper');

        wrapper.style.height = wrapper
                .querySelector('.blog__sidebar-item-tags')
                .clientHeight
            + "px";
    }
});

function setTagsWrapperHeight() {
    let tagsWrapper = document.querySelector('.blog__sidebar-item-tags-wrapper');

    if (tagsWrapper.clientHeight <= 120) {
        tagsWrapper.style.height = "auto";
    } else {
        let showAll = document.querySelector(".blog__sidebar-items-tags-show-all");

        tagsWrapper.style.height = "120px";
        showAll.style.display = "inline-flex";

        showAll.addEventListener('click', function () {
            tagsWrapper.style.height = tagsWrapper
                .querySelector('.blog__sidebar-item-tags')
                .clientHeight + "px";

            this.style.display = "none";
        })
    }
}

function showAllTags() {
    if (document.querySelector(".blog__sidebar-items-tags-show-all")) {
        let showAllTagsBtn = document.querySelector(".blog__sidebar-items-tags-show-all");

        showAllTagsBtn.addEventListener('click', function () {
            let wrapper = document.querySelector('.blog__sidebar-item-tags-wrapper');

            wrapper.style.height = wrapper
                    .querySelector('.blog__sidebar-item-tags')
                    .clientHeight
                + "px";

            this.style.display = 'none';
            this.dataset.hide = 'true';
        })
    }
}
