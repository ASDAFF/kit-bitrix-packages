"use strict";

;(function () {

    document.addEventListener('DOMContentLoaded', function () {
        let btns = document.querySelectorAll('.mobile-menu-contact__block-btn');
        for (let i = 0; btns.length > i; i++) {
            btns[i].addEventListener('click', openBlockHandler);
        }
    });

    function openBlockHandler (evt) {
        evt.preventDefault();
        this.classList.toggle('open');
        let blockHide = this.parentElement.parentElement.querySelector('.mobile-menu-contact__block-more');
        blockHide.classList.toggle('open');
        $(this.parentElement.parentElement.querySelector('.mobile-menu-contact__block-more')).slideToggle();
    }
})();