'use strict';

function BitrixSmallCart(){}

BitrixSmallCart.prototype = {

	activate: function ()
	{
		this.addBask=false;
		this.actionDelete = false;
		this.refreshQuantity = false;
		this.cartElement = BX(this.cartId);
		this.fixedPosition = this.arParams.POSITION_FIXED == 'Y';
		if (this.fixedPosition)
		{
			this.cartClosed = true;
			this.maxHeight = false;
			this.itemRemoved = false;
			this.verticalPosition = this.arParams.POSITION_VERTICAL;
			this.horizontalPosition = this.arParams.POSITION_HORIZONTAL;
			this.topPanelElement = BX("bx-panel");

			this.fixAfterRender(); // TODO onready
			this.fixAfterRenderClosure = this.closure('fixAfterRender');

			var fixCartClosure = this.closure('fixCart');
			this.fixCartClosure = fixCartClosure;

			if (this.topPanelElement && this.verticalPosition == 'top')
				BX.addCustomEvent(window, 'onTopPanelCollapse', fixCartClosure);

			var resizeTimer = null;
			BX.bind(window, 'resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(fixCartClosure, 200);
			});
		}
        this.setCartBodyClosure = this.closure('setCartBody');               

		this.refreshMobileCount();
        
		BX.addCustomEvent(window, 'OnBasketChange', this.closure('refreshCart', {}));
		//BX.addCustomEvent(window, 'OnBasketChangeAfter', this.closure('refreshCartAfter', {}));
	},

	fixAfterRender: function ()
	{
		this.statusElement = BX(this.cartId + 'status');
		if (this.statusElement)
		{
			if (this.cartClosed)
				this.statusElement.innerHTML = this.openMessage;
			else
				this.statusElement.innerHTML = this.closeMessage;
		}
		this.productsElement = BX(this.cartId + 'products');
		this.fixCart();
	},

	closure: function (fname, data)
	{
		var obj = this;
		return data
			? function(){obj[fname](data)}
			: function(arg1){obj[fname](arg1)};
    },

    timerIdDebounce: false,

	toggleOpenCloseCart: function (action)
	{
        if(this.debounce) {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(() => {
                
                if(action=="open")
                    this.cartClosed=true;
                else
                    this.cartClosed=false;
                if (this.cartClosed)
                {
                    BX.removeClass(this.cartElement, 'bx-closed');
                    BX.addClass(this.cartElement, 'bx-opener');
                    BX.addClass(this.cartElement, 'bx-max-height');
                    $('.overlay-basket').addClass("overlay-active");
                    if(this.addBask) {
                        $('.window-without-bg').css('display','none');
                    }
                    else {
                        $('.window-without-bg').slideDown(300);
                        // $('.window-without-bg').css('display','block');

                    }

                    //this.statusElement.innerHTML = this.closeMessage;
                    this.cartClosed = false;
                    this.fixCart();
                }
                else // Opened
                {
                    $('.window-without-bg').slideUp(300);
                    BX.addClass(this.cartElement, 'bx-closed');
                    BX.removeClass(this.cartElement, 'bx-opener');
                    BX.removeClass(this.cartElement, 'bx-max-height');
                    $('.overlay-basket').removeClass("overlay-active");
                    //this.statusElement.innerHTML = this.openMessage;
                    this.cartClosed = true;
                    var itemList = this.cartElement.querySelector("[data-role='basket-item-list']");

                    //if (itemList)
                    //	itemList.style.top = "auto";
                }
                setTimeout(this.fixCartClosure, 100);
                        
            }, 300)
        } else {
        	var _this = this;

            this.debounce = setTimeout(function () {

                if(action=="open")
					_this.cartClosed=true;
                else
					_this.cartClosed=false;
                if (_this.cartClosed)
                {
                    BX.removeClass(_this.cartElement, 'bx-closed');
                    BX.addClass(_this.cartElement, 'bx-opener');
                    BX.addClass(_this.cartElement, 'bx-max-height');
                    
                    $('.overlay-basket').addClass("overlay-active");
                    if(_this.addBask) {
                        $('.window-without-bg').css('display','none');
                    }
                    else {
                        $('.window-without-bg').slideDown(300);
                        // $('.window-without-bg').css('display','block');

                    }
                    _this.cartClosed = false;

					_this.fixCart();
                }
                else // Opened
                {
                    $('.window-without-bg').slideUp(300);
                    BX.addClass(_this.cartElement, 'bx-closed');
                    BX.removeClass(_this.cartElement, 'bx-opener');
                    BX.removeClass(_this.cartElement, 'bx-max-height');
                    
                    $('.overlay-basket').removeClass("overlay-active");
                    //this.statusElement.innerHTML = this.openMessage;
					_this.cartClosed = true;
                    var itemList = _this.cartElement.querySelector("[data-role='basket-item-list']");
                }
                setTimeout(_this.fixCartClosure, 100);
            }, 100)
        }
	},

	setVerticalCenter: function(windowHeight)
	{
		var top = windowHeight/2 - (this.cartElement.offsetHeight/2);
		if (top < 5)
			top = 5;
		this.cartElement.style.top = top + 'px';
	},

	fixCart: function()
	{
		if(this.addBask)
		{
			$('.window-without-bg').slideDown(1000);
		}
		// set horizontal center
		if (this.horizontalPosition == 'hcenter')
		{
			var windowWidth = 'innerWidth' in window
				? window.innerWidth
				: document.documentElement.offsetWidth;
			var left = windowWidth/2 - (this.cartElement.offsetWidth/2);
			if (left < 5)
				left = 5;
			this.cartElement.style.left = left + 'px';
		}

		var windowHeight = 'innerHeight' in window
			? window.innerHeight
			: document.documentElement.offsetHeight;

		// set vertical position
		switch (this.verticalPosition) {
			case 'top':
				if (this.topPanelElement)
					this.cartElement.style.top = this.topPanelElement.offsetHeight + 5 + 'px';
				break;
			case 'vcenter':
				this.setVerticalCenter(windowHeight);
				break;
		}





		// toggle max height
		if (this.productsElement)
		{
			var itemList = this.cartElement.querySelector("[data-role='basket-item-list']");
			if (this.cartClosed)
			{
				if (this.maxHeight)
				{
					BX.removeClass(this.cartElement, 'bx-max-height');
					if (itemList)
						itemList.style.top = "auto";
					this.maxHeight = false;
				}
			}
			else // Opened
			{
				if (this.maxHeight)
				{
					if (this.productsElement.scrollHeight == this.productsElement.clientHeight)
					{
						BX.removeClass(this.cartElement, 'bx-max-height');
						if (itemList)
							itemList.style.top = "auto";
						this.maxHeight = false;
					}
				}
				else
				{
					if (this.verticalPosition == 'top' || this.verticalPosition == 'vcenter')
					{
						if (this.cartElement.offsetTop + this.cartElement.offsetHeight >= windowHeight)
						{
							BX.addClass(this.cartElement, 'bx-max-height');
							if (itemList)
								itemList.style.top = 82+"px";
							this.maxHeight = true;
						}
					}
					else
					{
						if (this.cartElement.offsetHeight >= windowHeight)
						{
							BX.addClass(this.cartElement, 'bx-max-height');
							if (itemList)
								itemList.style.top = 82+"px";
							this.maxHeight = true;
						}
					}
				}
			}

			if (this.verticalPosition == 'vcenter')
				this.setVerticalCenter(windowHeight);



		}
	},

	refreshCart: function (data)
	{
		if (this.itemRemoved)
		{
			this.itemRemoved = false;
			return;
		}

		data.sessid = BX.bitrix_sessid();
		data.siteId = this.siteId;
		data.templateName = this.templateName;
		data.arParams = this.arParams;
		if($("#open-basket-origami__tab_basket").prop("checked"))
			data.tab = "buy";
		else data.tab = "delay"
        createCartLoader($('#bx_basketFKauiIproducts'));
        
		BX.ajax({
			url: this.ajaxPath,
			method: 'POST',
			dataType: 'html',
			data: data,
            onsuccess: (data) => {               
                this.setCartBodyClosure(data);
                basketToggle();
                removeCartLoader();

            }
		});
	},

	refreshPlusCart: function (id, ratio, productID, quantity)
	{   var count = +quantity.parentElement.querySelector('.open-basket-product__quantity-value').value;
		this.refreshCart ({refresh: ratio, ID: id, count:count + ratio, productID: productID});
		this.refreshQuantity = true;
		//BX.onCustomEvent('OnBasketChange');
	},

	refreshMinusCart: function (id, ratio, productID, quantity)
	{
        var count = +quantity.parentElement.querySelector('.open-basket-product__quantity-value').value;
		count = count - ratio;
        if (count < ratio) {
            return;
        }
		this.refreshCart ({refresh: ratio, ID: id, count:count, productID: productID});
		this.refreshQuantity = true;

		//BX.onCustomEvent('OnBasketChange');
	},
	refreshInputCart: function (id, ratio, productID, quantity)
	{
        var count = +quantity.parentElement.querySelector('.open-basket-product__quantity-value').value;

        if(count<ratio)
			count = ratio;
        else
		{
			var ost = count % ratio;
			if(ost!=0)
			{
				count = count - ost;
			}
		}

		quantity.value = count;
        /*count = parseInt(count, 10) || 1;
        if (count <= ratio) {
            count = ratio;
        }*/
		this.refreshCart ({refresh: 1, ID: id, count:count, productID: productID});
		this.refreshQuantity = true;

		//BX.onCustomEvent('OnBasketChange');
	},

	refreshDelay: function (id)
	{
		this.refreshCart ({refresh: 1, ID: id, delay:1});
		this.refreshQuantity = true;

		//BX.onCustomEvent('OnBasketChange');
	},

	refreshBuy: function (id)
	{
		this.refreshCart ({refresh: 1, ID: id, buy:1});
		this.refreshQuantity = true;

		//BX.onCustomEvent('OnBasketChange');
	},

	removeList: function ()
	{
		this.refreshCart ({deleteList: 1});
		this.refreshQuantity = true;
	},

	setCartBody: function (result)
	{
		if (this.cartElement)
			this.cartElement.innerHTML = result.replace(/#CURRENT_URL#/g, this.currentUrl);
		if (this.fixedPosition)
            setTimeout(this.fixAfterRenderClosure, 100);            
            
		if(this.actionDelete || this.refreshQuantity)
		{            
			$('.window-without-bg').css('display','block');
		}
		this.refreshMobileCount();


		$('.origami_main_scroll').each(function (index, item) {
			new PerfectScrollbar(item);
		});

        this.actionDelete =false;
        this.refreshQuantity = false;
		//BX.removeCustomEvent(window, 'OnBasketChange', this.closure('refreshCart', {}));

	},

	refreshMobileCount: function ()
	{
		if(document.querySelector('.header-two'))
		{
			this.qntBasket = $("#"+this.cartId+" .header-two__basket-buy span").html();
			this.qntDelay = $("#"+this.cartId+" .header-two__basket-favorites span").html();
			this.qntCompare = $("#"+this.cartId+" .header-two__basket-compare span").html();
		}else{
			this.qntBasket = $("#"+this.cartId+" .basket-block__link_main_basket span").html();
			this.qntDelay = $("#"+this.cartId+" .basket-block__wish span").html();
			this.qntCompare = $("#"+this.cartId+" .basket-block__compare span").html();
		}

		//for mobile menu
		$("#menu div.container_menu_mobile__list .mobile_icon_chart-bar+span").html(this.qntCompare);
		$("#menu div.container_menu_mobile__list .mobile_icon_heart+span").html(this.qntDelay);
		$("#menu div.container_menu_mobile__list .mobile_icon_shopping-basket+span").html(this.qntBasket);

		//for mobile basket
		$("#basket_top_mobile_cont_to span").html(this.qntBasket);
	},

	removeItemFromCart: function (id)
	{
		this.actionDelete = true;
		this.refreshCart ({sbblRemoveItemFromCart: id});
		this.itemRemoved = true;
		BX.onCustomEvent('OnBasketChange');
	}
};

