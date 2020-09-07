export default class SendData {
    constructor() {
        let timer;

        this.sendData = function (data, update) {
            if (!timer) {
                timer = setTimeout(() => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', `${document.location.origin}/include/ajax/basket_react.php`);
                    xhr.setRequestHeader('Content-type', 'application/json;');
                    data.UPDATE = 'Y';
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            BX.onCustomEvent('OnBasketChange');
                        }
                        if (xhr.status === 200 && update) {
                            update(JSON.parse(xhr.response));
                        } else {
                        }
                    };
                    xhr.send(JSON.stringify(data));
                }, 250);
            } else {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', `${document.location.origin}/include/ajax/basket_react.php`);
                    xhr.setRequestHeader('Content-type', 'application/json;');
                    data.UPDATE = 'Y';
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            BX.onCustomEvent('OnBasketChange');
                        }
                        if (xhr.status === 200 && update) {
                            update(JSON.parse(xhr.response));
                        } else {
                        }
                    };
                    xhr.send(JSON.stringify(data));
                }, 250);
            }


        };
    }
};
