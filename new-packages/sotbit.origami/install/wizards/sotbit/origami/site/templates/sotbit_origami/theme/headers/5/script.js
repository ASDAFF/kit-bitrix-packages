"use strict";

document.addEventListener('DOMContentLoaded', function () {
  var header = document.getElementById('header-three');
  var sidebar = document.getElementById('header-sidebar');
  var bxPanel = document.getElementById('bx-panel');
  setPosition();
  window.addEventListener('scroll', setPosition);
  window.addEventListener('resize', setPosition);

  if (bxPanel) {
    var observerBxPanel = new MutationObserver(setPosition);
    observerBxPanel.observe(bxPanel, {
      attributes: true
    });
  }

  function setPosition() {
    if (window.innerWidth > 1023) {
      if (bxPanel && bxPanel.classList.contains('bx-panel-fixed')) {
        header.style.top = getHeightElement(bxPanel) + 'px';
        sidebar.style.top = getHeightElement(bxPanel) + getHeightElement(header) + 'px';
        sidebar.style.height = window.innerHeight - (getHeightElement(bxPanel) - window.pageYOffset + getHeightElement(header)) + 'px';
      } else {
        if (window.pageYOffset >= getHeightElement(bxPanel)) {
          header.style.top = '0px';
          sidebar.style.top = getHeightElement(header) + 'px';
          sidebar.style.height = window.innerHeight - getHeightElement(header) + 'px';
        } else {
          header.style.top = getHeightElement(bxPanel) - window.pageYOffset + 'px';
          sidebar.style.top = getHeightElement(bxPanel) + getHeightElement(header) - window.pageYOffset + 'px';
          sidebar.style.height = window.innerHeight - (getHeightElement(bxPanel) - window.pageYOffset + getHeightElement(header)) + 'px';
        }
      }

      return;
    } else {
      if (bxPanel && bxPanel.classList.contains('bx-panel-fixed')) {
        header.style.top = getHeightElement(bxPanel) + 'px';
        sidebar.style.height = '';
        sidebar.style.top = '';
      } else {
        if (window.pageYOffset >= getHeightElement(bxPanel)) {
          header.style.top = '0px';
          sidebar.style.height = '';
          sidebar.style.top = '';
        } else {
          header.style.top = getHeightElement(bxPanel) - window.pageYOffset + 'px';
          sidebar.style.top = '';
          sidebar.style.height = '';
        }
      }

      return;
    }

    showMobileView();
  }

  function showMobileView() {
    header.style.top = '';
    sidebar.style.bottom = '0px';
    sidebar.style.top = '';
    sidebar.style.height = '';
  }

  function getHeightElement(item) {
    return item ? item.offsetHeight : 0;
  }
});
document.addEventListener('DOMContentLoaded', function () {
  var cityMenu = document.getElementById('menu-city');
  var cityHeader = document.querySelector('[data-entity="open_region"]');
  var compareMenuCount = document.getElementById('menu-compare-count');
  var favoritesMenuCount = document.getElementById('menu-favorites-count');
  var basketMenuCount = document.getElementById('menu-basket-count');
  var compareHeaderCount = document.getElementById('compare-count');
  var favoritesHeaderCount = document.getElementById('favorites-count');
  var basketHeaderCount = document.getElementById('basket-count');
  var city = document.querySelector('.header-three__city');
  changeCity();
  changeCount();
  var observerCity = new MutationObserver(changeCity);
  observerCity.observe(city, {
    childList: true,
    subtree: true
  });
  var basket = document.querySelector('.header-three__basket');
  var observerCompare = new MutationObserver(changeCount);
  observerCompare.observe(basket, {
    childList: true,
    subtree: true
  });

  function changeCity() {
    if (cityHeader && cityMenu) {
      createCopyTextContent(cityHeader, cityMenu);
    }
  }

  function changeCount() {
    compareHeaderCount = document.getElementById('compare-count');
    favoritesHeaderCount = document.getElementById('favorites-count');
    basketHeaderCount = document.getElementById('basket-count');

    if (compareHeaderCount) {
      createCopyTextContent(compareHeaderCount, compareMenuCount);
      toggleActive(compareMenuCount);
    }

    if (favoritesHeaderCount) {
      createCopyTextContent(favoritesHeaderCount, favoritesMenuCount);
      toggleActive(favoritesMenuCount);
    }

    if (basketHeaderCount) {
      createCopyTextContent(basketHeaderCount, basketMenuCount);
      toggleActive(basketMenuCount);
    }
  }

  function toggleActive(observerItem) {
    if (parseInt(observerItem.innerText) !== 0 && !observerItem.classList.contains('active')) {
      observerItem.classList.add('active');
    }

    if (parseInt(observerItem.innerText) === 0) {
      observerItem.classList.remove('active');
    }
  }

  function createCopyTextContent(inEl, outEl) {
    outEl.innerText = inEl.innerText.replace(/\r?\n/g, "");
  }

  ;
  var arrSubMenu = document.querySelectorAll('.section__item-submenu');
  var _iteratorNormalCompletion = true;
  var _didIteratorError = false;
  var _iteratorError = undefined;

  try {
    var _loop = function _loop() {
      var item = _step.value;
      var titleItem = item.previousElementSibling;
      var heightItem = item.offsetHeight;
      titleItem.dataset.submenu = true;
      item.style.height = '0px';
      titleItem.addEventListener('click', function (evt) {
        evt.currentTarget.classList.toggle('open');

        if (evt.currentTarget.classList.contains('open')) {
          item.style.height = heightItem + 'px';
        } else {
          item.style.height = '0px';
        }
      });
    };

    for (var _iterator = arrSubMenu[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
      _loop();
    }
  } catch (err) {
    _didIteratorError = true;
    _iteratorError = err;
  } finally {
    try {
      if (!_iteratorNormalCompletion && _iterator.return != null) {
        _iterator.return();
      }
    } finally {
      if (_didIteratorError) {
        throw _iteratorError;
      }
    }
  }

  var menu = document.querySelector('#menu-header-three');
  var overlay = menu.querySelector('.menu__overlay');
  var btnClose = document.querySelector('[data-entity="close_menu"]');
  var btnOpen = document.querySelector('[data-entity="open_menu"]');

  if (btnOpen) {
    btnOpen.addEventListener('click', function (evt) {
      menu.classList.add('show');
    });
  }

  btnClose.addEventListener('click', function (evt) {
    menu.classList.remove('show');
  });
  overlay.addEventListener('click', function (evt) {
    menu.classList.remove('show');
  });
  var menuWrapper = menu.querySelector('.menu__wrap-scroll');
  new PerfectScrollbar(menuWrapper, {
    wheelSpeed: 0.5,
    wheelPropagation: true,
    minScrollbarLength: 20
  });
});