function initBtnErrorShare() {
    window.addEventListener("DOMContentLoaded", function () {
        let wrapper = document.querySelector(".btn_error-share__overlays-wrapper");
        let btnErrorShare = wrapper.querySelector(".btn_error-share");
        let overlayBlack = wrapper.querySelector(".btn_error-share__overlay.overlay-black");

        btnErrorShare.addEventListener("click", function () {
            setActive();
        });

        overlayBlack.addEventListener("click", setActive);

        function setActive() {
            if (wrapper.dataset.active) {
                delete wrapper.dataset.active;
            } else {
                wrapper.dataset.active = true;
            }
        }

        hideErrorButtons();
    });

    window.addEventListener('scroll', hideErrorButtons);
    window.addEventListener('resize', hideErrorButtons);
}
function foundError(siteDir, lid, item) {
    createMainLoader($('body'));

    $.ajax({
        url: siteDir + 'include/ajax/found_error.php',
        type: 'POST',
        data: {'lid': lid},
        success: function (html) {
            removeMainLoader($('body'));
            showModal(html);
        }
    });
}

/*
* var xhr = new XMLHttpRequest();

var body = 'name=' + encodeURIComponent(name) +
  '&surname=' + encodeURIComponent(surname);

xhr.open("POST", '/submit', true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

xhr.onreadystatechange = ...;

xhr.send(body);
* */
function callSubscribePopup(siteDir, lid, item) {
    let obj = {
        'lid': lid,
        'url': item.dataset["address"]
    };
    let data = JSON.stringify(obj);
    let request = new XMLHttpRequest();

    let body = 'lid=' + encodeURIComponent(lid) + '&url=' + encodeURIComponent(item.dataset["address"]);

    createMainLoader($('body'));

    request.open('POST', siteDir + 'include/ajax/subscribe_popup.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.send(body);

    request.onload = function () {
        if (request.status === 200) {
            let response = request.response;
            removeMainLoader($('body'));
            showSubscribeModal(response);
        }
    }
}

// function showSubscribeModal(html) {
//     let block = document.createElement("div");
//     block.innerHTML = '<div class="wrap-popup-window popup-subscribe__wrapper">' +
//         '<div class="modal-popup-bg-white" onclick="closeModal();">&nbsp;</div>' +
//         '<div class="popup-window">' +
//         '<div class="popup-close-subscribe" onclick="closeModal();">' +
//         '<svg class="close-modal" width="17" height="17">' +
//         '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>' +
//         '</svg>' +
//         '</div>' +
//         '<div class="popup-content">' +
//         html +
//         '</div>' +
//         '</div>' +
//         '</div>';
//
//     let bodyEl = document.getElementsByTagName("body");
//
//     bodyEl[0].appendChild(block);
//
// }

function showSubscribeModal(html) {
    let block = '<div class="wrap-popup-window popup-subscribe__wrapper">' +
        '<div class="modal-popup-bg-white" onclick="closeModal();">&nbsp;</div>' +
        '<div class="popup-window">' +
        '<div class="popup-close-subscribe" onclick="closeModal();">' +
        '<svg class="close-modal" width="17" height="17">' +
        '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>' +
        '</svg>' +
        '</div>' +
        '<div class="popup-content">' +
        html +
        '</div>' +
        '</div>' +
        '</div>';

    $("body").append(block);
}

function hideErrorButtons() {
    if (document.querySelector('.btn-round-menu-open.detail-menu-open')
        && document.querySelector('.btn_error-share__overlays-wrapper')) {
        document.querySelector('.btn_error-share__overlays-wrapper').style.display = 'none';
    }
}
