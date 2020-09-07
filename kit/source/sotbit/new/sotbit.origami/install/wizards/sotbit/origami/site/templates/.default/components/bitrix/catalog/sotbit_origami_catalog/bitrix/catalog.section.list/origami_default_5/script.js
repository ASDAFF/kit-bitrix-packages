window.addEventListener("DOMContentLoaded", function () {
    let descriptionWrapper = document.querySelectorAll(".description_text-wrapper");
    let sections = document.querySelectorAll(".catalog-section_item");

    showMoreDescription(descriptionWrapper);
    setCardHeight();

    for (let i = 0; i < sections.length; i++) {
        let hideShowBtns = sections[i].querySelectorAll(".show_hide-buttons");

        for (let a = 0; a < hideShowBtns.length; a++) {
            hideShowBtns[a].addEventListener("click", function () {
                onClickShowHideBtn(descriptionWrapper[i]);
            })
        }

        if (sections[i].querySelector(".items_links-mobile")) {

            let toggleWrapper = sections[i].querySelector(".icon-nav_button-mobile_wrapper");

            sections[i].setAttribute("class", sections[i].getAttribute("class") + " mobile_closed");
            toggleWrapper.addEventListener("click", function () {
                showHideMobileLinks(sections[i]);
            });
        }

    }


    let sectionLength = sections.length;

    for (let i = 0; i < sectionLength; i++) {
        let sectionWrapper = sections[i].querySelector(".catalog-section_wrapper");
        sectionWrapper.style.zIndex = sectionLength - i + 1;
    }
});

window.addEventListener("resize", function () {
    let sections = document.querySelectorAll(".catalog-section_item");
    let descriptionWrapper = document.querySelectorAll(".description_text-wrapper");

    showMoreDescription(descriptionWrapper);

    for (let i = 0; i < sections.length; i++) {
        collapseCard(sections[i]);
    }

    setCardHeight();
});

window.onload = function () {
    setCardHeight();
};

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

function showMoreDescription(descriptionWrapper) {

    for (let i = 0; i < descriptionWrapper.length; i++) {

        let description_text = descriptionWrapper[i].querySelector(".description_text");

        if (--description_text.clientHeight > convertRemToPixels(1.43) * 3) {

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

function setCardHeight() {
    let sectionItems = document.querySelectorAll(".catalog-section_item");


    for (let i = 0; i < sectionItems.length; i++) {
        let itemHead = sectionItems[i].querySelector(".section_item-head");

        sectionItems[i].style.height = "auto";
        itemHead.style.height = "auto";

        let wrapper = sectionItems[i].querySelector(".catalog-section_wrapper");
        let windowWidth = window.innerWidth || document.documentElement.clientWidth;
        let itemHeadHeight = itemHead.clientHeight;

        if (windowWidth > 1000) {
            sectionItems[i].style.height = wrapper.clientHeight + "px";
            itemHead.style.height = ++itemHeadHeight + "px";
        } else {
            sectionItems[i].style.height = "auto";
            itemHead.style.height = "auto";
        }

        wrapper.addEventListener("mouseleave", function () {
            let windowWidth = window.innerWidth || document.documentElement.clientWidth;
            if (windowWidth > 1000) {
                collapseCard(this);
            }
        })
    }
}

function clearClassText(classText) {
    classText = classText.replace(" closed", "");
    classText = classText.replace(" opened", "");
    classText = classText.replace(" show_more", "");

    return classText;
}

function collapseCard(wrapper) {
    let className;

    if (wrapper.querySelector(".description_text-wrapper.show_more.opened")) {
        let descriptionBlock = wrapper.querySelector(".description_text-wrapper.show_more.opened");

        className = descriptionBlock.getAttribute("class");
        className = className.replace("opened", "closed");

        descriptionBlock.setAttribute("class", className);

        descriptionBlock.style.height = "4.29rem";
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

function convertRemToPixels(rem) {
    return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
}
