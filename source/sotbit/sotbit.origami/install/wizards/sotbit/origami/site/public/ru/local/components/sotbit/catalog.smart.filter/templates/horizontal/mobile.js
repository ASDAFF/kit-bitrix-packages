var insertFilter = false;
var reBindOnce = false;

function checkMobileFilter(mql) {

    if (typeof window.trackBarOptions !== 'undefined') {
        window.trackBarCurrentValues = {};
        for (var key in window.trackBarOptions) {
            window.trackBarCurrentValues[key] = {
                'leftValue': window['trackBar' + key].minInput.value,
                'rightValue': window['trackBar' + key].maxInput.value,
            }
        }
    }

    if (mql.matches) {
        if (!insertFilter) {
            $(".mobile_filter_form").html($(".bx_filter_wrapper").html());
            $(".bx_filter_wrapper .bx_filter").remove();
            insertFilter = true;

            (function () {

                if (!document.querySelector(".mobile_filter_icon_sorting_mobile")) {

                    let mobileFilterSorting_SortingIcon = document.createElement("svg"),
                        mobileFilterSorting_ListIcon = document.createElement("svg"),
                        wrapper = document.querySelectorAll(".mobile_sorting_svg_icons");

                    mobileFilterSorting_SortingIcon.setAttribute("class", "mobile_filter_icon_sorting_mobile");
                    mobileFilterSorting_SortingIcon.setAttribute("height", "12px");
                    mobileFilterSorting_SortingIcon.setAttribute("width", "12px");

                    mobileFilterSorting_ListIcon.setAttribute("class", "mobile_filter_icon_list_mobile");
                    mobileFilterSorting_ListIcon.setAttribute("height", "14px");
                    mobileFilterSorting_ListIcon.setAttribute("width", "12px");

                    mobileFilterSorting_SortingIcon.innerHTML = '<svg class="mobile_filter_icon_sorting_mobile" width="12" height="12">\n' +
                        '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_sorting_mobile"></use>\n' +
                        '</svg>';

                    mobileFilterSorting_ListIcon.innerHTML = '<svg class="mobile_filter_icon_list_mobile" width="12" height="14">\n' +
                        '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_list_mobile"></use>\n' +
                        '</svg>';

                    wrapper[0].appendChild(mobileFilterSorting_SortingIcon);
                    wrapper[1].appendChild(mobileFilterSorting_ListIcon);
                }
            })();

            (function () {
                    let mobileFilter = document.querySelector(".mobile_filter_form");
                    let titles = getTitles();

                    $(mobileFilter).ready(
                        setMobileFilterEvents()
                    );

                    function setMobileFilterEvents() {
                        let mobileFilterButton = document.querySelector(".mobile_filter_btn"),
                            bxFilterSection = document.querySelector(".bx_filter"),
                            mobileFilter = document.querySelector(".mobile_filter_form"),
                            formSmartFilter = mobileFilter.querySelector(".bx_filter form"),
                            mobileFilterItems = mobileFilter.querySelectorAll(".catalog_content__filter_horizon_item .catalog_content__filter_horizon_item_name"),
                            checkBoxWrappers = mobileFilter.querySelectorAll("#style-overflow-search"),
                            mobileFilterButtonsWrapper = mobileFilter.querySelector(".bx_filter_popup_result.fonts__middle_comment.right");

                        window.displayApplyNumber = function () {
                            displayApplyNumber();
                        };

                        window.sortFilterMainMenuItems = function () {
                            sortFilterMainMenuItems();
                        };

                        addTitles(formSmartFilter, "main-mobile-filter-menu");
                        addColorsTitles();

                        for (let i = 0; i < checkBoxWrappers.length; i++) {
                            addTitles(checkBoxWrappers[i], "checkbox-wrapper-mobile-filter-" + i);
                        }

                        setEventListeners();
                        sortColorsItems();
                        relocateHiddenFilterProps();

                        function addColorsTitles() {
                            if (mobileFilter.querySelector(".bx-filter-param-btn-inline.checkboxes_with_pictures")) {
                                addTitles(mobileFilter.querySelector(".bx-filter-param-btn-inline.checkboxes_with_pictures"), "colors-mobile-filter");
                            }

                            if (mobileFilter.querySelector(".col-xs-12 .bx-filter-param-btn-block")) {
                                addTitles(mobileFilter.querySelector(".col-xs-12 .bx-filter-param-btn-block"), "colors-mobile-filter");
                            }
                        }

                        function sortColorsItems() {
                            if (isFilterMobile()) {
                                if (mobileFilter.querySelector(".col-xs-12 .bx-filter-param-btn-block")
                                    || mobileFilter.querySelector(".bx-filter-param-btn-inline.checkboxes_with_pictures")) {

                                    let colors = mobileFilter.querySelector(".col-xs-12 .bx-filter-param-btn-block")
                                        || mobileFilter.querySelector(".bx-filter-param-btn-inline.checkboxes_with_pictures");

                                    let inputs = colors.getElementsByTagName("input"),
                                        inputsArrayId = getCheckBoxesById(inputs);

                                    if (isSomeColorCheckboxChecked(inputsArrayId)) {
                                        displayFilterTitles(".colors-mobile-filter", "");

                                        for (let i = 0; i < inputsArrayId.length; i++) {

                                            if (document
                                                .querySelector("[for='" + inputsArrayId[i] + "']")
                                                .getAttribute("class") === "bx-filter-param-label bx-active") {

                                                document.getElementById(inputsArrayId[i]).style.order = "1";
                                                document.querySelector("[for='" + inputsArrayId[i] + "']").style.order = "1";

                                            } else {

                                                document.getElementById(inputsArrayId[i]).style.order = "3";
                                                document.querySelector("[for='" + inputsArrayId[i] + "']").style.order = "3";

                                            }
                                        }
                                    } else {
                                        displayFilterTitles(".colors-mobile-filter", "none");

                                        for (let i = 0; i < inputsArrayId.length; i++) {

                                        }
                                    }
                                }
                            }
                        }

                        function getCheckBoxesById(inputs) {
                            let allId = [];
                            for (let i = 0; i < inputs.length; i++) {
                                allId[i] = inputs[i].getAttribute("id");
                            }
                            return allId;
                        }

                        function isSomeColorCheckboxChecked(arrayOfId) {
                            for (let i = 0; i < arrayOfId.length; i++) {
                                if (document.querySelector("[for='" + arrayOfId[i] + "']").getAttribute("class") === "bx-filter-param-label bx-active") {
                                    return true;
                                }
                            }
                            return false;
                        }

                        function reset() {
                            displayFilterTitles(".main-mobile-filter-menu", "none");

                            if (mobileFilter.querySelector(".bx-filter-param-btn-inline.checkboxes_with_pictures")
                                || mobileFilter.querySelector(".col-xs-12 .bx-filter-param-btn-block")) {
                                displayFilterTitles(".colors-mobile-filter", "none");
                            }

                            for (let i = 0; i < checkBoxWrappers.length; i++) {
                                displayFilterTitles(".checkbox-wrapper-mobile-filter-" + i, "none");
                            }

                            if (document
                                .querySelectorAll(".catalog_content__filter_horizon_item_wrapper.filter_block_wrapper_max_height")
                                .length > 0) {

                                let filterBlockWrapperMaxHeight = document
                                    .querySelectorAll(".catalog_content__filter_horizon_item_wrapper.filter_block_wrapper_max_height");

                                for (let i = 0; i < filterBlockWrapperMaxHeight.length; i++) {
                                    filterBlockWrapperMaxHeight[i]
                                        .setAttribute("class", "catalog_content__filter_horizon_item_wrapper");
                                }
                            }
                        }

                        function sortCheckBoxes() {
                            if (isFilterMobile()) {
                                for (let i = 0; i < checkBoxWrappers.length; i++) {
                                    let checkBoxes = checkBoxWrappers[i].querySelectorAll(".checkbox__input"),
                                        paramBoxes = checkBoxWrappers[i].querySelectorAll(".bx_filter_parameters_box_checkbox");

                                    if (isSomeCheckboxChecked(checkBoxWrappers[i])) {
                                        displayFilterTitles(".checkbox-wrapper-mobile-filter-" + i, "");

                                        for (let i = 0; i < checkBoxes.length; i++) {
                                            paramBoxes[i].style.order = (checkBoxes[i].checked) ? "1" : "3";
                                        }

                                    } else {
                                        displayFilterTitles(".checkbox-wrapper-mobile-filter-" + i, "none");

                                        for (let i = 0; i < checkBoxes.length; i++) {
                                            if (paramBoxes[i].hasAttribute("order")) {
                                                paramBoxes[i].removeAttribute("order");
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        function relocateHiddenFilterProps() {
                            for (let i = 0; i < checkBoxWrappers.length; i++) {
                                if (checkBoxWrappers[i].querySelector(".hidden_filter_props")) {
                                    let hiddenProps = checkBoxWrappers[i].querySelector(".hidden_filter_props"),
                                        hiddenPropsInnerHTML = hiddenProps.innerHTML;

                                    while (hiddenProps.children.length > 0) {
                                        hiddenProps.removeChild(hiddenProps.firstChild);
                                    }
                                    hiddenProps.innerHTML = '';
                                    hiddenProps.style.display = "none";

                                    checkBoxWrappers[i].innerHTML += hiddenPropsInnerHTML;
                                }
                            }
                        }

                        function isSomeCheckboxChecked(wrapper) {
                            let checkboxes = wrapper.getElementsByTagName("input");

                            for (let i = 0; i < checkboxes.length; i++) {
                                if (checkboxes[i].checked) {
                                    return true;
                                }
                            }
                            return false;
                        }

                        function displayApplyNumber() {
                            let modNumber = parseInt(document.getElementById("modef_num").textContent),
                                latestTextSetFilterButton,
                                setFilterButton = mobileFilter.querySelector(".popup_result_btns [name='set_filter']"),
                                filterItems = document.querySelectorAll(".catalog_content__filter_horizon_item");

                            if (isSomeItemSelected(filterItems)) {
                                let filterBlockWrapperMaxHeight = mobileFilter
                                    .querySelectorAll(".catalog_content__filter_horizon_item_wrapper.filter_block_wrapper_max_height");

                                for (let i = 0; i < filterBlockWrapperMaxHeight.length; i++) {
                                    filterBlockWrapperMaxHeight[i]
                                        .setAttribute("class", "catalog_content__filter_horizon_item_wrapper");
                                }

                                mobileFilterButtonsWrapper.style.opacity = "1";

                                if (modNumber % 10 === 1) {
                                    latestTextSetFilterButton = " " + titles[3];
                                } else if (modNumber % 10 === 2
                                    || modNumber % 10 === 3
                                    || modNumber % 10 === 4) {

                                    latestTextSetFilterButton = " " + titles[4];

                                } else {
                                    latestTextSetFilterButton = " " + titles[5];
                                }

                                setFilterButton.setAttribute("value", titles[2] + " " + modNumber + latestTextSetFilterButton);

                            } else {
                                let filterBlockWrapperMaxHeight = mobileFilter
                                    .querySelectorAll(".catalog_content__filter_horizon_item_wrapper");

                                for (let i = 0; i < filterBlockWrapperMaxHeight.length; i++) {
                                    filterBlockWrapperMaxHeight[i]
                                        .setAttribute("class", "catalog_content__filter_horizon_item_wrapper filter_block_wrapper_max_height");
                                }

                                mobileFilterButtonsWrapper.style.opacity = "0";
                                setFilterButton.setAttribute("value", titles[6]);
                            }
                        }

                        function sortFilterMainMenuItems() {
                            if (isFilterMobile()) {
                                let filterItems = document.querySelectorAll(".catalog_content__filter_horizon_item"),
                                    selectedNumber = document.querySelector(".mobile_filter-selected_number"),
                                    setFilterButton = mobileFilter.querySelector("[name='set_filter']");

                                if (isSomeItemSelected(filterItems)) {
                                    try {
                                        displayFilterTitles(".main-mobile-filter-menu", "");
                                    } catch (e) {
                                    }
                                    for (let i = 0; i < filterItems.length; i++) {
                                        filterItems[i].style.order = (isItemSelected(filterItems[i]) > 0) ? "1" : "3";
                                    }
                                    displayApplyNumber();

                                } else {
                                    try {
                                        displayFilterTitles(".main-mobile-filter-menu", "none");
                                    } catch (e) {
                                    }
                                    for (let i = 0; i < filterItems.length; i++) {
                                        filterItems[i].style.order = "0";
                                    }
                                    setFilterButton.setAttribute("value", titles[6]);

                                }

                                let numberSelectedItems = getNumberSelectedItems(filterItems);

                                if (numberSelectedItems === 0) {
                                    selectedNumber.style.display = "none";
                                } else {
                                    selectedNumber.style.display = "inline-block";
                                    selectedNumber.textContent = numberSelectedItems.toString();
                                }
                            }
                        }

                        function getNumberSelectedItems(items) {
                            let count = 0;
                            for (let i = 0; i < items.length; i++) {
                                count = (isItemSelected(items[i])) ? ++count : count;
                            }
                            return count;
                        }

                        function isSomeItemSelected(items) {
                            for (let i = 0; i < items.length; i++) {
                                if (isItemSelected(items[i])) {
                                    return true;
                                }
                            }
                            return false;
                        }

                        function isItemSelected(item) {
                            let innerItem = item.querySelector(".selected_items");
                            return innerItem.textContent.length > 0;
                        }

                        function createTitle(className, innerText) {
                            let element = document.createElement("div");
                            element.setAttribute("class", className);
                            element.textContent = innerText;
                            element.style.display = "none";

                            return element;
                        }

                        function addTitles(parent, subClass) {
                            let classNames = "used-now-title " + subClass;
                            parent
                                .appendChild(createTitle(classNames, titles[0]));

                            classNames = "not-used-now-title " + subClass;
                            parent
                                .appendChild(createTitle(classNames, titles[1]));
                        }

                        function isFilterMobile() {
                            let mobileFilter = document.querySelector(".mobile_filter_form");
                            return mobileFilter.children.length > 0
                        }

                        function displayFilterTitles(subClass, display) {
                            let usedNowTitle = document.querySelector(".used-now-title" + subClass),
                                notUsedNowTitle = document.querySelector(".not-used-now-title" + subClass);

                            usedNowTitle.style.display = display;
                            notUsedNowTitle.style.display = display;
                        }

                        function setEventListeners() {
                            mobileFilterButton.addEventListener("click", function () {
                                    sortFilterMainMenuItems();
                                    displayApplyNumber();
                                }
                            );

                            bxFilterSection.addEventListener("click", function () {
                                    sortFilterMainMenuItems();
                                    displayApplyNumber();
                                }
                            );

                            mobileFilter.addEventListener("click", function () {
                                    sortFilterMainMenuItems();
                                    displayApplyNumber();
                                }
                            );

                            window.addEventListener("resize", function () {
                                    if (isFilterMobile()) {
                                        sortFilterMainMenuItems();
                                        sortColorsItems();
                                        sortCheckBoxes();
                                        displayApplyNumber();
                                    } else {
                                        reset();
                                    }
                                }
                            );

                            for (let i = 0; i < mobileFilterItems.length; i++) {
                                mobileFilterItems[i].addEventListener("click", function (event) {
                                        sortColorsItems();
                                        sortCheckBoxes();
                                        displayApplyNumber();
                                    }
                                )
                            }
                        }
                    }
                }
            )();
        }
        if (!reBindOnce) {
            reBindBtns();
            reBindOnce = true;
        }
    } else {
        if (insertFilter) {
            $(".bx_filter_wrapper").html($(".mobile_filter_form").html()).find(".bx_filter").removeAttr("style");
            $(".mobile_filter_form .bx_filter").remove();
            insertFilter = false;
        }
    }

    searchfieldRefresh();

    if (typeof window.trackBarOptions !== 'undefined') {
        for (var key in window.trackBarOptions) {
            window.trackBarOptions[key].curMinPrice = window.trackBarCurrentValues[key].leftValue;
            window.trackBarOptions[key].curMaxPrice = window.trackBarCurrentValues[key].rightValue;
            window['trackBar' + key] = new BX.Iblock.SmartFilter(window.trackBarOptions[key]);
            window['trackBar' + key].minInput.value = window.trackBarCurrentValues[key].leftValue;
            window['trackBar' + key].maxInput.value = window.trackBarCurrentValues[key].rightValue;
        }
    }
}

var mql = window.matchMedia("(max-width: 991px)");
mql.addListener(checkMobileFilter);


function reBindBtns() {
    $(".mobile_filter_form, .bx_filter_wrapper").on("click", "[name='del_filter']", function () {
        window.location.href = $(".bx_filter .bx_filter_popup_result .popup_result_btns a.del_filter").attr("href");
    });
    $(".mobile_filter_form, .bx_filter_wrapper").on("click", "[name='set_filter']", function () {
        window.location.href = $(".bx_filter .bx_filter_popup_result .popup_result_btns a.set_filter").attr("href");
    });
}


$(document).ready(function () {

    checkMobileFilter(mql);

    $(".mobile_filter_btn").click(function () {
        $(".mobile_filter_form .bx_filter").show("slide", {direction: "left"});
        $(".mobile_filter_form .bx_filter").after("<div class='mobile_filter_overlay'></div>");
    });

    $(".mobile_filter_form").on("click", ".catalog_content__filter_horizon_title", function () {
        $(".mobile_filter_form .bx_filter").hide("slide", {direction: "left"});
        $(".mobile_filter_overlay").remove();
    });

    $(".mobile_filter_form").on("click", ".mobile_filter_overlay", function () {
        $(".mobile_filter_form .bx_filter").hide("slide", {direction: "left"});
        $(this).remove();
    });

    $(".mobile_filter_form").on("click", ".properties_block_title", function () {
        $(this).parent().hide("slide", {direction: "right"}, function () {
            $(this).find("span.color_value").remove();
            $(this).find(".properties_block_title").remove();
        });
    });

});
