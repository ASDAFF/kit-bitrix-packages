
;(function () {
    document.addEventListener('DOMContentLoaded', function() {

        let header = {};
        header.block = document.getElementById('main-header');
        header.heigth = header.block.offsetHeight;
        header.btn = header.block.querySelector('.header_info_block-icon__fixed-header');
        header.subMenu = header.block.querySelector('.block_main_menu');
        header.headerCard = header.block.querySelector('.window_basket');
        let BXpanel = document.querySelector('.bx-panel-fixed');
        let searchBlock = document.getElementById('title-search');

        if(searchBlock) {
            document.querySelector('.page').parentNode.insertBefore(searchBlock, document.querySelector('.page'));
        }
        

        if(header.headerCard) {
            header.headerCard.addEventListener('wheel', function (evt) {
                evt.preventDefault();
                
            });
        }

        function handlerScroll() { 
            if (window.pageYOffset > header.heigth) {
                header.block.classList.add('fix-header-one');
                header.block.nextElementSibling.style.paddingTop = header.heigth + "px";  
                
                if(document.querySelector('#title-search') && document.querySelector('.bx-panel-fixed')) {
                    document.querySelector('#title-search').style.top = BXpanel.offsetHeight + 'px';
                }               
                
            } else {
                header.block.classList.remove('fix-header-one');
                header.subMenu.classList.remove(('active'));
                header.block.nextElementSibling.style.paddingTop = "";

                if(document.querySelector('#title-search')) {
                    document.querySelector('#title-search').style.top = '0px';
                }
            }   
        }

        header.btn.addEventListener('click', function () {
            header.subMenu.classList.toggle('active');
        });

        function addFixedHeader () {
            window.addEventListener('scroll', handlerScroll);
            if (window.pageYOffset > header.heigth) {
                header.block.classList.add('fix-header-one');
            } else {
                header.block.classList.remove('fix-header-one');
            }
        }

        function removeFixedHeader () {
            window.removeEventListener('scroll', handlerScroll);
            header.block.classList.remove('fix-header-one');
            header.block.nextElementSibling.style.paddingTop = "";
        }


        function addMobileFixedHeader () {
            if (window.innerWidth < 768) {
                addFixedHeader();
                
            } else {
                removeFixedHeader();
            }
        }

        function addDesktopFixedHeader () {
            if (window.innerWidth >= 768) {
                addFixedHeader();
                
            } else {
                removeFixedHeader();
            }
        }

        window.fixedHeader = function (params) {
            if(params === 'mobile') {
                addMobileFixedHeader ();
                window.addEventListener('resize', addMobileFixedHeader);
            }

            if(params === 'desktop') {
                addDesktopFixedHeader ();
                window.addEventListener('resize', addDesktopFixedHeader);
            }

            if(params === 'all') {
                window.removeEventListener('resize', addDesktopFixedHeader);
                window.removeEventListener('resize', addMobileFixedHeader);
                addFixedHeader();
            }  

            if(params === 'del') {
                window.removeEventListener('resize', addDesktopFixedHeader);
                window.removeEventListener('resize', addMobileFixedHeader);
                removeFixedHeader();
            }       
        }
    });
})();
