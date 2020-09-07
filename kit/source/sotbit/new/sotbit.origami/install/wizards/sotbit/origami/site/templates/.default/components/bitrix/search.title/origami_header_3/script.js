"use strict";

function removeLogoSearch(item) {
  var searchField = item.querySelector('input');
  var searchLogo = item.querySelector('.header-search__logo');
  item.classList.add('form-search--logo-hide');

  if (searchLogo) {
    searchField.focus();
    searchLogo.remove();
  }
}

;
document.addEventListener('DOMContentLoaded', function () {
  var search = document.querySelector('#search');
  var searchField = search.querySelector('#title-search-input');
  var searchBtnClose = search.querySelector('#search-btn-close');
  searchField.addEventListener('input', function () {
    if (searchField.value && window.innerWidth < 1023) {
      search.classList.add('state-input');
    }

    if (!searchField.value && window.innerWidth < 1023) {
      search.classList.remove('state-input');
    }
  });
  searchBtnClose.addEventListener('click', function () {
    searchField.value = '';

    if (!searchField.value && window.innerWidth < 1023) {
      search.classList.remove('state-input');
    }
  });
});