$('.origami_main_scroll').each(function(index, item){
    new PerfectScrollbar(item);
});



function basketToggle() {
    
    var linkBasket = document.querySelectorAll('.basket-block__link');
    for (let i = 0; i < linkBasket.length; i++) {
    linkBasket[i].addEventListener('mouseenter', function (evt) {
        if (document.querySelector('.bx-basket-item-list-container')) {

            if(evt.target.classList.contains('basket-block__link_main_basket')) {
                document.getElementById('open-basket-origami__tab_basket').checked = 'true';				
            }
            if(evt.target.classList.contains('basket-block__wish')) {
                document.getElementById('open-basket-origami__tab_wish').checked = 'true';
            }
        }  
    });
    }


    var linksBasket = document.querySelectorAll('#bx_basketFKauiI a');
    for (let i = 0; i < linksBasket.length; i++) {
    linksBasket[i].addEventListener('mouseenter', function (evt) {
        if (document.querySelector('.bx-basket-item-list-container')) {

            if(evt.target.classList.contains('header-two__basket-buy')) {
                document.getElementById('open-basket-origami__tab_basket').checked = 'true';
                document.querySelector('.bx-basket-item-list-container').classList.remove('wish-active');				
            }
            if(evt.target.classList.contains('header-two__basket-favorites')) {
                document.getElementById('open-basket-origami__tab_wish').checked = 'true';
                document.querySelector('.bx-basket-item-list-container').classList.add('wish-active');
            }
        } 
    });
    }

    if(document.querySelector('.open-basket-origami__tabs')) {
        document.querySelector('.open-basket-origami__tabs').addEventListener('click', function(evt) {
            if (document.getElementById('open-basket-origami__tab_basket').checked) {
                document.querySelector('.bx-basket-item-list-container').classList.remove('wish-active');
            }
            if (document.getElementById('open-basket-origami__tab_wish').checked) {
                document.querySelector('.bx-basket-item-list-container').classList.add('wish-active');
            }
        });
    }
    

    if(document.querySelector('.open-basket-product-btn__more-buy')) {
        document.querySelector('.open-basket-product-btn__more-buy').addEventListener('click', function() {
            document.querySelector('.window_basket').style.display = 'none';
        });
    }
}

$(document).ready(function () {
    basketToggle();
});