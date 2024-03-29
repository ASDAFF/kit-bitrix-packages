window.KitRegions = function (arParams) {
    var input = document.getElementById("region-input");
    var wrap = document.getElementsByClassName("select-city__dropdown-wrap");
    var modal = document.getElementsByClassName("select-city__modal");
    var overlay = document.getElementsByClassName("modal__overlay");
    var yes = document.getElementsByClassName("select-city__dropdown__choose__yes");
    var no = document.getElementsByClassName("select-city__dropdown__choose__no");
    var textCity = document.getElementsByClassName("select-city__block__text-city");
    var close = document.getElementsByClassName("select-city__close");
    var item = document.getElementsByClassName("select-city__list_item");
    var tab = document.getElementsByClassName("select-city__tab");
    var tabContent = document.getElementsByClassName("select-city__tab_content");

    var params = JSON.parse(arParams.arParams);

    input.addEventListener('input', function () {
        var list = document.getElementsByClassName("select-city__list_item");
        var letters = document.getElementsByClassName("select-city__list_letter_wrapper");
        var value = this.value.toLowerCase();
        if (list.length) {
            for (var i = 0; i < list.length; ++i) {
                list[i].style.display = "block";
                let city = list[i].innerHTML.toLowerCase().trim();
                if (value.length > 0) {
                    if (city.substr(0, value.length) != value) {
                        list[i].style.display = "none";
                    }
                }
            }
        }
        if (letters.length) {
            for (var i = 0; i < letters.length; ++i) {
                var was = false;
                var child = letters[i].childNodes;
                for (var j = 0; j < child.length; ++j) {
                    if (child[j].className == 'select-city__list') {
                        let child2 = child[j].childNodes;
                        if (child2.length) {
                            for (var k = 0; k < child2.length; ++k) {
                                if (child2[k].className == 'select-city__list_item') {
                                    let style = getComputedStyle(child2[k]);
                                    if (style.display != 'none') {
                                        was = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                letters[i].style.display = "block";
                if (!was) {
                    letters[i].style.display = "none";
                }
            }
        }
    });

    let cityTitle = document.getElementsByClassName("select-city__modal__title");

    if (cityTitle.length) {
        if (cityTitle[0].children.length) {
            for (var i = 0; i < cityTitle[0].children.length; ++i) {
                cityTitle[0].children[i].addEventListener('click', function () {
                    var idLocation = this.dataset.locationId;
                    if (idLocation !== undefined && idLocation > 0) {
                        if (params.FROM_LOCATION == 'Y') {
                            SetCookie('kit_regions_location_id', idLocation, {'domain': arParams.rootDomain});
                            SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});

                            var xhr = new XMLHttpRequest();
                            var body = 'id=' + idLocation + '&action=getDomainByLocation';
                            xhr.open("POST", arParams.componentFolder + '/ajax.php', false);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                            xhr.onreadystatechange = function () {
                                if (this.readyState != 4) return;
                                var answer = JSON.parse(this.responseText);
                                if (answer.ID) {
                                    SetCookie('kit_regions_id', answer.ID, {'domain': arParams.rootDomain});
                                    if (arParams.singleDomain == 'Y') {
                                        location.reload();
                                    } else {
                                        document.location.href = answer.CODE;
                                    }
                                }
                            }
                            xhr.send(body);
                        }
                    }
                });
            }
        }
    }


    let cityInMessage = document.getElementsByClassName("select-city__under_input");
    if (cityInMessage.length) {
        if (cityInMessage[0].children.length) {
            for (var i = 0; i < cityInMessage[0].children.length; ++i) {
                cityInMessage[0].children[i].addEventListener('click', function () {
                    var idLocation = this.dataset.locationId;
                    if (idLocation !== undefined && idLocation > 0) {
                        if (params.FROM_LOCATION == 'Y') {

                            SetCookie('kit_regions_location_id', idLocation, {'domain': arParams.rootDomain});
                            SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});

                            var xhr = new XMLHttpRequest();
                            var body = 'id=' + idLocation + '&action=getDomainByLocation';
                            xhr.open("POST", arParams.componentFolder + '/ajax.php', false);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                            xhr.onreadystatechange = function () {
                                if (this.readyState != 4) return;
                                var answer = JSON.parse(this.responseText);
                                if (answer.ID) {
                                    SetCookie('kit_regions_id', answer.ID, {'domain': arParams.rootDomain});
                                    if (arParams.singleDomain == 'Y') {
                                        location.reload();
                                    } else {
                                        document.location.href = answer.CODE;
                                    }
                                }
                            }
                            xhr.send(body);
                        }
                    }
                });
            }
        }

    }

    document.addEventListener('click', function (evt) {
        if (evt.target.classList.contains('select-city__dropdown__choose__yes')) {

            wrap[0].style.display = 'none';

            if (params.FROM_LOCATION == 'Y') {
                var idLocation = yes[0].dataset.id;
                SetCookie('kit_regions_location_id', idLocation, {'domain': arParams.rootDomain});
                SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});

                var xhr = new XMLHttpRequest();
                var body = 'id=' + idLocation + '&action=getDomainByLocation';
                xhr.open("POST", arParams.componentFolder + '/ajax.php', false);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function () {
                    if (this.readyState != 4) return;
                    var answer = JSON.parse(this.responseText);
                    if (answer.ID) {
                        SetCookie('kit_regions_id', answer.ID, {'domain': arParams.rootDomain});
                        if (arParams.singleDomain == 'Y') {
                            //location.reload();
                        } else {
                            document.location.href = answer.CODE;
                        }
                    }
                }
                xhr.send(body);
            } else {
                SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});
                SetCookie('kit_regions_id', yes[0].dataset.id, {'domain': arParams.rootDomain});
                if (arParams.singleDomain != 'Y') {
                    var url = '';
                    for (var i = 0; i < arParams.list.length; ++i) {
                        if (arParams.list[i]['ID'] == yes[0].dataset.id) {
                            url = arParams.list[i]['URL'];
                        }
                    }
                    if (url.length > 0) {
                        document.location.href = url;
                    }
                } else {
                    //location.reload();
                }
            }
        }

        if (evt.target.classList.contains('select-city__dropdown__choose__no')) {
            Open();
        }
    });


    // ---temp----
    //for(var n = 0; textCity.length > n; n++) {
    //    textCity[n].addEventListener('click',function ()
    //    {
    //        createMainLoader ($('body'));
    //        Open();
    //    });

    $(document).on('click', '.select-city__block__text-city', function () {
        createMainLoader($('body'));
        Open();
    });

    //}


    function Open() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", arParams.templateFolder + '/ajax.php');
        xhr.send();
        xhr.onload = () => {

            removeMainLoader();
            modal[0].innerHTML = xhr.responseText;
            close[0].addEventListener('click', function () {
                Close();
            });

            var tab = document.getElementsByClassName("select-city__tab");
            for (var i = 0; i < tab.length; ++i) {
                tab[i].addEventListener('click', function () {
                    if (this.classList.contains('active')) {
                        return false;
                    }
                    for (var j = 0; j < tab.length; ++j) {
                        tab[j].classList.remove('active');
                    }
                    this.classList.add('active');
                    for (var j = 0; j < tabContent.length; ++j) {
                        tabContent[j].classList.remove('active');
                        if (tabContent[j].dataset.countryId == this.dataset.countryId) {
                            tabContent[j].classList.add('active');
                        }
                    }
                });
            }

            var input = document.getElementById("region-input");
            input.addEventListener('input', function () {
                var list = document.getElementsByClassName("select-city__list_item");
                var letters = document.getElementsByClassName("select-city__list_letter_wrapper");
                var value = this.value.toLowerCase();
                if (list.length) {
                    for (var i = 0; i < list.length; ++i) {
                        list[i].style.display = "block";
                        let city = list[i].innerHTML.toLowerCase().trim();
                        if (value.length > 0) {
                            if (city.substr(0, value.length) != value) {
                                list[i].style.display = "none";
                            }
                        }
                    }
                }
                if (letters.length) {
                    for (var i = 0; i < letters.length; ++i) {
                        var was = false;
                        var child = letters[i].childNodes;
                        for (var j = 0; j < child.length; ++j) {
                            if (child[j].className == 'select-city__list') {
                                let child2 = child[j].childNodes;
                                if (child2.length) {
                                    for (var k = 0; k < child2.length; ++k) {
                                        if (child2[k].className == 'select-city__list_item') {
                                            let style = getComputedStyle(child2[k]);
                                            if (style.display != 'none') {
                                                was = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        letters[i].style.display = "block";
                        if (!was) {
                            letters[i].style.display = "none";
                        }
                    }
                }
            });

            let cityTitle = document.getElementsByClassName("select-city__modal__title");

            if (cityTitle.length) {
                if (cityTitle[0].children.length) {
                    for (var i = 0; i < cityTitle[0].children.length; ++i) {
                        cityTitle[0].children[i].addEventListener('click', function () {
                            var idLocation = this.dataset.index;
                            if (idLocation !== undefined && idLocation > 0) {
                                if (params.FROM_LOCATION == 'Y') {
                                    SetCookie('kit_regions_location_id', idLocation, {'domain': arParams.rootDomain});
                                    SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});

                                    var xhr = new XMLHttpRequest();
                                    var body = 'id=' + idLocation + '&action=getDomainByLocation';
                                    xhr.open("POST", arParams.componentFolder + '/ajax.php', false);
                                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                                    xhr.onreadystatechange = function () {
                                        if (this.readyState != 4) return;
                                        var answer = JSON.parse(this.responseText);
                                        if (answer.ID) {
                                            SetCookie('kit_regions_id', answer.ID, {'domain': arParams.rootDomain});
                                            if (arParams.singleDomain == 'Y') {
                                                location.reload();
                                            } else {
                                                document.location.href = answer.CODE;
                                            }
                                        }
                                    }
                                    xhr.send(body);
                                }
                            }
                        });
                    }
                }
            }


            let cityInMessage = document.getElementsByClassName("select-city__under_input");
            if (cityInMessage.length) {
                if (cityInMessage[0].children.length) {
                    for (var i = 0; i < cityInMessage[0].children.length; ++i) {
                        cityInMessage[0].children[i].addEventListener('click', function () {
                            var idLocation = this.dataset.locationId;
                            if (idLocation !== undefined && idLocation > 0) {
                                if (params.FROM_LOCATION == 'Y') {

                                    SetCookie('kit_regions_location_id', idLocation, {'domain': arParams.rootDomain});
                                    SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});

                                    var xhr = new XMLHpRequest();
                                    var body = 'id=' + idLocation + '&action=getDomainByLocation';
                                    xhr.open("POST", arParams.componentFolder + '/ajax.php', false);
                                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                                    xhr.onreadystatechange = function () {
                                        if (this.readyState != 4) return;
                                        var answer = JSON.parse(this.responseText);
                                        if (answer.ID) {
                                            SetCookie('kit_regions_id', answer.ID, {'domain': arParams.rootDomain});
                                            if (arParams.singleDomain == 'Y') {
                                                location.reload();
                                            } else {
                                                document.location.href = answer.CODE;
                                            }
                                        }
                                    }
                                    xhr.send(body);
                                }
                            }
                        });
                    }
                }
            }

            var item = document.getElementsByClassName("select-city__list_item");
            for (var i = 0; i < item.length; ++i) {
                item[i].addEventListener('click', function () {
                    if (params.FROM_LOCATION == 'Y') {
                        var id = this.getAttribute('data-index');
                        SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});
                        SetCookie('kit_regions_location_id', id, {'domain': arParams.rootDomain});

                        var xhr = new XMLHttpRequest();
                        var body = 'id=' + encodeURIComponent(id) + '&action=getDomainByLocation';
                        xhr.open("POST", arParams.componentFolder + '/ajax.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                        xhr.onreadystatechange = function () {
                            if (this.readyState != 4) return;
                            let answer = JSON.parse(this.responseText);
                            if (answer.ID) {
                                SetCookie('kit_regions_id', answer.ID, {'domain': arParams.rootDomain});
                                if (arParams.singleDomain == 'Y') {
                                    location.reload();
                                } else {
                                    document.location.href = answer.CODE;
                                }
                            }
                        }
                        xhr.send(body);
                    } else {
                        var id = this.getAttribute('data-index');
                        SetCookie('kit_regions_city_choosed', 'Y', {'domain': '.' + arParams.rootDomain});
                        SetCookie('kit_regions_id', id, {'domain': arParams.rootDomain});
                        if (arParams.singleDomain == 'Y') {
                            location.reload();
                        } else {
                            var url = '';
                            for (var i = 0; i < arParams.list.length; ++i) {
                                if (arParams.list[i]['ID'] == id) {
                                    url = arParams.list[i]['URL'];
                                }
                            }
                            if (url.length > 0) {
                                document.location.href = url;
                            }
                        }
                    }
                });
            }

            modal[0].style.display = 'flex';
            overlay[0].style.display = 'block';
            wrap[0].style.display = 'none';

            if (isMobile()) {
                document.getElementById("mm-1").style.overflow = "hidden";
            }

            // scrollSity();

            let tabsWrapper = document.querySelector(".tabs_wrapper");
            let tabsWrapperInner = document.querySelector(".select-city__tabs_wrapper");

            let inputExampleWrapper = document.querySelector(".input-example-wrapper");
            let inputExampleText = document.querySelector(".select-city__wrapper__input");

            tabsWrapper.style.height = tabsWrapperInner.clientHeight + "px";
            inputExampleWrapper.style.height = ++inputExampleText.clientHeight + "px";

            input.addEventListener("focus", function () {
                if (isMobile()) {
                    tabsWrapper.style.height = 0;
                    inputExampleWrapper.style.height = 0;
                }
            });

            input.addEventListener("blur", function () {
                if (isMobile()) {
                    tabsWrapper.style.height = tabsWrapperInner.clientHeight + "px";
                    inputExampleWrapper.style.height = ++inputExampleText.clientHeight + "px";
                }
            });

            setScrollContainerHeight();

            let modalRegionsScroll = document.querySelector('.scroll-container');

            new PerfectScrollbar(modalRegionsScroll, {
                wheelSpeed: 0.5,
                wheelPropagation: true,
                minScrollbarLength: 20
            });
        }

    }

    function isMobile() {
        let windowWidth = window.innerWidth || document.documentElement.clientWidth;
        let windowHeight = window.innerHeight || document.documentElement.clientHeight;
        return windowWidth <= 1023 || windowHeight <= 740;
    }

    window.addEventListener("resize", setScrollContainerHeight);

    function setScrollContainerHeight() {
        let title = document.querySelector(".select_city-modal-title");
        let scrollContainer = document.querySelector(".scroll-container");
        let windowHeight = window.innerHeight || document.documentElement.clientHeight;

        if (scrollContainer) {
            scrollContainer.style.height = isMobile()
                ? windowHeight - title.clientHeight - 20 + "px"
                : scrollContainer.style.height = "600px";
        }
    }

    function scrollSity(params) {
        if (document.querySelector(".select-city__modal-wrap")) {
            if (document.querySelector(".select-city__list_wrapper_favorites")) {
                let favoritesSity = document.querySelectorAll(".select-city__list_wrapper_favorites");
                let arr = [];
                for (let i = 0; i < favoritesSity.length; i++) {
                    favoritesSity[i].style.position = 'relative';
                    favoritesSity[i].style.marginRight = '8px';

                    let currentScroll = new PerfectScrollbar(favoritesSity[i], {
                        wheelSpeed: 0.5,
                        wheelPropagation: true,
                        minScrollbarLength: 20
                    });
                    arr.push(currentScroll);
                }

                if (document.querySelector('.select-city__tabs')) {
                    document.querySelector('.select-city__tabs').addEventListener('click', function () {
                        for (let i = 0; i < arr.length; i++) {
                            arr[i].update();
                        }
                    });
                }
            }

            if (document.querySelector(".select-city__list_wrapper_cities")) {
                let listSity = document.querySelectorAll(".select-city__list_wrapper_cities");
                let arr = [];
                for (let i = 0; i < listSity.length; i++) {
                    listSity[i].style.position = 'relative';
                    let currentScroll = new PerfectScrollbar(listSity[i], {
                        wheelSpeed: 0.5,
                        wheelPropagation: true,
                        minScrollbarLength: 20
                    });
                    arr.push(currentScroll);
                }

                if (document.querySelector('.select-city__tabs')) {
                    document.querySelector('.select-city__tabs').addEventListener('click', function () {
                        for (let i = 0; i < arr.length; i++) {
                            arr[i].update();
                        }
                    });
                }
            }
        }
    }

    function Close() {
        modal[0].style.display = 'none';
        if (isMobile()) {
            document.getElementById("mm-1").style.overflow = "auto";
        }
        overlay[0].style.display = 'none';
    }

    function SetCookie(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }
        options.path = '/';
        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }
        document.cookie = updatedCookie;
    }
}

