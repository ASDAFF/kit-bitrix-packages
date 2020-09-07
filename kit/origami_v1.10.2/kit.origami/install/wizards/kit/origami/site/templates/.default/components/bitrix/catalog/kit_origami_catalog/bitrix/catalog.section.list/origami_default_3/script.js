window.addEventListener("DOMContentLoaded", function () {
    let descriptionWrapper = document.querySelectorAll(".description_text-wrapper"),
        sections = document.querySelectorAll(".catalog-section_item");

    showMoreDescription(descriptionWrapper);
    showMobileSubitems(sections);

    for (let i = 0; i < sections.length; i++) {
        showSubLinks(sections[i]);
    }

    for (let i = 0; i < sections.length; i++) {
        let hideShowBtns = sections[i].querySelectorAll(".show_hide-buttons");

        for (let a = 0; a < hideShowBtns.length; a++) {
            hideShowBtns[a].addEventListener("click", function () {
                onClickShowHideBtn(descriptionWrapper[i]);
            })
        }
    }

    let sectionLength = sections.length;

    for (let i = 0; i < sectionLength; i++) {
        let sectionWrapper = sections[i].querySelector(".catalog-section_wrapper");
        sectionWrapper.style.zIndex = sectionLength - i + 1;
    }

    setCardHeight();
});

window.onload = function () {
    setCardHeight();
};

window.addEventListener("resize", function () {
    let blockWrappers = document.querySelectorAll(".catalog-section_wrapper");
    let descriptionWrapper = document.querySelectorAll(".description_text-wrapper");
    showMoreDescription(descriptionWrapper);

    for (let i = 0; i < blockWrappers.length; i++) {
        collapseCard(blockWrappers[i]);
    }

    setCardHeight();
});

function showSubLinks(section) {
    let items = section.querySelectorAll(".catalog_links .catalog-items_links");

    for (let i = 0; i < items.length; i++) {

        if (items[i].querySelector(".catalog-items-items_list")) {

            let showMoreBtn = items[i].querySelector(".icon-nav_button");
            let itemsListWrapper = items[i].querySelector(".catalog-items-items_list-wrapper");
            let itemsList = itemsListWrapper.querySelector(".catalog-items-items_list");

            itemsListWrapper.style.height = "0px";

            showMoreBtn.addEventListener("click", function () {
                replaceSubLinksClass(items[i], itemsList.clientHeight);

                let windowWidth = window.innerWidth || document.documentElement.clientWidth;

                if (windowWidth > 1000) {
                    try {
                        overflowLinksSection(section, itemsList);
                    } catch (e) {
                    }
                }
            });

        }
    }
}

function replaceSubLinksClass(item, itemsListHeight) {
    let itemClass = item.getAttribute("class"),
        itemsListWrapper = item.querySelector(".catalog-items-items_list-wrapper");

    if (itemClass.includes("active")) {
        let newClass = itemClass.replace(" active", "");
        item.setAttribute("class", newClass);

        itemsListWrapper.style.height = "0px";
    } else {
        item.setAttribute("class", itemClass + " active");

        itemsListWrapper.style.height = itemsListHeight + "px";
    }
}

function setCardHeight() {
    let sectionItems = document.querySelectorAll(".catalog-section_item");

    for (let i = 0; i < sectionItems.length; i++) {
        sectionItems[i].style.height = "auto";

        let wrapper = sectionItems[i].querySelector(".catalog-section_wrapper");

        let windowWidth = window.innerWidth || document.documentElement.clientWidth;

        if (windowWidth > 1000) {
            sectionItems[i].style.height = wrapper.clientHeight + "px";
        } else {
            sectionItems[i].style.height = "auto";
        }

        wrapper.addEventListener("mouseleave", function () {
            let windowWidth = window.innerWidth || document.documentElement.clientWidth;
            if (windowWidth > 1000) {
                collapseCard(this);
            }
        })
    }
}

function showMoreDescription(descriptionWrapper) {

    for (let i = 0; i < descriptionWrapper.length; i++) {

        let description_text = descriptionWrapper[i].querySelector(".description_text");

        if (description_text.clientHeight > convertRemToPixels(1.43) * 3) {

            let classText = descriptionWrapper[i].getAttribute("class");

            classText = clearClassText(classText);
            classText += " show_more closed";

            descriptionWrapper[i].setAttribute("class", classText);
            descriptionWrapper[i].style.height = "4.29rem";
        } else {
            let classText = descriptionWrapper[i].getAttribute("class");
            descriptionWrapper[i].setAttribute("class", clearClassText(classText));
        }

    }
}

