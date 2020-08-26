export default class ApiService {
    constructor() {
        this.getData = function () {
            return new Promise((res, rej) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.location.origin}/include/ajax/basket_react.php`);
                xhr.onload = function () {
                    if (xhr.status === 200) {

                        res(JSON.parse(xhr.response));
                    } else {
                        rej(xhr.status);
                    }
                };
                xhr.send();
            });
        };
    }
};
