;(function resizeCheckStockPopup() {
    let wrapper = document.querySelector(".wrap-popup-window"),
        popupResizeableContent = wrapper.querySelector(".popup_resizeable_content"),
        popupWindow = wrapper.querySelector(".popup-window"),
        popupContent = wrapper.querySelector(".popup-content"),
        popupTitle = wrapper.querySelector(".kit_order_phone__title");

    resize();
    setUpListeners();

    function setUpListeners() {
        popupResizeableContent.addEventListener("scroll", putTitleShadow);
        window.addEventListener("resize", resize);
    }

    function resize() {
        resizePopupContent();
        putTitleShadow();
    }

    function resizePopupContent() {
        let clientHeight = document.documentElement.clientHeight * 0.97,
            newHeight;

        popupResizeableContent.style.overflowY = "hidden";
        popupResizeableContent.style.height = "auto";

        if (popupContent.clientHeight > (popupWindow.clientHeight + 2)
            || popupContent.clientHeight > (clientHeight + 2)) {

            newHeight = (clientHeight < popupWindow.clientHeight) ?
                (clientHeight - popupTitle.clientHeight) :
                (popupWindow.clientHeight - popupTitle.clientHeight);

            popupResizeableContent.style.overflowY = "auto";
            popupResizeableContent.style.height = newHeight + "px";
        }
    }

    function putTitleShadow() {
        let title = wrapper.querySelector(".kit_order_phone__title");
        let scrolled = popupResizeableContent.scrollTop;

        if (scrolled === 0) {
            title.style.boxShadow = "none";
        } else {
            title.style.boxShadow = "0 2px 5px 3px rgba(0,0,0,.1)";
        }
    }
})();
