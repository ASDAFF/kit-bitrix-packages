(function(window) {
    'use strict';

    if (window.JCCatalogElement)
        return;

    var BasketButton = function (params) {
        BasketButton.superclass.constructor.apply(this, arguments);
        this.buttonNode = BX.create('BUTTON', {
            props: {className: 'simple_btn to_basket_btn', id: this.id},
            style: typeof params.style === 'object' ? params.style : {},
            text: params.text,
            events: this.contextEvents
        });

        if (BX.browser.IsIE()) {
            this.buttonNode.setAttribute('hideFocus', 'hidefocus');
        }
    };

    BX.extend(BasketButton, BX.PopupWindowButton);

    window.JCCatalogElement = function(arParams) {

        this.arParams = arParams;
        this.productType = 0;
        this.config = {
            useCatalog: true,
            showQuantity: true,
            showPrice: true,
            showAbsent: true,
            showOldPrice: false,
            showPercent: false,
            showSkuProps: false,
            useSubscribe: false,
            usePopup: false,
            usePriceRanges: false,
            basketAction: ['BUY'],
            showClosePopup: false,
            templateTheme: '',
            useEnhancedEcommerce: false,
            dataLayerName: 'dataLayer',
            brandProperty: false,
            skuOff: false,
            alt: '',
            title: '',
            site_dir:'',
            site_id:'',
            iblock_id:'',
        };

        this.checkQuantity = false;
        this.maxQuantity = 0;
        this.minQuantity = 0;
        this.stepQuantity = 1;
        this.isDblQuantity = false;
        this.canBuy = true;
        this.canSubscription = true;
        this.updateViewedCount = false;

        this.currentPriceMode = '';
        this.currentPrices = [];
        this.currentPriceSelected = 0;
        this.currentQuantityRanges = [];
        this.currentQuantityRangeSelected = 0;
        this.obAllPrices = null;
        this.allPrices = {};

        this.precision = 6;
        this.precisionFactor = Math.pow(10, this.precision);

        this.visual = {};
        this.basketMode = '';
        this.product = {
            checkQuantity: false,
            maxQuantity: 0,
            stepQuantity: 1,
            startQuantity: 1,
            isDblQuantity: false,
            canBuy: true,
            canSubscription: true,
            name: '',
            pict: {},
            id: 0,
            addUrl: '',
            buyUrl: ''
        };
        this.mess = {};

        this.basketData = {
            useProps: false,
            emptyProps: false,
            quantity: 'quantity',
            props: 'prop',
            basketUrl: '',
            sku_props: '',
            sku_props_var: 'basket_props',
            add_url: '',
            buy_url: ''
        };

        this.treeProps = [];
        this.selectedValues = {};

        this.quantityDelay = null;
        this.quantityTimer = null;

        this.obProduct = null;
        this.obQuantity = null;
        this.obQuantityUp = null;
        this.obQuantityDown = null;
        this.obPrice = {
            price: null,
            full: null,
            discount: null,
            percent: null,
            total: null
        };

        this.obTree = null;
        this.obPriceRanges = null;
        this.obBuyBtn = null;
        this.obAddToBasketBtn = null;
        this.obBasketActions = null;
        this.obNotAvail = null;
        this.obSubscribe = null;
        this.obSkuProps = null;
        this.obMainSkuProps = null;
        this.obMeasure = null;
        this.obQuantityLimit = {
            all: null,
            value: null
        };

        this.obTabsPanel = null;

        this.node = {};
        // top panel small card
        this.smallCardNodes = {};

        this.viewedCounter = {
            path: '/bitrix/components/bitrix/catalog.element/ajax.php',
            params: {
                AJAX: 'Y',
                SITE_ID: '',
                PRODUCT_ID: 0,
                PARENT_ID: 0
            }
        };

        this.obPopupWin = null;
        this.basketUrl = '';
        this.basketParams = {};

        this.errorCode = 0;

        if (typeof arParams === 'object') {
            this.successThanksText = arParams.CONFIG.THANKS;//
            this.successSuccesMessage = arParams.CONFIG.SUCCESS_MESSAGE;

            this.params = arParams;
            this.initConfig();
            if (this.params.MESS) {
                this.mess = this.params.MESS;
            }

            if (arParams.PRODUCT.ALL_PRICES) {
                this.allPrices = arParams.PRODUCT.ALL_PRICES;
            }

            this.initProductData();
            this.initBasketData();

            if (this.errorCode === 0) {
                BX.ready(BX.delegate(this.init, this));
            }

            this.params = {};
            BX.addCustomEvent(window, 'OnBasketChangeAfter', BX.proxy(this.refreshProducts, this));
        }
    };

    window.JCCatalogElement.prototype = {

        getEntity: function(parent, entity, additionalFilter)
        {
            if (!parent || !entity)
                return null;

            additionalFilter = additionalFilter || '';

            return parent.querySelector(additionalFilter + '[data-entity="' + entity + '"]');
        },

        init: function()
        {
            var i = 0,
                j = 0,
                treeItems = null;



            this.obProduct = BX(this.visual.ID);

            if (!this.obProduct)
            {
                this.errorCode = -1;
            }

            this.obAllPrices = BX(this.visual.ALL_PRICES);

            if (this.config.showPrice)
            {
                this.obPrice.price = BX(this.visual.PRICE_ID);
                if (!this.obPrice.price && !this.obAllPrices  && this.config.useCatalog)
                {
                    this.errorCode = -16;
                }
                else
                {
                    if(this.obPrice.price)
                    {
                        this.obPrice.total = BX(this.visual.PRICE_TOTAL);

                        if (this.config.showOldPrice)
                        {
                            this.obPrice.full = BX(this.visual.OLD_PRICE_ID);
                            this.obPrice.discount = BX(this.visual.DISCOUNT_PRICE_ID);

                            if (!this.obPrice.full || !this.obPrice.discount)
                            {
                                this.config.showOldPrice = false;
                            }
                        }

                        if (this.config.showPercent)
                        {
                            this.obPrice.percent = BX(this.visual.DISCOUNT_PERCENT_ID);
                            if (!this.obPrice.percent)
                            {
                                this.config.showPercent = false;
                            }
                        }
                    }
                    if(this.obAllPrices)
                    {
                        if (this.config.showPercent)
                        {
                            this.obPrice.percent = BX(this.visual.DISCOUNT_PERCENT_ID);
                            if (!this.obPrice.percent)
                            {
                                this.config.showPercent = false;
                            }
                        }
                    }

                }

                this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
                if (this.obBasketActions)
                {
                    if (BX.util.in_array('BUY', this.config.basketAction))
                    {
                        this.obBuyBtn = BX(this.visual.BUY_LINK);
                    }

                    if (BX.util.in_array('ADD', this.config.basketAction))
                    {
                        this.obAddToBasketBtn = BX(this.visual.ADD_BASKET_LINK);
                    }
                }
                this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);
            }

            if (this.config.showQuantity)
            {
                this.obQuantity = BX(this.visual.QUANTITY_ID);
                this.node.quantity = this.getEntity(this.obProduct, 'quantity-block');
                if (this.visual.QUANTITY_UP_ID)
                {
                    this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
                }

                if (this.visual.QUANTITY_DOWN_ID)
                {
                    this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
                }
            }

            if (this.productType === 3)
            {
                if (this.visual.TREE_ID)
                {
                    this.obTree = BX(this.visual.TREE_ID);
                    if (!this.obTree)
                    {
                        //this.errorCode = -256;
                    }
                }

                if (this.visual.QUANTITY_MEASURE)
                {
                    this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
                }

                if (this.visual.QUANTITY_LIMIT && this.config.showMaxQuantity !== 'N')
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
            }

            this.initPopup();

            if(BX('modal_oc'))
            {
                BX.bind(BX('modal_oc'), 'click', BX.proxy(this.oc, this));
            }

            if (this.obTabsPanel || this.smallCardNodes.panel)
            {
                this.checkTopPanels();
                BX.bind(window, 'scroll', BX.proxy(this.checkTopPanels, this));
            }

            if (this.errorCode === 0)
            {
                if (this.config.showQuantity)
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
                this.fixFontCheck();
                //this.setAnalyticsDataLayer('showDetail');


                this.obBuyBtn && BX.bind(this.obBuyBtn, 'click', BX.proxy(this.buyBasket, this));
                this.smallCardNodes.buyButton && BX.bind(this.smallCardNodes.buyButton, 'click', BX.proxy(this.buyBasket, this));

                this.obAddToBasketBtn && BX.bind(this.obAddToBasketBtn, 'click', BX.proxy(this.add2Basket, this));
                this.smallCardNodes.addButton && BX.bind(this.smallCardNodes.addButton, 'click', BX.proxy(this.add2Basket, this));
                this.setBasket();
            }
        },

        initConfig: function()
        {
            if (this.params.PRODUCT_TYPE)
            {
                this.productType = parseInt(this.params.PRODUCT_TYPE, 10);
            }

            if (this.params.CONFIG.USE_CATALOG !== 'undefined' && BX.type.isBoolean(this.params.CONFIG.USE_CATALOG))
            {
                this.config.useCatalog = this.params.CONFIG.USE_CATALOG;
            }

            this.config.site_dir = this.params.CONFIG.SITE_DIR;
            this.config.site_id = this.params.CONFIG.SITE_ID;
            this.config.iblock_id = this.params.CONFIG.IBLOCK_ID;

            this.config.showQuantity = this.params.CONFIG.SHOW_QUANTITY;
            this.config.showPrice = this.params.CONFIG.SHOW_PRICE;
            this.config.showPercent = this.params.CONFIG.SHOW_DISCOUNT_PERCENT;
            this.config.showOldPrice = this.params.CONFIG.SHOW_OLD_PRICE;
            this.config.useSubscribe = this.params.CONFIG.USE_SUBSCRIBE;
            this.config.showMaxQuantity = this.params.CONFIG.SHOW_MAX_QUANTITY;
            this.config.relativeQuantityFactor = parseInt(this.params.CONFIG.RELATIVE_QUANTITY_FACTOR);
            this.config.usePriceRanges = this.params.CONFIG.USE_PRICE_COUNT;

            if (this.params.CONFIG.ADD_TO_BASKET_ACTION)
            {
                this.config.basketAction = this.params.CONFIG.ADD_TO_BASKET_ACTION;
            }

            this.config.showClosePopup = this.params.CONFIG.SHOW_CLOSE_POPUP;
            this.config.templateTheme = this.params.CONFIG.TEMPLATE_THEME || '';

            this.config.useEnhancedEcommerce = this.params.CONFIG.USE_ENHANCED_ECOMMERCE === 'Y';
            this.config.dataLayerName = this.params.CONFIG.DATA_LAYER_NAME;
            this.config.brandProperty = this.params.CONFIG.BRAND_PROPERTY;

            this.config.alt = this.params.CONFIG.ALT || '';
            this.config.title = this.params.CONFIG.TITLE || '';

            if (!this.params.VISUAL || typeof this.params.VISUAL !== 'object' || !this.params.VISUAL.ID)
            {
                this.errorCode = -1;
                return;
            }

            this.visual = this.params.VISUAL;
        },

        initProductData: function()
        {
            var j = 0;

            if (this.params.PRODUCT && typeof this.params.PRODUCT === 'object')
            {
                if (this.config.showQuantity)
                {
                    this.product.checkQuantity = this.params.PRODUCT.CHECK_QUANTITY;
                    this.product.isDblQuantity = this.params.PRODUCT.QUANTITY_FLOAT;

                    if (this.config.showPrice)
                    {
                        this.currentPriceMode = this.params.PRODUCT.ITEM_PRICE_MODE;
                        this.currentPrices = this.params.PRODUCT.ITEM_PRICES;
                        this.currentPriceSelected = this.params.PRODUCT.ITEM_PRICE_SELECTED;
                        this.currentQuantityRanges = this.params.PRODUCT.ITEM_QUANTITY_RANGES;
                        this.currentQuantityRangeSelected = this.params.PRODUCT.ITEM_QUANTITY_RANGE_SELECTED;
                    }

                    if (this.product.checkQuantity)
                    {
                        this.product.maxQuantity = this.product.isDblQuantity
                            ? parseFloat(this.params.PRODUCT.MAX_QUANTITY)
                            : parseInt(this.params.PRODUCT.MAX_QUANTITY, 10);
                    }

                    this.product.stepQuantity = this.product.isDblQuantity
                        ? parseFloat(this.params.PRODUCT.STEP_QUANTITY)
                        : parseInt(this.params.PRODUCT.STEP_QUANTITY, 10);
                    this.checkQuantity = this.product.checkQuantity;
                    this.isDblQuantity = this.product.isDblQuantity;
                    this.stepQuantity = this.product.stepQuantity;
                    this.maxQuantity = this.product.maxQuantity;
                    this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;

                    if (this.isDblQuantity)
                    {
                        this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
                    }
                }

                //this.setCompared(this.offers[index].COMPARED);

                this.product.canBuy = this.params.PRODUCT.CAN_BUY;
                this.canSubscription = this.product.canSubscription = this.params.PRODUCT.SUBSCRIPTION;

                this.product.name = this.params.PRODUCT.NAME;
                this.product.pict = this.params.PRODUCT.PICT;
                this.product.id = this.params.PRODUCT.ID;
                this.product.category = this.params.PRODUCT.CATEGORY;

                if (this.params.PRODUCT.ADD_URL)
                {
                    this.product.addUrl = this.params.PRODUCT.ADD_URL;
                }

                if (this.params.PRODUCT.BUY_URL)
                {
                    this.product.buyUrl = this.params.PRODUCT.BUY_URL;
                }
            }
            else
            {
                this.errorCode = -1;
            }
        },

        initBasketData: function()
        {
            if (this.params.BASKET && typeof this.params.BASKET === 'object')
            {
                if (this.productType === 1 || this.productType === 2)
                {
                    this.basketData.useProps = this.params.BASKET.ADD_PROPS;
                    this.basketData.emptyProps = this.params.BASKET.EMPTY_PROPS;
                }

                if (this.params.BASKET.QUANTITY)
                {
                    this.basketData.quantity = this.params.BASKET.QUANTITY;
                }

                if (this.params.BASKET.PROPS)
                {
                    this.basketData.props = this.params.BASKET.PROPS;
                }

                if (this.params.BASKET.BASKET_URL)
                {
                    this.basketData.basketUrl = this.params.BASKET.BASKET_URL;
                }

                this.basketData.urlAjax = this.params.BASKET.BASKET_URL_AJAX;
                if (this.params.BASKET.ADD_URL_TEMPLATE)
                {
                    this.basketData.add_url = this.params.BASKET.ADD_URL_TEMPLATE;
                }

                if (this.params.BASKET.BUY_URL_TEMPLATE)
                {
                    this.basketData.buy_url = this.params.BASKET.BUY_URL_TEMPLATE;
                }

                if (this.basketData.add_url === '' && this.basketData.buy_url === '')
                {
                    this.errorCode = -1024;
                }
            }
        },

        oc: function()
        {
            let id = 0;
            switch (this.productType)
            {
                case 1: // product
                case 2: // set
                    id = this.product.id;
                    break;
                case 3: // sku
                    id = this.offers[this.offerNum].ID;
                    break;
            }
            if(id > 0)
            {
                var item = document.querySelector('#modal_oc');
                createBtnLoader(item);
                $.ajax({
                    url: this.config.site_dir + 'include/ajax/oc.php',
                    type: 'POST',
                    data:{'id':id,'site_id':this.config.site_id,'basketData':this.basketData.sku_props,'iblockId':this.config.iblock_id},
                    success: function(html)
                    {
                        showModal(html);
                        removeBtnLoader(item);
                    }
                });
            }
        },

        ocModification: function(event)
        {
            let id = $(event.target).closest('.product-presence').data('id');
            if(id > 0)
            {
                createBtnLoader (event.target);
                $.ajax({
                    url: this.config.site_dir + 'include/ajax/oc.php',
                    type: 'POST',
                    data:{'id':id,'site_id':this.config.site_id,'basketData':this.basketData.sku_props,'iblockId':this.config.iblock_id},
                    success: function(html)
                    {
                        removeBtnLoader (event.target);
                        showModal(html);
                    }
                });
            }
        },

        initPopup: function()
        {
            if(this.config.showZoom == 'Y'){
                var _this = this;
                $(document).on('click','.zoomIt_area',function(){

                    if(document.querySelectorAll('.product-item-detail-slider-image').length > 1  && document.querySelector('[id$="_skudiv"]')) {
                        $('.zoomIt_area').hide();
                        $('.zoomIt_zoomed').hide();
                        _this.openPhotoSwipe();
                    } else {
                        BX.delegate(this.openPhotoSwipe, this);
                    }
                });
                BX.bind(this.node.zoomContainer, 'click', BX.delegate(this.openPhotoSwipe, this));
            }
            else{
                BX.bind(this.node.zoomContainer, 'click', BX.delegate(this.openPhotoSwipe, this));
            }
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

            if (this.errorCode === 0 && this.config.showQuantity && this.canBuy && !this.isGift)
            {
                curValue = this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10);
                if (!isNaN(curValue))
                {
                    curValue += this.stepQuantity;

                    curValue = this.checkQuantityRange(curValue, 'up');

                    if (this.checkQuantity && curValue > this.maxQuantity)
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

        quantityUpModification: function(event)
        {
            let value = +$(event.target).closest('.service-detail-item__content-hidden-counter').find('.service-detail-item__content-hidden-counter-input').val();
            let ratio = +$(event.target).closest('.service-detail-item__content-hidden-counter').find('.service-detail-item__content-hidden-counter-input').data("ratio");
            value = value+ratio;
            $(event.target).closest('.service-detail-item__content-hidden-counter').find('.service-detail-item__content-hidden-counter-input').val(value);
        },

        quantityDownModification: function(event)
        {
            let value = +$(event.target).closest('.service-detail-item__content-hidden-counter').find('.service-detail-item__content-hidden-counter-input').val();
            let ratio = +$(event.target).closest('.service-detail-item__content-hidden-counter').find('.service-detail-item__content-hidden-counter-input').data("ratio");
            value = value-ratio;
            if(value < ratio){
                value = ratio;
            }
            $(event.target).closest('.service-detail-item__content-hidden-counter').find('.service-detail-item__content-hidden-counter-input').val(value);
        },

        quantityDown: function()
        {
            var curValue = 0,
                boolSet = true;

            if (this.errorCode === 0 && this.config.showQuantity && this.canBuy && !this.isGift)
            {
                curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
                if (!isNaN(curValue))
                {
                    curValue -= this.stepQuantity;

                    curValue = this.checkQuantityRange(curValue, 'down');

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

            if (this.errorCode === 0 && this.config.showQuantity)
            {
                if (this.canBuy)
                {
                    curValue = this.isDblQuantity ? parseFloat(this.obQuantity.value) : Math.round(this.obQuantity.value);
                    if (!isNaN(curValue))
                    {
                        curValue = this.checkQuantityRange(curValue);

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

        checkPriceRange: function(quantity)
        {
            if (typeof quantity === 'undefined'|| this.currentPriceMode !== 'Q')
            {
                return;
            }
            var range, found = false;

            for (var i in this.currentQuantityRanges)
            {
                if (this.currentQuantityRanges.hasOwnProperty(i))
                {
                    range = this.currentQuantityRanges[i];

                    if (
                        parseFloat(quantity) >= parseFloat(range.SORT_FROM)
                        && (
                            range.SORT_TO === 'INF'
                            || parseFloat(quantity) <= parseFloat(range.SORT_TO)
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

        refreshProducts: function(data)
        {
            this.recountProducts();
        },

        recountProducts: function()
        {
            if(this.obBasketActions.children[0].classList.contains('btnLoaderCustom')) {
                removeBtnLoader(this.obBasketActions.children[0]);
            }

            if(arBasketID.indexOf(parseInt(this.product.id))>=0)
            {
                $(this.obProduct).find(".service-order__banner-buttons .product-detail-info-block-basket").hide();
                $(this.obProduct).find(".service-order__banner-buttons .product-detail-info-block-path-to-basket").show();
            }else{
                $(this.obProduct).find(".service-order__banner-buttons .product-detail-info-block-basket").show();
                $(this.obProduct).find(".service-order__banner-buttons .product-detail-info-block-path-to-basket").hide();
            }

        },

        getCanBuy: function(arFilter)
        {
            var i,
                j = 0,
                boolOneSearch = true,
                boolSearch = false;

            for (i = 0; i < this.offers.length; i++)
            {
                boolOneSearch = true;

                for (j in arFilter)
                {
                    if (arFilter[j] !== this.offers[i].TREE[j])
                    {
                        boolOneSearch = false;
                        break;
                    }
                }

                if (boolOneSearch)
                {
                    if (this.offers[i].CAN_BUY)
                    {
                        boolSearch = true;
                        break;
                    }
                }
            }

            return boolSearch;
        },

        checkQuantityRange: function(quantity, direction)
        {
            if (typeof quantity === 'undefined'|| this.currentPriceMode !== 'Q')
            {
                return quantity;
            }
            quantity = parseFloat(quantity);

            var nearestQuantity = quantity;
            var range, diffFrom, absDiffFrom, diffTo, absDiffTo, shortestDiff;

            for (var i in this.currentQuantityRanges)
            {
                if (this.currentQuantityRanges.hasOwnProperty(i))
                {
                    range = this.currentQuantityRanges[i];

                    if (
                        parseFloat(quantity) >= parseFloat(range.SORT_FROM)
                        && (
                            range.SORT_TO === 'INF'
                            || parseFloat(quantity) <= parseFloat(range.SORT_TO)
                        )
                    )
                    {
                        nearestQuantity = quantity;
                        break;
                    }
                    else
                    {
                        diffFrom = parseFloat(range.SORT_FROM) - quantity;
                        absDiffFrom = Math.abs(diffFrom);
                        diffTo = parseFloat(range.SORT_TO) - quantity;
                        absDiffTo = Math.abs(diffTo);

                        if (shortestDiff === undefined || shortestDiff > absDiffFrom)
                        {
                            if (
                                direction === undefined
                                || (direction === 'up' && diffFrom > 0)
                                || (direction === 'down' && diffFrom < 0)
                            )
                            {
                                shortestDiff = absDiffFrom;
                                nearestQuantity = parseFloat(range.SORT_FROM);
                            }
                        }

                        if (shortestDiff === undefined || shortestDiff > absDiffTo)
                        {
                            if (
                                direction === undefined
                                || (direction === 'up' && diffFrom > 0)
                                || (direction === 'down' && diffFrom < 0)
                            )
                            {
                                shortestDiff = absDiffTo;
                                nearestQuantity = parseFloat(range.SORT_TO);
                            }
                        }
                    }
                }
            }

            return nearestQuantity;
        },

        setPrice: function()
        {
            var economyInfo = '', price;

            if (this.obQuantity)
            {
                this.checkPriceRange(this.obQuantity.value);
            }

            //this.checkQuantityControls();

            price = this.currentPrices[this.currentPriceSelected];

            if (this.isGift)
            {
                price.PRICE = 0;
                price.DISCOUNT = price.BASE_PRICE;
                price.PERCENT = 100;
            }

            if (this.obPrice.price)
            {

                if (price)
                {
                    BX.adjust(this.obPrice.price, {html: BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true)});
                    this.smallCardNodes.price && BX.adjust(this.smallCardNodes.price, {
                        html: BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true)
                    });
                }
                else
                {
                    BX.adjust(this.obPrice.price, {html: ''});
                    this.smallCardNodes.price && BX.adjust(this.smallCardNodes.price, {html: ''});
                }
                if (price && price.RATIO_PRICE !== price.RATIO_BASE_PRICE)
                {
                    if (this.config.showOldPrice)
                    {
                        this.obPrice.full && BX.adjust(this.obPrice.full, {
                            style: {display: ''},
                            html: BX.Currency.currencyFormat(price.RATIO_BASE_PRICE, price.CURRENCY, true)
                        });
                        this.smallCardNodes.oldPrice && BX.adjust(this.smallCardNodes.oldPrice, {
                            style: {display: ''},
                            html: BX.Currency.currencyFormat(price.RATIO_BASE_PRICE, price.CURRENCY, true)
                        });

                        if (this.obPrice.discount)
                        {
                            economyInfo = BX.message('ECONOMY_INFO_MESSAGE');
                            economyInfo = economyInfo.replace('#ECONOMY#', BX.Currency.currencyFormat(price.RATIO_DISCOUNT, price.CURRENCY, true));

                            let itemSave = BX.findChildren(this.obPrice.discount, {"tag" : "span", "class" : "service-order__banner-economy-amount"}, true, true);

                            BX.adjust(this.obPrice.discount, {
                                style: {display: ''},
                                //html: economyInfo
                            });

                            BX.adjust(itemSave[0], {
                                html: economyInfo
                            });
                        }
                    }

                    if (this.config.showPercent)
                    {
                        this.obPrice.percent && BX.adjust(this.obPrice.percent, {
                            style: {display: ''},
                            html: -price.PERCENT + '%'
                        });
                    }
                }
                else
                {
                    if (this.config.showOldPrice)
                    {
                        this.obPrice.full && BX.adjust(this.obPrice.full, {style: {display: 'none'}, html: ''});
                        this.smallCardNodes.oldPrice && BX.adjust(this.smallCardNodes.oldPrice, {style: {display: 'none'}, html: ''});

                        let itemSave = BX.findChildren(this.obPrice.discount, {"tag" : "span", "class" : "service-order__banner-economy-amount"}, true, true);

                        this.obPrice.discount && BX.adjust(this.obPrice.discount, {
                            style: {display: 'none'},
                            //html: ''
                        });

                        BX.adjust(itemSave[0], {
                            html: ''
                        });
                    }

                    if (this.config.showPercent)
                    {
                        this.obPrice.percent && BX.adjust(this.obPrice.percent, {style: {display: 'none'}, html: ''});
                    }
                }

                if (this.obPrice.total)
                {
                    if (price && this.obQuantity && this.obQuantity.value != this.stepQuantity)
                    {
                        BX.adjust(this.obPrice.total, {
                            html: BX.message('PRICE_TOTAL_PREFIX') + ' <strong>'
                                + BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true)
                                + '</strong>',
                            style: {display: ''}
                        });
                    }
                    else
                    {
                        BX.adjust(this.obPrice.total, {
                            html: '',
                            style: {display: 'none'}
                        });
                    }
                }
            }
            if(this.obAllPrices)
            {
                if (price && price.RATIO_PRICE !== price.RATIO_BASE_PRICE)
                {
                    if (this.config.showPercent)
                    {
                        this.obPrice.percent && BX.adjust(this.obPrice.percent, {
                            style: {display: ''},
                            html: -price.PERCENT + '%'
                        });
                    }
                }
                else
                {
                    if (this.config.showPercent)
                    {
                        this.obPrice.percent && BX.adjust(this.obPrice.percent, {style: {display: 'none'}, html: ''});
                    }
                }
            }
        },

        setBasket: function(basketID)
        {
            if(arBasketID.indexOf(parseInt(this.product.id))>=0)
            {
                $(this.obProduct).find(".product-detail-info-block-basket").hide();
                $(this.obProduct).find(".product-detail-info-block-path-to-basket").show();
            }
        },

        initBasketUrl: function()
        {
            this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);

            this.basketUrl = this.basketUrl.replace('#ID#', this.product.id.toString());
            this.basketParams = {
                'ajax_basket': 'Y'
            };

            if (this.config.showQuantity)
            {
                this.basketParams[this.basketData.quantity] = this.obQuantity.value;
            }
        },

        fillBasketProps: function()
        {
            if (!this.visual.BASKET_PROP_DIV)
                return;

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

        sendToBasket: function()
        {
            if (!this.canBuy)
                return;
            createBtnLoader (this.obBasketActions.children[0]);

            this.initBasketUrl();

            var params = {};

            this.fillBasketProps();
            params['props'] = this.basketData.sku_props;
            //params['price'] = this.currentPrices[this.currentPriceSelected];
            params['action'] = 'add';

            if(this.obQuantity == null)
                params['qnt'] = this.currentPrices[this.currentPriceSelected].MIN_QUANTITY;
            else
                params['qnt'] = this.obQuantity.value;

            params['id'] = this.product.id.toString();

            BX.ajax({
                method: 'POST',
                dataType: 'json',
                url: this.basketData.urlAjax,
                data:{params:JSON.stringify(params)},
                onsuccess:BX.proxy(this.basketResult, this),
            });
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

        basket: function()
        {
            var contentBasketProps = '';

            if (!this.canBuy)
                return;

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
                        text: BX.message('BTN_SEND_PROPS'),
                        events: {
                            click: BX.delegate(this.sendToBasket, this)
                        }
                    })
                ]);
                this.obPopupWin.show();
            }
            else
            {
                this.sendToBasket();
            }
        },

        basketResult: function(arResult)
        {
            var popupContent, popupButtons, productPict;

            if (this.obPopupWin)
            {
                this.obPopupWin.close();
            }

            if (!BX.type.isPlainObject(arResult))
                return;

            // if (arResult.STATUS === 'OK')
            //             // {
            //             //     this.setAnalyticsDataLayer('addToCart');
            //             //     // removeBtnLoader(this.obBasketActions.children[0]);
            //             // }

            if (arResult.STATUS === 'OK' && this.basketMode === 'BUY')
            {
                this.basketRedirect();
            }
            else
            {
                if(this.arParams.ADD_PRODUCT_TO_BASKET_MODE == 'popup') {
                    this.initPopupWindow();
                }

                if (arResult.STATUS === 'OK')
                {
                    BX.onCustomEvent('OnBasketChange');

                    popupContent = '<div class="kit_order_success_show add-to-basket-detail" style="max-width: 429px">\n' +
                        '<div class="popup-window-message-content">\n' +
                        '<svg class="popup-window-icon-check">\n' +
                        '<use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_form"></use>\n' +
                        '</svg>\n' +
                        '<div>\n' +
                        '<div class="popup-window-message-title">' + this.successThanksText + '</div>\n' +
                        '<div style="font-size: 16px;">' + this.successSuccesMessage + '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>';

                    if (this.config.showClosePopup)
                    {
                        popupButtons = [
                            new BasketButton({
                                text: BX.message('BTN_MESSAGE_BASKET_REDIRECT'),
                                events: {
                                    click: BX.delegate(this.basketRedirect, this)
                                },
                                style: {marginRight: '10px'}
                            }),
                            new BasketButton({
                                text: BX.message('BTN_MESSAGE_CLOSE_POPUP'),
                                events: {
                                    click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                                },
                            })
                        ];
                    }
                    else
                    {
                        popupButtons = [
                            new BasketButton({
                                text: BX.message('BTN_MESSAGE_BASKET_REDIRECT'),
                                events: {
                                    click: BX.delegate(this.basketRedirect, this)
                                },
                            })
                        ];
                    }
                }
                else
                {
                    popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
                        + (arResult.MESSAGE ? arResult.MESSAGE : BX.message('BASKET_UNKNOWN_ERROR'))
                        + '</p></div>';
                    popupButtons = [
                        new BasketButton({
                            text: BX.message('BTN_MESSAGE_CLOSE'),
                            events: {
                                click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                            }
                        })
                    ];
                }

                if(!(this.obPopupWin == null || this.obPopupWin == 'undefined') ) {
                    this.obPopupWin.setTitleBar(arResult.STATUS === 'OK' ? BX.message('TITLE_SUCCESSFUL') : BX.message('TITLE_ERROR'));
                    this.obPopupWin.setContent(popupContent);
                    this.obPopupWin.setButtons(popupButtons);
                    this.obPopupWin.show();
                }
            }
        },

        basketRedirect: function()
        {
            location.href = (this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('BASKET_URL'));
        },

        add2BasketModification: function(event)
        {
            this.basketModificationMode = 'ADD';
            this.basketModification(event);
        },

        buyBasketModification: function(event)
        {
            this.basketModificationMode = 'BUY';
            this.basketModification(event);
        },

        initPopupWindow: function()
        {
            if (this.obPopupWin)
                return;

            this.obPopupWin = BX.PopupWindowManager.create('CatalogElementBasket_' + this.visual.ID, null, {
                autoHide: false,
                offsetLeft: 0,
                offsetTop: 0,
                overlay: true,
                closeByEsc: true,
                titleBar: true,
                closeIcon: true,
                contentColor: 'white',
                className: this.config.templateTheme ? 'bx-' + this.config.templateTheme : ''
            });
        },

        incViewedCounter: function()
        {
            if (this.currentIsSet && !this.updateViewedCount)
            {

                this.viewedCounter.params.PRODUCT_ID = this.product.id;
                this.viewedCounter.params.PARENT_ID = this.product.id;

                this.viewedCounter.params.SITE_ID = BX.message('SITE_ID');
                this.updateViewedCount = true;

                BX.ajax.post(
                    this.viewedCounter.path,
                    this.viewedCounter.params,
                    BX.delegate(function()
                    {
                        this.updateViewedCount = false;
                    }, this)
                );
            }
        },

        allowViewedCount: function(update)
        {
            this.currentIsSet = true;

            if (update)
            {
                this.incViewedCounter();
            }
        },

        fixFontCheck: function()
        {
            if (BX.type.isDomNode(this.obPrice.price))
            {
                BX.FixFontSize && BX.FixFontSize.init({
                    objList: [{
                        node: this.obPrice.price,
                        maxFontSize: 28,
                        smallestValue: false,
                        scaleBy: this.obPrice.price.parentNode
                    }],
                    onAdaptiveResize: true
                });

            }
        },

    };

})(window);
