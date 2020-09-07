window.addEventListener('DOMContentLoaded', () => {
    const AWAIT_SECONDS = 5000;
    let awaitSeconds = AWAIT_SECONDS;

    let waitElement = setInterval(function () {
        if (document.querySelectorAll(".sorting__svg-icons").length >= 2) {
            clearInterval(waitElement);
            addSortIcons();
        }

        awaitSeconds -= 200;

        if (awaitSeconds <= 0) {
            clearInterval(waitElement);
        }
    }, 200);
});

function addSortIcons() {
    if (!document.querySelector(".mobile_filter_icon_sorting_mobile")) {

        let mobileFilterSorting_SortingIcon = document.createElement("div"),
            mobileFilterSorting_ListIcon = document.createElement("div"),
            wrapper = document.querySelectorAll(".sorting__svg-icons");

        mobileFilterSorting_SortingIcon.innerHTML = `<svg class="mobile_filter_icon_sorting_mobile" width="12" height="12">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_sorting_mobile"></use>
                </svg>`;

        mobileFilterSorting_ListIcon.innerHTML = `<svg class="mobile_filter_icon_list_mobile" width="12" height="14">
               <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_list_mobile"></use>
                </svg>`;

        wrapper[0].appendChild(mobileFilterSorting_SortingIcon);
        wrapper[1].appendChild(mobileFilterSorting_ListIcon);
    }
}
