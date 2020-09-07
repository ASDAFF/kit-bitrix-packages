;(function resize() {
    let wrapper,
        wrappers = document.querySelectorAll(".wrap-popup-window");

    for (let i = 0; i < wrappers.length; i++) {
        if (wrappers[i].querySelector(".want_gift-resizeable_content_wide")) {
            wrapper = wrappers[i];
            break;
        }
    }

    window.resizeWGPopup = function () {
        resizeWantGiftPopup();
    };

    function resizeWantGiftPopup() {
        let popupResizeableContentWide = wrapper.querySelector(".want_gift-resizeable_content_wide"),
            popupResizeableContentNarrowly = wrapper.querySelector(".want_gift-resizeable_content"),
            titleWide = wrapper.querySelector(".kit_order_phone__title_wide"),
            titleNarrowly = wrapper.querySelector(".kit_order_phone__title_narrowly"),
            popupContent = wrapper.querySelector(".popup-content"),
            popupWindow = wrapper.querySelector(".popup-window");

        setUpListeners();
        resizePopUp();

        setTimeout(resizePopUp, 1000);

        function setUpListeners() {
            popupResizeableContentWide.addEventListener("scroll", putTitleShadow);

            popupResizeableContentNarrowly.addEventListener("scroll", putTitleShadow);

            window.addEventListener("resize", function () {
                resizePopUp();
                putTitleShadow();
            });
        }

        function resizePopUp() {
            let wantGiftImage = wrapper.querySelector(".kit_want_gift_image");

            if (titleWide.clientHeight > titleNarrowly.clientHeight) {
                resizePopupContent(popupResizeableContentWide, titleWide, popupResizeableContentNarrowly);
                let wantGiftScrollBar = new PerfectScrollbar(wrapper.querySelector(".want_gift-resizeable_content_wide"));

                if (wantGiftImage.clientHeight > popupWindow.clientHeight) {
                    wantGiftImage.style.height = popupWindow.clientHeight + "px";
                } else {
                    wantGiftImage.style.height = "auto";
                }

            } else {
                resizePopupContent(popupResizeableContentNarrowly, titleNarrowly, popupResizeableContentWide);
            }
        }

        function resizePopupContent(resizeableContent, title, unresizeable) {
            let clientHeight = document.documentElement.clientHeight * 0.97,
                newHeight;

            resizeableContent.style.overflowY = "hidden";
            resizeableContent.style.height = "auto";

            unresizeable.style.overflowY = "hidden";
            unresizeable.style.height = "auto";

            if (popupContent.clientHeight > popupWindow.clientHeight || popupContent.clientHeight > clientHeight) {

                newHeight = (clientHeight < popupWindow.clientHeight) ?
                    (clientHeight - title.clientHeight) :
                    (popupWindow.clientHeight - title.clientHeight);

                resizeableContent.style.overflowY = "auto";
                resizeableContent.style.height = newHeight + "px";
            }
        }

        function putTitleShadow() {
            let scrolledWide = popupResizeableContentWide.scrollTop,
                scrolledNarrowly = popupResizeableContentNarrowly.scrollTop;

            if (scrolledWide === 0) {
                titleWide.style.boxShadow = "none";
            } else {
                titleWide.style.boxShadow = "0 2px 5px 3px rgba(0,0,0,.1)";
            }

            if (scrolledNarrowly === 0) {
                titleNarrowly.style.boxShadow = "none";
            } else {
                titleNarrowly.style.boxShadow = "0 2px 5px 3px rgba(0,0,0,.1)";
            }
        }
    }
})();