function onClickShowHideBtn(descriptionWrapper) {
    let description_text = descriptionWrapper.querySelector(".description_text");
    let descriptionFullHeight =
        description_text.clientHeight
        / parseFloat(getComputedStyle(document.documentElement).fontSize);

    let classText = descriptionWrapper.getAttribute("class");
    let newClass;

    if (classText.includes("opened")) {

        newClass = classText.replace("opened", "closed");
        descriptionWrapper.setAttribute("class", newClass);
        descriptionWrapper.style.height = "4.29rem";

    } else if (classText.includes("closed")) {

        newClass = classText.replace("closed", "opened");
        descriptionWrapper.setAttribute("class", newClass);
        descriptionWrapper.style.height = descriptionFullHeight + "rem";

    } else {
        console.log("classname error");
    }
}

function clearClassText(classText) {
    classText = classText.replace(" closed", "");
    classText = classText.replace(" opened", "");
    classText = classText.replace(" show_more", "");

    return classText;
}

function showHideMobileLinks(section) {
    let className = section.getAttribute("class");

    if (className.includes("mobile_closed")) {
        className = className.replace("mobile_closed", "mobile_opened");
        section.setAttribute("class", className);
    } else if (className.includes("mobile_opened")) {
        className = className.replace("mobile_opened", "mobile_closed");
        section.setAttribute("class", className);
    }
}

function showMobileSubitems(sections) {
    for (let i = 0; i < sections.length; i++) {

        if (sections[i].querySelector(".items_links-mobile")) {
            let toggleMobileWrapper = sections[i].querySelector(".icon-nav_button-mobile_wrapper");

            sections[i].setAttribute("class", sections[i].getAttribute("class") + " mobile_closed");
            toggleMobileWrapper.addEventListener("click", function () {
                showHideMobileLinks(sections[i]);
            });
        }
    }
}

function collapseCard(wrapper) {
    let className;
    let section = document.querySelectorAll(".catalog-section_item");

    if (wrapper.querySelector(".description_text-wrapper.show_more.opened")) {
        let descriptionBlock = wrapper.querySelector(".description_text-wrapper.show_more.opened");

        descriptionBlock.style.height = "4.29rem";

        className = descriptionBlock.getAttribute("class");
        className = className.replace("opened", "closed");

        descriptionBlock.setAttribute("class", className);
    }

    if (wrapper.querySelectorAll(".catalog-items_links.active").length > 0) {
        let activeLinksList = wrapper.querySelectorAll(".catalog-items_links.active");

        for (let i = 0; i < activeLinksList.length; i++) {
            className = activeLinksList[i].getAttribute("class");
            className = className.replace(" active", "");

            activeLinksList[i].setAttribute("class", className);

            let sublinksWrapper = activeLinksList[i].querySelector(".catalog-items-items_list-wrapper");

            sublinksWrapper.style.height = "0px";
        }
    }

    let linkWrapper = document.querySelectorAll(".catalog-section_item-description");

    for (let i = 0; i < linkWrapper.length; i++) {

        setTimeout(function () {
            linkWrapper[i].style.height = "auto";
        }, 600);
    }
}

function overflowLinksSection(section, sublinks) {
    let scrollTop = document.documentElement.scrollTop,
        sectionBottom = section.querySelector(".catalog-section_wrapper").getBoundingClientRect().bottom,
        sectionBottomPos = scrollTop + sectionBottom,
        documentBottomPos = document.body.clientHeight;

    let sublinksHeight = sublinks.clientHeight;

    if (sectionBottomPos + sublinksHeight > documentBottomPos) {
        let linksSection = section.querySelector(".catalog-section_item-description");
        let descriptionBlockHeight = section.querySelector(".description_block").clientHeight;
        let sectionWrapperHeight = section.querySelector(".catalog-section_wrapper").clientHeight;
        let titleHeight = section.querySelector(".catalog-section_item-title").clientHeight;

        let newHeight = sectionWrapperHeight - descriptionBlockHeight - 81 - titleHeight;

        linksSection.style.overflowY = "auto";
        linksSection.style.height = newHeight + "px";

    }
}

function convertRemToPixels(rem) {
    return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
}