//-----mobile multiRegion---------
document.addEventListener("DOMContentLoaded", function (event) {
    var MOBILE_SIZE = 768;

    var mobileCityDrow = function () {
        if (window.innerWidth <= MOBILE_SIZE) {
            var mobileSelectDropdownWrap = document.querySelector('.select-city__dropdown-wrap');
            var mobileSelectCityModal = document.querySelector('.select-city__modal');
            var mobileModalOverlay = document.querySelector('.modal__overlay');
            var page = document.getElementsByClassName('page')[0];

            var mobileWrapFix = document.createElement('div');
            mobileWrapFix.classList.add('mobileSelectCity');

            mobileWrapFix.appendChild(mobileSelectDropdownWrap);
            mobileWrapFix.appendChild(mobileSelectCityModal);
            mobileWrapFix.appendChild(mobileModalOverlay);

            page.insertBefore(mobileWrapFix, page.firstChild);

            var onCityChange = document.getElementsByClassName('header_info_block__block_region__title');

            onCityChange[0].addEventListener('click', function () {
                mobileSelectCityModal.style.display = 'block';
                mobileModalOverlay.style.display = 'block';
                mobileSelectDropdownWrap.remove();
                var api = $("#menu").data("mmenu");
                api.close();
            });

        }

    };

    if (document.querySelector('.header_info_block')) {
        mobileCityDrow();
    }
});
