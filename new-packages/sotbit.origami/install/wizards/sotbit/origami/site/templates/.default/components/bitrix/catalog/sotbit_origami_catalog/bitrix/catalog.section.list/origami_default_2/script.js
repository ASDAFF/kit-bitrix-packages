window.addEventListener("DOMContentLoaded", function () {
    let items = document.querySelectorAll(".catalog_links .catalog-items_links"),
        descriptionWrapper = document.querySelectorAll(".description_text-wrapper"),
        sections = document.querySelectorAll(".catalog-section_item");

    showMoreDescription(descriptionWrapper);
    setMobileToggleEvents(sections);

    for (let i = 0; i < items.length; i++) {
        showSubLinks(items[i]);
    }

    for (let i = 0; i < sections.length; i++) {
        let hideShowBtns = sections[i].querySelectorAll(".show_hide-buttons");

        for (let a = 0; a < hideShowBtns.length; a++) {
            hideShowBtns[a].addEventListener("click", function () {
                onClickShowHideBtn(descriptionWrapper[i]);
            })
        }
    }
});

window.addEventListener("resize", function () {
    let descriptionWrapper = document.querySelectorAll(".description_text-wrapper");

    showMoreDescription(descriptionWrapper);
});

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

function showSubLinks(item) {

    if (item.querySelector(".catalog-items-items_list")) {

        let showMoreBtn = item.querySelector(".icon-nav_button"),
            itemsList = item.querySelector(".catalog-items-items_list");
        let itemsListHeight = itemsList.clientHeight;

        itemsList.style.height = "0px";
        itemsList.style.position = "relative";

        showMoreBtn.addEventListener("click", function () {
            showItemsList(item, itemsListHeight);
        });

    }
}

function showItemsList(item, itemsListHeight) {
    let itemClass = item.getAttribute("class"),
        itemsList = item.querySelector(".catalog-items-items_list");

    if (itemClass.includes("active")) {
        let newClass = itemClass.replace(" active", "");
        item.setAttribute("class", newClass);

        itemsList.style.height = "0px";
    } else {
        item.setAttribute("class", itemClass + " active");

        itemsList.style.height = itemsListHeight + "px";
    }
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

function setMobileToggleEvents(sections) {
    for (let i = 0; i < sections.length; i++) {

        if (sections[i].querySelector(".items_links-mobile")) {
            let toggle = sections[i].querySelector(".items_titles .icon-nav_button-mobile_wrapper");

            sections[i].setAttribute("class", sections[i].getAttribute("class") + " mobile_closed");
            toggle.addEventListener("click", function () {
                showHideMobileLinks(sections[i]);
            });
        }
    }
}

function convertRemToPixels(rem) {
    return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
}
