function initSubscribe(message, id) {
    let btn = BX('bx_subscribe_btn_' + id);
    let form = BX('bx_subscribe_subform_' + id);

    if (!btn) {
        return;
    }

    BX.bind(form, 'submit', function () {
        btn.disabled = true;
        setTimeout(function () {
            btn.disabled = false;
        }, 2000);

        return true;
    });
}

function createSubscribePopup() {
    BX.ready(function () {
        var oPopup = BX.PopupWindowManager.create('sender_subscribe_component', window.body, {
            autoHide: true,
            offsetTop: 1,
            offsetLeft: 0,
            lightShadow: true,
            closeIcon: true,
            closeByEsc: true,
            overlay: {
                backgroundColor: 'rgba(57,60,67,0.82)', opacity: '80'
            }
        });
        oPopup.setContent(BX("sender-subscribe-response-cont"));
        oPopup.show();

        let popupCentering = function () {
            if (document.getElementById("sender_subscribe_component")) {
                let popUp = document.getElementById("sender_subscribe_component");
                let overlay = document.getElementById("popup-window-overlay-sender_subscribe_component");

                popUp.style.position = "fixed";
                popUp.style.top = "calc(50% - " + popUp.clientHeight / 2 + "px)";
                popUp.style.left = "calc(50% - " + popUp.clientWidth / 2 + "px)";
                overlay.style.position = "fixed";
                overlay.style.height = "100%";
                overlay.style.width = "100%";
            }
        };

        popupCentering();

        window.addEventListener("resize", popupCentering);
        document.querySelector("#sender_subscribe_component .popup-window-close-icon").addEventListener("click", () => {
            oPopup.destroy();
            window.removeEventListener("resize", popupCentering);
        });

        document.querySelector("#popup-window-overlay-sender_subscribe_component").addEventListener("click", () => {
            oPopup.destroy();
            window.removeEventListener("resize", popupCentering);
        });

    });

}

function showSubscribtions(item) {
    item.classList.toggle("active");
}
