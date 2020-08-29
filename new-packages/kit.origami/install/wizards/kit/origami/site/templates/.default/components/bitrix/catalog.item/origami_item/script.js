(function (window){
	'use strict';

	if (window.JCCatalogItemTab)
		return;

	var BasketButton = function(params)
	{
		BasketButton.superclass.constructor.apply(this, arguments);
		this.buttonNode = BX.create('span', {
			props: {className: 'simple_btn ' + (params.className || ''), id: this.id},
			style: typeof params.style === 'object' ? params.style : {},
			text: params.text,
			events: this.contextEvents
		});

		if (BX.browser.IsIE())
		{
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
		this.useCompare = false;
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

		this.compareData = {
			compareUrl: '',
			compareDeleteUrl: '',
			comparePath: ''
		};

		this.wishData = {
			wishUrl: '',
		};

		this.defaultPict = {
			pict: null,
			secondPict: null
		};


		this.touch = null;

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

		this.offers = [];
		this.offerNum = 0;
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
		this.obWish = null;
		this.obOc = null;
		this.obUrl = null;
		this.obAllPrices = null;
		this.siteID = 's1';

		this.obPopupWin = null;
		this.basketUrl = '';
		this.basketParams = {};
		this.isTouchDevice = BX.hasClass(document.documentElement, 'bx-touch');
		this.hoverTimer = null;
		this.hoverStateChangeForbidden = false;
		this.mouseX = null;
		this.mouseY = null;
		this.wishes = {};

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
			this.showSkuProps = arParams.SHOW_SKU_PROPS;
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
			this.useCompare = arParams.DISPLAY_COMPARE;
			this.fullDisplayMode = arParams.PRODUCT_DISPLAY_MODE === 'Y';
			this.bigData = arParams.BIG_DATA;
			this.viewMode = arParams.VIEW_MODE || '';
			this.templateTheme = arParams.TEMPLATE_THEME || '';
			this.useEnhancedEcommerce = arParams.USE_ENHANCED_ECOMMERCE === 'Y';
			this.dataLayerName = arParams.DATA_LAYER_NAME;
			this.brandProperty = arParams.BRAND_PROPERTY;

			this.visual = arParams.VISUAL;

			this.wishes = arParams.WISHES;

			if (arParams.PRODUCT.ALL_PRICES)
			{
				this.allPrices = arParams.PRODUCT.ALL_PRICES;
			}

			switch (this.productType)
			{
				case 0: // no catalog
				case 1: // product
				case 2: // set
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

						if (arParams.PRODUCT.MORE_PHOTO_COUNT)
						{
							this.product.morePhotoCount = arParams.PRODUCT.MORE_PHOTO_COUNT;
							this.product.morePhoto = arParams.PRODUCT.MORE_PHOTO;
						}

						if (arParams.PRODUCT.RCM_ID)
						{
							this.product.rcmId = arParams.PRODUCT.RCM_ID;
						}

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
					else
					{
						this.errorCode = -1;
					}

					break;
				case 3: // sku
					if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object')
					{
						this.product.name = arParams.PRODUCT.NAME;
						this.product.id = arParams.PRODUCT.ID;
						this.product.iblock_id = arParams.PRODUCT.IBLOCK_ID;
						this.product.DETAIL_PAGE_URL = arParams.PRODUCT.DETAIL_PAGE_URL;
						this.product.morePhotoCount = arParams.PRODUCT.MORE_PHOTO_COUNT;
						this.product.morePhoto = arParams.PRODUCT.MORE_PHOTO;

						if (arParams.PRODUCT.RCM_ID)
						{
							this.product.rcmId = arParams.PRODUCT.RCM_ID;
						}
					}

					if (arParams.OFFERS && BX.type.isArray(arParams.OFFERS))
					{
						this.offers = arParams.OFFERS;
						this.offerNum = 0;

						if (arParams.OFFER_SELECTED)
						{
							this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
						}

						if (isNaN(this.offerNum))
						{
							this.offerNum = 0;
						}

						if (arParams.TREE_PROPS)
						{
							this.treeProps = arParams.TREE_PROPS;
						}

						if (arParams.DEFAULT_PICTURE)
						{
							this.defaultPict.pict = arParams.DEFAULT_PICTURE.PICTURE;
							this.defaultPict.secondPict = arParams.DEFAULT_PICTURE.PICTURE_SECOND;
						}
					}

					break;
				default:
					this.errorCode = -1;
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

				if (3 === this.productType)
				{
					if (arParams.BASKET.SKU_PROPS)
					{
						this.basketData.sku_props = arParams.BASKET.SKU_PROPS;
					}
				}

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

			if (this.useCompare)
			{

				if (arParams.COMPARE && typeof arParams.COMPARE === 'object')
				{
					if (arParams.COMPARE.COMPARE_PATH)
					{
						this.compareData.comparePath = arParams.COMPARE.COMPARE_PATH;
					}

					if (arParams.COMPARE.COMPARE_URL_TEMPLATE)
					{
						this.compareData.compareUrl = arParams.COMPARE.COMPARE_URL_TEMPLATE;
					}
					else
					{
						this.useCompare = false;
					}

					if (arParams.COMPARE.COMPARE_DELETE_URL_TEMPLATE)
					{
						this.compareData.compareDeleteUrl = arParams.COMPARE.COMPARE_DELETE_URL_TEMPLATE;
					}
					else
					{
						this.useCompare = false;
					}
				}
				else
				{
					this.useCompare = false;
				}
			}
			this.wishData.wishUrl = arParams.WISH.WISH_URL_TEMPLATE;

			this.addProductToBasketMode = arParams.ADD_PRODUCT_TO_BASKET_MODE;
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

			this.obWish = BX(this.visual.WISH_LINK_ID);


			if (this.obWish)
			{
				BX.bind(this.obWish, 'click', BX.proxy(this.wish, this));
				this.setWish();
			}

			this.obAllPrices = BX(this.visual.ALL_PRICES);

			this.obProduct = BX(this.visual.ID);
			if (!this.obProduct)
			{
				this.errorCode = -1;
			}

			this.obPict = BX(this.visual.PICT_ID);
			if (!this.obPict)
			{
				//this.errorCode = -2;
			}

			if (this.secondPict && this.visual.SECOND_PICT_ID)
			{
				this.obSecondPict = BX(this.visual.SECOND_PICT_ID);
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
				this.obUrl = this.obProduct.querySelectorAll('.product-card-inner__img-link, .product-card-inner__title-link');
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
					this.obQuantityLimit.value = this.obQuantityLimit.all.querySelector('[data-entity="quantity-limit-value"]');
					if (!this.obQuantityLimit.value)
					{
						this.obQuantityLimit.all = null;
					}
				}
			}

			if (this.productType === 3 && this.fullDisplayMode)
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

			if (this.showSkuProps)
			{
				if (this.visual.DISPLAY_PROP_DIV)
				{
					this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
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

				switch (this.productType)
				{
					case 0: // no catalog
					case 1: // product
					case 2: // set

						this.checkQuantityControls();

						/*var check = false;
						for (var key in this.wishes)
						{
							if(this.wishes[key] == this.id)
							{
								check = true;
							}
						}
						this.setWished(check);*/

						break;
					case 3: // sku
						if (this.offers.length > 0)
						{
							treeItems = BX.findChildren(this.obTree, {class: 'product-card-inner__option-item'}, true);

							if (treeItems && treeItems.length)
							{
								for (i = 0; i < treeItems.length; i++)
								{
									BX.bind(treeItems[i], 'click', BX.delegate(this.selectOfferProp, this));
								}
							}
							this.setCurrent();
						}

						break;
				}

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

				if (this.useCompare)
				{
					this.obCompare = BX(this.visual.COMPARE_LINK_ID);
					if (this.obCompare)
					{
						BX.bind(this.obCompare, 'click', BX.proxy(this.compare, this));
					}

					BX.addCustomEvent('onCatalogDeleteCompare', BX.proxy(this.checkDeletedCompare, this));
					this.setCompare();
				}

				BX.addCustomEvent(window, 'OnBasketChangeAfter', BX.proxy(this.refreshProducts, this));

			}
		},

		setAnalyticsDataLayer: function(action)
		{
			if (!this.useEnhancedEcommerce || !this.dataLayerName)
				return;

			var item = {},
				info = {},
				variants = [],
				i, k, j, propId, skuId, propValues;

			switch (this.productType)
			{
				case 0: //no catalog
				case 1: //product
				case 2: //set
					item = {
						'id': this.product.id,
						'name': this.product.name,
						'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
						'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty
					};
					break;
				case 3: //sku
					for (i in this.offers[this.offerNum].TREE)
					{
						if (this.offers[this.offerNum].TREE.hasOwnProperty(i))
						{
							propId = i.substring(5);
							skuId = this.offers[this.offerNum].TREE[i];

							for (k in this.treeProps)
							{
								if (this.treeProps.hasOwnProperty(k) && this.treeProps[k].ID == propId)
								{
									for (j in this.treeProps[k].VALUES)
									{
										propValues = this.treeProps[k].VALUES[j];
										if (propValues.ID == skuId)
										{
											variants.push(propValues.NAME);
											break;
										}
									}

								}
							}
						}
					}

					item = {
						'id': this.offers[this.offerNum].ID,
						'name': this.offers[this.offerNum].NAME,
						'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
						'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty,
						'variant': variants.join('/')
					};
					break;
			}

			switch (action)
			{
				case 'addToCart':
					info = {
						'event': 'addToCart',
						'ecommerce': {
							'currencyCode': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '',
							'add': {
								'products': [{
									'name': item.name || '',
									'id': item.id || '',
									'price': item.price || 0,
									'brand': item.brand || '',
									'category': item.category || '',
									'variant': item.variant || '',
									'quantity': this.showQuantity && this.obQuantity ? this.obQuantity.value : 1
								}]
							}
						}
					};
					break;
			}

			window[this.dataLayerName] = window[this.dataLayerName] || [];
			window[this.dataLayerName].push(info);
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

		rememberProductRecommendation: function()
		{
			// save to RCM_PRODUCT_LOG
			var cookieName = BX.cookie_prefix + '_RCM_PRODUCT_LOG',
				cookie = this.getCookie(cookieName),
				itemFound = false;

			var cItems = [],
				cItem;

			if (cookie)
			{
				cItems = cookie.split('.');
			}

			var i = cItems.length;

			while (i--)
			{
				cItem = cItems[i].split('-');

				if (cItem[0] == this.product.id)
				{
					// it's already in recommendations, update the date
					cItem = cItems[i].split('-');

					// update rcmId and date
					cItem[1] = this.product.rcmId;
					cItem[2] = BX.current_server_time;

					cItems[i] = cItem.join('-');
					itemFound = true;
				}
				else
				{
					if ((BX.current_server_time - cItem[2]) > 3600 * 24 * 30)
					{
						cItems.splice(i, 1);
					}
				}
			}

			if (!itemFound)
			{
				// add recommendation
				cItems.push([this.product.id, this.product.rcmId, BX.current_server_time].join('-'));
			}

			// serialize
			var plNewCookie = cItems.join('.'),
				cookieDate = new Date(new Date().getTime() + 1000 * 3600 * 24 * 365 * 10).toUTCString();

			document.cookie = cookieName + "=" + plNewCookie + "; path=/; expires=" + cookieDate + "; domain=" + BX.cookie_domain;
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

		quantitySet: function(index)
		{
			var resetQuantity, strLimit;

			var newOffer = this.offers[index],
				oldOffer = this.offers[this.offerNum];

			if (this.errorCode === 0)
			{
				this.canBuy = newOffer.CAN_BUY;

				this.currentPriceMode = newOffer.ITEM_PRICE_MODE;
				this.currentPrices = newOffer.ITEM_PRICES;
				this.currentPriceSelected = newOffer.ITEM_PRICE_SELECTED;
				this.currentQuantityRanges = newOffer.ITEM_QUANTITY_RANGES;
				this.currentQuantityRangeSelected = newOffer.ITEM_QUANTITY_RANGE_SELECTED;

				if (this.canBuy)
				{
					if (this.blockNodes.quantity)
					{
						BX.style(this.blockNodes.quantity, 'display', '');
					}

					if (this.obBasketActions)
					{
						BX.style(this.obBasketActions, 'display', '');
					}

					if (this.obNotAvail)
					{
						BX.style(this.obNotAvail, 'display', 'none');
					}

					if (this.obSubscribe)
					{
						BX.style(this.obSubscribe, 'display', 'none');
					}

					if (this.obOc)
					{
						BX.style(this.obOc, 'display', '');
					}
				}
				else
				{
					if (this.blockNodes.quantity)
					{
						BX.style(this.blockNodes.quantity, 'display', 'none');
					}

					if (this.obBasketActions)
					{
						BX.style(this.obBasketActions, 'display', 'none');
					}

					if (this.obNotAvail)
					{
						BX.style(this.obNotAvail, 'display', '');
					}

					if (this.obSubscribe)
					{
						if (newOffer.CATALOG_SUBSCRIBE === 'Y')
						{
							BX.style(this.obSubscribe, 'display', '');
							this.obSubscribe.setAttribute('data-item', newOffer.ID);
							BX(this.visual.SUBSCRIBE_ID + '_hidden').click();

						}
						else
						{
							BX.style(this.obSubscribe, 'display', 'none');
						}
					}

					if (this.obOc)
					{
						BX.style(this.obOc, 'display', 'none');
					}
				}

				this.isDblQuantity = newOffer.QUANTITY_FLOAT;
				this.checkQuantity = newOffer.CHECK_QUANTITY;

				if (this.isDblQuantity)
				{
					this.stepQuantity = Math.round(parseFloat(newOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
					this.maxQuantity = parseFloat(newOffer.MAX_QUANTITY);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				}
				else
				{
					this.stepQuantity = parseInt(newOffer.STEP_QUANTITY, 10);
					this.maxQuantity = parseInt(newOffer.MAX_QUANTITY, 10);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseInt(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				}

				if (this.showQuantity)
				{
					var isDifferentMinQuantity = oldOffer.ITEM_PRICES.length
						&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED]
						&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED].MIN_QUANTITY != this.minQuantity;

					if (this.isDblQuantity)
					{
						resetQuantity = Math.round(parseFloat(oldOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor !== this.stepQuantity
							|| isDifferentMinQuantity
							|| oldOffer.MEASURE !== newOffer.MEASURE
							|| (
								this.checkQuantity
								&& parseFloat(oldOffer.MAX_QUANTITY) > this.maxQuantity
								&& parseFloat(this.obQuantity.value) > this.maxQuantity
							);
					}
					else
					{
						resetQuantity = parseInt(oldOffer.STEP_QUANTITY, 10) !== this.stepQuantity
							|| isDifferentMinQuantity
							|| oldOffer.MEASURE !== newOffer.MEASURE
							|| (
								this.checkQuantity
								&& parseInt(oldOffer.MAX_QUANTITY, 10) > this.maxQuantity
								&& parseInt(this.obQuantity.value, 10) > this.maxQuantity
							);
					}
					if(this.obQuantity)
					{
						this.obQuantity.disabled = !this.canBuy;
					}


					if (resetQuantity)
					{
						this.obQuantity.value = this.minQuantity;
					}

					if (this.obMeasure)
					{
						if (newOffer.MEASURE)
						{
							BX.adjust(this.obMeasure, {html: newOffer.MEASURE});
						}
						else
						{
							BX.adjust(this.obMeasure, {html: ''});
						}
					}
				}
				if (this.obQuantityLimit.all)
				{
					if (!this.checkQuantity)
					{
						BX.adjust(this.obQuantityLimit.value, {html: ''});
						BX.adjust(this.obQuantityLimit.all, {style: {display: 'none'}});
					}
					else
					{
						if (this.showMaxQuantity === 'M')
						{
							if(this.maxQuantity == 0)
							{
								strLimit = '<span class="product-card-inner__quantity product-card-inner__quantity--none">'+
									BX.message('RELATIVE_QUANTITY_NO')

									+'</span>';

							}
							else
							{
								if(this.maxQuantity / this.stepQuantity >= this.relativeQuantityFactor)
								{
									strLimit = '<span class="product-card-inner__quantity product-card-inner__quantity--lot">'+
										BX.message('RELATIVE_QUANTITY_MANY')+

										'</span>';

								}
								else
								{
									strLimit = '<span class="product-card-inner__quantity product-card-inner__quantity--few">'+
										BX.message('RELATIVE_QUANTITY_FEW')+

										'</span>';

								}
							}
						}
						else
						{
							if(this.maxQuantity == 0)
							{
								strLimit = '<span class="product-card-inner__quantity product-card-inner__quantity--none">'+
									this.maxQuantity;
								if (newOffer.MEASURE)
								{
									strLimit += (' ' + newOffer.MEASURE);
								}
								strLimit +='</span>';
							}
							else
							{
								if(this.maxQuantity / this.stepQuantity >= this.relativeQuantityFactor)
								{
									strLimit = '<span class="product-card-inner__quantity product-card-inner__quantity--lot">'+
										this.maxQuantity;
									if (newOffer.MEASURE)
									{
										strLimit += (' ' + newOffer.MEASURE);
									}
									strLimit +='</span>';
								}
								else
								{
									strLimit = '<span class="product-card-inner__quantity product-card-inner__quantity--few">'+
										this.maxQuantity;
									if (newOffer.MEASURE)
									{
										strLimit += (' ' + newOffer.MEASURE);
									}
									strLimit +='</span>';
								}
							}
						}
						BX.adjust(this.obQuantityLimit.value, {html: strLimit});
						BX.adjust(this.obQuantityLimit.all, {style: {display: ''}});
					}
				}
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

		selectOfferProp: function()
		{
			var i = 0,
				value = '',
				strTreeValue = '',
				arTreeItem = [],
				rowItems = null,
				target = BX.proxy_context;

			if (target && target.hasAttribute('data-treevalue'))
			{
				if (BX.hasClass(target, 'selected'))
					return;

				strTreeValue = target.getAttribute('data-treevalue');
				arTreeItem = strTreeValue.split('_');
				if (this.searchOfferPropIndex(arTreeItem[0], arTreeItem[1]))
				{
					rowItems = BX.findChildren(target.parentNode, {class: 'product-card-inner__option-item'}, false);
					if (rowItems && 0 < rowItems.length)
					{
						for (i = 0; i < rowItems.length; i++)
						{
							value = rowItems[i].getAttribute('data-onevalue');

							if (value === arTreeItem[1])
							{
								BX.addClass(rowItems[i], 'checked');
								$(rowItems[i]).find('input').attr('checked','');
							}
							else
							{
								BX.removeClass(rowItems[i], 'checked');
								$(rowItems[i]).find('input').removeAttr('checked');
							}
						}
					}
				}

				if(arBasketID.indexOf(parseInt(this.offers[this.offerNum].ID)) != -1)
				{
					$(this.obProduct).find(".product-card-inner__buy").hide();
					$(this.obProduct).find(".product-card-inner__product-basket").show();
				}else{
					$(this.obProduct).find(".product-card-inner__buy").show();
					$(this.obProduct).find(".product-card-inner__product-basket").hide();
				}
			}
		},

		searchOfferPropIndex: function(strPropID, strPropValue)
		{
			var strName = '',
				arShowValues = false,
				i, j,
				arCanBuyValues = [],
				allValues = [],
				index = -1,
				arFilter = {},
				tmpFilter = [];

			for (i = 0; i < this.treeProps.length; i++)
			{
				if (this.treeProps[i].ID === strPropID)
				{
					index = i;
					break;
				}
			}

			if (-1 < index)
			{
				for (i = 0; i < index; i++)
				{
					strName = 'PROP_'+this.treeProps[i].ID;
					arFilter[strName] = this.selectedValues[strName];
				}
				strName = 'PROP_'+this.treeProps[index].ID;
				arShowValues = this.getRowValues(arFilter, strName);
				if (!arShowValues)
				{
					return false;
				}
				if (!BX.util.in_array(strPropValue, arShowValues))
				{
					return false;
				}
				arFilter[strName] = strPropValue;
				for (i = index+1; i < this.treeProps.length; i++)
				{
					strName = 'PROP_'+this.treeProps[i].ID;
					arShowValues = this.getRowValues(arFilter, strName);
					if (!arShowValues)
					{
						return false;
					}
					allValues = [];
					if (this.showAbsent)
					{
						arCanBuyValues = [];
						tmpFilter = [];
						tmpFilter = BX.clone(arFilter, true);
						for (j = 0; j < arShowValues.length; j++)
						{
							tmpFilter[strName] = arShowValues[j];
							allValues[allValues.length] = arShowValues[j];
							if (this.getCanBuy(tmpFilter))
								arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
					else
					{
						arCanBuyValues = arShowValues;
					}
					if (this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues))
					{
						arFilter[strName] = this.selectedValues[strName];
					}
					else
					{
						if (this.showAbsent)
							arFilter[strName] = (arCanBuyValues.length > 0 ? arCanBuyValues[0] : allValues[0]);
						else
							arFilter[strName] = arCanBuyValues[0];
					}
					this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
				}
				this.selectedValues = arFilter;
				this.changeInfo();
			}
			return true;
		},

		updateRow: function(intNumber, activeID, showID, canBuyID)
		{
			var i = 0,
				value = '',
				isCurrent = false,
				rowItems = null;
			if(this.obTree)
			{
				var lineContainer = this.obTree.querySelectorAll('[data-entity="sku-line-block"]'),
					listContainer;
				if (intNumber > -1 && intNumber < lineContainer.length)
				{
					listContainer = lineContainer[intNumber];
					rowItems = BX.findChildren(listContainer, {class: 'product-card-inner__option-item'}, false);
					if (rowItems && 0 < rowItems.length)
					{
						if(rowItems.length == 1)
						{
							listContainer.parentElement.style.display = 'none';
						}
						for (i = 0; i < rowItems.length; i++)
						{
							value = rowItems[i].getAttribute('data-onevalue');
							isCurrent = value === activeID;
							if (isCurrent)
							{
								BX.addClass(rowItems[i], 'checked');
								$(rowItems[i]).find('input').attr('checked','');
							}
							else
							{
								BX.removeClass(rowItems[i], 'checked');
								$(rowItems[i]).find('input').removeAttr('checked');
							}
							if (BX.util.in_array(value, canBuyID))
							{
								$(rowItems[i]).find('input').removeAttr('disabled');
								BX.removeClass(rowItems[i], 'disabled');
							}
							else
							{
								BX.addClass(rowItems[i], 'disabled');
								$(rowItems[i]).find('input').attr('disabled','');
							}

							rowItems[i].style.display = BX.util.in_array(value, showID) ? '' : 'none';

							if (isCurrent)
							{
								lineContainer[intNumber].style.display = (value == 0 && canBuyID.length == 1) ? 'none' : '';
							}
						}
					}
				}
			}
		},

		getRowValues: function(arFilter, index)
		{
			var i = 0,
				j,
				arValues = [],
				boolSearch = false,
				boolOneSearch = true;

			if (0 === arFilter.length)
			{
				for (i = 0; i < this.offers.length; i++)
				{
					if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
					{
						arValues[arValues.length] = this.offers[i].TREE[index];
					}
				}
				boolSearch = true;
			}
			else
			{
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
						if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
						{
							arValues[arValues.length] = this.offers[i].TREE[index];
						}
						boolSearch = true;
					}
				}
			}
			return (boolSearch ? arValues : false);
		},

		getCanBuy: function(arFilter)
		{
			var i, j,
				boolSearch = false,
				boolOneSearch = true;

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

		setCurrent: function()
		{
			var i,
				j = 0,
				arCanBuyValues = [],
				strName = '',
				arShowValues = false,
				arFilter = {},
				tmpFilter = [],
				current = this.offers[this.offerNum].TREE;

			for (i = 0; i < this.treeProps.length; i++)
			{
				strName = 'PROP_'+this.treeProps[i].ID;
				arShowValues = this.getRowValues(arFilter, strName);
				if (!arShowValues)
				{
					break;
				}
				if (BX.util.in_array(current[strName], arShowValues))
				{
					arFilter[strName] = current[strName];
				}
				else
				{
					arFilter[strName] = arShowValues[0];
					this.offerNum = 0;
				}
				if (this.showAbsent)
				{
					arCanBuyValues = [];
					tmpFilter = [];
					tmpFilter = BX.clone(arFilter, true);
					for (j = 0; j < arShowValues.length; j++)
					{
						tmpFilter[strName] = arShowValues[j];
						if (this.getCanBuy(tmpFilter))
						{
							arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
				}
				else
				{
					arCanBuyValues = arShowValues;
				}
				this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}

			this.selectedValues = arFilter;


			this.changeInfo();
			//var time = performance.now();
			//$("#check_offer_basket_" + this.product.id).addClass("check_offer_basket_pr_" + this.offers[this.offerNum].ID);

		},

		changeInfo: function()
		{
			var i, j,
				index = -1,
				boolOneSearch = true,
				quantityChanged;

			for (i = 0; i < this.offers.length; i++)
			{
				boolOneSearch = true;
				for (j in this.selectedValues)
				{
					if (this.selectedValues[j] !== this.offers[i].TREE[j])
					{
						boolOneSearch = false;
						break;
					}
				}
				if (boolOneSearch)
				{
					index = i;
					break;
				}
			}

			if (index > -1)
			{
				if (this.obPict && !BX.hasClass(this.obPict, "lazy"))
				{
					if(this.offers[index].PREVIEW_PICTURE.SRC)
					{
						BX.adjust(this.obPict, {props: {
								src: this.offers[index].PREVIEW_PICTURE.SRC,
						}});
						/*BX.cleanNode(this.obPict);
						this.obPict.appendChild(
							BX.create('IMG', {
								props: {
									src: this.offers[index].PREVIEW_PICTURE.SRC,
									alt: '',
									title: ''
								},
							})
						);*/
					}
					else{
						if(this.offers[index].MORE_PHOTO !== undefined)
						{
							BX.adjust(this.obPict, {props: {
									src: this.offers[index].MORE_PHOTO[0].SRC,
							}});
							/*BX.cleanNode(this.obPict);
							this.obPict.appendChild(
								BX.create('IMG', {
									props:
										{
											src: this.offers[index].MORE_PHOTO[0].SRC,
											alt: '',
											title: ''
										},
								})
							);*/
						}
					}
				}

				if (this.obWish) {
                    if (this.offers[index].CAN_BUY) {
                        this.obWish.style.display = 'inline-block';
                    } else {
                        this.obWish.style.display = 'none';
                    }
                }


				if(this.offers[index].URL)
				{
					for(let i = 0; this.obUrl.length > i; i++) {
						this.obUrl[i].setAttribute('href', this.offers[index].URL);
					}
				}

				if (this.showSkuProps && this.obSkuProps)
				{
					if (this.offers[index].DISPLAY_PROPERTIES.length)
					{
						BX.adjust(this.obSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES});
					}
					else
					{
						BX.adjust(this.obSkuProps, {style: {display: 'none'}, html: ''});
					}
				}

				this.quantitySet(index);
				this.setPrice();

				if(this.obAllPrices)
				{
					var keys = Object.keys(this.allPrices[this.offers[index]['ID']]);
					if (keys.length > 1)
					{
						this.obAllPrices.style.display = 'block';
						$(this.obAllPrices).find('p[data-price-id]').hide();
						$(this.obAllPrices).find('p[data-oldprice-id]').hide();
						$(this.obAllPrices).find('div[data-discount-id]').hide();

						$(this.obAllPrices).find('div[data-id]').hide();

						for (var id in this.allPrices[this.offers[index]['ID']])
						{
							let strPrice = this.allPrices[this.offers[index]['ID']][id]['PRINT_PRICE'];
							let strDiscount = this.allPrices[this.offers[index]['ID']][id]['PRINT_DISCOUNT'];
							let strOldPrice = this.allPrices[this.offers[index]['ID']][id]['PRINT_BASE_PRICE'];
							let vDiscount = +this.allPrices[this.offers[index]['ID']][id]['DISCOUNT'];

							$(this.obAllPrices).find('p[data-price-id="' + id + '"]').html(strPrice);
							$(this.obAllPrices).find('p[data-oldprice-id="' + id + '"]').html(strOldPrice);
							$(this.obAllPrices).find('div[data-discount-id="' + id + '"] span').html(strDiscount);

							$(this.obAllPrices).find('div[data-id="'+ id +'"]').show();

							$(this.obAllPrices).find('p[data-price-id="' + id + '"]').show();
							if(vDiscount)
							{
								$(this.obAllPrices).find('p[data-oldprice-id="' + id + '"]').show();
								$(this.obAllPrices).find('div[data-discount-id="' + id + '"]').show();
							}
						}
					}
					else
					{
						this.obAllPrices.style.display = 'none';
					}
				}
				this.setCompared(this.offers[index].COMPARED);
				if(this.wishes.length > 0)
				{
					var check = false;
					for (var key in this.wishes)
					{
						if (this.wishes[key] == this.offers[index]['ID'])
						{
							check = true;
						}
					}

					this.setWished(check);
				}

				this.offerNum = index;
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

		checkQuantityControls: function()
		{
			if (!this.obQuantity)
				return;

			var reachedTopLimit = this.checkQuantity && parseFloat(this.obQuantity.value) + this.stepQuantity > this.maxQuantity,
				reachedBottomLimit = parseFloat(this.obQuantity.value) - this.stepQuantity < this.minQuantity;

			if (reachedTopLimit)
			{
				BX.addClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
			}
			else if (BX.hasClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled'))
			{
				BX.removeClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
			}

			if (reachedBottomLimit)
			{
				BX.addClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
			}
			else if (BX.hasClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled'))
			{
				BX.removeClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
			}

			if (reachedTopLimit && reachedBottomLimit)
			{
				BX.adjust(this.obProduct.querySelector('[data-entity="quantity-block"]'), {
					style: {display: 'none'},
				});
				//this.obQuantity.setAttribute('disabled', 'disabled');
			}
			else
			{
				BX.adjust(this.obProduct.querySelector('[data-entity="quantity-block"]'), {
					style: {display: ''},
				});
				//this.obQuant
                // ity.removeAttribute('disabled');
			}

			if(this.checkQuantity && !this.canBuy){
				BX.adjust(this.obProduct.querySelector('[data-entity="quantity-block"]'), {
					style: {display: 'none'},
				});
			}

		},

		setPrice: function()
		{
			var obData, price;

			if (this.obQuantity)
			{
				this.checkPriceRange(this.obQuantity.value);
			}

			this.checkQuantityControls();

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

						let itemSave = BX.findChildren(this.obPriceSave, {"tag" : "span", "class" : "product-card-inner__saving-value"}, true, true);

						BX.adjust(this.obPriceSave, {
							style: {display: ''},
						});

						BX.adjust(itemSave[0], {
							html: str2
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

		compare: function(event)
		{
			var checkbox = this.obCompare,
				target = BX.getEventTarget(event),
				checked = true;

			if (checkbox)
			{
				checked = target === checkbox ? checkbox.checked : !checkbox.checked;
			}
			if(BX.hasClass(checkbox,'active')){
				checked = false;
			}

			var url = checked ? this.compareData.compareUrl : this.compareData.compareDeleteUrl,
				compareLink;

			if (url)
			{
				if (target !== checkbox)
				{
					BX.PreventDefault(event);
					this.setCompared(checked);
				}
				url = url.replace('&amp;','&');

				switch (this.productType)
				{
					case 0: // no catalog
					case 1: // product
					case 2: // set
						compareLink = url.replace('#ID#', this.product.id.toString());
						break;
					case 3: // sku
						compareLink = url.replace('#ID#', this.offers[this.offerNum].ID);
						break;
				}

				BX.ajax({
					method: 'POST',
					dataType: checked ? 'json' : 'html',
					url: compareLink + (compareLink.indexOf('?') !== -1 ? '&' : '?') + 'ajax_action=Y',
					onsuccess: checked
						? BX.proxy(this.compareResult, this)
						: BX.proxy(this.compareDeleteResult, this)
				});
			}
		},

		wish: function(event)
		{
			var check = 0;
			var _this = this;
			if(BX.hasClass(this.obWish, 'active'))
			{
				check = 1;
			}

			var url =  this.wishData.wishUrl;

			if (url)
			{
				var params = {};

				this.fillBasketProps();
				params['props'] = this.basketData.sku_props;
				if(this.obQuantity == null)
					params['qnt'] = this.currentPrices[this.currentPriceSelected].MIN_QUANTITY;
				else
					params['qnt'] = this.obQuantity.value;

				if (check == 1)
				{

					params['action'] = 'remove';
				}
				else
				{
					params['action'] = 'add';
				}

				switch (this.productType)
				{
					case 0: // no catalog
					case 1: // product
					case 2: // set
						params['id'] = this.product.id.toString();
						break;
					case 3: // sku
						params['id'] = this.offers[this.offerNum].ID;
						break;
				}

				BX.ajax({
					method: 'POST',
					dataType: 'json',
					url: url,
					data:{params:JSON.stringify(params)},
					onsuccess:function(data)
					{
						if (check == 1)
						{
							BX.removeClass(_this.obWish, 'active');
							_this.obWish.title = BX.message('WISH_TO');
								_this.deleteWishID(params['id']);
						}
						else
						{
							BX.addClass(_this.obWish, 'active');
							_this.obWish.title = BX.message('WISH_IN');
								_this.addWishID(params['id']);
						}

						BX.onCustomEvent('OnBasketChange');
					},
				});
			}
		},

		compareResult: function(result) {
			var popupContent, popupButtons;

			if (this.obPopupWin) {
				this.obPopupWin.close();
			}

			if (!BX.type.isPlainObject(result))
				return;

			if (result.STATUS !== 'OK') {
				this.initPopupWindow();
			}

			if (this.offers.length > 0) {
				this.offers[this.offerNum].COMPARED = result.STATUS === 'OK';
			}

			if (result.STATUS === 'OK') {
				BX.onCustomEvent('OnCompareChange');

				popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
					+ BX.message('COMPARE_MESSAGE_OK')
					+ '</p></div>';

				if (this.showClosePopup) {
					popupButtons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
							events: {
								click: BX.delegate(this.compareRedirect, this)
							},
							style: {marginRight: '10px'}
						}),
						new BasketButton({
							text: BX.message('BTN_MESSAGE_CLOSE_POPUP'),
							events: {
								click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
							}
						})
					];
				}
				else {
					popupButtons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
							events: {
								click: BX.delegate(this.compareRedirect, this)
							}
						})
					];
				}
			}
			else {
				popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
					+ (result.MESSAGE ? result.MESSAGE : BX.message('COMPARE_UNKNOWN_ERROR'))
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

			if (result.STATUS !== 'OK')
			{
				this.obPopupWin.setTitleBar(BX.message('COMPARE_TITLE'));
				this.obPopupWin.setContent(popupContent);
				this.obPopupWin.setButtons(popupButtons);
				this.obPopupWin.show();
				this.obPopupWin.centering('CatalogSectionBasket_' + this.obPopupWin.resizeId);
			}

			BX.onCustomEvent('OnBasketChange');
		},

		compareDeleteResult: function()
		{
			BX.onCustomEvent('OnCompareChange');
			BX.removeClass(this.obCompare,'active');

			if (this.offers && this.offers.length)
			{
				this.offers[this.offerNum].COMPARED = false;
			}

			BX.onCustomEvent('OnBasketChange');
		},

		wishResult: function(result)
		{
			this.obWish.addClass('active');
		},

		wishDeleteResult: function()
		{
			this.obWish.removeClass('active');
		},

		setCompared: function(state)
		{
			if (!this.obCompare)
				return;

			var checkbox = this.obCompare;
			if (checkbox)
			{
				if(state == 1)
				{
					checkbox.classList.add("active");
					checkbox.title = BX.message('COMPARE_IN');
				}
				else
				{
					checkbox.classList.remove("active");
					checkbox.title = BX.message('COMPARE_TO');
				}
			}
		},

		setWished: function(state)
		{
			if (!this.obWish)
				return;

			var checkbox = this.obWish;
			if (checkbox)
			{
				if(state == 1)
				{
					checkbox.classList.add("active");
					checkbox.title = BX.message('WISH_IN');
				}
				else
				{
					checkbox.classList.remove("active");
					checkbox.title = BX.message('WISH_TO');
				}
			}
		},

		setBasket: function()
		{

			if(this.offers.length>0)
			{
				var id = parseInt(this.offers[this.offerNum].ID);
				if(arBasketID.indexOf(id)>=0)
				{
					$(this.obProduct).find('.product-card-inner__buy').hide();
					$(this.obProduct).find('.product-card-inner__product-basket').show();
				}else{
					$(this.obProduct).find('.product-card-inner__buy').show();
					$(this.obProduct).find('.product-card-inner__product-basket').hide();
				}

			}else{
				if(arBasketID.indexOf(parseInt(this.product.id))>=0)
				{
					$(this.obProduct).find(".product-card-inner__buy").hide();
					$(this.obProduct).find(".product-card-inner__product-basket").show();
				}else{
					$(this.obProduct).find(".product-card-inner__buy").show();
					$(this.obProduct).find(".product-card-inner__product-basket").hide();
				}
			}
		},

		setWish: function()
		{
			if(this.offers.length>0)
			{
				var id = parseInt(this.offers[this.offerNum].ID);
				if(arDelayID.indexOf(id)>=0)
				{
					this.setWished(true);
				}else{
					this.setWished(false);
				}
			}else{
				var id = parseInt(this.product.id);
				if(arDelayID.indexOf(id)>=0)
				{
					this.setWished(true);
				}else{
					this.setWished(false);
				}
			}
		},

		setCompare: function()
		{
			if(this.offers.length>0)
			{
				var id = parseInt(this.offers[this.offerNum].ID);
				if(arCompareID.indexOf(id)>=0)
				{
					this.setCompared(true);
				}else{
					this.setCompared(false);
				}
			}else{
				var id = parseInt(this.product.id);
				if(arCompareID.indexOf(id)>=0)
				{
					this.setCompared(true);
				}else{
					this.setCompared(false);
				}
			}
		},

		refreshProducts: function(data)
		{
			this.recountProducts();
		},

		recountProducts: function()
		{
			this.setBasket();
			this.setWish();
			this.setCompare();
		},

		addWishID: function(ID)
		{
			if (!ID)
				return;
			if(BX.type.isArray(arDelayID))
				arDelayID.push(ID);
			else arDelayID = [ID];
		},

		deleteWishID: function(ID)
		{
			if (!ID)
				return;
			if(BX.type.isArray(arDelayID))
			{
				var index = arDelayID.indexOf(ID);
				if (index > -1) {
					arDelayID.splice(index, 1);
				}
			}

		},

		compareRedirect: function()
		{
			if (this.compareData.comparePath)
			{
				location.href = this.compareData.comparePath;
			}
			else
			{
				this.obPopupWin.close();
			}
		},

		checkDeletedCompare: function(id)
		{
			switch (this.productType)
			{
				case 0: // no catalog
				case 1: // product
				case 2: // set
					if (this.product.id == id)
					{
						this.setCompared(false);
					}

					break;
				case 3: // sku
					var i = this.offers.length;
					while (i--)
					{
						if (this.offers[i].ID == id)
						{
							this.offers[i].COMPARED = false;

							if (this.offerNum == i)
							{
								this.setCompared(false);
							}

							break;
						}
					}
			}
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

			switch (this.productType)
			{
				case 0: // no catalog
				case 1: // product
				case 2: // set
					params['id'] = this.product.id.toString();
					break;
				case 3: // sku
					params['id'] = this.offers[this.offerNum].ID;
					break;
			}

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
			switch (this.productType)
			{
				case 1: // product
				case 2: // set
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
					break;
				case 3: // sku
					this.sendToBasket();
					break;
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

			if (successful)
			{
				this.setAnalyticsDataLayer('addToCart');
			}

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

					if  (BX.findParent(this.obProduct, {className: 'bx_sale_gift_main_products'}, 10))
					{
						BX.onCustomEvent('onAddToBasketMainProduct', [this]);
					}

					switch (this.productType)
					{
						case 1: // product
						case 2: // set
							strPict = this.product.morePhoto[0].SRC;
							break;
						case 3: // sku
							strPict = this.offers[this.offerNum].MORE_PHOTO[0].SRC;
							break;
					}

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
								className: 'to_basket_btn',
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
								className: 'to_basket_btn',
								events: {
									click: BX.delegate(this.basketRedirect, this)
								}
							})
						];
					}

					if(this.offers[this.offerNum] != undefined && this.offers[this.offerNum].ID != undefined)
						arBasketID.push(parseInt(this.offers[this.offerNum].ID));
					else
						arBasketID.push(this.product.id);
					var btnsBlock = this.obProduct.querySelector('[data-entity="buttons-block"]');

					btnsBlock.getElementsByClassName('product-card-inner__buy')[0].style.display = 'none';
					btnsBlock.getElementsByClassName('product-card-inner__product-basket')[0].style.display = 'flex';
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



window.calcHeight = function () {
    if(document.querySelectorAll('.product_card__inner.product_card__inner--two')) {
        var itemWrapperTwo = document.getElementsByClassName('product_card__inner product_card__inner--two');
        for (let i = 0; itemWrapperTwo.length > i; i++ ) {
            itemWrapperTwo[i].style.minHeight = "";
            if (window.innerWidth > 768) {
                itemWrapperTwo[i].style.minHeight = itemWrapperTwo[i].offsetHeight + 'px';
            }
        }
    }
    // if(document.querySelectorAll('.product_card__inner.product_card__inner--two-min')) {
    //     let itemWrapperTwoMin = Array.prototype.slice.call(document.querySelectorAll('.product_card__inner.product_card__inner--two-min .product-card-inner__img-wrapper'));
    //     itemWrapperTwoMin.forEach(function (item) {
    //         if (window.innerWidth <= 768) {
    //             item.style.height = '';
    //             item.style.height = item.offsetWidth + 'px';
    //         }
    //     });
    // }

    if(document.querySelectorAll('.product_card__inner.product_card__inner--three')) {
         var itemWrapperThree = document.getElementsByClassName('product_card__inner product_card__inner--three');
            for (let i = 0; itemWrapperThree.length > i; i++ ) {
                itemWrapperThree[i].style.minHeight = "";
                if (window.innerWidth > 768) {
                    itemWrapperThree[i].style.minHeight = itemWrapperThree[i].offsetHeight + 'px';
                }
            }
    }

    if(document.querySelectorAll('.product_card__inner.product_card__inner--four')) {
        var itemWrapperFour = document.getElementsByClassName('product_card__inner product_card__inner--four');
        for (let i = 0; itemWrapperFour.length > i; i++ ) {
            itemWrapperFour[i].style.minHeight = "";
            if (window.innerWidth > 768) {
                itemWrapperFour[i].style.minHeight = itemWrapperFour[i].offsetHeight + 'px';
            }
        }
    }

    if(document.querySelectorAll('.product_card__inner.product_card__inner--five')) {
        var itemWrapperFive = document.getElementsByClassName('product_card__inner product_card__inner--five');
        for (let i = 0; itemWrapperFive.length > i; i++ ) {
            itemWrapperFive[i].style.minHeight = "";
            if (window.innerWidth > 768) {
                itemWrapperFive[i].style.minHeight = itemWrapperFive[i].offsetHeight + 'px';
            }
        }
    }
}

$(document).ready(function () {
    window.addEventListener('resize', function() {
        window.calcHeight();
    });
});
