BX.addCustomEvent('onAjaxSuccess', function () {
    setRowsHeight();
    setDeleteRowEvnts();
    setHideShowCheckboxEvent();
    createScrolls();
    relocateDuplicateScroll();
    showHideMobileDescription();
    hideMobileProperties();
    setMobileRowsHeight();
    setMobileDataHeights();
});

window.addEventListener("load", function () {
    setRowsHeight();
    setDeleteRowEvnts();
    setHideShowCheckboxEvent();
    createScrolls();
    relocateDuplicateScroll();
    showHideMobileDescription();
    hideMobileProperties();
    setMobileRowsHeight();
    setMobileDataHeights();
    highLightRow();
});

window.addEventListener("DOMContentLoaded", setRowsHeight);

window.addEventListener("resize", function () {
    setRowsHeight();
    setDescriptionDataHeight();
    setMobileRowsHeight();
    setMobileDataHeights();
    setChangesOnResize();
});

BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function () {
    var CompareClass = function (wrapObjId, reloadUrl) {
        this.wrapObjId = wrapObjId;
        this.reloadUrl = reloadUrl;
        BX.addCustomEvent(window, 'onCatalogDeleteCompare', BX.proxy(this.reload, this));
    };

    CompareClass.prototype.MakeAjaxAction = function (url) {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            url,
            {
                ajax_action: 'Y'
            },
            BX.proxy(this.reloadResult, this)
        );
    };

    CompareClass.prototype.reload = function () {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            this.reloadUrl,
            {
                compare_result_reload: 'Y'
            },
            BX.proxy(this.reloadResult, this)
        );
    };

    CompareClass.prototype.reloadResult = function (result) {
        BX.closeWait();
        BX(this.wrapObjId).innerHTML = result;
    };

    CompareClass.prototype.delete = function (url) {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            url,
            {
                ajax_action: 'Y'
            },
            BX.proxy(this.deleteResult, this)
        );
    };

    CompareClass.prototype.deleteProperty = function (url) {
        BX.showWait(BX(this.wrapObjId));
        BX.ajax.post(
            url,
            {
                ajax_action: 'Y'
            },
            BX.proxy(this.deleteResult, this)
        );
    };

    CompareClass.prototype.deleteResult = function (result) {
        BX.closeWait();
        BX.onCustomEvent('OnCompareChange');
        BX(this.wrapObjId).innerHTML = result;
    };

    return CompareClass;
})();

function setRowsHeight() {
    let propertyTitles = document.querySelectorAll(".compare-property_title-wrapper");
    let propertyRows = document.querySelectorAll(".compare-propertie_row");

    if (!isMobile()) {

        if (propertyTitles.length === propertyRows.length) {

            for (let i = 0; i < propertyTitles.length; i++) {
                let propTitleHeight = propertyTitles[i]
                    .querySelector(".compare-property_title-content")
                    .clientHeight;

                let propItems = propertyRows[i].querySelectorAll(".compare-propertie_item");

                let rowMaxHeight = getMaxPropertyHeight(propItems);

                ++rowMaxHeight; //border
                ++propTitleHeight; //border

                let rowHeight =
                    propTitleHeight <= rowMaxHeight
                        ? rowMaxHeight
                        : propTitleHeight;

                propertyTitles[i].style.height = rowHeight + "px";
                propertyRows[i].style.height = rowHeight + "px"; //set to inline for animation

                propertyTitles[i].dataset.height = rowHeight;
                propertyRows[i].dataset.height = rowHeight;

            }

        } else {
            console.warn("propertyTitles.length != propertyRow.length");
        }

    }
}

