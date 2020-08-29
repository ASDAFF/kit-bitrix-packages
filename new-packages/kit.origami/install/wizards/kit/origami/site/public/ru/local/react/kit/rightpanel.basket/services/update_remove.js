export default class UpdateRemove {
    constructor() {
        this.sendData = function (dataSend, updateCallBack, isReloadBasketLine) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', `${document.location.origin}/include/ajax/basket_react.php`);
            xhr.setRequestHeader('Content-type', 'application/json;');
            dataSend.UPDATE = 'Y';
            xhr.onload = function () {
                if (xhr.status === 200 && updateCallBack) {
                    updateCallBack(JSON.parse(xhr.response));
                    // BX.onCustomEvent('OnBasketChange');
                } else {
                }

                if (isReloadBasketLine) {
                    BX.onCustomEvent('OnBasketChange')
                }
            };
            xhr.send(JSON.stringify(dataSend));
        };
    }
};
