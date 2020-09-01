(function (window) {
    'use strict';

    if (window.JCCatalogItemTab)
        return;

    var BasketButton = function (params) {
        BasketButton.superclass.constructor.apply(this, arguments);
        this.buttonNode = BX.create('button', {
            props: {className: 'service-detail-item__content-hidden-button ' + (params.className || ''), id: this.id},
            style: typeof params.style === 'object' ? params.style : {},
            text: params.text,
            events: this.contextEvents
        });

        if (BX.browser.IsIE()) {
            this.buttonNode.setAttribute("hideFocus", "hidefocus");
        }
    };
    BX.extend(BasketButton, BX.PopupWindowButton);

    window.JCCatalogItemTab = function (arParams)
    {
        this.productType = 0;

        this.showQuantity = true;
        this.showAbsent = true;
        this.secondPict = false;
        this.showOldPrice = false;
        this.showMaxQuantity = 'N';
        this.relativeQuantityFactor = 5;
        this.showPercent = false;
        this.showSkuProps = false;
        this.basketAction = 'ADD';
        this.showClosePopup = false;
        this.showSubscription = false;
        this.visual = {
            ID: '',
            PICT_ID: '',
            SECOND_PICT_ID: '',
            QUANTITY_ID: '',
            QUANTITY_UP_ID: '',
            QUANTITY_DOWN_ID: '',
            PRICE_ID: '',
            PRICE_OLD_ID: '',
            DSC_PERC: '',
            SECOND_DSC_PERC: '',
            DISPLAY_PROP_DIV: '',
            BASKET_PROP_DIV: '',
            SUBSCRIBE_ID: ''
        };
        this.product = {
            checkQuantity: false,
            maxQuantity: 0,
            stepQuantity: 1,
            isDblQuantity: false,
            canBuy: true,
            name: '',
            pict: {},
            id: 0,
            addUrl: '',
            buyUrl: ''
        };

        this.basketMode = '';
        this.basketData = {
            useProps: false,
            emptyProps: false,
            quantity: 'quantity',
            props: 'prop',
            basketUrl: '',
            sku_props: '',
            sku_props_var: 'basket_props',
            add_url: '',
            buy_url: '',
            ocUrl: '',
        };

        this.quantityDelay = null;
        this.quantityTimer = null;

        this.checkQuantity = false;
        this.maxQuantity = 0;
        this.minQuantity = 0;
        this.stepQuantity = 1;
        this.isDblQuantity = false;
        this.canBuy = true;
        this.precision = 6;
        this.precisionFactor = Math.pow(10, this.precision);
        this.bigData = false;
        this.fullDisplayMode = false;
        this.viewMode = '';
        this.templateTheme = '';

        this.currentPriceMode = '';
        this.currentPrices = [];
        this.currentPriceSelected = 0;
        this.currentQuantityRanges = [];
        this.currentQuantityRangeSelected = 0;

        this.treeProps = [];
        this.selectedValues = {};

        this.obProduct = null;
        this.blockNodes = {};
        this.obQuantity = null;
        this.obQuantityUp = null;
        this.obQuantityDown = null;
        this.obQuantityLimit = {};
        this.obPict = null;
        this.obSecondPict = null;
        this.obPrice = null;
        this.obTree = null;
        this.obBuyBtn = null;
        this.obBasketActions = null;
        this.obNotAvail = null;
        this.obSubscribe = null;
        this.obDscPerc = null;
        this.obSecondDscPerc = null;
        this.obSkuProps = null;
        this.obMeasure = null;
        this.obCompare = null;
        this.obOc = null;
        this.obUrl = null;
        this.obAllPrices = null;
        this.siteID = 's1';

        this.obPopupWin = null;
        this.basketUrl = '';
        this.basketParams = {};
        this.hoverTimer = null;
        this.hoverStateChangeForbidden = false;
        this.mouseX = null;
        this.mouseY = null;

        this.allPrices = {};

        this.useEnhancedEcommerce = false;
        this.dataLayerName = 'dataLayer';
        this.brandProperty = false;

        this.errorCode = 0;

        if (typeof arParams === 'object')
        {
            if (arParams.PRODUCT_TYPE)
            {
                this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
            }
            this.showQuantity = arParams.SHOW_QUANTITY;
            this.showAbsent = arParams.SHOW_ABSENT;
            this.secondPict = arParams.SECOND_PICT;
            this.showOldPrice = arParams.SHOW_OLD_PRICE;
            this.showMaxQuantity = arParams.SHOW_MAX_QUANTITY;
            this.relativeQuantityFactor = parseInt(arParams.RELATIVE_QUANTITY_FACTOR);

            this.successThanksText = arParams.THANKS;//
            this.successSuccesMessage = arParams.SUCCESS_MESSAGE;

            this.showPercent = arParams.SHOW_DISCOUNT_PERCENT;
            this.showSubscription = arParams.USE_SUBSCRIBE;

            if (arParams.ADD_TO_BASKET_ACTION)
            {
                this.basketAction = arParams.ADD_TO_BASKET_ACTION;
            }

            if (arParams.SITE_ID)
            {
                this.siteID = arParams.SITE_ID;
            }

            this.showClosePopup = arParams.SHOW_CLOSE_POPUP;
            this.fullDisplayMode = arParams.PRODUCT_DISPLAY_MODE === 'Y';
            this.bigData = arParams.BIG_DATA;
            this.viewMode = arParams.VIEW_MODE || '';
            this.templateTheme = arParams.TEMPLATE_THEME || '';
            this.useEnhancedEcommerce = arParams.USE_ENHANCED_ECOMMERCE === 'Y';
            this.dataLayerName = arParams.DATA_LAYER_NAME;
            this.brandProperty = arParams.BRAND_PROPERTY;

            this.visual = arParams.VISUAL;

            if (arParams.PRODUCT.ALL_PRICES)
            {
                this.allPrices = arParams.PRODUCT.ALL_PRICES;
            }

            if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object')
            {
                this.id = arParams.PRODUCT.ID;
                this.currentPriceMode = arParams.PRODUCT.ITEM_PRICE_MODE;
                this.currentPrices = arParams.PRODUCT.ITEM_PRICES;
                this.currentPriceSelected = arParams.PRODUCT.ITEM_PRICE_SELECTED;
                this.currentQuantityRanges = arParams.PRODUCT.ITEM_QUANTITY_RANGES;
                this.currentQuantityRangeSelected = arParams.PRODUCT.ITEM_QUANTITY_RANGE_SELECTED;

                if (this.showQuantity)
                {
                    this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
                    this.product.isDblQuantity = arParams.PRODUCT.QUANTITY_FLOAT;

                    if (this.product.checkQuantity)
                    {
                        this.product.maxQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MAX_QUANTITY) : parseInt(arParams.PRODUCT.MAX_QUANTITY, 10));
                    }

                    this.product.stepQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.STEP_QUANTITY) : parseInt(arParams.PRODUCT.STEP_QUANTITY, 10));

                    this.checkQuantity = this.product.checkQuantity;
                    this.isDblQuantity = this.product.isDblQuantity;
                    this.stepQuantity = this.product.stepQuantity;
                    this.maxQuantity = this.product.maxQuantity;
                    this.minQuantity = this.currentPriceMode === 'Q'
                        ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY)
                        : this.stepQuantity;

                    if (this.isDblQuantity)
                    {
                        this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
                    }
                }

                this.product.canBuy = arParams.PRODUCT.CAN_BUY;

                this.canBuy = this.product.canBuy;
                this.product.name = arParams.PRODUCT.NAME;
                this.product.pict = arParams.PRODUCT.PICT;
                this.product.id = arParams.PRODUCT.ID;
                this.product.iblock_id = arParams.PRODUCT.IBLOCK_ID;
                this.product.DETAIL_PAGE_URL = arParams.PRODUCT.DETAIL_PAGE_URL;

                if (arParams.PRODUCT.ADD_URL)
                {
                    this.product.addUrl = arParams.PRODUCT.ADD_URL;
                }

                if (arParams.PRODUCT.BUY_URL)
                {
                    this.product.buyUrl = arParams.PRODUCT.BUY_URL;
                }

                if (arParams.BASKET && typeof arParams.BASKET === 'object')
                {
                    this.basketData.useProps = arParams.BASKET.ADD_PROPS;
                    this.basketData.emptyProps = arParams.BASKET.EMPTY_PROPS;
                }
            }

            if (arParams.BASKET && typeof arParams.BASKET === 'object')
            {
                if (arParams.BASKET.QUANTITY)
                {
                    this.basketData.quantity = arParams.BASKET.QUANTITY;
                }

                if (arParams.BASKET.PROPS)
                {
                    this.basketData.props = arParams.BASKET.PROPS;
                }

                if (arParams.BASKET.BASKET_URL)
                {
                    this.basketData.basketUrl = arParams.BASKET.BASKET_URL;
                }

                if (arParams.BASKET.OC_URL)
                {
                    this.basketData.ocUrl = arParams.BASKET.OC_URL;
                }

                this.basketData.basketUrlAjax = arParams.BASKET.BASKET_URL_AJAX;

                if (arParams.BASKET.ADD_URL_TEMPLATE)
                {
                    this.basketData.add_url = arParams.BASKET.ADD_URL_TEMPLATE;
                }

                if (arParams.BASKET.BUY_URL_TEMPLATE)
                {
                    this.basketData.buy_url = arParams.BASKET.BUY_URL_TEMPLATE;
                }

                if (this.basketData.add_url === '' && this.basketData.buy_url === '')
                {
                    this.errorCode = -1024;
                }
            }
        }

        if (this.errorCode === 0)
        {
            BX.ready(BX.delegate(this.init,this));
        }
    };

    window.JCCatalogItemTab.prototype = {
        init: function()
        {
            var i = 0,
                treeItems = null;

            if(this.visual.OC_ID)
            {
                this.obOc = BX(this.visual.OC_ID);
            }

            if(this.obOc)
            {
                BX.bind(this.obOc, 'click', BX.proxy(this.oc, this));
            }

            this.obAllPrices = BX(this.visual.ALL_PRICES);

            this.obProduct = BX(this.visual.ID);
            if (!this.obProduct)
            {
                this.errorCode = -1;
            }

            this.obPrice = BX(this.visual.PRICE_ID);
            this.obPriceOld = BX(this.visual.PRICE_OLD_ID);
            this.obPriceTotal = BX(this.visual.PRICE_TOTAL_ID);
            this.obPriceSave = BX(this.visual.PRICE_SAVE);

            if (!this.obPrice)
            {
                //this.errorCode = -16;
            }

            if(this.obProduct)
            {
                this.obUrl = this.obProduct.querySelectorAll('.service-detail-item__image-wrapper, .service-detail-item__title');
            }

            if (this.showQuantity && this.visual.QUANTITY_ID)
            {
                this.obQuantity = BX(this.visual.QUANTITY_ID);
                this.blockNodes.quantity = this.obProduct.querySelector('[data-entity="quantity-block"]');

                if (!this.isTouchDevice)
                {
                    BX.bind(this.obQuantity, 'focus', BX.proxy(this.onFocus, this));
                    BX.bind(this.obQuantity, 'blur', BX.proxy(this.onBlur, this));
                }

                if (this.visual.QUANTITY_UP_ID)
                {
                    this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
                }

                if (this.visual.QUANTITY_DOWN_ID)
                {
                    this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
                }
            }

            if (this.visual.QUANTITY_LIMIT && this.showMaxQuantity !== 'N')
            {
                this.obQuantityLimit.all = BX(this.visual.QUANTITY_LIMIT);
                if (this.obQuantityLimit.all)
                {
                    this.obQuantityLimit.value = this.obQuantityLimit.all;
                    if (!this.obQuantityLimit.value)
                    {
                        this.obQuantityLimit.all = null;
                    }
                }
            }

            this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
            if (this.obBasketActions)
            {
                if (this.visual.BUY_ID)
                {
                    this.obBuyBtn = BX(this.visual.BUY_ID);
                    this.setBasket();
                }
            }

            this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);

            if (this.showSubscription)
            {
                this.obSubscribe = BX(this.visual.SUBSCRIBE_ID);
            }

            if (this.showPercent)
            {
                if (this.visual.DSC_PERC)
                {
                    this.obDscPerc = BX(this.visual.DSC_PERC);
                }
                if (this.secondPict && this.visual.SECOND_DSC_PERC)
                {
                    this.obSecondDscPerc = BX(this.visual.SECOND_DSC_PERC);
                }
            }

            //this.setWish();
            if (this.errorCode === 0)
            {

                if (this.bigData)
                {
                    var links = BX.findChildren(this.obProduct, {tag:'a'}, true);
                    if (links)
                    {
                        for (i in links)
                        {
                            if (links.hasOwnProperty(i))
                            {
                                if (links[i].getAttribute('href') == this.product.DETAIL_PAGE_URL)
                                {
                                    BX.bind(links[i], 'click', BX.proxy(this.rememberProductRecommendation, this));
                                }
                            }
                        }
                    }
                }
                if (this.showQuantity)
                {
                    var startEventName = this.isTouchDevice ? 'touchstart' : 'mousedown';
                    var endEventName = this.isTouchDevice ? 'touchend' : 'mouseup';

                    if (this.obQuantityUp)
                    {
                        BX.bind(this.obQuantityUp, startEventName, BX.proxy(this.startQuantityInterval, this));
                        BX.bind(this.obQuantityUp, endEventName, BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityUp, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityUp, 'click', BX.delegate(this.quantityUp, this));
                    }

                    if (this.obQuantityDown)
                    {
                        BX.bind(this.obQuantityDown, startEventName, BX.proxy(this.startQuantityInterval, this));
                        BX.bind(this.obQuantityDown, endEventName, BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityDown, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityDown, 'click', BX.delegate(this.quantityDown, this));

                    }

                    if (this.obQuantity)
                    {
                        BX.bind(this.obQuantity, 'change', BX.delegate(this.quantityChange, this));
                    }
                }

                //this.checkQuantityControls();

                if (this.obBuyBtn)
                {
                    if (this.basketAction === 'ADD')
                    {
                        BX.bind(this.obBuyBtn, 'click', BX.proxy(this.add2Basket, this));

                    }
                    else
                    {
                        BX.bind(this.obBuyBtn, 'click', BX.proxy(this.buyBasket, this));
                    }
                }

                BX.addCustomEvent(window, 'OnBasketChangeAfter', BX.proxy(this.refreshProducts, this));

            }
        },

        captureMousePosition: function(event)
        {
            this.mouseX = event.clientX;
            this.mouseY = event.clientY;
        },

        getCookie: function(name)
        {
            var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));

            return matches ? decodeURIComponent(matches[1]) : null;
        },

        startQuantityInterval: function()
        {
            var target = BX.proxy_context;
            var func = target.id === this.visual.QUANTITY_DOWN_ID
                ? BX.proxy(this.quantityDown, this)
                : BX.proxy(this.quantityUp, this);

            this.quantityDelay = setTimeout(
                BX.delegate(function() {
                    this.quantityTimer = setInterval(func, 150);
                }, this),
                300
            );
        },

        clearQuantityInterval: function()
        {
            clearTimeout(this.quantityDelay);
            clearInterval(this.quantityTimer);
        },

        quantityUp: function()
        {
            var curValue = 0,
                boolSet = true;

            if (this.errorCode === 0 && this.showQuantity && this.canBuy)
            {
                curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
                if (!isNaN(curValue))
                {
                    curValue += this.stepQuantity;
                    if (this.checkQuantity)
                    {
                        if (curValue > this.maxQuantity)
                        {
                            boolSet = false;
                        }
                    }

                    if (boolSet)
                    {
                        if (this.isDblQuantity)
                        {
                            curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
                        }

                        this.obQuantity.value = curValue;

                        this.setPrice();
                    }
                }
            }
        },

        quantityDown: function()
        {
            var curValue = 0,
                boolSet = true;

            if (this.errorCode === 0 && this.showQuantity && this.canBuy)
            {
                curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
                if (!isNaN(curValue))
                {
                    curValue -= this.stepQuantity;

                    this.checkPriceRange(curValue);

                    if (curValue < this.minQuantity)
                    {
                        boolSet = false;
                    }

                    if (boolSet)
                    {
                        if (this.isDblQuantity)
                        {
                            curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
                        }

                        this.obQuantity.value = curValue;

                        this.setPrice();
                    }
                }
            }
        },

        quantityChange: function()
        {
            var curValue = 0,
                intCount;

            if (this.errorCode === 0 && this.showQuantity)
            {
                if (this.canBuy)
                {
                    curValue = this.isDblQuantity ? parseFloat(this.obQuantity.value) : Math.round(this.obQuantity.value);
                    if (!isNaN(curValue))
                    {
                        if (this.checkQuantity)
                        {
                            if (curValue > this.maxQuantity)
                            {
                                curValue = this.maxQuantity;
                            }
                        }

                        this.checkPriceRange(curValue);

                        if (curValue < this.minQuantity)
                        {
                            curValue = this.minQuantity;
                        }
                        else
                        {
                            intCount = Math.round(
                                Math.round(curValue * this.precisionFactor / this.stepQuantity) / this.precisionFactor
                            ) || 1;
                            curValue = (intCount <= 1 ? this.stepQuantity : intCount * this.stepQuantity);
                            curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
                        }

                        this.obQuantity.value = curValue;
                    }
                    else
                    {
                        this.obQuantity.value = this.minQuantity;
                    }
                }
                else
                {
                    this.obQuantity.value = this.minQuantity;
                }

                this.setPrice();
            }
        },

        eq: function(obj, i)
        {
            var len = obj.length,
                j = +i + (i < 0 ? len : 0);

            return j >= 0 && j < len ? obj[j] : {};
        },

        oc: function()
        {

            let id = 0;
            id = this.product.id;
            if(id > 0)
            {
                var item = this.obOc;
                createBtnLoader(item);

                $.ajax({
                    url: this.basketData.ocUrl,
                    type: 'POST',
                    data:{'id':id,'site_id':this.siteID,'basketData':this.basketData.sku_props, 'iblockId':this.product.iblock_id},
                    success: function(html)
                    {
                        showModal(html);
                        removeBtnLoader(item);
                    }
                });
            }
        },

        checkPriceRange: function(quantity)
        {
            if (typeof quantity === 'undefined'|| this.currentPriceMode != 'Q')
                return;

            var range, found = false;

            for (var i in this.currentQuantityRanges)
            {
                if (this.currentQuantityRanges.hasOwnProperty(i))
                {
                    range = this.currentQuantityRanges[i];

                    if (
                        parseInt(quantity) >= parseInt(range.SORT_FROM)
                        && (
                            range.SORT_TO == 'INF'
                            || parseInt(quantity) <= parseInt(range.SORT_TO)
                        )
                    )
                    {
                        found = true;
                        this.currentQuantityRangeSelected = range.HASH;
                        break;
                    }
                }
            }

            if (!found && (range = this.getMinPriceRange()))
            {
                this.currentQuantityRangeSelected = range.HASH;
            }

            for (var k in this.currentPrices)
            {
                if (this.currentPrices.hasOwnProperty(k))
                {
                    if (this.currentPrices[k].QUANTITY_HASH == this.currentQuantityRangeSelected)
                    {
                        this.currentPriceSelected = k;
                        break;
                    }
                }
            }
        },

        getMinPriceRange: function()
        {
            var range;

            for (var i in this.currentQuantityRanges)
            {
                if (this.currentQuantityRanges.hasOwnProperty(i))
                {
                    if (
                        !range
                        || parseInt(this.currentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)
                    )
                    {
                        range = this.currentQuantityRanges[i];
                    }
                }
            }

            return range;
        },

        setPrice: function()
        {
            var obData, price;

            if (this.obQuantity)
            {
                this.checkPriceRange(this.obQuantity.value);
            }

            //this.checkQuantityControls();

            price = this.currentPrices[this.currentPriceSelected];

            //if (this.obPrice)
            {
                if (this.obPrice && price)
                {
                    let str = '';
                    if(BX.Currency !== undefined){
                        str = BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true);
                    }
                    if(str.length == 0){
                        str = price.PRINT_RATIO_PRICE;
                    }

                    BX.adjust(this.obPrice, {html: str});
                }
                else if(this.obPrice)
                {
                    BX.adjust(this.obPrice, {html: ''});
                }

                if (this.obPrice && this.showOldPrice && this.obPriceOld)
                {
                    if (price && price.RATIO_PRICE !== price.RATIO_BASE_PRICE)
                    {
                        let str = '';
                        if(BX.Currency !== undefined){
                            str = BX.Currency.currencyFormat(price.RATIO_BASE_PRICE, price.CURRENCY, true);
                        }
                        if(str.length == 0){
                            str = price.PRINT_RATIO_BASE_PRICE;
                        }

                        BX.adjust(this.obPriceOld, {
                            style: {display: ''},
                            html: str
                        });

                        let str2 = '';
                        if(BX.Currency !== undefined){
                            str2 = BX.Currency.currencyFormat(price.DISCOUNT, price.CURRENCY, true);
                        }
                        if(str2.length == 0){
                            str2 = price.PRINT_DISCOUNT;
                        }

                        let itemSave = BX.findChildren(this.obPriceSave, {"tag" : "span", "class" : "service-detail-item__economy-amount"}, true, true);

                        BX.adjust(this.obPriceSave, {
                            style: {display: ''},
                        });

                    }
                    else
                    {
                        BX.adjust(this.obPriceOld, {
                            style: {display: 'none'},
                            html: ''
                        });

                        BX.adjust(this.obPriceSave, {
                            style: {display: 'none'},
                        });
                    }
                }

                if (this.obPrice && this.obPriceTotal)
                {
                    if (price && this.obQuantity && this.obQuantity.value != this.stepQuantity)
                    {
                        BX.adjust(this.obPriceTotal, {
                            html: BX.message('PRICE_TOTAL_PREFIX') + ' <strong>'
                                + BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true)
                                + '</strong>',
                            style: {display: ''}
                        });
                    }
                    else
                    {
                        BX.adjust(this.obPriceTotal, {
                            html: '',
                            style: {display: 'none'}
                        });
                    }
                }

                if ((this.obPrice || this.obAllPrices) && this.showPercent)
                {
                    if (price && parseInt(price.DISCOUNT) > 0)
                    {
                        obData = {style: {display: ''}, html: -price.PERCENT + '%'};
                    }
                    else
                    {
                        obData = {style: {display: 'none'}, html: ''};
                    }

                    if (this.obDscPerc)
                    {
                        BX.adjust(this.obDscPerc, obData);
                    }

                    if (this.obSecondDscPerc)
                    {
                        BX.adjust(this.obSecondDscPerc, obData);
                    }
                }
            }
        },

        setBasket: function()
        {
            if(arBasketID.indexOf(parseInt(this.product.id))>=0)
            {
                $(this.obProduct).find(".service-detail-item__content-hidden-form").hide();
                $(this.obProduct).find(".service-detail-item__content-hidden-form__at_basket").show();
            }else{
                $(this.obProduct).find(".service-detail-item__content-hidden-form").show();
                $(this.obProduct).find(".service-detail-item__content-hidden-form__at_basket").hide();
            }
        },

        refreshProducts: function(data)
        {
            this.recountProducts();
        },

        recountProducts: function()
        {
            this.setBasket();
        },

        initBasketUrl: function()
        {
            this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);
            this.basketUrl = this.basketUrl.replace('&amp;','&');
            switch (this.productType)
            {
                case 1: // product
                case 2: // set
                    this.basketUrl = this.basketUrl.replace('#ID#', this.product.id.toString());
                    break;
                case 3: // sku
                    this.basketUrl = this.basketUrl.replace('#ID#', this.offers[this.offerNum].ID);
                    break;
            }
            this.basketParams = {
                'ajax_basket': 'Y'
            };
            if (this.showQuantity)
            {
                this.basketParams[this.basketData.quantity] = this.obQuantity.value;
            }
            if (this.basketData.sku_props)
            {
                this.basketParams[this.basketData.sku_props_var] = this.basketData.sku_props;
            }
        },

        fillBasketProps: function()
        {
            if (!this.visual.BASKET_PROP_DIV)
            {
                return;
            }
            var
                i = 0,
                propCollection = null,
                foundValues = false,
                obBasketProps = null;

            if (this.basketData.useProps && !this.basketData.emptyProps)
            {
                if (this.obPopupWin && this.obPopupWin.contentContainer)
                {
                    obBasketProps = this.obPopupWin.contentContainer;
                }
            }
            else
            {
                obBasketProps = BX(this.visual.BASKET_PROP_DIV);
            }
            if (obBasketProps)
            {
                propCollection = obBasketProps.getElementsByTagName('select');
                if (propCollection && propCollection.length)
                {
                    for (i = 0; i < propCollection.length; i++)
                    {
                        if (!propCollection[i].disabled)
                        {
                            switch (propCollection[i].type.toLowerCase())
                            {
                                case 'select-one':
                                    this.basketParams[propCollection[i].name] = propCollection[i].value;
                                    foundValues = true;
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
                propCollection = obBasketProps.getElementsByTagName('input');
                if (propCollection && propCollection.length)
                {
                    for (i = 0; i < propCollection.length; i++)
                    {
                        if (!propCollection[i].disabled)
                        {
                            switch (propCollection[i].type.toLowerCase())
                            {
                                case 'hidden':
                                    this.basketParams[propCollection[i].name] = propCollection[i].value;
                                    foundValues = true;
                                    break;
                                case 'radio':
                                    if (propCollection[i].checked)
                                    {
                                        this.basketParams[propCollection[i].name] = propCollection[i].value;
                                        foundValues = true;
                                    }
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
            if (!foundValues)
            {
                this.basketParams[this.basketData.props] = [];
                this.basketParams[this.basketData.props][0] = 0;
            }
        },

        add2Basket: function()
        {
            this.basketMode = 'ADD';
            this.basket();
        },

        buyBasket: function()
        {
            this.basketMode = 'BUY';
            this.basket();
        },

        sendToBasket: function()
        {
            if (!this.canBuy)
            {
                return;
            }
            createBtnLoader(this.obBuyBtn);
            // check recommendation
            if (this.product && this.product.id && this.bigData)
            {
                this.rememberProductRecommendation();
            }

            this.initBasketUrl();

            var params = {};

            this.fillBasketProps();
            params['props'] = this.basketData.sku_props;
            params['price'] = this.currentPrices[this.currentPriceSelected];
            params['action'] = 'add';

            if(this.obQuantity == null)
                params['qnt'] = this.currentPrices[this.currentPriceSelected].MIN_QUANTITY;
            else
                params['qnt'] = this.obQuantity.value;

            params['id'] = this.product.id.toString();

            var tempThis = this;
            BX.ajax({
                method: 'POST',
                dataType: 'json',
                url: this.basketData.basketUrlAjax,
                data:{params:JSON.stringify(params)},
                onsuccess: function (arResult) {
                    tempThis.basketResult(arResult);
                    removeBtnLoader(tempThis.obBuyBtn);
                },
            });
        },

        basket: function()
        {
            var contentBasketProps = '';
            if (!this.canBuy)
            {
                return;
            }
            if (this.basketData.useProps && !this.basketData.emptyProps)
            {
                this.initPopupWindow();
                this.obPopupWin.setTitleBar(BX.message('TITLE_BASKET_PROPS'));
                if (BX(this.visual.BASKET_PROP_DIV))
                {
                    contentBasketProps = BX(this.visual.BASKET_PROP_DIV).innerHTML;
                }
                this.obPopupWin.setContent(contentBasketProps);
                this.obPopupWin.setButtons([
                    new BasketButton({
                        text: BX.message('BTN_MESSAGE_SEND_PROPS'),
                        events: {
                            click: BX.delegate(this.sendToBasket, this)
                        }
                    })
                ]);
                this.obPopupWin.show();
                this.obPopupWin.centering('CatalogSectionBasket_' + this.obPopupWin.resizeId);
            }
            else
            {
                this.sendToBasket();
            }
        },

        basketResult: function(arResult)
        {

            var strContent = '',
                strPict = '',
                successful,
                buttons = [];

            if (this.obPopupWin)
                this.obPopupWin.close();

            if (!BX.type.isPlainObject(arResult))
                return;

            successful = arResult.STATUS === 'OK';


            if (successful && this.basketAction === 'BUY')
            {
                this.basketRedirect();
            }
            else
            {
                if (!successful || this.addProductToBasketMode === 'popup')
                {
                    this.initPopupWindow();
                }

                if (successful)
                {
                    BX.onCustomEvent('OnBasketChange');
                    // strContent = '<div style="width: 100%; margin: 0; text-align: center;"><img src="'
                    // 	+ strPict + '" height="130" style="max-height: 130px; max-width: 350px;" alt="" title=""><p style="padding-top: 15px;">' + this.product.name + '</p></div>';

                    strContent = '<div class="sotbit_order_success_show" style="max-width: 429px">\n' +
                        '<div class="popup-window-message-content">\n' +
                        '<svg class="popup-window-icon-check">\n' +
                        '<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_form"></use>\n' +
                        '</svg>\n' +
                        '<div>\n' +
                        '<div class="popup-window-message-title">' + this.successThanksText + '</div>\n' +
                        '<div style="font-size: 16px;">' + this.successSuccesMessage + '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>';

                    if (this.showClosePopup)
                    {
                        buttons = [
                            new BasketButton({
                                text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
                                className: 'product-card-inner__product-basket-btn',
                                events: {
                                    click: BX.delegate(this.basketRedirect, this)
                                },
                                style: {marginRight: '10px'}
                            }),
                            new BasketButton({
                                text: BX.message("BTN_MESSAGE_CLOSE_POPUP"),
                                events: {
                                    click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                                }
                            })
                        ];
                    }
                    else
                    {
                        buttons = [
                            new BasketButton({
                                text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
                                className: 'product-card-inner__product-basket-btn',
                                events: {
                                    click: BX.delegate(this.basketRedirect, this)
                                }
                            })
                        ];
                    }

                    arBasketID.push(this.product.id);
                    var btnsBlock = this.obProduct.querySelector('[data-entity="buttons-block"]');

                    btnsBlock.getElementsByClassName('service-detail-item__content-hidden-form')[0].style.display = 'none';
                    btnsBlock.getElementsByClassName('service-detail-item__content-hidden-form__at_basket')[0].style.display = 'flex';
                }
                else
                {
                    strContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
                        + (arResult.MESSAGE ? arResult.MESSAGE : BX.message('BASKET_UNKNOWN_ERROR'))
                        + '</p></div>';
                    buttons = [
                        new BasketButton({
                            text: BX.message('BTN_MESSAGE_CLOSE'),
                            events: {
                                click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                            }
                        })
                    ];
                }

                if (!successful || this.addProductToBasketMode === 'popup')
                {
                    this.obPopupWin.setTitleBar(successful ? BX.message('TITLE_SUCCESSFUL') : BX.message('TITLE_ERROR'));
                    this.obPopupWin.setContent(strContent);
                    this.obPopupWin.setButtons(buttons);
                    this.obPopupWin.show();
                    this.obPopupWin.centering('CatalogSectionBasket_' + this.obPopupWin.resizeId);
                }
            }
        },

        basketRedirect: function()
        {
            location.href = (this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('BASKET_URL'));
        },

        initPopupWindow: function()
        {
            if (this.obPopupWin)
                return;

            this.obPopupWin = BX.PopupWindowManager.create('CatalogSectionBasket_' + this.visual.ID, null, {
                autoHide: true,
                offsetLeft: 0,
                offsetTop: 0,
                overlay : true,
                closeByEsc: true,
                titleBar: true,
                closeIcon: true,
                contentColor: 'white',
                //className: this.templateTheme ? 'bx-' + this.templateTheme : ''
                className: 'to_basket'
            });


            /* ====== override ========*/
            this.obPopupWin.adjustPosition = function () {
            };
            /* ====== /end override ========*/

            this.obPopupWin.resizeId = this.visual.ID;

            this.obPopupWin.centering = function (id) {
                this.id = id;
                let overlay = document.getElementById("popup-window-overlay-" + id);

                overlay.style.position = "fixed";
                overlay.style.width = "100%";
                overlay.style.height = "100%";

                centeringPosition(id);

                window.addEventListener("resize", () => {centeringPosition(id)});

                function centeringPosition(thisId) {
                    let thisPopup = document.getElementById(thisId);

                    thisPopup.style.position = "fixed";
                    thisPopup.style.top = "calc(50% - " + thisPopup.clientHeight / 2 + "px)";
                    thisPopup.style.left = "calc(50% - " + thisPopup.clientWidth / 2 + "px)";
                }
            };


        }

    };


})(window);
