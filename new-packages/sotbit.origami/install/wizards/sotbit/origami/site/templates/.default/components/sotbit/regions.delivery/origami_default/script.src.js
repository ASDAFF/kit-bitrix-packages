class SotbitRegionsDelivery
{
    constructor(params) {
        this.tabID = document.getElementById('TAB_DELIVERY');
        this.contentID = document.getElementById('DELIVERY_CONTENT');
        var _this = this;
        this.tabID.addEventListener('click',function (event) {
            _this.clickTab();
        });
        this.componentPath = params.componentPath;
        this.parameters = params.parameters;
        this.siteId = params.siteId;
        this.template = params.template;
        this.isContentLoad = false;


        this.handlerResponse = function () {
            this.rootId = params.root;
            this.root = document.querySelector('.detailed-tabs__tabs-content [id^="sotbit-delivery"]') ||
                document.querySelector('.detailed-feat__item-content [id^="sotbit-delivery"]');
            // this.changeRegion = this.root.querySelector('[data-entity="change-region"]');
            this.modal = this.root.querySelector('[data-entity="modal"]');
            this.overlay = this.root.querySelector('[data-entity="overlay"]');
            this.close = this.root.querySelector('[data-entity="close"]');
            this.country = this.root.querySelectorAll('[data-entity="country"]');
            this.tab = this.root.querySelectorAll('[data-entity="tab-content"]');
            this.item = this.root.querySelectorAll('[data-entity="item"]');


            if(this.item.length > 1){
                for(let i =0; i < this.item.length;++i){
                    this.item[i].addEventListener('click',event => this.chooseLocation(this.item[i].dataset.index));
                }
            }
            this.filter = this.root.querySelector('[data-entity="filter-regions"]');
            // this.changeRegion.addEventListener('click',event => this.showPopup());
            document.addEventListener('click', function (evt) {
                handlerClickChangeRegion(evt, _this);
            });

            function handlerClickChangeRegion (evt, item) {
                if (evt.target.closest("[data-entity = 'change-region']")) {
                    item.showPopup();
                }
            }

            // let target = document.querySelector('.detailed-tabs__tabs-content #DELIVERY_CONTENT').cloneNode(true);
            // console.log(target);
            // let mobileDelivery = document.querySelector('.detailed-tabs__tabs.js-tabs #DELIVERY_CONTENT');
            // if (target && mobileDelivery){
            //     mobileDelivery.replaceWith(target);
            // }
            if(this.overlay !== null)
            {
                this.overlay.addEventListener('click', event => _this.closePopup());
            }
            if(this.close !== null)
            {
                this.close.addEventListener('click', event => this.closePopup());
            }
            if(this.country.length > 1){
                for(let i =0; i < this.country.length;++i){
                    this.country[i].addEventListener('click',event => this.chooseCountry(this.country[i].dataset.countryId));
                }
            }
            if(this.filter !== null)
            {
                this.filter.addEventListener('input', event => this.filterItems(this.filter.value));
            }

            let regions = document.querySelectorAll('.detailed-tabs__delivery-description');
            if(regions.length > 1) {
                let currentRegion = regions[regions.length - 1].textContent;
                for (let i = 0; regions.length - 1 > i; i++) {
                    regions[i].textContent = currentRegion;
                }
            }

            window.toggle = this.toggleWaysDelivery();
        }

        if(params.startAjax) {
            this.startAjax();
        }
    }

    toggleWaysDelivery () {
        const COUNT_VISIBLE_ITEM = 3;
        const itemsWrapper = this.root.querySelector('.detailed-tabs__delivery-way-items-wrapper');
        const itemsContainer = this.root.querySelector('.detailed-tabs__delivery-way-items-container');
        const items = this.root.querySelectorAll('.detailed-tabs__delivery-way-item');
        const btnShow = this.root.querySelector('.detailed-tabs__delivery-way-btn-show');
        let heightWrapper = null;
        const MAGRIN_BOTTOM = 20;
        let heightItemsVisible = null;

        if (items.length > COUNT_VISIBLE_ITEM) {
            heightWrapper = itemsWrapper.offsetHeight;
            heightItemsVisible = getHeightVisible(COUNT_VISIBLE_ITEM, items) + MAGRIN_BOTTOM * (COUNT_VISIBLE_ITEM - 1);
            btnShow.style.display = 'flex';
            itemsWrapper.style.height = heightItemsVisible + 'px';
            itemsContainer.classList.add('overflow');
            btnShow.addEventListener('click', handlerClickBtn);
        }

        function calcStartHeight() {
            itemsWrapper.style.height = 'auto';
            heightWrapper = itemsWrapper.offsetHeight;
            heightItemsVisible = getHeightVisible(COUNT_VISIBLE_ITEM, items) + MAGRIN_BOTTOM * (COUNT_VISIBLE_ITEM - 1);
            itemsWrapper.style.height = heightItemsVisible + 'px';
        }


        function getHeightVisible(count, items) {
            let height = null;
            for (let i = 0; COUNT_VISIBLE_ITEM > i; i++) {
                height += items[i].offsetHeight;
            }
            return height;
        }

        function handlerClickBtn() {
            if (!itemsWrapper.classList.contains('open')) {
                itemsWrapper.classList.add('open');
                itemsWrapper.style.height = heightWrapper + 'px';
            } else {
                itemsWrapper.classList.remove('open');
                itemsWrapper.style.height = getHeightVisible(COUNT_VISIBLE_ITEM, items) + MAGRIN_BOTTOM + 'px';
            }
        }

        window.addEventListener('resize', function () {
            calcStartHeight();
        });

        return {
            calcHeight: calcStartHeight,
        }
    }

    showPopup(){
        this.modal.style.display = 'block';
        this.overlay.style.display = 'block';
    }
    closePopup(){
        this.modal.style.display = 'none';
        this.overlay.style.display = 'none';
    }
    chooseCountry(countryId){
        if(this.tab.length > 1){
            for(let i =0;i<this.tab.length;++i){
                this.country[i].classList.remove('active');
                this.tab[i].classList.remove('active');
                if(this.tab[i].dataset.countryId == countryId){
                    this.tab[i].classList.add('active');
                    this.country[i].classList.add('active');
                }
            }
        }
    }
    filterItems(value){
        let list = this.root.querySelectorAll('[data-entity="item"]');
        let letters = this.root.querySelectorAll('[data-entity="letter"]');
        value.toLowerCase();
        if(list.length){
            for(var i = 0; i < list.length; ++i){
                list[i].style.display = "block";
                let city = list[i].innerHTML.toLowerCase().trim();
                if(value.length > 0){
                    if(city.substr(0,value.length) != value){
                        list[i].style.display = "none";
                    }
                }
            }
        }
        if(letters.length){
            for(var i = 0; i < letters.length; ++i){
                var was = false;
                var child = letters[i].childNodes;
                for(var j=0; j<child.length; ++j){
                    if(child[j].dataset !== undefined && child[j].dataset.entity == 'item-list'){
                        let child2 = child[j].childNodes;
                        if(child2.length){
                            for(var k=0; k<child2.length; ++k){
                                if(child2[k].dataset !== undefined && child2[k].dataset.entity == 'item'){
                                    let style = getComputedStyle(child2[k]);
                                    if(style.display != 'none'){
                                        was = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                letters[i].style.display = "block";
                if(!was){
                    letters[i].style.display = "none";
                }
            }
        }
    }
    chooseLocation(id){
        BX.showWait();
        var defaultData = {
            siteId: this.siteId,
            template: this.template,
            parameters: this.parameters,
        };

        var data = {
            Id: id,
        };

        BX.ajax({
            url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
            method: 'POST',
            dataType: 'html',
            timeout: 60,
            data: BX.merge(defaultData, data),
            onsuccess: BX.delegate(function(result){
                this.root.outerHTML = result;
                this.handlerResponse();
            }, this)
        });
        BX.closeWait();
    }

    clickTab(){

        if (this.isContentLoad) {
            return;
        }

        var defaultData = {
            siteId: this.siteId,
            template: this.template,
            parameters: this.parameters,
        };
        createLoadersMore($(this.contentID));

        BX.ajax({
            url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
            method: 'POST',
            dataType: 'html',
            timeout: 60,
            async: true,
            data: defaultData,
            onsuccess: BX.delegate(function(result){
                this.contentID.innerHTML = result;
                this.isContentLoad = true;
                removeLoadersMore($(this.contentID));
                this.handlerResponse();
                if (window.innerWidth <= 768) {
                    $('#TAB_DELIVERY').click();
                    $('#TAB_DELIVERY').click();
                }
            }, this)
        });
    }

    startAjax(){
        this.clickTab();
    }
}