function setChangesOnResize() {
    if (isMobile()) {

        let propertyTitles = document.querySelectorAll(".compare-property_title-wrapper"),
            propertyRows = document.querySelectorAll(".compare-propertie_row");

        for (let i = 0; i < propertyRows.length; i++) {
            propertyRows[i].style.height = "auto";
            propertyTitles[i].style.heigth = "auto";
        }

    } else {

        let propsWrappers = document.querySelectorAll(".compare-propertie_wpapper"),
            mobilePropsTitles = document.querySelectorAll(".mobile-compare-property_title-border"),
            mobileTitleWrapper = document.querySelectorAll(".mobile-compare-property_title-border"),
            rowMobileWrapper = document.querySelectorAll(".compare-row_mobile_wrapper");

        for (let i = 0; i < propsWrappers.length; i++) {
            propsWrappers[i].style.height = "auto";
        }

        for (let i = 0; i < mobileTitleWrapper.length; i++) {
            rowMobileWrapper[i].style.height = "auto";

            delete rowMobileWrapper[i].dataset.deleted;
            delete mobilePropsTitles[i].dataset.deleted;
        }
    }
}

function getMaxPropertyHeight(props) {
    let propsHeights = [];

    for (let i = 0; i < props.length; i++) {
        propsHeights[i] = props[i].clientHeight;
    }

    return Math.max.apply(null, propsHeights);
}

function setDeleteRowEvnts() {
    let deleteAddRowBtns = document.querySelectorAll(".icon_cancel_comparison_small");
    let propertyTitles = document.querySelectorAll(".compare-property_title-wrapper");
    let propertyRows = document.querySelectorAll(".compare-propertie_row");

    for (let i = 0; i < deleteAddRowBtns.length; i++) {
        deleteAddRowBtns[i].addEventListener("click", function () {

            if (propertyTitles[i].dataset.deleted) {
                showElement.call(propertyTitles[i]);
                showElement.call(propertyRows[i]);

                delete propertyTitles[i].dataset.deleted;
                delete propertyRows[i].dataset.deleted;

            } else {
                hideElement.call(propertyRows[i]);
                hideElement.call(propertyTitles[i]);

                propertyTitles[i].dataset.deleted = true;
                propertyRows[i].dataset.deleted = true;
            }
        })
    }
}

let deletedRows = {
    show: function () {
        let propertyTitles = document.querySelectorAll(".compare-property_title-wrapper");
        let propertyRows = document.querySelectorAll(".compare-propertie_row");

        for (let i = 0; i < propertyTitles.length; i++) {

            if (propertyTitles[i].dataset.deleted) {
                showElement.call(propertyTitles[i]);
                showElement.call(propertyRows[i]);
            }
        }
    },

    hide: function () {
        let propertyTitles = document.querySelectorAll(".compare-property_title-wrapper");
        let propertyRows = document.querySelectorAll(".compare-propertie_row");

        for (let i = 0; i < propertyTitles.length; i++) {

            if (propertyTitles[i].dataset.deleted) {
                hideElement.call(propertyRows[i]);
                hideElement.call(propertyTitles[i]);
            }
        }

    },

    showMobile: function () {
        let mobileTitlesWrapper = document.querySelectorAll(".mobile-compare-property_title-border");
        let mobileRowWrappers = document.querySelectorAll(".compare-row_mobile_wrapper");

        for (let i = 0; i < mobileTitlesWrapper.length; i++) {

            if (mobileTitlesWrapper[i].dataset.deleted) {
                showElement.call(mobileTitlesWrapper[i]);
                showElement.call(mobileRowWrappers[i]);
            }
        }
    },

    hideMobile: function () {
        let mobileTitlesWrapper = document.querySelectorAll(".mobile-compare-property_title-border");
        let mobileRowWrappers = document.querySelectorAll(".compare-row_mobile_wrapper");

        for (let i = 0; i < mobileTitlesWrapper.length; i++) {

            if (mobileTitlesWrapper[i].dataset.deleted) {
                hideElement.call(mobileTitlesWrapper[i]);
                hideElement.call(mobileRowWrappers[i]);
            }
        }
    }
};

function hideElement() {
    this.style.height = "0px";
    this.style.overflow = "hidden";
}

function showElement() {
    this.style.overflow = "";
    this.style.height = this.dataset.height + "px";
}

