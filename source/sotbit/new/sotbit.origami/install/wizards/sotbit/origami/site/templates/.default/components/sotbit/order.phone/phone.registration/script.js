;(function resizeOrderPhonePopup() {
    let wrapper,
        wrappers = document.querySelectorAll(".wrap-popup-window");

    for (let i = 0; i < wrappers.length; i++) {
        if (wrappers[i].querySelector(".popup_resizeable_content")) {
            wrapper = wrappers[i];
            break;
        }
    }

    let popupResizeableContent = wrapper.querySelector(".popup_resizeable_content"),
        popupWindow = wrapper.querySelector(".popup-window"),
        popupContent = wrapper.querySelector(".popup-content"),
        popupTitle = wrapper.querySelector(".sotbit_order_phone__title"),
        submitBtn = wrapper.querySelector(".popup-window-submit_button");

    resizePopupContent();
    putTitleShadow();
    setUpListeners();

    function setUpListeners() {
        popupResizeableContent.addEventListener("scroll", putTitleShadow);

        window.addEventListener("resize", function () {
            resizePopupContent();
            putTitleShadow();
        });

        window.addEventListener("load", function () {
            resizePopupContent();
            putTitleShadow();
        });

        submitBtn.addEventListener("click", function () {
            resizePopupContent();
            putTitleShadow();
        });
    }

    let buyInClickScroll = new PerfectScrollbar(popupResizeableContent);

    function resizePopupContent() {
        let clientHeight = document.documentElement.clientHeight * 0.97,
            newHeight;

        popupResizeableContent.style.overflowY = "hidden";
        popupResizeableContent.style.height = "auto";

        if ((popupContent.clientHeight > (popupWindow.clientHeight + 2)) || (popupContent.clientHeight > clientHeight)) {

            newHeight = (clientHeight < popupWindow.clientHeight) ?
                (clientHeight - popupTitle.clientHeight) :
                (popupWindow.clientHeight - popupTitle.clientHeight);

            popupResizeableContent.style.overflowY = "auto";
            popupResizeableContent.style.height = newHeight + "px";
        }
    }

    function putTitleShadow() {
        let scrolled = popupResizeableContent.scrollTop;

        if (scrolled === 0) {
            popupTitle.style.boxShadow = "none";
        } else {
            popupTitle.style.boxShadow = "0 2px 5px 3px rgba(0,0,0,.1)";
        }
    }

})();

function fixCountryPopup(element) {
    let timer = 3000;

    let waitElement = setInterval(function () {
        if (document.getElementById('phoneNumberInputSelectCountry')) {
            setFixCountryPopupPosition(element);
            clearInterval(waitElement);
        }

        timer -= 0.3;

        if (timer <= 0) {
            clearInterval(waitElement);
        }
    }, 0.3);
}

function setFixCountryPopupPosition(element) {
    let top = element.getBoundingClientRect().top;
    document.getElementById('phoneNumberInputSelectCountry').style.top = top - 206 + 'px';
    document.getElementById('phoneNumberInputSelectCountry').style.opacity = '1';
}

function EventsAfterClose(){
	let popup_wrap = document.querySelector(".wrap-popup-window"),
		close_modal = popup_wrap.querySelector(".popup-close"),
		popup_bg = popup_wrap.querySelector(".modal-popup-bg");

	popup_wrap.addEventListener("click", function(e) {
		if (e.target == close_modal || e.target == popup_bg) {

			BX.ajax.post("/bitrix/components/sotbit/order.phone/ajax.php",
				{'SMS_CLEAR':'Y'}, function (response) {

				})

		}
	});
}
EventsAfterClose();