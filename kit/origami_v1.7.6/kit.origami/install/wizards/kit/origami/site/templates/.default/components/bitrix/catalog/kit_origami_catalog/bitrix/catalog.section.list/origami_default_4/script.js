'use strict';

window.addEventListener("DOMContentLoaded", function () {

    let sections = document.querySelectorAll(".catalog-section_item");

    for (let i = 0; i < sections.length; i++) {

        if (sections[i].querySelector(".items_links-mobile")) {
            let toggleWrapper = sections[i].querySelector(".icon-nav_button-mobile_wrapper");

            sections[i].setAttribute("class", sections[i].getAttribute("class") + " mobile_closed");
            toggleWrapper.addEventListener("click", function () {
                showHideMobileLinks(sections[i]);
            });
        }
    }

});

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