function setHideShowCheckboxEvent() {
    let showHideCheckboxes = document.querySelectorAll(".compare-show_deleted");

    for (let i = 0; i < showHideCheckboxes.length; i++) {
        showHideCheckboxes[i].addEventListener("click", function () {
            if (showHideCheckboxes[i].checked) {
                isMobile()
                    ? deletedRows.showMobile()
                    : deletedRows.show();
                checkAllCheckboxes.call(showHideCheckboxes, true);
            } else {
                isMobile()
                    ? deletedRows.hideMobile()
                    : deletedRows.hide();
                checkAllCheckboxes.call(showHideCheckboxes, false);
            }
        })
    }
}

function checkAllCheckboxes(checked) {
    for (let i = 0; i < this.length; i++) {
        this[i].checked = checked;
    }
}

function relocateDuplicateScroll() {
    let duplicateScroll = document.querySelector(".compare-properties_block .ps__rail-x");

    duplicateScroll.style.bottom = "";
    duplicateScroll.dataset.topscroll = "true";
}

function createScrolls() {
    let compareScrollTable = new PerfectScrollbar('.compare-properties_block', {
        wheelSpeed: 0.3,
        wheelPropagation: true,
        minScrollbarLength: 10
    });

    let compareScrollTableDuplicate = new PerfectScrollbar('.compare-properties_block', {
        wheelSpeed: 0.3,
        wheelPropagation: true,
        minScrollbarLength: 10
    });
}

function isMobile() {
    let windowWidth = window.innerWidth || document.documentElement.clientWidth;
    return windowWidth <= 768;
}

function hideMobileProperties() {
    let showHideMobileButtons = document.querySelectorAll(".mobile-icon_cancel_comparison_small");
    let mobileTitleWrappers = document.querySelectorAll(".mobile-compare-property_title-border");
    let rowTableWrapper = document.querySelectorAll(".compare-row_mobile_wrapper");
    let descriptions = document.querySelectorAll(".mobile-compare-property_title-description-wrapper");

    setMobileDataHeights();

    for (let i = 0; i < showHideMobileButtons.length; i++) {

        showHideMobileButtons[i].addEventListener("click", function () {
            if (mobileTitleWrappers[i].dataset.deleted) {
                delete mobileTitleWrappers[i].dataset.deleted;
                delete rowTableWrapper[i].dataset.deleted;

                showElement.call(mobileTitleWrappers[i]);
                showElement.call(rowTableWrapper[i]);

            } else {
                mobileTitleWrappers[i].dataset.deleted = true;
                rowTableWrapper[i].dataset.deleted = true;

                showElement.call(mobileTitleWrappers[i]);
                showElement.call(rowTableWrapper[i]);

                hideElement.call(mobileTitleWrappers[i]);
                hideElement.call(rowTableWrapper[i]);

                if (descriptions[i].dataset.show) {
                    delete descriptions[i].dataset.show;
                    descriptions[i].style.height = "0";
                }

            }
        })
    }
}

function setMobileDataHeights() {
    let mobileTitleWrapper = document.querySelectorAll(".mobile-compare-property_title-border");
    let rowMobileWrapper = document.querySelectorAll(".compare-row_mobile_wrapper");
    let compareRow = document.querySelectorAll(".compare-propertie_row");

    if (mobileTitleWrapper.length !== rowMobileWrapper.length) {
        console.warn("incorrect rows number");
    }

    if (isMobile()) {
        for (let i = 0; i < mobileTitleWrapper.length; i++) {

            delete compareRow[i].dataset.deleted;

            let propertyTitleHeight = mobileTitleWrapper[i]
                .querySelector(".mobile-compare-property_title")
                .clientHeight;
            let propsLeft = rowMobileWrapper[i].querySelectorAll(".mobile-compare-product_title-border_wrapper");
            let propsRight = rowMobileWrapper[i].querySelectorAll(".compare-propertie_wpapper");

            let propsHeight = getMobilePropsHeight(propsLeft, propsRight);

            ++propertyTitleHeight;

            mobileTitleWrapper[i].dataset.height = propertyTitleHeight;
            mobileTitleWrapper[i].style.height = propertyTitleHeight + "px"; //animation

            rowMobileWrapper[i].dataset.height = propsHeight;
            rowMobileWrapper[i].style.height = propsHeight + "px"; //animation
        }
    }
}

