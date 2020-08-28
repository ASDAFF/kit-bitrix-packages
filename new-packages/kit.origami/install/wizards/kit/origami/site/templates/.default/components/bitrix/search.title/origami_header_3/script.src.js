
    function removeLogoSearch (item) {
        const searchField = item.querySelector('input');
        const searchLogo = item.querySelector('.header-search__logo');
        item.classList.add('form-search--logo-hide');
        if(searchLogo) {
            searchField.focus();
            searchLogo.remove();
        }
    };


document.addEventListener('DOMContentLoaded', function () {
    const search = document.querySelector('#search');
    const searchField = search.querySelector('#title-search-input');
    const searchBtnClose = search.querySelector('#search-btn-close');
    searchField.addEventListener('input', () => {
        if(searchField.value  && (window.innerWidth < 1023)) {
            search.classList.add('state-input');
        }
        if(!searchField.value  && (window.innerWidth < 1023)) {
            search.classList.remove('state-input');
        }
    });

    searchBtnClose.addEventListener('click', ()=> {
        searchField.value = '';
        if(!searchField.value  && (window.innerWidth < 1023)) {
            search.classList.remove('state-input');
        }
    });
});