function getMobilePropsHeight(propsLeft, propsRight) {
    let propsHeightLeft = 0;
    let propsHeightRight = 0;
    for (let a = 0; a < propsLeft.length; a++) {
        propsHeightLeft += parseInt(propsLeft[a].clientHeight) + 1;
        propsHeightRight += parseInt(propsRight[a].clientHeight) + 1;
    }
    return Math.max(propsHeightLeft, propsHeightRight);
}

function setMobileRowsHeight() {
    let mobileProductTitleWrappers = document.querySelectorAll(".mobile-compare-product_title-border_wrapper");
    let propsWrappers = document.querySelectorAll(".compare-propertie_wpapper");
    let propsTitleWrappers = document.querySelectorAll(".compare-property_title-wrapper");

    if (isMobile()) {
        for (let i = 0; i < propsTitleWrappers.length; i++) {
            delete propsTitleWrappers[i].dataset.deleted;
        }

        if (mobileProductTitleWrappers.length === propsWrappers.length) {

            for (let i = 0; i < mobileProductTitleWrappers.length; i++) {
                let mobileProductTitle = mobileProductTitleWrappers[i].querySelector(".mobile-compare-product_title");
                let property = propsWrappers[i].querySelector(".compare-propertie_item");

                let rowHeight = mobileProductTitle.clientHeight <= property.clientHeight
                    ? property.clientHeight
                    : mobileProductTitle.clientHeight;

                mobileProductTitleWrappers[i].style.height = rowHeight + "px";
                propsWrappers[i].style.height = rowHeight + "px";

            }

        } else {
            console.warn(".mobile-compare-product_title-border_wrapper lenght != " +
                ".compare-propertie_wpapper lenght");
        }

    }
}

function showHideMobileDescription() {
    let showDescriptionButton = document.querySelectorAll(".mobile-icon_question_small");
    let descriptionWrapper = document.querySelectorAll(".mobile-compare-property_title-description-wrapper");

    setDescriptionDataHeight();

    for (let i = 0; i < showDescriptionButton.length; i++) {
        showDescriptionButton[i].addEventListener("click", function () {
            showMobileDescription.call(descriptionWrapper[i]);
        })
    }
}

function setDescriptionDataHeight() {
    let descriptionText = document.querySelectorAll(".mobile-compare-property_title-description");
    let descriptionWrapper = document.querySelectorAll(".mobile-compare-property_title-description-wrapper");

    for (let i = 0; i < descriptionText.length; i++) {
        descriptionWrapper[i].dataset.height = descriptionText[i].clientHeight;
        descriptionWrapper[i].style.height = "0px";
    }
}

function showMobileDescription() {
    if (this.dataset.show) {
        delete this.dataset.show;
        this.style.height = "0px";
    } else {
        this.dataset.show = true;
        this.style.height = this.dataset.height + "px";
    }
}

function highLightRow() {
    let items = document.querySelectorAll(".compare-property_title-wrapper");
    let rows = document.querySelectorAll(".compare-propertie_row");

    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener("mouseover", function () {
            let trs = rows[i].querySelectorAll(".compare-propertie_wpapper");

            items[i].style.backgroundColor = "#f7f7f7";

            for (let a = 0; a < trs.length; a++) {
                trs[a].style.backgroundColor = "#f7f7f7";
            }
        });

        items[i].addEventListener("mouseout", function () {
            let trs = rows[i].querySelectorAll(".compare-propertie_wpapper");

            items[i].style.backgroundColor = "";

            for (let a = 0; a < trs.length; a++) {
                trs[a].style.backgroundColor = "";
            }
        });

        rows[i].addEventListener("mouseover", function () {
            let trs = rows[i].querySelectorAll(".compare-propertie_wpapper");

            items[i].style.backgroundColor = "#f7f7f7";

            for (let a = 0; a < trs.length; a++) {
                trs[a].style.backgroundColor = "#f7f7f7";
            }
        });

        rows[i].addEventListener("mouseout", function () {
            let trs = rows[i].querySelectorAll(".compare-propertie_wpapper");

            items[i].style.backgroundColor = "";

            for (let a = 0; a < trs.length; a++) {
                trs[a].style.backgroundColor = "";
            }
        })
    }
}
